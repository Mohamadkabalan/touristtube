package scm.data_handler;

import java.util.Date;

public class ConnectionParameters
{	
	public BasicConnectionParameters basicConnectionParameters;
	public int opened = 0;
	public String connectionStatus;
	public int userId = 0;
	
	public ConnectionParameters()
	{
		this.basicConnectionParameters = new BasicConnectionParameters();
	}
	
	public ConnectionParameters(BasicConnectionParameters basicParameters)
	{
		this.basicConnectionParameters = basicParameters;
	}
	
	public BasicConnectionParameters getBasicConnectionParameters()
	{
		return this.basicConnectionParameters;
	}
	
	public BasicConnectionParameters getBasicParameters()
	{
		return this.basicConnectionParameters;
	}
	
	public void setBasicConnectionParameters(BasicConnectionParameters basicParameters)
	{
		this.basicConnectionParameters = basicParameters;
	}
	
	public void setBasicParameters(BasicConnectionParameters basicParameters)
	{
		this.basicConnectionParameters = basicParameters;
	}
	
	public String getToken()
	{
		return this.basicConnectionParameters.getToken();
	}
	
	public String getConversationId()
	{
		return this.basicConnectionParameters.getConversationId();
	}
	
	public Date getCurrentDate()
	{
		return this.basicConnectionParameters.getCurrentDate();
	}
	
	public Date getLastRefreshed()
	{
		return this.basicConnectionParameters.getLastRefreshed();
	}
	
	public Date getTimeToLive()
	{
		return this.basicConnectionParameters.getTimeToLive();
	}
	
	public Date getTTL()
	{
		return this.basicConnectionParameters.getTTL();
	}
	
	public Date getExpiryDate()
	{
		return this.basicConnectionParameters.getExpiryDate();
	}
	
	public int isOpened()
	{
		return this.opened;
	}
	
	public void setOpened()
	{
		this.opened= 1;
	}
	
	public void setClosed()
	{
		this.opened = 0;
	}
	
	public String getConnectionStatus()
	{
		return this.connectionStatus;
	}
	
	public void setConnectionStatus(String status)
	{
		this.connectionStatus = status;
	}
	
	public int getUserId()
	{
		return this.userId;
	}
	
	public void setUserId(int clientID)
	{
		this.userId = clientID;
	}
	
	public boolean isLocked()
	{
		return this.basicConnectionParameters.locked;
	}
	
	public boolean locked()
	{
		return this.basicConnectionParameters.locked;
	}
	
	public void lock()
	{
		this.basicConnectionParameters.locked = true;
	}
	
	public void setLocked(boolean locked)
	{
		this.basicConnectionParameters.locked = locked;
	}
}
