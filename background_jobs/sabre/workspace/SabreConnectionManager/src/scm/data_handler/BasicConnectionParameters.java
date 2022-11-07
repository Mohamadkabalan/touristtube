package scm.data_handler;

import java.util.Calendar;
import java.util.Date;
import java.util.TimeZone;

import scm.configuration.PropertyValues;

public class BasicConnectionParameters
{
	public int id;
	public String token;
	public String conversationId;
	public Date creationDate, lastRefreshed, expiryDate, timeToLive, currentDate;
	public boolean locked = false;
	
	public BasicConnectionParameters()
	{
		this.initDates();
	}
	
	public BasicConnectionParameters(int id, String token, String conversationId)
	{
		this.id = id;
		this.token = token;
		this.conversationId = conversationId;
		
		this.initDates();
	}
	
	public BasicConnectionParameters(String token, String conversationId)
	{
		this.token = token;
		this.conversationId = conversationId;
		
		this.initDates();
	}
	
	public void initDates()
	{
		Calendar cal = Calendar.getInstance(TimeZone.getTimeZone(PropertyValues.timezone));
		
		this.currentDate = cal.getTime();
		
		this.creationDate = cal.getTime();
		this.lastRefreshed = cal.getTime();
		
		cal.add(Calendar.MINUTE, PropertyValues.connectionRefreshCycleMinutes);
		this.timeToLive = cal.getTime();
		
		cal.setTime(this.lastRefreshed);
		
		cal.add(Calendar.DAY_OF_MONTH, PropertyValues.connectionLifetimeDays);
		this.expiryDate = cal.getTime();
	}
	
	public int getId()
	{
		return this.id;
	}
	
	public void setId(int id)
	{
		this.id = id;
	}
	
	public String getToken()
	{
		return this.token;
	}
	
	public void setToken(String token)
	{
		this.token = token;
	}
	
	public String getConversationId()
	{
		return this.conversationId;
	}
	
	public void setConversationId(String conversationId)
	{
		this.conversationId = conversationId;
	}
	
	public Date getCurrentDate()
	{
		return this.currentDate;
	}
	
	public Date getCreationDate()
	{
		return this.creationDate;
	}
	
	public Date getLastRefreshed()
	{
		return this.lastRefreshed;
	}
	
	public long getLastRefreshedInMillis()
	{
		return this.lastRefreshed.getTime();
	}
	
	public void setLastRefreshed(Date date)
	{
		this.lastRefreshed = date;
	}
	
	public Date getExpiryDate()
	{
		return this.expiryDate;
	}
	
	public long getExpiryDateInMillis()
	{
		return this.expiryDate.getTime();
	}
	
	public void setExpiryDate(Date expiryDate)
	{
		this.expiryDate = expiryDate;
	}
	
	public Date getTimeToLive()
	{
		return this.timeToLive;
	}
	
	public Date getTTL()
	{
		return this.timeToLive;
	}
	
	public void setTimeToLive(Date ttl)
	{
		this.timeToLive = ttl;
	}
	
	public void setTTL(Date ttl)
	{
		this.timeToLive = ttl;
	}
	
	public boolean isLocked()
	{
		return this.locked;
	}
	
	public boolean locked()
	{
		return this.locked;
	}
	
	public void lock()
	{
		this.locked = true;
	}
	
	public void setLocked(boolean locked)
	{
		this.locked = locked;
	}
	
	public String toShortString()
	{
		return new String("connection:: " + this.id);
	}
}
