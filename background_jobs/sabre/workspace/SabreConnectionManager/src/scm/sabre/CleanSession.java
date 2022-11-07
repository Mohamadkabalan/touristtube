package scm.sabre;

import java.io.IOException;
import java.util.Date;
import java.util.Hashtable;

import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;

import scm.InitSessions;
import scm.MultiThreadedSocketServer;
import scm.configuration.PropertyValues;
import scm.data_handler.BasicConnectionParameters;
import scm.https.MessagePoster;
import scm.utils.Utils;

public class CleanSession
{
	static Date TimeNowDateFormat = new Date();
	
	public boolean clean(BasicConnectionParameters basicParameters)
	{
		// MultiThreadedSocketServer.scmLogger.trace("It's time (TTL) for the cleaning of the session:: " + InitSessions.sdf_timestamp.format(basicParameters.getTTL()));
		
		String contextInfo = "CleanSession:: clean(" + basicParameters.toShortString() + ")";
		
		Hashtable<String, String> contextParameters = new Hashtable<String, String>(1);
		contextParameters.put("connection", String.valueOf(basicParameters.getId()));
		// contextParameters.put("securityToken", basicParameters.getToken());
		// contextParameters.put("conversationId", basicParameters.getConversationId());
		
		MessagePoster messagePoster = new MessagePoster("cleanSession", contextParameters, PropertyValues.serviceEndpoint);
		
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
		strOut.append("			<eb:Service eb:type=\"Sabre\">IgnoreTransactionLLSRQ</eb:Service>");
		strOut.append("			<eb:Action>IgnoreTransactionLLSRQ</eb:Action>");
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
		// strOut.append("				 <IgnoreTransactionRQ xmlns=\"https://webservices.havail.sabre.com/sabreXML/2011/10\" xmlns:xs=\"http://www.w3.org/2001/XMLSchema\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" ReturnHostCommand=\"false\" TimeStamp=\"" + InitSessions.sdft_timestamp.format(basicParameters.getCurrentDate()) + "\" Version=\"2.0.0\" />");
		strOut.append("				 <IgnoreTransactionRQ xmlns=\"http://webservices.sabre.com/sabreXML/2011/10\" xmlns:xs=\"http://www.w3.org/2001/XMLSchema\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" ReturnHostCommand=\"false\" Version=\"2.0.0\" />");
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
			MultiThreadedSocketServer.scmLogger.error(contextInfo, basicParameters.getId() + " has not been cleaned, XML response is null");
			
			return false;
		}
		
		boolean isCleaned = this.isSessionCleaned(basicParameters.getId(), xmlResponse);
		
		MultiThreadedSocketServer.scmLogger.debug(contextInfo, basicParameters.getId() + " has" + (isCleaned?"":" not") + " been cleaned");
		
		return isCleaned;
	}
	
	private boolean isSessionCleaned(int connectionId, String xmlResponse)
	{
		boolean successful = false;
		
		String contextInfo = "CleanSession:: isSessionCleaned(connection:: " + connectionId + ")";
		
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
		
		NodeList nList = doc.getElementsByTagName("stl:ApplicationResults");
		
		if (nList != null && nList.getLength() == 1)
		{
			Node nNode = nList.item(0);
			
			if(nNode.getNodeType() == Node.ELEMENT_NODE)
			{
				Element eElement = (Element) nNode;
				
				successful = (eElement.getAttribute("status").equals("Complete") && eElement.getElementsByTagName("stl:Success") != null);
			}
			else
				return false;
		}
		
		return successful;
	}
}
