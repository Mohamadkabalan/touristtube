package scm.sabre;

import java.io.IOException;
import java.util.Hashtable;

import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.NodeList;

import scm.InitSessions;
import scm.MultiThreadedSocketServer;
import scm.configuration.PropertyValues;
import scm.data_handler.BasicConnectionParameters;
import scm.https.MessagePoster;
import scm.utils.Utils;

public class CloseSession
{
	public boolean close(BasicConnectionParameters basicParameters)
	{
		String contextInfo = "CloseSession:: close(" + basicParameters.toShortString() + ")";
		
		Hashtable<String, String> contextParameters = new Hashtable<String, String>(1);
		contextParameters.put("connection", String.valueOf(basicParameters.getId()));
		// contextParameters.put("securityToken", basicParameters.getToken());
		// contextParameters.put("conversationId", basicParameters.getConversationId());
		
		MessagePoster messagePoster = new MessagePoster("closeSession", contextParameters, PropertyValues.serviceEndpoint);
		
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
		strOut.append("			<eb:Service eb:type=\"Sabre\">SessionCloseRQ</eb:Service>");
		strOut.append("			<eb:Action>SessionCloseRQ</eb:Action>");
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
		strOut.append("			<SessionCloseRQ>");
		strOut.append("				  <POS>");
		strOut.append("				  		<Source PseudoCityCode=\"" + PropertyValues.soapIPCC + "\"/>");
		strOut.append("				  </POS>");
		strOut.append("			 </SessionCloseRQ>");
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
			
			MultiThreadedSocketServer.scmLogger.errorException("CloseSession:: close(" + basicParameters.toShortString() + ")", e, InitSessions.properties.loggingFullTrace);
		}
		
		if (xmlResponse == null)
		{
			MultiThreadedSocketServer.scmLogger.error(contextInfo, basicParameters.getId() + " has not been closed, XML response is null");
			
			return false;
		}
		
		boolean isClosed = this.isSessionClosed(basicParameters.getId(), xmlResponse);
		
		MultiThreadedSocketServer.scmLogger.debug(contextInfo, basicParameters.getId() + " has" + (isClosed?"":" not") + " been closed");
		
		return isClosed;
	}
	
	private boolean isSessionClosed(int connectionId, String xmlResponse)
	{
		boolean successful = false;
		
		String contextInfo = "CloseSession:: isSessionClosed(connectionId:: " + connectionId + ")";
		
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
		
		NodeList nodelist_sessionCloseRS = doc.getElementsByTagName("SessionCloseRS");
		
		if (nodelist_sessionCloseRS != null && nodelist_sessionCloseRS.getLength() == 1)
		{
			Element sessionCloseRS = (Element) nodelist_sessionCloseRS.item(0);
			
			successful = (sessionCloseRS.hasAttributes() && sessionCloseRS.hasAttribute("status") && sessionCloseRS.getAttribute("status").equals("Approved"));
		}
		
		return successful;
	}
}
