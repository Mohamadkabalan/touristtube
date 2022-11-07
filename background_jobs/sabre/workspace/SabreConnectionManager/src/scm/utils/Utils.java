package scm.utils;

import java.io.ByteArrayInputStream;
import java.io.IOException;
import java.io.InputStream;
import java.nio.charset.StandardCharsets;
import java.util.Enumeration;
import java.util.Hashtable;
import java.util.Iterator;
import java.util.List;
import java.util.Map;
import java.util.Set;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.parsers.ParserConfigurationException;

import org.w3c.dom.Document;
import org.xml.sax.SAXException;

public class Utils 
{
	public static String hashToLogString(Hashtable<String, String> h)
	{
		StringBuffer strBuffer = new StringBuffer("");
		
		if (h != null && !h.isEmpty())
		{
			strBuffer.append('[');
			
			int counter = 0;
			
			Enumeration<String> keys = h.keys();
			
			while (keys.hasMoreElements())
			{
				String key = keys.nextElement();
				
				strBuffer.append((++counter > 1?", ":"") + key + ":: " + h.get(key));
			}
			
			strBuffer.append("]");
		}
		
		return strBuffer.toString();
	}
	
	public static String hashToLogString(Map<String, List<String>> h)
	{
		StringBuffer strBuffer = new StringBuffer("");
		
		if (h != null && !h.isEmpty())
		{
			strBuffer.append('[');
			
			int counter = 0;
			
			Iterator<String> keys = h.keySet().iterator();
			
			while (keys.hasNext())
			{
				String key = keys.next();
				
				strBuffer.append((++counter > 1?", ":"") + key + ":: " + h.get(key).toString());
			}
			
			strBuffer.append("]");
		}
		
		return strBuffer.toString();
	}
	
	public static Hashtable<String, String> merge(Hashtable<String, String> h1, Hashtable<String, String> h2)
	{
		Hashtable<String, String> h = new Hashtable<String, String>();
		
		if (h1 != null && !h1.isEmpty())
		{
			if (h2 != null && !h2.isEmpty())
			{
				Enumeration<String> keys = h1.keys();
				
				while (keys.hasMoreElements())
				{
					String key = keys.nextElement();
					
					h.put(key, (h2.containsKey(key)?h2.get(key):h1.get(key)));
				}
				
				
				keys = h2.keys();
				
				while (keys.hasMoreElements())
				{
					String key = keys.nextElement();
					
					if (!h.contains(key))
						h.put(key,  h2.get(key));
				}
			}
			else
			{
				Enumeration<String> keys = h1.keys();
				
				while (keys.hasMoreElements())
				{
					String key = keys.nextElement();
					
					h.put(key,  h1.get(key));
				}
			}
		}
		else if (h2 != null && !h2.isEmpty())
		{
			Enumeration<String> keys = h2.keys();
			
			while (keys.hasMoreElements())
			{
				String key = keys.nextElement();
				
				h.put(key,  h2.get(key));
			}
		}
		
		return h;
	}
	
	public static Document xmlStringToDOMDocument(String xmlResponse) throws ParserConfigurationException, SAXException, IOException
	{
		Document doc = null;
		
		DocumentBuilderFactory factory = DocumentBuilderFactory.newInstance();
		InputStream fis = null;
		
		DocumentBuilder builder = factory.newDocumentBuilder();
		
		fis = new ByteArrayInputStream(xmlResponse.getBytes(StandardCharsets.UTF_8));
		
		doc = builder.parse(fis);
		// optional, but recommended
		// read this - http://stackoverflow.com/questions/13786607/normalization-in-dom-parsing-with-java-how-does-it-work
		doc.getDocumentElement().normalize();
		
		fis.close();
		fis = null;
		
		return doc;
	}
}
