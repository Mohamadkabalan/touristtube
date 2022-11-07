package scm.sabre;

import java.io.IOException;
import java.util.Hashtable;

import org.w3c.dom.Document;
import org.w3c.dom.NodeList;

import scm.InitSessions;
import scm.MultiThreadedSocketServer;
import scm.configuration.PropertyValues;
import scm.data_handler.BasicConnectionParameters;
import scm.https.MessagePoster;
import scm.utils.Utils;

public class RefreshSession
{
	public boolean refresh(BasicConnectionParameters basicParameters)
	{
		return this.refresh(basicParameters, false);
	}
	
	public boolean refresh(BasicConnectionParameters basicParameters, boolean forcedMode)
	{
		String contextInfo = "RefreshSession:: refresh(" + basicParameters.toShortString() + ")";
		
		if (!forcedMode)
			MultiThreadedSocketServer.scmLogger.debug(contextInfo, "last refreshed:: " + InitSessions.sdf_timestamp.format(basicParameters.getLastRefreshed()));
		
		Hashtable<String, String> contextParameters = new Hashtable<String, String>(1);
		contextParameters.put("connection", String.valueOf(basicParameters.getId()));
		// contextParameters.put("securityToken", basicParameters.getToken());
		// contextParameters.put("conversationId", basicParameters.getConversationId());
		
		MessagePoster messagePoster = new MessagePoster("refreshSession", contextParameters, PropertyValues.serviceEndpoint);
		
		StringBuffer strOut = new StringBuffer("<?xml version=\"1.0\"?>");
		
		strOut.append("<SOAP-ENV:Envelope xmlns:SOAP-ENV=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:eb=\"http://www.ebxml.org/namespaces/messageHeader\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" xmlns:xsd=\"http://www.w3.org/1999/XMLSchema\">");
		strOut.append("	<SOAP-ENV:Header>");
		strOut.append("		<eb:MessageHeader SOAP-ENV:mustUnderstand=\"1\" eb:version=\"1.0\">");
		strOut.append("			<eb:From>");
		strOut.append("					<eb:PartyId type=\"urn:x12.org:IO5:01\">touristtube.com</eb:PartyId>");
		strOut.append("			</eb:From>");
		strOut.append("			<eb:To>");
		strOut.append("					<eb:PartyId type=\"urn:x12.org:IO5:01\">webservices.havail.sabre.com</eb:PartyId>");
		strOut.append("			</eb:To>");
		strOut.append("			<eb:CPAId>" + PropertyValues.soapIPCC + "</eb:CPAId>");
		strOut.append("			<eb:ConversationId>" + basicParameters.getConversationId() + "</eb:ConversationId>");
		strOut.append("			<eb:Service eb:type=\"Sabre\">OTA_PingRQ</eb:Service>");
		strOut.append("			<eb:Action>OTA_PingRQ</eb:Action>");
		strOut.append("			<eb:MessageData>");
		strOut.append("				<eb:MessageId>mid:" + basicParameters.getConversationId() + "</eb:MessageId>");
		strOut.append("				<eb:Timestamp>" + InitSessions.sdftz_timestamp.format(basicParameters.getCurrentDate()) + "</eb:Timestamp>");
		strOut.append("				<eb:TimeToLive>" + InitSessions.sdftz_timestamp.format(basicParameters.getTTL()) + "</eb:TimeToLive>");
		strOut.append("			</eb:MessageData>");
		strOut.append("		</eb:MessageHeader>");
		strOut.append("		<wsse:Security xmlns:wsse=\"http://schemas.xmlsoap.org/ws/2002/12/secext\" xmlns:wsu=\"http://schemas.xmlsoap.org/ws/2002/12/utility\">");
		strOut.append("		 <wsse:BinarySecurityToken valueType=\"String\" EncodingType=\"wsse:Base64Binary\">" + basicParameters.getToken() + "</wsse:BinarySecurityToken>");
		strOut.append("		</wsse:Security>");
		strOut.append("	</SOAP-ENV:Header>");
		strOut.append("	<SOAP-ENV:Body>");
		strOut.append("			<OTA_PingRQ xmlns=\"http://www.opentravel.org/OTA/2003/05\" TimeStamp=\"" + InitSessions.sdft_timestamp.format(basicParameters.getCurrentDate()) + "\" Version=\"1.0.0\">");
		strOut.append("				<EchoData>Are you there</EchoData>");
		strOut.append("			</OTA_PingRQ>");
		strOut.append("	</SOAP-ENV:Body>");
		strOut.append("</SOAP-ENV:Envelope>");
		
		String xmlResponse = null;
		
		try
		{
			xmlResponse = messagePoster.postMessage(strOut.toString());
		}
		catch (IOException e)
		{
			MultiThreadedSocketServer.scmLogger.error(contextInfo, e.getMessage());
			
			MultiThreadedSocketServer.scmLogger.errorException(contextInfo, e, InitSessions.properties.loggingFullTrace);
		}
		
		if (xmlResponse == null)
		{
			MultiThreadedSocketServer.scmLogger.error(contextInfo, basicParameters.getId() + " has not been refreshed, XML response is null");
			
			return false;
		}
		
		boolean isRefreshed = this.isSessionRefreshed(basicParameters.getId(), xmlResponse);
		
		MultiThreadedSocketServer.scmLogger.debug(contextInfo, basicParameters.getId() + " has" + (isRefreshed?"":" not") + " been refreshed");
		
		return isRefreshed;
	}
	
	private boolean isSessionRefreshed(int connectionId, String xmlResponse)
	{
		String contextInfo = "RefreshSession:: isSessionRefreshed(connection:: " + connectionId + ")";
		
		Document doc = null;
		
		try
		{
			doc = Utils.xmlStringToDOMDocument(xmlResponse);
		}
		catch (Exception e)
		{
			MultiThreadedSocketServer.scmLogger.error(contextInfo, e.getMessage());
			
			MultiThreadedSocketServer.scmLogger.errorException(contextInfo, e, InitSessions.properties.loggingFullTrace);
			
			return false;
		}
		
		NodeList nList = doc.getElementsByTagName("Success");
		
		return (nList != null && nList.getLength() == 1);
	}
}
