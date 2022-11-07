package scm;

import java.io.IOException;
import java.net.ServerSocket;
import java.net.SocketException;

import scm.logging.ScmLogger;

public class MultiThreadedSocketServer
{
	protected static ServerSocket serverSocket;
	protected static boolean keepListening = true;
	protected static int nActiveConnections = 0;
	
	public static ScmLogger scmLogger;
	
	public MultiThreadedSocketServer(String configFilePath, boolean initConnections, boolean forcedRefresh)
	{
		InitSessions initSessions = new InitSessions();
		
		MultiThreadedSocketServer.scmLogger = new ScmLogger();
		
		initSessions.init(configFilePath);
		
		MultiThreadedSocketServer.scmLogger.setFullTrace(InitSessions.properties.loggingFullTrace);
		
		initSessions.prepare(initConnections, forcedRefresh);
		
		
		MultiThreadedSocketServer.scmLogger.info("Starting server on port " + InitSessions.properties.server_port);
		
		try
		{
			serverSocket = new ServerSocket(InitSessions.properties.server_port);
		}
		catch(IOException ioe)
		{
			MultiThreadedSocketServer.scmLogger.fatal("Could not create server socket on port " + InitSessions.properties.server_port + ", quitting.");
			
			System.exit(-1);
		}
		
		// Successfully created Server Socket, now wait for connections.
		while(keepListening)
		{
			try
			{
				// (new ClientServiceThread(serverSocket.accept())).start();
				(new ClientThread(serverSocket.accept())).start();
			}
			catch (SocketException se)
			{
				if (MultiThreadedSocketServer.keepListening)
				{
					MultiThreadedSocketServer.scmLogger.error("Server Socket unexpectedly closed...");
					MultiThreadedSocketServer.scmLogger.errorException("MultiThreadedSocketServer", se);
				}
			}
			catch(IOException ioe)
			{
				if (MultiThreadedSocketServer.keepListening)
				{
					MultiThreadedSocketServer.scmLogger.error("Server Socket unexpectedly closed...");
					MultiThreadedSocketServer.scmLogger.errorException("MultiThreadedSocketServer", ioe);
				}
			}
		}
		
		
		int activeConnectionsCount = 0;
		
		do
		{
			synchronized(this)
			{
				activeConnectionsCount = MultiThreadedSocketServer.nActiveConnections;
			}
			
			if (activeConnectionsCount == 0)
				break;
			
			MultiThreadedSocketServer.scmLogger.info("Server stopped accepting new connections, waiting for " + activeConnectionsCount + " connections to be fully serviced");
			
			try
			{
				Thread.sleep(InitSessions.properties.waitingTimeMS);
			}
			catch (InterruptedException e)
			{
				
			}
		}
		while (activeConnectionsCount != 0);
		
		
		MultiThreadedSocketServer.scmLogger.debug("MultiThreadedSocketServer", "Server stopped");
		
		try
		{
			serverSocket.close();
			
			serverSocket = null;
			
			MultiThreadedSocketServer.scmLogger.debug("MultiThreadedSocketServer", "Server Socket closed");
		}
		catch(Exception ioe)
		{
			MultiThreadedSocketServer.scmLogger.error("Problem stopping server socket");
			MultiThreadedSocketServer.scmLogger.errorException("MultiThreadedSocketServer", ioe);
			
			System.exit(-1);
		}
		
		
		boolean cyclicCheckersBusy = false;
		
		do
		{
			synchronized(InitSessions.periodicConnectionChecker)
			{
				cyclicCheckersBusy = InitSessions.periodicConnectionChecker.isBusy(); // more likely to occur, so we use this first
				
				if (!cyclicCheckersBusy) // acquire lock only if necessary
				{
					synchronized(InitSessions.dailyConnectionChecker)
					{
						cyclicCheckersBusy = InitSessions.dailyConnectionChecker.isBusy();
					}
				}
			}		
			
			if (!cyclicCheckersBusy)
				break;
			
			MultiThreadedSocketServer.scmLogger.info("Server stopped accepting new connections, waiting for cyclic checkers to terminate");
			
			try
			{
				Thread.sleep(InitSessions.properties.waitingTimeMS);
			}
			catch (InterruptedException e)
			{
				
			}
		}
		while (cyclicCheckersBusy);
	}
	
	public static void main(String[] args) throws Exception, Throwable
	{
		// run w/ -Dlog4j.configurationFile=conf/log4j2.xml
		// java -Djdk.tls.client.protocols=TLSv1.2 -Dlog4j.configurationFile=/home/scm/scm_service/conf/log4j2.xml -jar /home/scm/scm_service/SabreConnectionManager.jar /home/scm/scm_service/conf/config.properties true false &>>/home/scm/scm.log &
		
		if(args.length == 0)
		{
			System.err.println("Error:: No configuration file name given!");
			
			System.exit(-1);
		}
		
		String strArg = "";
		
		boolean initConnections = true;
		
		if (args.length >= 2)
		{
			strArg = args[1];
			initConnections = !(strArg.equals("0") || strArg.equalsIgnoreCase("f") || strArg.equalsIgnoreCase("false") || strArg.equalsIgnoreCase("n") || strArg.equalsIgnoreCase("no"));
		}
		
		boolean forcedRefresh = false;
		
		if (args.length >= 3)
		{
			strArg = args[2];
			forcedRefresh = !(strArg.equals("0") || strArg.equalsIgnoreCase("f") || strArg.equalsIgnoreCase("false") || strArg.equalsIgnoreCase("n") || strArg.equalsIgnoreCase("no"));
		}
		
		new MultiThreadedSocketServer(args[0], initConnections, forcedRefresh);
	}
}
