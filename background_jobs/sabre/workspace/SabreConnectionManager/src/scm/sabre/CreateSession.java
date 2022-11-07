package scm.sabre;

import java.io.IOException;
import java.util.UUID;

import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;

import scm.InitSessions;
import scm.MultiThreadedSocketServer;
import scm.configuration.PropertyValues;
import scm.data_handler.BasicConnectionParameters;
import scm.data_handler.ConnectionParameters;
import scm.https.MessagePoster;
import scm.utils.Utils;

public class CreateSession
{
	public ConnectionParameters create()
	{
		ConnectionParameters connectionParameters = new ConnectionParameters();
		
		String contextInfo = "CreateSession:: create()";
		
		String conversationId = UUID.randomUUID().toString();
		
		MessagePoster messagePoster = new MessagePoster("createSession", PropertyValues.serviceEndpoint);
		
		StringBuffer strOut = new StringBuffer("<?xml version=\"1.0\"?>");
		
		strOut.append("<SOAP-ENV:Envelope xmlns:SOAP-ENV=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:eb=\"http://www.ebxml.org/namespaces/messageHeader\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" xmlns:xsd=\"http://www.w3.org/1999/XMLSchema\">");
		strOut.append("	<SOAP-ENV:Header>");
		strOut.append("		<eb:MessageHeader SOAP-ENV:mustUnderstand=\"1\" eb:version=\"1.0\">");
		strOut.append("			<eb:ConversationId>" + conversationId + "@touristtube.com</eb:ConversationId>");
		strOut.append("			<eb:From>");
		strOut.append("					<eb:PartyId type=\"urn:x12.org:IO5:01\">touristtube.com</eb:PartyId>");
		strOut.append("			</eb:From>");
		strOut.append("			<eb:To>");
		strOut.append("					<eb:PartyId type=\"urn:x12.org:IO5:01\">webservices.havail.sabre.com</eb:PartyId>");
		strOut.append("			</eb:To>");
		strOut.append("			<eb:CPAId>" + PropertyValues.soapIPCC + "</eb:CPAId>");
		strOut.append("			<eb:Service eb:type=\"Sabre\">SessionCreateRQ</eb:Service>");
		strOut.append("			<eb:Action>SessionCreateRQ</eb:Action>");
		strOut.append("			<eb:MessageData>");
		strOut.append("				<eb:MessageId>mid:" + conversationId + "@touristtube.com</eb:MessageId>");
		strOut.append("				<eb:Timestamp>" + InitSessions.sdftz_timestamp.format(connectionParameters.getCurrentDate()) + "</eb:Timestamp>");
		strOut.append("				<eb:TimeToLive>" + InitSessions.sdftz_timestamp.format(connectionParameters.getTTL()) + "</eb:TimeToLive>");
		strOut.append("			</eb:MessageData>");
		strOut.append("		</eb:MessageHeader>");
		strOut.append("		<wsse:Security xmlns:wsse=\"http://schemas.xmlsoap.org/ws/2002/12/secext\" xmlns:wsu=\"http://schemas.xmlsoap.org/ws/2002/12/utility\">");
		strOut.append("			<wsse:UsernameToken>");
		strOut.append("				<wsse:Username>" + PropertyValues.soapUsername + "</wsse:Username>");
		strOut.append("				<wsse:Password>" + PropertyValues.soapPassword + "</wsse:Password>");
		strOut.append("				<Organization>" + PropertyValues.soapIPCC + "</Organization>");
		strOut.append("				<Domain>" + PropertyValues.soapDomain + "</Domain>");
		strOut.append("			</wsse:UsernameToken>");
		strOut.append("		</wsse:Security>");
		strOut.append("	</SOAP-ENV:Header>");
		strOut.append("	<SOAP-ENV:Body>");
		/*
		strOut.append(" 	<eb:Manifest soap-env:mustUnderstand=\"1\" eb:version=\"1.0\">");
		strOut.append("			<eb:Reference xlink:href=\"cid:rootelement\" xlink:type=\"simple\" />");
		strOut.append("		</eb:Manifest>");
		*/
		strOut.append("		<SessionCreateRQ>");
		strOut.append("			<POS>");
		strOut.append("				<Source PseudoCityCode=\"" + PropertyValues.soapIPCC + "\"/>");
		strOut.append("			</POS>");
		strOut.append("		</SessionCreateRQ>");
		// strOut.append("		<ns:SessionCreateRQ xmlns:ns=\"http://www.opentravel.org/OTA/2002/11\" />");
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
			
			MultiThreadedSocketServer.scmLogger.errorException("CreateSession:: create()", e, InitSessions.properties.loggingFullTrace);
		}
		
		if (xmlResponse == null)
		{
			MultiThreadedSocketServer.scmLogger.error(contextInfo, "connection has not been created, XML response is null");
			
			return null;
		}
		
		BasicConnectionParameters basicParameters = this.isSessionCreated(connectionParameters.getBasicParameters(), xmlResponse);
		if (basicParameters == null)
		{
			MultiThreadedSocketServer.scmLogger.error(contextInfo, "connection has not been created");
			
			return null;
		}
		
		MultiThreadedSocketServer.scmLogger.debug(contextInfo, "connection has been created");
		
		connectionParameters.setConnectionStatus("FREE");
		
		connectionParameters.setOpened();
		
		return connectionParameters;
	}
	
	public ConnectionParameters createSession(int userId)
	{
		ConnectionParameters connectionParameters = this.create();
		
		if (connectionParameters == null)
			return null;
		
		connectionParameters.setUserId(userId);
		
		return connectionParameters;
	}
	
	private BasicConnectionParameters isSessionCreated(BasicConnectionParameters basicParameters, String xmlResponse)
	{
		String contextInfo = "CreateSession:: isSessionCreated()";
		
		Document doc = null;
		
		try
		{
			doc = Utils.xmlStringToDOMDocument(xmlResponse);
		}
		catch (Exception e)
		{
			MultiThreadedSocketServer.scmLogger.error(contextInfo,  e.getMessage());
			
			MultiThreadedSocketServer.scmLogger.errorException(contextInfo, e, InitSessions.properties.loggingFullTrace);
			
			return null;
		}
		
		NodeList nList = doc.getElementsByTagName("soap-env:Envelope");
		
		// for (int temp = 0;temp < nList.getLength();temp++)
		if (nList != null && nList.getLength() == 1)
		{
			// Node nNode = nList.item(temp);
			Node nNode = nList.item(0);
			
			if(nNode.getNodeType() == Node.ELEMENT_NODE)
			{
				Element eElement = (Element) nNode;
				
				Node firstChild = eElement.getElementsByTagName("soap-env:Body").item(0).getFirstChild();
				
				if (firstChild.getNodeName().contains("Fault"))
				{
					Element firstElement = (Element) firstChild;
					
					NodeList fcList = firstElement.getElementsByTagName("faultcode");
					
					MultiThreadedSocketServer.scmLogger.error(contextInfo,  "faultcode:: " + fcList.item(0).getTextContent());
					
					return null;
				}
				
				
				basicParameters.setToken(eElement.getElementsByTagName("wsse:BinarySecurityToken").item(0).getTextContent());
				basicParameters.setConversationId(eElement.getElementsByTagName("ConversationId").item(0).getTextContent());
				
				return basicParameters;
			}
			else
				return null;
		}
		else
			return null;
	}
}
