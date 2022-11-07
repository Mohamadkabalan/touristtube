package scm.https;

import java.io.BufferedReader;
import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.io.OutputStreamWriter;
import java.io.UnsupportedEncodingException;
import java.io.Writer;
import java.net.MalformedURLException;
import java.net.ProtocolException;
import java.net.URL;
import java.nio.charset.Charset;
import java.util.Enumeration;
import java.util.Hashtable;
import java.util.zip.GZIPInputStream;
import java.util.zip.GZIPOutputStream;

import javax.net.ssl.HttpsURLConnection;

import scm.InitSessions;
import scm.MultiThreadedSocketServer;
import scm.utils.Utils;

public class MessagePoster 
{
	private String urlString = null;
	private String traceString = "";
	
	private HttpsURLConnection connection = null;
	
	public MessagePoster(String caller, String urlString)
	{
		this.urlString = urlString;
		
		this.traceString = (caller != null?caller:"");
	}
	
	public MessagePoster(String caller, Hashtable<String, String> contextParameters, String urlString)
	{
		this.urlString = urlString;
		
		this.traceString = (caller != null?caller + ":: ":"") + Utils.hashToLogString(contextParameters);
	}
	
	private Hashtable<String, String> defaultRequestProperties()
	{
		Hashtable<String, String> requestProperties = new Hashtable<String, String>(6);
		
		requestProperties.put("username", "null");
		requestProperties.put("password", "");
		requestProperties.put("auth_method", "none");
		
		if (InitSessions.properties.gzipInput)
			requestProperties.put("Accept-Encoding", "gzip");
		
		if (InitSessions.properties.gzipOutput)
			requestProperties.put("Content-Encoding", "gzip");
		
		requestProperties.put("Content-Type", "text/xml;charset=UTF-8");
		requestProperties.put("SOAPAction", "OTA");
		requestProperties.put("Connection", "Keep-Alive");
		
		return requestProperties;
	}
	
	public void initConnection(String requestMethod)
	{
		String contextInfo = "MessagePoster:: initConnection():: " + this.traceString;
		
		URL url = null;
		
		try
		{
			url = new URL(this.urlString);
		}
		catch (MalformedURLException e)
		{
			MultiThreadedSocketServer.scmLogger.fatal(contextInfo, this.urlString + ":: " + e.getMessage());
			
			MultiThreadedSocketServer.scmLogger.fatalException(contextInfo, e, InitSessions.properties.loggingFullTrace);
			
			return;
		}
		
		MultiThreadedSocketServer.scmLogger.trace(contextInfo, "Connecting to " + this.urlString);
		
		try
		{
			this.connection = (HttpsURLConnection) url.openConnection();
		}
		catch (IOException e)
		{
			MultiThreadedSocketServer.scmLogger.trace(contextInfo, "Error creating connection to " + this.urlString + ":: " + e.getMessage());
			
			return;
		}
		
		
		MultiThreadedSocketServer.scmLogger.trace(contextInfo, "Successfully created connection to " + this.urlString);
		
		this.connection.setDoOutput(true);
		this.connection.setDoInput(true);
		
		
		MultiThreadedSocketServer.scmLogger.debug(contextInfo, "HttpsURLConnection connect timeout:: " + this.connection.getConnectTimeout() + " ms");
		
		try
		{
			this.connection.setConnectTimeout(InitSessions.properties.connectionServiceTimeoutMS);
			
			MultiThreadedSocketServer.scmLogger.debug(contextInfo, "HttpsURLConnection new connect timeout:: " + this.connection.getConnectTimeout() + " ms");
		}
		catch (IllegalArgumentException e)
		{
			MultiThreadedSocketServer.scmLogger.error(contextInfo, "Could not set HttpsURLConnection connect timeout to " + InitSessions.properties.connectionServiceTimeoutMS + " ms");
		}
		
		
		MultiThreadedSocketServer.scmLogger.debug(contextInfo, "HttpsURLConnection read timeout:: " + this.connection.getReadTimeout() + " ms");
		
		try
		{
			this.connection.setReadTimeout(InitSessions.properties.connectionReadTimeoutMS);
			
			MultiThreadedSocketServer.scmLogger.debug(contextInfo, "HttpsURLConnection new read timeout:: " + this.connection.getReadTimeout() + " ms");
		}
		catch (IllegalArgumentException e)
		{
			MultiThreadedSocketServer.scmLogger.error(contextInfo, "Could not set HttpsURLConnection read timeout to " + InitSessions.properties.connectionReadTimeoutMS + " ms");
		}
		
		try
		{
			this.connection.setInstanceFollowRedirects(true);
		}
		catch (SecurityException e)
		{
			MultiThreadedSocketServer.scmLogger.error(contextInfo, "Could not setup HttpsURLConnection to follow redirects");
		}
		
		
		try
		{
			this.connection.setRequestMethod(requestMethod);
		}
		catch (ProtocolException e)
		{
			MultiThreadedSocketServer.scmLogger.trace(contextInfo, e.getMessage());
		}
		
		Hashtable<String, String> defaultRequestProperties = this.defaultRequestProperties();
		
		if (!defaultRequestProperties.isEmpty())
		{
			Enumeration<String> propertyNames = defaultRequestProperties.keys();
			
			while (propertyNames.hasMoreElements())
			{
				String propertyName = propertyNames.nextElement();
				
				this.connection.setRequestProperty(propertyName, defaultRequestProperties.get(propertyName));
			}
		}
		
		MultiThreadedSocketServer.scmLogger.debug(contextInfo, "default request properties:: " + Utils.hashToLogString(defaultRequestProperties));
	}
	
	public void setupRequest(Hashtable<String, String> requestProperties)
	{
		if(requestProperties != null && !requestProperties.isEmpty())
		{
			Enumeration<String> propertyNames = requestProperties.keys();
			
			while (propertyNames.hasMoreElements())
			{
				String propertyName = propertyNames.nextElement();
				
				this.connection.setRequestProperty(propertyName,  requestProperties.get(propertyName));
			}
		}
	}
	
	private String gunzipStream(InputStream inStream) throws IOException, UnsupportedEncodingException
	{
		String contextInfo = "MessagePoster:: gunzipStream()";
		
		int bytesRead = 0;
		
		byte[] byteBuffer = new byte[1024];
		
		ByteArrayOutputStream byteArrayOutputStream = new ByteArrayOutputStream();
		
		GZIPInputStream gzipInputStream = null;
		
		try
		{
			gzipInputStream = new GZIPInputStream(inStream);
			
			while ((bytesRead = gzipInputStream.read(byteBuffer)) != -1)
			{
				byteArrayOutputStream.write(byteBuffer,  0, bytesRead);
			}
		}
		catch (IOException e)
		{
			MultiThreadedSocketServer.scmLogger.errorException(contextInfo, e);
			
			gzipInputStream = null;
			
			throw(e);
		}
		
		gzipInputStream = null;
		
		byte[] dataBytes = byteArrayOutputStream.toByteArray();
		
		byteArrayOutputStream = null;
		
		String response = null;
		
		try
		{
			response = new String(dataBytes, "UTF-8");
		}
		catch (UnsupportedEncodingException e)
		{
			MultiThreadedSocketServer.scmLogger.errorException(contextInfo, e);
			
			throw(e);
		}
		
		return response;
	}
	
	private String readStream(InputStream inStream) throws IOException
	{
		StringBuilder responseMessage = new StringBuilder("");
		
		String contextInfo = "MessagePoster:: readStream()";
		
		BufferedReader bufferedReader = null;
		
		String line;
		
		try
		{
			bufferedReader = new BufferedReader(new InputStreamReader(inStream));
			
			while((line = bufferedReader.readLine()) != null)
			{
				responseMessage.append(line);
			}
			
		}
		catch(IOException e)
		{
			MultiThreadedSocketServer.scmLogger.errorException(contextInfo, e);
			
			throw(e);
		}
		finally
		{
			if(bufferedReader != null)
			{
				try
				{
					bufferedReader.close();
				}
				catch(IOException e)
				{
					MultiThreadedSocketServer.scmLogger.errorException(contextInfo, e);
				}
				
				bufferedReader = null;
			}
		}
		
		return responseMessage.toString();
	}
	
	private void sendMessage(String message) throws IOException
	{
		String contextInfo = "MessagePoster:: sendMessage()";
		
		OutputStream outStream = null;
		Writer wout = null;
		
		try
		{
			outStream = this.connection.getOutputStream();
			wout = new OutputStreamWriter(outStream);
		
			wout.write(message);
			
			wout.flush();
			wout.close();
			
			outStream = null;
			wout = null;
			
			MultiThreadedSocketServer.scmLogger.debug(contextInfo, "Message successfully sent");
		}
		catch(IOException e)
		{
			outStream = null;
			wout = null;
			
			MultiThreadedSocketServer.scmLogger.errorException(contextInfo, e);
			
			throw(e);
		}
	}
	
	private void sendGzippedMessage(String message) throws IOException
	{
		String contextInfo = "MessagePoster:: sendGzippedMessage()";
		
		ByteArrayOutputStream byteArrayOutputStream = new ByteArrayOutputStream();
		
		GZIPOutputStream gzipOutputStream = null;
		
		try
		{
			gzipOutputStream = new GZIPOutputStream(byteArrayOutputStream);
			
			gzipOutputStream.write(message.getBytes(Charset.forName("UTF8")));
			
			this.connection.getOutputStream().write(byteArrayOutputStream.toByteArray());
			
			MultiThreadedSocketServer.scmLogger.debug(contextInfo, "Message successfully sent");
		}
		catch (IOException e)
		{
			MultiThreadedSocketServer.scmLogger.errorException(contextInfo, e);
			
			gzipOutputStream = null;
			
			throw(e);
		}
		
		try
		{
			gzipOutputStream.flush();
			gzipOutputStream.close();
		}
		catch (IOException e)
		{
			// NOP
		}
		
		gzipOutputStream = null;
		
		byteArrayOutputStream = null;
	}
	
	
	public String postMessage(String message, Hashtable<String, String> requestProperties, int attemptNumber) throws IOException
	{
		String contextInfo = "MessagePoster:: postMessage():: [attemptNumber:: " + attemptNumber + "]:: " + this.traceString;
		
		MultiThreadedSocketServer.scmLogger.debug(contextInfo, "About to send request:: " + message);
		
		String response;
			
		this.initConnection("POST");
		
		if (this.connection == null)
			return null;
		
		this.setupRequest(requestProperties);
		
		MultiThreadedSocketServer.scmLogger.debug(contextInfo, "request properties:: " + Utils.hashToLogString(this.connection.getRequestProperties()));
		
		if (InitSessions.properties.gzipOutput)
		{
			this.sendGzippedMessage(message);
		}
		else
		{
			this.sendMessage(message);
		}
		
		MultiThreadedSocketServer.scmLogger.debug(contextInfo, "HTTP response code:: " + this.connection.getResponseCode());
		
		InputStream inStream = null;
		
		try
		{
			inStream = this.connection.getInputStream();
		}
		catch (IOException e)
		{
			MultiThreadedSocketServer.scmLogger.error(contextInfo, "Could not get Input Stream");
			
			MultiThreadedSocketServer.scmLogger.errorException(contextInfo, e);
			
			InputStream errorStream = this.connection.getErrorStream();
			
			if (errorStream != null)
			{
				String errorString = null;
				
				try
				{
					if (InitSessions.properties.gzipInput)
					{
						errorString = this.gunzipStream(errorStream);
					}
					else
					{
						errorString = this.readStream(errorStream);
					}
					
					MultiThreadedSocketServer.scmLogger.error(contextInfo, "Received error:: " + errorString);
					
					return errorString;
				}
				catch (IOException er)
				{
					MultiThreadedSocketServer.scmLogger.error(contextInfo, "Could not get Error Stream");
					
					MultiThreadedSocketServer.scmLogger.errorException(contextInfo, er);
				}
			}
			
			if (attemptNumber < InitSessions.properties.maxConnectionAttempts)
			{
				try
				{
					Thread.sleep(InitSessions.properties.connectionReestablishmentWaitingMS);
				}
				catch (InterruptedException we)
				{
					
				}
				
				attemptNumber++;
				
				return this.postMessage(message, requestProperties, attemptNumber);
			}
			else
			{
				throw(e);
			}
		}
		
		if (InitSessions.properties.gzipInput)
		{
			response = this.gunzipStream(inStream);
		}
		else
		{
			response = this.readStream(inStream);
		}
		
		MultiThreadedSocketServer.scmLogger.debug(contextInfo, "Received response:: " + response);
		
		return response;
	}
	
	public String postMessage(String message, Hashtable<String, String> requestProperties) throws IOException
	{
		return this.postMessage(message, requestProperties, 1);
	}
	
	public String postMessage(String message) throws IOException
	{
		 return this.postMessage(message, null, 1);
	}
}
