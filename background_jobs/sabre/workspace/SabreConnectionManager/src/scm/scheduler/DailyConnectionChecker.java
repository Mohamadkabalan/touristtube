package scm.scheduler;

import java.time.Duration;
import java.util.ArrayList;
import java.util.Enumeration;
import java.util.Timer;
import java.util.TimerTask;
import java.util.Vector;

import scm.InitSessions;
import scm.MultiThreadedSocketServer;
import scm.configuration.PropertyValues;
import scm.data_handler.BasicConnectionParameters;
import scm.database.DatabaseAdapter;

public class DailyConnectionChecker
{
	private Timer timer;
	private DailyTask dailyTask;
	private boolean busy = false;
	
	public DailyConnectionChecker()
	{
		MultiThreadedSocketServer.scmLogger.debug("DailyConnectionChecker", "Starting");
		
		this.timer = new Timer("DailyConnectionChecker");
		this.dailyTask = new DailyTask(this);
		
		this.timer.scheduleAtFixedRate(dailyTask, 0, Duration.ofDays(1).toMillis());
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
		String contextInfo = "DailyConnectionChecker:: cancel()";
		
		if(this.dailyTask != null)
		{
			MultiThreadedSocketServer.scmLogger.debug(contextInfo, "Stopping the DailyTask");
			
			this.dailyTask.cancel();
			
			synchronized(this)
			{
				if (!this.busy)
					this.dailyTask = null;
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

class DailyTask extends TimerTask
{
	private DailyConnectionChecker dailyConnectionChecker;
	
	public DailyTask(DailyConnectionChecker dailyConnectionChecker)
	{
		super();
		
		this.dailyConnectionChecker = dailyConnectionChecker;
	}
	
	public void run()
	{
		synchronized(this.dailyConnectionChecker)
		{
			this.dailyConnectionChecker.setBusy(true);
		}
		
		MultiThreadedSocketServer.scmLogger.debug("DailyTask:: run()", "Started");
		
		this.closeConnections(DatabaseAdapter.connectionsToCloseImmediately(PropertyValues.connectionLifetimeSafetyMarginDays));
		
		Vector<BasicConnectionParameters> connections = DatabaseAdapter.connectionsToClose(PropertyValues.connectionLifetimeSafetyMarginDays);
		
		if (connections.size() != 0)
		{
			Enumeration<BasicConnectionParameters> bpEnumeration = connections.elements();
			
			ArrayList<Integer> ids = new ArrayList<Integer>(0);
			
			while (bpEnumeration.hasMoreElements())
			{
				ids.add(bpEnumeration.nextElement().getId());
			}
			
			if (DatabaseAdapter.lockConnections(ids))
			{
				MultiThreadedSocketServer.scmLogger.debug("DailyTask:: run()", "Expired connections successfully locked:: " + ids.toString());
			}
			else
			{
				MultiThreadedSocketServer.scmLogger.error("DailyTask:: run()", "Failed to lock expired connections:: " + ids.toString());
			}
		}
		
		while (connections.size() != 0)
		{
			try
			{
				Thread.sleep(Duration.ofMinutes(1).toMillis());
			}
			catch(InterruptedException e)
			{
				e.printStackTrace();
			}
			
			this.closeConnections(DatabaseAdapter.connectionsToCloseImmediately(PropertyValues.connectionLifetimeSafetyMarginDays));
			
			connections = DatabaseAdapter.connectionsToClose(PropertyValues.connectionLifetimeSafetyMarginDays);
		}
		
		MultiThreadedSocketServer.scmLogger.debug("DailyTask:: run()", "Completed");
		
		synchronized(this.dailyConnectionChecker)
		{
			this.dailyConnectionChecker.setBusy(false);
		}
	}
	
	private void closeConnections(Vector<BasicConnectionParameters> connections)
	{
		if (connections == null || connections.isEmpty())
			return;
		
		String contextInfo = "DailyTask:: closeConnections()";
		
		Enumeration<BasicConnectionParameters> bpEnumeration = connections.elements();
		
		while (bpEnumeration.hasMoreElements())
		{
			BasicConnectionParameters basicParameters = bpEnumeration.nextElement();
			
			if (InitSessions.closeConnection(basicParameters, "scm"))
			{
				MultiThreadedSocketServer.scmLogger.debug(contextInfo, basicParameters.toShortString() + " closed successfully");
			}
			else
			{
				MultiThreadedSocketServer.scmLogger.error(contextInfo, basicParameters.toShortString() + " not closed successfully");
			}
		}
	}
}
