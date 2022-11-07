package scm.configuration;

import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.util.ArrayList;
import java.util.Hashtable;
import java.util.Properties;

import scm.MultiThreadedSocketServer;

public class PropertyValues
{
	private Properties properties = new Properties();
	
	public static ArrayList<String> reloadableValues = new ArrayList<String>();
	
	// public static boolean debug = false;
	public static boolean loggingFullTrace = false;
	
	public static int server_port = 1234;
	public static String db_host = "127.0.0.1";
	public static int db_port = 3306;
	public static String db_name = "touristtube";
	public static String db_user = "touristtube";
	public static String db_password = "touristtube";
	public static boolean db_use_ssl = false;
	
	public static String timezone = "Europe/Paris";
	
	public static String serviceEndpoint = "https://webservices.sabre.com/websvc";
	public static int connectionReadTimeoutMS = 2000;
	public static int maxConnectionAttempts = 2;
	public static int connectionReestablishmentWaitingMS = 2;
	
	public static boolean gzipInput = false;
	public static boolean gzipOutput = false;
	
	public static String soapUsername = "141258";
	public static String soapPassword = "WS328537";
	public static String soapIPCC = "YV4I";
	public static String soapDomain = "DEFAULT";
	
	public static int connectionLifetimeDays = 7;
	public static int connectionLifetimeSafetyMarginDays = 2;
	
	public static int connectionRefreshCycleMinutes = 15;
	public static int connectionRefreshSafetyMarginMinutes = 3;
	public static double shortPeriodCheckerCycleMinutes = 4.5; 
	
	public static int maxBFMConnections = 31;
	public static int maxBookingConnections = 19;
	
	public static int nInitialBFMConnections = 5;
	public static int nInitialBookingConnections = 3;
	
	public static int nConnectionsToOpenWhenNeeded = 2;
	
	public static int connectionServiceTimeoutMS = 5000;
	public static int waitingTimeMS = 1000;
	public static int nConnectionServiceRetries = 4;
	
	public static boolean autoCleanOnClose = false;
	
	public static Hashtable<String, Object> propertyValues = new Hashtable<String, Object>();
	
	public PropertyValues()
	{
		reloadableValues.add("logging.full_trace");
		reloadableValues.add("connection_read_timeout_ms");
		reloadableValues.add("max_connection_attempts");
		reloadableValues.add("connection_reestablishment_waiting_ms");
		reloadableValues.add("gzip_input");
		reloadableValues.add("gzip_output");
		reloadableValues.add("connection_service_timeout_ms");
		reloadableValues.add("waiting_time_ms");
		reloadableValues.add("n_connection_service_retries");
		reloadableValues.add("auto_clean_on_close");
	}
	
	public void getPropertyValues(String propertiesFilePath) // throws IOException
	{
		try
		{
			properties.load(new FileInputStream(propertiesFilePath));
			
			MultiThreadedSocketServer.scmLogger.debug("PropertyValues:: getPropertyValues(propertiesFilePath:: " + propertiesFilePath + ")", "loaded configuration file " + propertiesFilePath);
			
			// debug = Boolean.parseBoolean(properties.getProperty("debug", String.valueOf(debug)));
			
			server_port = Integer.parseInt(properties.getProperty("server.port", String.valueOf(server_port)));
			db_host = properties.getProperty("db.host", db_host);
			db_port = Integer.parseInt(properties.getProperty("db.port", String.valueOf(db_port)));
			db_user = properties.getProperty("db.name", db_name);
			db_user = properties.getProperty("db.user", db_user);
			db_password = properties.getProperty("db.password", db_password);
			db_use_ssl = properties.getProperty("db.use_ssl", String.valueOf(db_use_ssl)).equalsIgnoreCase("true");
			
			timezone = properties.getProperty("environment.timezone", timezone);
			
			serviceEndpoint = properties.getProperty("service_endpoint", serviceEndpoint);
			
			soapUsername = properties.getProperty("soap.username", soapUsername);
			soapPassword = properties.getProperty("soap.password", soapPassword);
			soapIPCC = properties.getProperty("soap.ipcc", soapIPCC);
			soapDomain = properties.getProperty("soap.domain", soapDomain);
			
			connectionLifetimeDays = Integer.parseInt(properties.getProperty("connection_lifetime_days", String.valueOf(connectionLifetimeDays)));
			connectionLifetimeSafetyMarginDays = Integer.parseInt(properties.getProperty("connection_lifetime_safety_margin_days", String.valueOf(connectionLifetimeSafetyMarginDays)));
			
			connectionRefreshCycleMinutes = Integer.parseInt(properties.getProperty("connection_refresh_cycle_minutes", String.valueOf(connectionRefreshCycleMinutes)));
			connectionRefreshSafetyMarginMinutes = Integer.parseInt(properties.getProperty("connection_refresh_safety_margin_minutes", String.valueOf(connectionRefreshSafetyMarginMinutes)));
			shortPeriodCheckerCycleMinutes = Double.parseDouble(properties.getProperty("short_period_checker_cycle_minutes", String.valueOf(shortPeriodCheckerCycleMinutes)));
			
			maxBFMConnections = Integer.parseInt(properties.getProperty("max_bfm_connections", String.valueOf(maxBFMConnections)));
			maxBookingConnections = Integer.parseInt(properties.getProperty("max_booking_connections", String.valueOf(maxBookingConnections)));
			
			nInitialBFMConnections = Integer.parseInt(properties.getProperty("n_initial_bfm_connections", String.valueOf(nInitialBFMConnections)));
			nInitialBookingConnections = Integer.parseInt(properties.getProperty("n_initial_booking_connections", String.valueOf(nInitialBookingConnections)));
			
			nConnectionsToOpenWhenNeeded = Integer.parseInt(properties.getProperty("n_connections_to_open_when_needed", String.valueOf(nConnectionsToOpenWhenNeeded)));
			
			this.fetchReloadableValues(properties);
		}
		catch(FileNotFoundException fnfe)
		{
			fnfe.printStackTrace();
		}
		catch(IOException ioe)
		{
			ioe.printStackTrace();
		}
		catch(IllegalArgumentException iae)
		{
			iae.printStackTrace();
		}
	}
	
	public void fetchReloadableValues(Properties properties)
	{
		if (properties == null || properties.isEmpty())
			return;
		
		loggingFullTrace = Boolean.parseBoolean(properties.getProperty("logging.full_trace", String.valueOf(loggingFullTrace)));
		
		connectionReadTimeoutMS = Integer.parseInt(properties.getProperty("connection_read_timeout_ms", String.valueOf(connectionReadTimeoutMS)));
		maxConnectionAttempts = Integer.parseInt(properties.getProperty("max_connection_attempts", String.valueOf(maxConnectionAttempts)));
		connectionReestablishmentWaitingMS = Integer.parseInt(properties.getProperty("connection_reestablishment_waiting_ms", String.valueOf(connectionReestablishmentWaitingMS)));
		
		gzipInput = Boolean.parseBoolean(properties.getProperty("gzip_input", String.valueOf(gzipInput)));
		gzipOutput = Boolean.parseBoolean(properties.getProperty("gzip_output", String.valueOf(gzipOutput)));
		
		connectionServiceTimeoutMS = Integer.parseInt(properties.getProperty("connection_service_timeout_ms", String.valueOf(connectionServiceTimeoutMS)));
		waitingTimeMS = Integer.parseInt(properties.getProperty("waiting_time_ms", String.valueOf(waitingTimeMS)));
		nConnectionServiceRetries = Integer.parseInt(properties.getProperty("n_connection_service_retries", String.valueOf(nConnectionServiceRetries)));
		
		autoCleanOnClose = Boolean.parseBoolean(properties.getProperty("auto_clean_on_close", String.valueOf(autoCleanOnClose)));
	}
	
	public void fetchReloadableValues()
	{
		this.fetchReloadableValues(this.properties);
	}
}
