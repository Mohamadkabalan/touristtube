package scm.scheduler;

import java.time.Duration;
import java.util.Enumeration;
import java.util.Timer;
import java.util.TimerTask;
import java.util.Vector;

import scm.MultiThreadedSocketServer;
import scm.configuration.PropertyValues;
import scm.data_handler.BasicConnectionParameters;
import scm.database.DatabaseAdapter;
import scm.sabre.RefreshSession;

public class PeriodicConnectionChecker
{
	private Timer timer;
	private ShortPeriodChecker shortPeriodChecker;
	private boolean busy = false;
	
	public PeriodicConnectionChecker(boolean forcedRefreshMode)
	{
		MultiThreadedSocketServer.scmLogger.debug("PeriodicConnectionChecker", "Starting in " + (forcedRefreshMode?"":"non-") + "forced mode");
		
		this.timer = new Timer("PeriodicConnectionChecker");
		this.shortPeriodChecker = new ShortPeriodChecker(forcedRefreshMode, this);
		this.shortPeriodChecker.run();
		
		this.timer.scheduleAtFixedRate(this.shortPeriodChecker,  0, (long) (Duration.ofMinutes(1).toMillis() * PropertyValues.shortPeriodCheckerCycleMinutes));
	}
	
	public void setForcedMode(boolean forced)
	{
		this.shortPeriodChecker.setForcedMode(forced);
	}
	
	public boolean inForcedRefreshMode()
	{
		return this.shortPeriodChecker.getForcedMode();
	}
	
	public boolean isBusy()
	{
		return this.busy;
	}
	
	protected void setBusy(boolean busy)
	{
		this.busy = busy;
	}
	
	public void cancel()
	{
		String contextInfo = "PeriodicConnectionChecker:: cancel()";
		
		if(this.shortPeriodChecker != null)
		{
			MultiThreadedSocketServer.scmLogger.debug(contextInfo, "Stopping the ShortPeriodChecker");
			
			this.shortPeriodChecker.cancel();
			
			synchronized(this)
			{
				if (!this.busy)
					this.shortPeriodChecker = null;
			}
		}
		
		if(this.timer != null)
		{
			MultiThreadedSocketServer.scmLogger.debug(contextInfo, "Stopping the Timer");
			
			this.timer.cancel();
			
			this.timer = null;
		}
	}
}

class ShortPeriodChecker extends TimerTask
{
	private boolean forcedMode = false;
	private PeriodicConnectionChecker periodicConnectionChecker;
	
	public ShortPeriodChecker(boolean forcedMode, PeriodicConnectionChecker periodicConnectionChecker)
	{
		super();
		
		this.forcedMode = forcedMode;
		
		this.periodicConnectionChecker = periodicConnectionChecker;
	}
	
	public boolean getForcedMode()
	{
		return this.forcedMode;
	}

	public void setForcedMode(boolean forced)
	{
		this.forcedMode = forced;
		
		MultiThreadedSocketServer.scmLogger.debug("ShortPeriodChecker:: setForcedMode(" + String.valueOf(forced) + ")", "forced mode set to " + String.valueOf(forced));
	}
	
	public void run()
	{
		synchronized(this.periodicConnectionChecker)
		{
			this.periodicConnectionChecker.setBusy(true);
		}
		
		String contextInfo = "ShortPeriodChecker:: run()";
		
		MultiThreadedSocketServer.scmLogger.debug(contextInfo, "Operating in " + (this.forcedMode?"":"non-") + "forced mode");
		
		int nReleased = DatabaseAdapter.releaseLongtimeReservedConnections(PropertyValues.connectionRefreshCycleMinutes - PropertyValues.connectionRefreshSafetyMarginMinutes, "scm");
		
		MultiThreadedSocketServer.scmLogger.debug(contextInfo, "Released " + nReleased + " connections");
		
		RefreshSession refreshSession = new RefreshSession();
		
		Vector<BasicConnectionParameters> connections = null;
		
		if (this.forcedMode)
			connections = DatabaseAdapter.openedConnections();
		else
			connections = DatabaseAdapter.connectionsToBeRefreshed(PropertyValues.connectionRefreshCycleMinutes - PropertyValues.connectionRefreshSafetyMarginMinutes);
		
		if(connections.size() == 0)
		{
			MultiThreadedSocketServer.scmLogger.debug(contextInfo, "No connection to refresh");
		}
		else
		{
			Enumeration<BasicConnectionParameters> bpEnumeration = connections.elements();
			
			while (bpEnumeration.hasMoreElements())
			{
				BasicConnectionParameters basicParameters = bpEnumeration.nextElement();
				
				MultiThreadedSocketServer.scmLogger.debug(contextInfo, basicParameters.getId() + " should be refreshed");
				// MultiThreadedSocketServer.scmLogger.debug(contextInfo, basicParameters.getToken() + " " + basicParameters.getConversationId());
				
				if(refreshSession.refresh(basicParameters))
				{
					MultiThreadedSocketServer.scmLogger.debug(contextInfo, basicParameters.getId() + " is refreshed");
					
					if (!DatabaseAdapter.updateLastRefreshed(basicParameters.getId()))
						MultiThreadedSocketServer.scmLogger.error(contextInfo, "Failed updating DB for refreshed connection:: " + basicParameters.getId());
				}
				else
				{
					DatabaseAdapter.closeConnection(basicParameters.getId(), "scm");
					
					MultiThreadedSocketServer.scmLogger.error(contextInfo, basicParameters.getId() + " is not refreshed");
				}
			}
		}
		
		MultiThreadedSocketServer.scmLogger.debug(contextInfo, "will repeat every " + PropertyValues.shortPeriodCheckerCycleMinutes + " minutes");
		
		synchronized(this.periodicConnectionChecker)
		{
			this.periodicConnectionChecker.setBusy(false);
		}
	}
}
