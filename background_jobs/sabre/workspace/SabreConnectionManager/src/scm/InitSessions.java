package scm;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Enumeration;
import java.util.TimeZone;
import java.util.Vector;

import scm.configuration.PropertyValues;
import scm.data_handler.BasicConnectionParameters;
import scm.data_handler.ConnectionParameters;
import scm.database.DatabaseAdapter;
import scm.sabre.CleanSession;
import scm.sabre.CloseSession;
import scm.sabre.CreateSession;
import scm.sabre.RefreshSession;
import scm.scheduler.DailyConnectionChecker;
import scm.scheduler.PeriodicConnectionChecker;

public class InitSessions
{
	public static short CONNECTION_TYPE_BFM = 1;
	public static short CONNECTION_TYPE_BOOKING = 2;
	
	public static DailyConnectionChecker dailyConnectionChecker;
	public static PeriodicConnectionChecker periodicConnectionChecker;
	
	public static SimpleDateFormat sdf_date = (new SimpleDateFormat("yyyy-MM-dd"));
	public static SimpleDateFormat sdf_timestamp = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
	public static SimpleDateFormat sdft_timestamp = new SimpleDateFormat("yyyy-MM-dd'T'HH:mm:ss");
	public static SimpleDateFormat sdftz_timestamp = new SimpleDateFormat("yyyy-MM-dd'T'HH:mm:ss'Z'");
	
	public static PropertyValues properties = new PropertyValues();
	// private static CreateSession requestSOAP = new CreateSession();
	
	public void init(String configFilePath)
	{
		InitSessions.properties.getPropertyValues(configFilePath);
		
		System.setProperty("user.timezone", PropertyValues.timezone);
		
		sdf_date.setTimeZone(TimeZone.getTimeZone(PropertyValues.timezone));
		sdf_timestamp.setTimeZone(TimeZone.getTimeZone(PropertyValues.timezone));
		
		MultiThreadedSocketServer.scmLogger.debug("InitSessions:: init(configFilePath:: " + configFilePath + ")", "Effective timezone:: " + PropertyValues.timezone);
	}
	
	public void prepare(boolean initConnections, boolean forcedRefresh)
	{
		String contextInfo = "InitSessions:: prepare(initConnections:: " + String.valueOf(initConnections) + ", forcedRefresh:: " + String.valueOf(forcedRefresh) + ")";
		
		MultiThreadedSocketServer.scmLogger.debug(contextInfo, "Creating the PeriodicConnectionChecker in " + (forcedRefresh?"":"non-") + "forced mode");
		periodicConnectionChecker = new PeriodicConnectionChecker(forcedRefresh);
		
		MultiThreadedSocketServer.scmLogger.debug(contextInfo, "Calling the DailyConnectionChecker");
		dailyConnectionChecker = new DailyConnectionChecker();
		
		if (initConnections)
		{
			InitSessions.initConnections();
		}
		else
		{
			ArrayList<Integer> ids = InitSessions.refreshAllOpenedConnections("scm");
			MultiThreadedSocketServer.scmLogger.debug(contextInfo, "Completed AUTO REFRESH of connections:: " + ids.toString());
		} 
	}
	
	public static void initConnections()
	{
		CreateSession createSession = new CreateSession();
		
		int maxAllowedFailures = PropertyValues.nInitialBFMConnections;
		
		int nFailures = 0;
		
		for (int i = 1;i <= PropertyValues.nInitialBFMConnections;i++)
		{
			ConnectionParameters connectionParameters = createSession.create();
			
			if (connectionParameters == null)
			{
				nFailures++;
				
				if (nFailures == maxAllowedFailures)
				{
					break;
				}
				
				try
				{
					Thread.sleep(InitSessions.properties.waitingTimeMS);
				}
				catch (InterruptedException e)
				{
					
				}
				
				i--;
				
				continue;
			}
			
			try
			{
				DatabaseAdapter.setupAClosedConnection(InitSessions.CONNECTION_TYPE_BFM, connectionParameters.getBasicConnectionParameters());
				
				MultiThreadedSocketServer.scmLogger.debug("InitSessions:: initConnections()", "Initial BFM Sessions - " + i + " / " + PropertyValues.nInitialBFMConnections);
			}
			catch(Exception e)
			{
				e.printStackTrace();
			}
		}
		
		maxAllowedFailures = PropertyValues.nInitialBookingConnections;
		nFailures = 0;
		
		for (int i = 1;i <= PropertyValues.nInitialBookingConnections;i++)
		{
			ConnectionParameters connectionParameters = createSession.create();
			
			if (connectionParameters == null)
			{
				nFailures++;
				
				if (nFailures == maxAllowedFailures)
				{
					break;
				}
				
				try
				{
					Thread.sleep(InitSessions.properties.waitingTimeMS);
				}
				catch (InterruptedException e)
				{
					
				}
				
				i--;
				
				continue;
			}
			
			try
			{
				DatabaseAdapter.setupAClosedConnection(InitSessions.CONNECTION_TYPE_BOOKING, connectionParameters.getBasicConnectionParameters());
				
				MultiThreadedSocketServer.scmLogger.debug("InitSessions:: initConnections()", "Initial Booking Sessions = " + i + " / " + PropertyValues.nInitialBookingConnections);
			}
			catch(Exception e)
			{
				e.printStackTrace();
			}
		}
	}
	
	public static BasicConnectionParameters provideBFMConnection(int user_id, String source)
	{
		BasicConnectionParameters basicParameters = null;
		
		if (user_id != 0)
		{
			basicParameters = DatabaseAdapter.fetchUserConnection(user_id, InitSessions.CONNECTION_TYPE_BFM);
			
			if (basicParameters != null)
			{
				DatabaseAdapter.assignConnection(basicParameters.getId(), user_id, source);
				
				return basicParameters;
			}
		}
		
		long startTime = System.currentTimeMillis();
		
		int nConnectionsInUse = DatabaseAdapter.countConnectionsInUse(InitSessions.CONNECTION_TYPE_BFM);
		
		if(nConnectionsInUse == PropertyValues.maxBFMConnections)
		{
			int retryNumber = 0;
			
			while((System.currentTimeMillis() - startTime) <= PropertyValues.connectionServiceTimeoutMS)
			{
				try
				{
					Thread.sleep(PropertyValues.waitingTimeMS);
				}
				catch(InterruptedException e)
				{
					e.printStackTrace();
				}
				
				basicParameters = DatabaseAdapter.fetchFreeConnection(InitSessions.CONNECTION_TYPE_BFM);
				
				if (basicParameters == null)
				{
					retryNumber++;
					
					if(retryNumber == PropertyValues.nConnectionServiceRetries)
					{
						MultiThreadedSocketServer.scmLogger.debug("InitSessions:: provideBFMConnection(user_id:: " + user_id + ", source:: " + source + ")", "All (" + PropertyValues.maxBFMConnections + ") BFM connections are INUSE, reached the maximum number of retries (" + PropertyValues.nConnectionServiceRetries + "), unable to return a reference to a connection.");
						
						return null;
					}
					
					continue;
				}
				
				DatabaseAdapter.assignConnection(basicParameters.getId(), user_id, source);
				
				return basicParameters;
			}
		}
		
		try
		{
			basicParameters = DatabaseAdapter.fetchFreeConnection(InitSessions.CONNECTION_TYPE_BFM);
			
			if(basicParameters != null)
			{
				DatabaseAdapter.assignConnection(basicParameters.getId(), user_id, source);
				
				return basicParameters;
				
			}
			else
			{
				if (openNewConnections(PropertyValues.maxBFMConnections, InitSessions.CONNECTION_TYPE_BFM) == 0)
					return null;
				
				return InitSessions.provideBFMConnection(user_id, source);
			}
		}
		catch(Exception e)
		{
			MultiThreadedSocketServer.scmLogger.error("InitSessions:: provideBFMConnection(user_id:: " + user_id + ", source:: " + source + ")", "Could not provide BFM connection");
			MultiThreadedSocketServer.scmLogger.errorException("InitSessions:: provideBFMConnection(user_id:: " + user_id + ", source:: " + source + ")",  e);
		}
		
		return null;
	}
	
	public static synchronized BasicConnectionParameters provideBookingConnection(int user_id, String source)
	{
		BasicConnectionParameters basicParameters = null;
		
		if (user_id != 0)
		{
			basicParameters = DatabaseAdapter.fetchUserConnection(user_id, InitSessions.CONNECTION_TYPE_BOOKING);
			
			if (basicParameters != null)
			{
				DatabaseAdapter.assignConnection(basicParameters.getId(), user_id, source);
				
				return basicParameters;
			}
		}
		
		long startTime = System.currentTimeMillis();
		
		int nConnectionsInUse = DatabaseAdapter.countConnectionsInUse(InitSessions.CONNECTION_TYPE_BOOKING);
		
		if(nConnectionsInUse == PropertyValues.maxBookingConnections)
		{
			int retryNumber = 0;
			
			while((System.currentTimeMillis() - startTime) <= PropertyValues.connectionServiceTimeoutMS)
			{
				try
				{
					Thread.sleep(PropertyValues.waitingTimeMS);
				}
				catch(InterruptedException e)
				{
					e.printStackTrace();
				}
				
				basicParameters = DatabaseAdapter.fetchFreeConnection(InitSessions.CONNECTION_TYPE_BOOKING);
				
				if (basicParameters == null)
				{
					retryNumber++;
					
					if(retryNumber == PropertyValues.nConnectionServiceRetries)
					{
						MultiThreadedSocketServer.scmLogger.debug("InitSessions:: provideBookingConnection(user_id:: " + user_id + ", source:: " + source + ")", "All (" + PropertyValues.maxBookingConnections + ") Booking connections are INUSE, reached the maximum number of retries (" + PropertyValues.nConnectionServiceRetries + "), unable to return a reference to a connection.");
						
						return null;
					}
					
					continue;
				}
				
				DatabaseAdapter.assignConnection(basicParameters.getId(), user_id, source);
				
				return basicParameters;
			}
		}
		
		try
		{
			basicParameters = DatabaseAdapter.fetchFreeConnection(InitSessions.CONNECTION_TYPE_BOOKING);
			
			if(basicParameters != null)
			{
				CleanSession cleanSession = new CleanSession();
				
				// basicParameters.initDates();
				
				if(cleanSession.clean(basicParameters))
				{
					DatabaseAdapter.assignConnection(basicParameters.getId(), user_id, source);
					
					return basicParameters;
				}
				else
				{
					DatabaseAdapter.lockConnection(basicParameters.getId());
					
					return InitSessions.provideBookingConnection(user_id, source);
				}
			}
			else
			{
				if (openNewConnections(PropertyValues.maxBookingConnections, InitSessions.CONNECTION_TYPE_BOOKING) == 0)
					return null;
				
				return InitSessions.provideBookingConnection(user_id, source);
			}
		}
		catch(Exception e)
		{
			MultiThreadedSocketServer.scmLogger.error("InitSessions:: provideBookingConnection(user_id:: " + user_id + ", source:: " + source + ")", "Could not provide booking connection");
			MultiThreadedSocketServer.scmLogger.errorException("InitSessions:: provideBookingConnection(user_id:: " + user_id + ", source:: " + source + ")",  e);
		}
		
		return null;
	}
	
	public static synchronized int openNewConnections(int max_connections, int connection_type)
	{
		int openedConnections = 0;
		
		int nConnectionsToOpen = PropertyValues.nConnectionsToOpenWhenNeeded;
		
		int nFreeConnections = max_connections - DatabaseAdapter.countConnectionsInUse(connection_type);
		
		if (nFreeConnections == 0)
			return 0;
		
		nConnectionsToOpen = Math.min(nConnectionsToOpen, nFreeConnections);
		
		CreateSession createSession = new CreateSession();
		
		int maxAllowedFailures = nConnectionsToOpen;
		int nFailures = 0;
		
		for (int i = 1;i <= nConnectionsToOpen;i++)
		{
			ConnectionParameters connectionParameters = createSession.create();
			
			if (connectionParameters == null)
			{
				nFailures++;
				
				if (nFailures == maxAllowedFailures)
				{
					break;
				}
				
				try
				{
					Thread.sleep(InitSessions.properties.waitingTimeMS);
				}
				catch (InterruptedException e)
				{
					
				}
				
				i--;
				
				continue;
			}
			
			DatabaseAdapter.setupAClosedConnection(connection_type, connectionParameters.getBasicConnectionParameters());
			
			openedConnections++;
		}
		
		return openedConnections;
	}
	
	public static boolean closeConnection(BasicConnectionParameters basicParameters, String source)
	{
		boolean closed = false;
		
		CloseSession closeSession = new CloseSession();
		
		if(closeSession.close(basicParameters))
		{
			if (DatabaseAdapter.closeConnection(basicParameters.getId(), source))
			{
				closed = true;
			}
		}
		
		return closed;
	}
	
	public static boolean setConnectionFree(int id, String source)
	{
		return DatabaseAdapter.releaseConnection(id, source);
	}
	
	public static boolean setConnectionFree(int id, String source, boolean lockConnection)
	{
		if (lockConnection)
			DatabaseAdapter.lockConnection(id);
		
		return DatabaseAdapter.releaseConnection(id, source);
	}
	
	public static boolean setTokenFree(String token, String source)
	{
		return DatabaseAdapter.releaseToken(token, source);
	}
	
	public static boolean setTokenFree(String token, String source, boolean lockToken)
	{
		if (lockToken)
			DatabaseAdapter.lockToken(token);
		
		return DatabaseAdapter.releaseToken(token, source);
	}
	
	public static boolean cleanToken(String token, String source)
	{
		BasicConnectionParameters basicParameters = DatabaseAdapter.fetchConnectionByToken(token);
		
		if (basicParameters == null)
			return false;
		
		CleanSession cleanSession = new CleanSession();
		return cleanSession.clean(basicParameters);
	}
	
	/*
	public static void debugString(String str)
	{
		if (PropertyValues.debug && str != null)
			System.out.println(InitSessions.sdf_timestamp.format(new Date()) + " " + str);
	}
	*/
	
	public static ArrayList<Integer> refreshConnections(ArrayList<Integer> ids, String source)
	{
		ArrayList<Integer> successful_ids = new ArrayList<Integer>(0);
		
		String contextInfo = "InitSessions:: refreshConnections(ids:: " + ids.toString() + ", source:: " + source + ")";
		
		Vector<BasicConnectionParameters> connections = DatabaseAdapter.openedConnectionsFromList(ids);
		
		if (connections.size() == 0)
			return successful_ids;
		
		RefreshSession refreshSession = new RefreshSession();
		
		Enumeration<BasicConnectionParameters> bpEnumeration = connections.elements();
		
		while (bpEnumeration.hasMoreElements())
		{
			BasicConnectionParameters basicParameters = bpEnumeration.nextElement();
			
			if(refreshSession.refresh(basicParameters, true)) // refresh in FORCED mode
			{
				MultiThreadedSocketServer.scmLogger.debug(contextInfo, basicParameters.getId() + " is refreshed (forced mode)");
				
				if (DatabaseAdapter.updateLastRefreshed(basicParameters.getId()))
					successful_ids.add(basicParameters.getId());
				else
					MultiThreadedSocketServer.scmLogger.error(contextInfo, "Error updating DB for refreshed connection " + basicParameters.getId() + " (forced mode)");
					
			}
			else
			{
				MultiThreadedSocketServer.scmLogger.error(contextInfo, basicParameters.getId() + " is not refreshed (forced mode)");
			}
		}
		
		return successful_ids;
	}
	
	public static ArrayList<Integer> refreshAllOpenedConnections(String source)
	{
		ArrayList<Integer> successful_ids = new ArrayList<Integer>(0);
		
		String contextInfo = "InitSessions:: refreshAllOpenedConnections(source:: " + source + ")";
		
		Vector<BasicConnectionParameters> connections = DatabaseAdapter.openedConnections();
		
		if (connections.size() == 0)
			return successful_ids;
		
		RefreshSession refreshSession = new RefreshSession();
		
		Enumeration<BasicConnectionParameters> bpEnumeration = connections.elements();
		
		while (bpEnumeration.hasMoreElements())
		{
			BasicConnectionParameters basicParameters = bpEnumeration.nextElement();
			
			if(refreshSession.refresh(basicParameters, true)) // refresh in FORCED mode
			{
				MultiThreadedSocketServer.scmLogger.debug(contextInfo, basicParameters.getId() + " is refreshed (forced mode)");
				
				if (DatabaseAdapter.updateLastRefreshed(basicParameters.getId()))
					successful_ids.add(basicParameters.getId());
				else
					MultiThreadedSocketServer.scmLogger.error(contextInfo, "Error updating DB for refreshed connection " + basicParameters.getId() + " (forced mode)");
					
			}
			else
			{
				MultiThreadedSocketServer.scmLogger.error(contextInfo, basicParameters.getId() + " is not refreshed (forced mode)");
			}
		}
		
		return successful_ids;
	}
	
	public static ArrayList<Integer> closeAllOpenedFreeConnections(String source)
	{
		ArrayList<Integer> successful_ids = new ArrayList<Integer>(0);
		
		Vector<BasicConnectionParameters> connections = DatabaseAdapter.openedFreeConnections();
		
		if (connections.size() == 0)
			return successful_ids;
		
		Enumeration<BasicConnectionParameters> bpEnumeration = connections.elements();
		
		while (bpEnumeration.hasMoreElements())
		{
			BasicConnectionParameters basicParameters = bpEnumeration.nextElement();
			
			if(InitSessions.closeConnection(basicParameters, source))
			{
				successful_ids.add(basicParameters.getId());
				
				MultiThreadedSocketServer.scmLogger.debug("InitSessions:: closeAllOpenedFreeConnections(source:: " + source + ")", basicParameters.getId() + " is closed");
			}
			else
			{
				MultiThreadedSocketServer.scmLogger.error("InitSessions:: closeAllOpenedFreeConnections(source:: " + source + ")", basicParameters.getId() + " is not closed");
			}
		}
		
		return successful_ids;
	}
	
	public static ArrayList<Integer> closeConnections(ArrayList<Integer> ids, String source)
	{
		ArrayList<Integer> successful_ids = new ArrayList<Integer>(0);
		
		Vector<BasicConnectionParameters> connections = DatabaseAdapter.openedFreeConnectionsFromList(ids);
		
		if (connections.size() == 0)
			return successful_ids;
		
		Enumeration<BasicConnectionParameters> bpEnumeration = connections.elements();
		
		while (bpEnumeration.hasMoreElements())
		{
			BasicConnectionParameters basicParameters = bpEnumeration.nextElement();
			
			if(InitSessions.closeConnection(basicParameters, source))
			{
				successful_ids.add(basicParameters.getId());
				
				MultiThreadedSocketServer.scmLogger.debug("InitSessions:: closeConnections(ids:: " + ids.toString() + ", source:: " + source + ")", basicParameters.getId() + " is closed");
			}
			else
			{
				MultiThreadedSocketServer.scmLogger.error(basicParameters.getId() + " is not closed");
			}
		}
		
		return successful_ids;
	}
}
