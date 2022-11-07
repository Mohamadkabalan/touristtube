package scm.logging;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.Iterator;
import java.util.UUID;

import org.apache.logging.log4j.Level;
import org.apache.logging.log4j.LogManager;
import org.apache.logging.log4j.Logger;
import org.apache.logging.log4j.core.LoggerContext;
import org.apache.logging.log4j.core.config.LoggerConfig;

public class ScmLogger
{
	private static boolean fullTrace = false;
	
	public static final Logger logger = LogManager.getLogger(ScmLogger.class);
	public static Level originalLoggerLevel;
	
	public ScmLogger()
	{
		ScmLogger.originalLoggerLevel = this.getCurrentLevel();
	}
	
	public ScmLogger(boolean fullTrace)
	{
		ScmLogger.fullTrace = fullTrace;
		
		ScmLogger.originalLoggerLevel = this.getCurrentLevel();
	}
	
	public void enableFullTrace()
	{
		ScmLogger.fullTrace = true;
	}
	
	public void disableFullTrace()
	{
		ScmLogger.fullTrace = false;
	}
	
	public boolean isFullTraceEnabled()
	{
		return ScmLogger.fullTrace;
	}
	
	public boolean isFullTraceDisabled()
	{
		return (!ScmLogger.fullTrace);
	}
	
	public void setFullTrace(boolean fullTrace)
	{
		ScmLogger.fullTrace = fullTrace;
	}
	
	public void log(Level level, String str)
	{
		logger.log(level, str);
	}
	
	public void log(String str)
	{
		this.log(Level.DEBUG, str);
	}
	
	private ArrayList<String> exceptionStackTraceElements(Exception e, boolean fullTrace)
	{
		ArrayList<String> traceElements = new ArrayList<String>();
		
		String uuidString = UUID.randomUUID().toString().replaceAll("\\-", "");
		
		StackTraceElement[] stackTraceElements = e.getStackTrace();
		int maxTraceElements = stackTraceElements.length;
		
		for(int indx = 0;indx < maxTraceElements;indx++)
		{
			StackTraceElement stackTraceElement = stackTraceElements[indx];
			
			traceElements.add(uuidString + (fullTrace?"[" + (indx + 1) + " / " + maxTraceElements + "]":"") + (indx == 0?"\t" + e.getMessage():"") + "\t" + (stackTraceElement.getFileName() == null?(stackTraceElement.getFileName() + ":: "):"") + stackTraceElement.getClassName() + ":: " + stackTraceElement.getMethodName() + "() (Line " + stackTraceElement.getLineNumber() + ")");
			
			if (!fullTrace)
				break;
		}
		
		return traceElements;
	}
	
	@SuppressWarnings("unused")
	private String exceptionToString(Exception e, boolean fullTrace)
	{
		String uuidString = UUID.randomUUID().toString().replaceAll("\\-", "");
		
		StringBuilder exceptionMessage = new StringBuilder("");
		
		StackTraceElement[] stackTraceElements = e.getStackTrace();
		int maxTraceElements = stackTraceElements.length;
		
		for(int indx = 0;indx < maxTraceElements;indx++)
		{
			StackTraceElement stackTraceElement = stackTraceElements[indx];
			
			exceptionMessage.append(uuidString + "[" + (indx + 1) + " / " + maxTraceElements + "]" + (indx == 0?"\t" + e.getMessage():"") + "\t" + (stackTraceElement.getFileName() == null?(stackTraceElement.getFileName() + ":: "):"") + ":: " + stackTraceElement.getClassName() + ":: " + stackTraceElement.getMethodName() + "() (Line " + stackTraceElement.getLineNumber() + ")");
			
			if (!fullTrace)
				break;
		}
		
		return exceptionMessage.toString();
	}
	
	public void trace(String str)
	{
		this.log(Level.TRACE, str);
	}
	
	public void trace(String contextInfo, String str)
	{
		this.trace((contextInfo.length() > 0?contextInfo + ":: ":"") + str);
	}
	
	public void traceException(Exception e, boolean fullTrace)
	{
		// this.trace(this.exceptionToString(e, fullTrace));
		
		ArrayList<String> traceElements = this.exceptionStackTraceElements(e, fullTrace);
		
		Iterator<String> traceElementsIterator = traceElements.iterator();
		
		while (traceElementsIterator.hasNext())
		{
			this.trace(traceElementsIterator.next());
		}
	}
	
	public void traceException(Exception e)
	{
		this.traceException(e, ScmLogger.fullTrace);
	}
	
	public void traceException(String contextInfo, Exception e, boolean fullTrace)
	{
		// this.trace(this.exceptionToString(contextInfo, e, fullTrace));
		
		ArrayList<String> traceElements = this.exceptionStackTraceElements(e, fullTrace);
		
		Iterator<String> traceElementsIterator = traceElements.iterator();
		
		while (traceElementsIterator.hasNext())
		{
			this.trace(contextInfo, traceElementsIterator.next());
		}
	}
	
	public void traceException(String contextInfo, Exception e)
	{
		this.traceException(contextInfo, e, ScmLogger.fullTrace);
	}
	
	public void debug(String str)
	{
		this.log(Level.DEBUG, str);
	}
	
	public void debug(String contextInfo, String str)
	{
		this.debug((contextInfo.length() > 0?contextInfo + ":: ":"") + str);
	}
	
	public void debugException(Exception e, boolean fullTrace)
	{
		// this.debug(this.exceptionToString(e, fullTrace));
		
		ArrayList<String> traceElements = this.exceptionStackTraceElements(e, fullTrace);
		
		Iterator<String> traceElementsIterator = traceElements.iterator();
		
		while (traceElementsIterator.hasNext())
		{
			this.debug(traceElementsIterator.next());
		}
	}
	
	public void debugException(Exception e)
	{
		this.debugException(e, ScmLogger.fullTrace);
	}
	
	public void debugException(String contextInfo, Exception e, boolean fullTrace)
	{
		// this.debug(this.exceptionToString(contextInfo, e, fullTrace));
		
		ArrayList<String> traceElements = this.exceptionStackTraceElements(e, fullTrace);
		
		Iterator<String> traceElementsIterator = traceElements.iterator();
		
		while (traceElementsIterator.hasNext())
		{
			this.debug(contextInfo, traceElementsIterator.next());
		}
	}
	
	public void debugException(String contextInfo, Exception e)
	{
		this.debugException(contextInfo, e, ScmLogger.fullTrace);
	}
	
	public void info(String str)
	{
		this.log(Level.INFO, str);
	}
	
	public void info(String contextInfo, String str)
	{
		this.info((contextInfo.length() > 0?contextInfo + ":: ":"") + str);
	}
	
	public void warn(String str)
	{
		this.log(Level.WARN, str);
	}
	
	public void warn(String contextInfo, String str)
	{
		this.warn((contextInfo.length() > 0?contextInfo + ":: ":"") + str);
	}
	
	public void error(String str)
	{
		this.log(Level.ERROR, str);
	}
	
	public void error(String contextInfo, String str)
	{
		this.error((contextInfo.length() > 0?contextInfo + ":: ":"") + str);
	}
	
	public void errorException(Exception e, boolean fullTrace)
	{
		// this.error(this.exceptionToString(e, fullTrace));
		
		ArrayList<String> traceElements = this.exceptionStackTraceElements(e, fullTrace);
		
		Iterator<String> traceElementsIterator = traceElements.iterator();
		
		while (traceElementsIterator.hasNext())
		{
			this.error(traceElementsIterator.next());
		}
	}
	
	public void errorException(Exception e)
	{
		this.errorException(e, ScmLogger.fullTrace);
	}
	
	public void errorException(String contextInfo, Exception e, boolean fullTrace)
	{
		// this.debug(this.exceptionToString(contextInfo, e, fullTrace));
		
		ArrayList<String> traceElements = this.exceptionStackTraceElements(e, fullTrace);
		
		Iterator<String> traceElementsIterator = traceElements.iterator();
		
		while (traceElementsIterator.hasNext())
		{
			this.error(contextInfo, traceElementsIterator.next());
		}
	}
	
	public void errorException(String contextInfo, Exception e)
	{
		this.errorException(contextInfo, e, ScmLogger.fullTrace);
	}
	
	public void fatal(String str)
	{
		this.log(Level.FATAL, str);
	}
	
	public void fatal(String contextInfo, String str)
	{
		this.fatal((contextInfo.length() > 0?contextInfo + ":: ":"") + str);
	}
	
	public void fatalException(Exception e, boolean fullTrace)
	{
		// this.fatal(this.exceptionToString(e, fullTrace));
		
		ArrayList<String> traceElements = this.exceptionStackTraceElements(e, fullTrace);
		
		Iterator<String> traceElementsIterator = traceElements.iterator();
		
		while (traceElementsIterator.hasNext())
		{
			this.fatal(traceElementsIterator.next());
		}
	}
	
	public void fatalException(Exception e)
	{
		this.fatalException(e, ScmLogger.fullTrace);
	}
	
	public void fatalException(String contextInfo, Exception e, boolean fullTrace)
	{
		// this.debug(this.exceptionToString(contextInfo, e, fullTrace));
		
		ArrayList<String> traceElements = this.exceptionStackTraceElements(e, fullTrace);
		
		Iterator<String> traceElementsIterator = traceElements.iterator();
		
		while (traceElementsIterator.hasNext())
		{
			this.fatal(contextInfo, traceElementsIterator.next());
		}
	}
	
	public void fatalException(String contextInfo, Exception e)
	{
		this.fatalException(contextInfo, e, ScmLogger.fullTrace);
	}
	
	public ArrayList<Level> getRegisteredLoggingLevels()
	{
		Level[] registeredLevels = Level.values();
		
		return new ArrayList<Level>(Arrays.asList(registeredLevels));
	}
	
	public ArrayList<String> getRegisteredLoggingLevelNames()
	{
		ArrayList<Level> registeredLevels = this.getRegisteredLoggingLevels();
		
		ArrayList<String> names = new ArrayList<String>(registeredLevels.size());
		
		Iterator<Level> levels = registeredLevels.iterator();
		
		while (levels.hasNext())
		{
			names.add(levels.next().name());
		}
		
		return names;
	}
	
	public boolean isLoggingLevelRegistered(Level loggingLevel)
	{
		  ArrayList<Level> registeredLevels = this.getRegisteredLoggingLevels();
		
		return registeredLevels.contains(loggingLevel);
	}
	
	public boolean isLoggingLevelRegistered(String loggingLevelName)
	{
		ArrayList<String> registeredLevels = this.getRegisteredLoggingLevelNames();
		
		return registeredLevels.contains(loggingLevelName);
	}
	
	public Level getCurrentLevel()
	{
		LoggerContext loggerContext = (LoggerContext) LogManager.getContext(false);
		LoggerConfig loggerConfiguration = loggerContext.getConfiguration().getLoggerConfig(LogManager.ROOT_LOGGER_NAME);
		
		return loggerConfiguration.getLevel();
	}
	
	public String getCurrentLevelName()
	{
		return this.getCurrentLevel().name();
	}
	
	public Level setCurrentLevel(Level loggingLevel)
	{
		LoggerContext loggerContext = (LoggerContext) LogManager.getContext(false);
		LoggerConfig loggerConfiguration = loggerContext.getConfiguration().getLoggerConfig(LogManager.ROOT_LOGGER_NAME);
		
		loggerConfiguration.setLevel(loggingLevel);
		loggerContext.updateLoggers();
		
		return this.getCurrentLevel();
	}
	
	public Level setCurrentLevel(String loggingLevelName)
	{
		if (loggingLevelName != null && this.isLoggingLevelRegistered(loggingLevelName))
		{
			return this.setCurrentLevel(Level.valueOf(loggingLevelName.toUpperCase()));
		}
		else
		{
			return this.getCurrentLevel();
		}
	}
	
	public void restore()
	{
		this.setCurrentLevel(ScmLogger.originalLoggerLevel);
	}
	
	public void on()
	{
		this.restore();
	}
	
	public void off(boolean logFatal)
	{
		this.setCurrentLevel((logFatal?Level.FATAL:Level.OFF));
	}
	
	public void off()
	{
		this.off(true);
	}
	
	public void enable()
	{
		this.on();
	}
	
	public void disable(boolean logFatal)
	{
		this.off(logFatal);
	}
	
	public void disable()
	{
		this.off();
	}
	
	public boolean isOn()
	{
		return (!this.getCurrentLevel().equals(Level.OFF));
	}

	public boolean isOff()
	{
		return this.getCurrentLevel().equals(Level.OFF);
	}
	
	public boolean isEnabled()
	{
		return this.isOn();
	}
	
	public boolean isDisabled()
	{
		return this.isOff();
	}
}
