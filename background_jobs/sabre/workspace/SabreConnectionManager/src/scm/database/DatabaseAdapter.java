package scm.database;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.sql.Timestamp;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Enumeration;
import java.util.Hashtable;
import java.util.TimeZone;
import java.util.Vector;

import scm.InitSessions;
import scm.MultiThreadedSocketServer;
import scm.configuration.PropertyValues;
import scm.data_handler.BasicConnectionParameters;

public class DatabaseAdapter
{
	private static Connection connect()
	{
		Connection con = null;
		
		try
		{
			Class.forName("com.mysql.jdbc.Driver");
			
			con = DriverManager.getConnection("jdbc:mysql://" + PropertyValues.db_host + ":" + PropertyValues.db_port + "/" + PropertyValues.db_name + "?useSSL=" + String.valueOf((PropertyValues.db_use_ssl)), PropertyValues.db_user, PropertyValues.db_password);
			
			if(con == null)
			{
				MultiThreadedSocketServer.scmLogger.error("Connection cannot be established");
			}
			
			return con;
		}
		catch(SQLException e)
		{
			/*
			InitSessions.debugString("SQLException:: " + e.getMessage());
			InitSessions.debugString("SQLState:: " + e.getSQLState());
			InitSessions.debugString("VendorError:: " + e.getErrorCode());
			
			e.printStackTrace();
			*/
			
			MultiThreadedSocketServer.scmLogger.debugException(e);
		}
		catch(Exception e)
		{
			e.printStackTrace();
		}
		
		return null;
	}
	
	
	
	public static int newConnection(Hashtable<String, String> connectionValues, int connectionLifetimeDays)
	{
		if(connectionValues == null || !connectionValues.containsKey("security_token") || !connectionValues.containsKey("conversation_id") || !connectionValues.containsKey("l"))
			return 0;
		
		Connection con = null;
		PreparedStatement stmt = null;
		ResultSet rs = null;
		
		con = connect();
		if(con == null)
			return 0;
		
		int id = 0;
		
		try
		{
			stmt = con.prepareStatement("INSERT INTO sabre_connection_pool (security_token, conversation_id, last_refreshed, expiry_date, opened, connection_type, connection_status, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)", Statement.RETURN_GENERATED_KEYS);
			
			stmt.setString(1, connectionValues.get("security_token"));
			stmt.setString(2, connectionValues.get("conversation_id"));
			
			Calendar cal = Calendar.getInstance(TimeZone.getTimeZone(PropertyValues.timezone));
			
			if(connectionValues.containsKey("last_refreshed"))
			{
				stmt.setTimestamp(3, new Timestamp(Long.valueOf(connectionValues.get("last_refreshed"))));
			}
			else
				stmt.setTimestamp(3, new Timestamp(cal.getTimeInMillis()));
			
			if (connectionValues.containsKey("expiry_date"))
			{
				stmt.setTimestamp(4, new Timestamp(Long.valueOf(connectionValues.get("expiry_date"))));
			}
			else
			{
				cal.add(Calendar.DAY_OF_MONTH, connectionLifetimeDays);
				stmt.setTimestamp(4, new Timestamp(cal.getTimeInMillis()));
			}
			
			if (connectionValues.containsKey("opened"))
				stmt.setBoolean(5,  (connectionValues.get("opened").equals("true") || connectionValues.get("opened").equals("1")));
			else
				stmt.setBoolean(5,  false);
			
			if (connectionValues.containsKey("connection_type"))
				stmt.setShort(6, Short.valueOf(connectionValues.get("connection_type")));
			else
				stmt.setShort(6, InitSessions.CONNECTION_TYPE_BFM);
			
			if (connectionValues.containsKey("connection_status"))
				stmt.setString(7,  connectionValues.get("connection_status"));
			else
				stmt.setString(7,  "FREE");
			
			if (connectionValues.containsKey("user_id"))
				stmt.setLong(8,  Long.valueOf(connectionValues.get("user_id")));
			else
				stmt.setLong(8,  0);
			
			if(stmt.executeUpdate() == 1)
			{
				rs = stmt.getGeneratedKeys();
				if(rs != null && rs.next())
				{
					id = rs.getInt(1);
				}
			}
		}
		catch(SQLException e)
		{
			e.printStackTrace();
		}
		catch(Exception e)
		{
			e.printStackTrace();
		}
		finally
		{
			if(rs != null)
			{
				try
				{
					rs.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				rs = null;
			}
			
			if(stmt != null)
			{
				try
				{
					stmt.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				stmt = null;
			}
			
			if(con != null)
			{
				try
				{
					con.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				con = null;
			}
		}
		
		return id;
	}
	
	
	public static int addConnection(Hashtable<String, String> connectionValues)
	{
		if(connectionValues == null || !connectionValues.containsKey("security_token") || !connectionValues.containsKey("conversation_id"))
			return 0;
		
		Connection con = null;
		Statement stmt = null;
		ResultSet rs = null;
		
		con = connect();
		if(con == null)
			return 0;
		
		StringBuffer insertFields = new StringBuffer("");
		
		StringBuffer insertValues = new StringBuffer("");
		
		Hashtable<String, Boolean> fieldSpecs = new Hashtable<String, Boolean>(8); // contains all fields, currently
																					// only tells if fields are
																					// SQL-quotable
		fieldSpecs.put("security_token", true);
		fieldSpecs.put("conversation_id", true);
		fieldSpecs.put("last_refreshed", true);
		fieldSpecs.put("expiry_date", true);
		fieldSpecs.put("opened", false);
		fieldSpecs.put("connection_type", false);
		fieldSpecs.put("connection_status", true);
		fieldSpecs.put("user_id", false);
		
		Enumeration<String> fields = fieldSpecs.keys();
		boolean pastFirstField = false;
		while(fields.hasMoreElements())
		{
			String tmpFieldName = fields.nextElement();
			
			if(connectionValues.containsKey(tmpFieldName))
			{
				insertFields.append((pastFirstField?", ":"") + tmpFieldName);
				
				insertValues.append((pastFirstField?", ":"") + (fieldSpecs.get(tmpFieldName)?"'":"") + connectionValues.get(tmpFieldName) + (fieldSpecs.get(tmpFieldName)?"'":""));
			}
			
			pastFirstField = true;
		}
		
		int id = 0;
		
		try
		{
			stmt = con.createStatement();
			
			int insertCount = stmt.executeUpdate("INSERT INTO sabre_connection_pool (" + insertFields + ") VALUES (" + insertValues.toString() + ")", Statement.RETURN_GENERATED_KEYS);
			if(insertCount == 1)
			{
				rs = stmt.getGeneratedKeys();
				if(rs != null && rs.next())
				{
					id = rs.getInt(1);
				}
			}
		}
		catch(SQLException e)
		{
			e.printStackTrace();
		}
		catch(Exception e)
		{
			e.printStackTrace();
		}
		finally
		{
			if(rs != null)
			{
				try
				{
					rs.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				rs = null;
			}
			
			if(stmt != null)
			{
				try
				{
					stmt.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				stmt = null;
			}
			
			if(con != null)
			{
				try
				{
					con.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				con = null;
			}
		}
		
		return id;
	}
	
	public static synchronized void setupAClosedConnection(int connectionType, BasicConnectionParameters basicParameters)
	{
		Connection con = null;
		Statement stmt = null;
		
		try
		{
			con = connect();
			stmt = con.createStatement();
			
			stmt.executeUpdate("UPDATE sabre_connection_pool SET creation_date = '" + InitSessions.sdf_date.format(basicParameters.getCreationDate()) + "', security_token = '" + basicParameters.getToken() + "', conversation_id = '" + basicParameters.getConversationId() + "', last_refreshed = '" + InitSessions.sdf_timestamp.format(basicParameters.getLastRefreshed()) + "', expiry_date = '" + InitSessions.sdf_date.format(basicParameters.getExpiryDate()) + "', opened = 1, connection_status = 'FREE', user_id = 0, last_reserved = NULL, source = 'scm' WHERE connection_type = " + connectionType + " AND opened = 0 LIMIT 1");
		}
		catch(Exception e)
		{
			e.printStackTrace();
		}
		finally
		{
			if(stmt != null)
			{
				try
				{
					stmt.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				stmt = null;
			}
			
			if(con != null)
			{
				try
				{
					con.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				con = null;
			}
		}
	}
	
	public static BasicConnectionParameters fetchUserConnection(int user_id, int connection_type)
	{
		BasicConnectionParameters basicParameters = null;
		
		Connection con = null;
		Statement stmt = null;
		ResultSet rs = null;
		
		try
		{
			con = connect();
			stmt = con.createStatement(ResultSet.TYPE_FORWARD_ONLY, ResultSet.CONCUR_READ_ONLY, ResultSet.CLOSE_CURSORS_AT_COMMIT); // ResultSet.CLOSE_CURSORS_AT_COMMIT or ResultSet.HOLD_CURSORS_OVER_COMMIT
			rs = stmt.executeQuery("SELECT * FROM sabre_connection_pool WHERE locked = false AND connection_status = 'INUSE' AND connection_type = " + connection_type + " AND opened = 1 AND user_id = " + user_id);
			
			if (rs != null && rs.next())
			{
				basicParameters = new BasicConnectionParameters(rs.getString("security_token"), rs.getString("conversation_id"));
				basicParameters.setId(rs.getInt("id"));
				basicParameters.setLastRefreshed(rs.getDate("last_refreshed"));
				basicParameters.setExpiryDate(rs.getDate("expiry_date"));
			}
		}
		catch(Exception e)
		{
			e.printStackTrace();
		}
		finally
		{
			if(rs != null)
			{
				try
				{
					rs.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				rs = null;
			}
			
			if(stmt != null)
			{
				try
				{
					stmt.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				stmt = null;
			}
			
			if(con != null)
			{
				try
				{
					con.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				con = null;
			}
		}
		
		return basicParameters;
	}
	
	public static BasicConnectionParameters fetchConnectionByToken(String token)
	{
		BasicConnectionParameters basicParameters = null;
		
		Connection con = null;
		Statement stmt = null;
		ResultSet rs = null;
		
		try
		{
			con = connect();
			stmt = con.createStatement(ResultSet.TYPE_FORWARD_ONLY, ResultSet.CONCUR_READ_ONLY, ResultSet.CLOSE_CURSORS_AT_COMMIT); // ResultSet.CLOSE_CURSORS_AT_COMMIT or ResultSet.HOLD_CURSORS_OVER_COMMIT
			rs = stmt.executeQuery("SELECT * FROM sabre_connection_pool WHERE security_token = '" + token + "'");
			
			if (rs != null && rs.next())
			{
				basicParameters = new BasicConnectionParameters(rs.getString("security_token"), rs.getString("conversation_id"));
				basicParameters.setId(rs.getInt("id"));
				basicParameters.setLastRefreshed(rs.getDate("last_refreshed"));
				basicParameters.setExpiryDate(rs.getDate("expiry_date"));
			}
		}
		catch(Exception e)
		{
			e.printStackTrace();
		}
		finally
		{
			if(rs != null)
			{
				try
				{
					rs.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				rs = null;
			}
			
			if(stmt != null)
			{
				try
				{
					stmt.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				stmt = null;
			}
			
			if(con != null)
			{
				try
				{
					con.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				con = null;
			}
		}
		
		return basicParameters;
	}
	
	public static synchronized BasicConnectionParameters fetchFreeConnection(int connection_type)
	{
		BasicConnectionParameters basicParameters = null;
		
		Connection con = null;
		Statement stmt = null;
		ResultSet rs = null;
		
		try
		{
			con = connect();
			stmt = con.createStatement(ResultSet.TYPE_FORWARD_ONLY, ResultSet.CONCUR_READ_ONLY, ResultSet.CLOSE_CURSORS_AT_COMMIT); // ResultSet.CLOSE_CURSORS_AT_COMMIT or ResultSet.HOLD_CURSORS_OVER_COMMIT
			rs = stmt.executeQuery("SELECT * FROM sabre_connection_pool WHERE connection_status = 'FREE' AND connection_type = " + connection_type + " AND opened = 1 AND NOT locked LIMIT 1");
			
			if (rs != null && rs.next())
			{
				basicParameters = new BasicConnectionParameters(rs.getString("security_token"), rs.getString("conversation_id"));
				basicParameters.setId(rs.getInt("id"));
				basicParameters.setLastRefreshed(rs.getDate("last_refreshed"));
				basicParameters.setExpiryDate(rs.getDate("expiry_date"));
			}
		}
		catch(Exception e)
		{
			e.printStackTrace();
		}
		finally
		{
			if(rs != null)
			{
				try
				{
					rs.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				rs = null;
			}
			
			if(stmt != null)
			{
				try
				{
					stmt.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				stmt = null;
			}
			
			if(con != null)
			{
				try
				{
					con.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				con = null;
			}
		}
		
		return basicParameters;
	}
	
	public static boolean assignConnection(int id, int user_id, String source)
	{
		boolean assigned = false;
		
		Connection con = null;
		Statement stmt = null;
		
		try
		{
			con = connect();
			stmt = con.createStatement();
			assigned = (stmt.executeUpdate("UPDATE sabre_connection_pool SET connection_status = 'INUSE', user_id = " + user_id + ", last_reserved = CURRENT_TIMESTAMP()" + ", source = '" + source + "' WHERE id = " + id) == 1);
		}
		catch(Exception e)
		{
			e.printStackTrace();
		}
		finally
		{
			if(stmt != null)
			{
				try
				{
					stmt.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				stmt = null;
			}
			
			if(con != null)
			{
				try
				{
					con.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				con = null;
			}
		}
		
		return assigned;
	}
	
	public static Vector<BasicConnectionParameters> connectionsToBeRefreshed(int minutesSinceLastRefreshed)
	{
		Vector<BasicConnectionParameters> connections = new Vector<BasicConnectionParameters>(0);
		
		Connection con = null;
		Statement stmt = null;
		ResultSet rs = null;
		
		try
		{
			con = connect();
			stmt = con.createStatement();
			// rs = stmt.executeQuery("SELECT id, security_token, conversation_id FROM sabre_connection_pool WHERE opened = 1 AND last_refreshed < (NOW() - INTERVAL 7 MINUTE) AND last_refreshed > (NOW() - INTERVAL 12 MINUTE)");
			rs = stmt.executeQuery("SELECT id, security_token, conversation_id FROM sabre_connection_pool WHERE opened = 1 AND TIMESTAMPDIFF(MINUTE, last_refreshed, CURRENT_TIMESTAMP()) > " + minutesSinceLastRefreshed);
			
			
			if (rs != null)
			{
				while(rs.next())
				{
					connections.add(new BasicConnectionParameters(rs.getInt("id"), rs.getString("security_token"), rs.getString("conversation_id")));
				}
			}
		}
		catch(Exception e)
		{
			e.printStackTrace();
		}
		finally
		{
			if(rs != null)
			{
				try
				{
					rs.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				rs = null;
			}
			
			if(stmt != null)
			{
				try
				{
					stmt.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				stmt = null;
			}
			
			if(con != null)
			{
				try
				{
					con.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				con = null;
			}
		}
		
		return connections;
	}
	
	public static Vector<BasicConnectionParameters> openedConnections()
	{
		Vector<BasicConnectionParameters> connections = new Vector<BasicConnectionParameters>(0);
		
		Connection con = null;
		Statement stmt = null;
		ResultSet rs = null;
		
		try
		{
			con = connect();
			stmt = con.createStatement();
			rs = stmt.executeQuery("SELECT id, security_token, conversation_id FROM sabre_connection_pool WHERE opened = 1");
			
			if (rs != null)
			{
				while (rs.next())
				{
					connections.add(new BasicConnectionParameters(rs.getInt("id"), rs.getString("security_token"), rs.getString("conversation_id")));
				}
			}
		}
		catch(Exception e)
		{
			e.printStackTrace();
		}
		finally
		{
			if(rs != null)
			{
				try
				{
					rs.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				rs = null;
			}
			
			if(stmt != null)
			{
				try
				{
					stmt.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				stmt = null;
			}
			
			if(con != null)
			{
				try
				{
					con.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				con = null;
			}
		}
		
		return connections;
	}
	
	public static Vector<BasicConnectionParameters> openedConnectionsFromList(ArrayList<Integer> ids)
	{
		Vector<BasicConnectionParameters> connections = new Vector<BasicConnectionParameters>(0);
		
		if (ids == null || ids.isEmpty())
			return connections;
		
		StringBuffer ids_csv = new StringBuffer("");
		
		for (short indx = 0;indx < ids.size();indx++)
		{
			ids_csv.append((indx == 0?"":", ") + ids.get(indx));
		}
		
		Connection con = null;
		Statement stmt = null;
		ResultSet rs = null;
		
		try
		{
			con = connect();
			stmt = con.createStatement();
			rs = stmt.executeQuery("SELECT id, security_token, conversation_id FROM sabre_connection_pool WHERE opened = 1 AND id IN (" + ids_csv.toString() + ")");
			
			if (rs != null)
			{
				while (rs.next())
				{
					connections.add(new BasicConnectionParameters(rs.getInt("id"), rs.getString("security_token"), rs.getString("conversation_id")));
				}
			}
		}
		catch(Exception e)
		{
			e.printStackTrace();
		}
		finally
		{
			if(rs != null)
			{
				try
				{
					rs.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				rs = null;
			}
			
			if(stmt != null)
			{
				try
				{
					stmt.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				stmt = null;
			}
			
			if(con != null)
			{
				try
				{
					con.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				con = null;
			}
		}
		
		return connections;
	}
	
	public static Vector<BasicConnectionParameters> openedFreeConnections()
	{
		Vector<BasicConnectionParameters> connections = new Vector<BasicConnectionParameters>(0);
		
		Connection con = null;
		Statement stmt = null;
		ResultSet rs = null;
		
		try
		{
			con = connect();
			stmt = con.createStatement();
			rs = stmt.executeQuery("SELECT id, security_token, conversation_id FROM sabre_connection_pool WHERE opened = 1 AND connection_status = 'FREE'");
			
			if (rs != null)
			{
				while (rs.next())
				{
					connections.add(new BasicConnectionParameters(rs.getInt("id"), rs.getString("security_token"), rs.getString("conversation_id")));
				}
			}
		}
		catch(Exception e)
		{
			e.printStackTrace();
		}
		finally
		{
			if(rs != null)
			{
				try
				{
					rs.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				rs = null;
			}
			
			if(stmt != null)
			{
				try
				{
					stmt.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				stmt = null;
			}
			
			if(con != null)
			{
				try
				{
					con.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				con = null;
			}
		}
		
		return connections;
	}
	
	public static Vector<BasicConnectionParameters> openedFreeConnectionsFromList(ArrayList<Integer> ids)
	{
		Vector<BasicConnectionParameters> connections = new Vector<BasicConnectionParameters>(0);
		
		if (ids == null || ids.isEmpty())
			return connections;
		
		StringBuffer ids_csv = new StringBuffer("");
		
		for (short indx = 0;indx < ids.size();indx++)
		{
			ids_csv.append((indx == 0?"":", ") + ids.get(indx));
		}
		
		Connection con = null;
		Statement stmt = null;
		ResultSet rs = null;
		
		try
		{
			con = connect();
			stmt = con.createStatement();
			
			rs = stmt.executeQuery("SELECT id, security_token, conversation_id FROM sabre_connection_pool WHERE opened = 1 AND connection_status = 'FREE' AND id IN (" + ids_csv.toString() + ")");
			
			if (rs != null)
			{
				while (rs.next())
				{
					connections.add(new BasicConnectionParameters(rs.getInt("id"), rs.getString("security_token"), rs.getString("conversation_id")));
				}
			}
		}
		catch(Exception e)
		{
			e.printStackTrace();
		}
		finally
		{
			if(rs != null)
			{
				try
				{
					rs.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				rs = null;
			}
			
			if(stmt != null)
			{
				try
				{
					stmt.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				stmt = null;
			}
			
			if(con != null)
			{
				try
				{
					con.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				con = null;
			}
		}
		
		return connections;
	}
	
	public static Vector<BasicConnectionParameters> connectionsToCloseImmediately(int expiryDateSafetyMargin)
	{
		Vector<BasicConnectionParameters> connections = new Vector<BasicConnectionParameters>(0);
		
		Connection con = null;
		Statement stmt = null;
		ResultSet rs = null;
		
		try
		{
			con = connect();
			stmt = con.createStatement();
			// rs = stmt.executeQuery("SELECT id, security_token, conversation_id FROM sabre_connection_pool WHERE opened = 1 AND DATEDIFF(CURRENT_DATE(), expiry_date) >= 5 AND DATEDIFF(CURRENT_DATE(), expiry_date) < 7");
			rs = stmt.executeQuery("SELECT id, security_token, conversation_id FROM sabre_connection_pool WHERE opened = 1 AND DATE_SUB(expiry_date, INTERVAL " + expiryDateSafetyMargin + " DAY) = CURRENT_DATE() AND connection_status = 'FREE'");
			
			if (rs != null)
			{
				while (rs.next())
				{
					connections.add(new BasicConnectionParameters(rs.getInt("id"), rs.getString("security_token"), rs.getString("conversation_id")));
				}
			}
		}
		catch(Exception e)
		{
			e.printStackTrace();
		}
		finally
		{
			if(rs != null)
			{
				try
				{
					rs.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				rs = null;
			}
			
			if(stmt != null)
			{
				try
				{
					stmt.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				stmt = null;
			}
			
			if(con != null)
			{
				try
				{
					con.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				con = null;
			}
		}
		
		return connections;
	}
	
	public static Vector<BasicConnectionParameters> connectionsToClose(int expiryDateSafetyMargin)
	{
		Vector<BasicConnectionParameters> connections = new Vector<BasicConnectionParameters>(0);
		
		Connection con = null;
		Statement stmt = null;
		ResultSet rs = null;
		
		try
		{
			con = connect();
			stmt = con.createStatement();
			// rs = stmt.executeQuery("SELECT id, security_token, conversation_id FROM sabre_connection_pool WHERE opened = 1 AND DATEDIFF(CURRENT_DATE(), expiry_date) >= 5 AND DATEDIFF(CURRENT_DATE(), expiry_date) < 7");
			rs = stmt.executeQuery("SELECT id, security_token, conversation_id FROM sabre_connection_pool WHERE opened = 1 AND DATE_SUB(expiry_date, INTERVAL " + expiryDateSafetyMargin + " DAY) = CURRENT_DATE() AND connection_status = 'INUSE'");
			
			if (rs != null)
			{
				while (rs.next())
				{
					connections.add(new BasicConnectionParameters(rs.getInt("id"), rs.getString("security_token"), rs.getString("conversation_id")));
				}
			}
		}
		catch(Exception e)
		{
			e.printStackTrace();
		}
		finally
		{
			if(rs != null)
			{
				try
				{
					rs.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				rs = null;
			}
			
			if(stmt != null)
			{
				try
				{
					stmt.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				stmt = null;
			}
			
			if(con != null)
			{
				try
				{
					con.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				con = null;
			}
		}
		
		return connections;
	}
	
	public static int countConnectionsInUse(int connection_type)
	{
		int rowCount = 0;
		Connection con = null;
		Statement stmt = null;
		ResultSet rs = null;
		
		try
		{
			con = connect();
			stmt = con.createStatement();
			rs = stmt.executeQuery("SELECT COUNT(*) FROM sabre_connection_pool WHERE connection_status = 'INUSE' AND opened = 1 AND connection_type = " + connection_type);
			
			if (rs != null)
			{
				rs.next();
				rowCount = rs.getInt(1);
			}
		}
		catch(Exception e)
		{
			e.printStackTrace();
			
		}
		finally
		{
			if(rs != null)
			{
				try
				{
					rs.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				rs = null;
			}
			
			if(stmt != null)
			{
				try
				{
					stmt.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				stmt = null;
			}
			
			if(con != null)
			{
				try
				{
					con.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				con = null;
			}
		}
		
		return rowCount;
	}
	
	public static boolean closeConnection(int id, String source)
	{
		boolean closed = false;
		Connection con = null;
		Statement stmt = null;
		
		try
		{
			con = connect();
			stmt = con.createStatement();
			
			int rs = stmt.executeUpdate("UPDATE sabre_connection_pool SET opened = 0, locked = false, connection_status = 'FREE', user_id = 0, last_reserved = NULL, source = '" + source + "' WHERE id = " + id);
			
			if(rs == 1)
			{
				closed = true;
			}
		}
		catch(Exception e)
		{
			e.printStackTrace();
			
		}
		finally
		{
			if(stmt != null)
			{
				try
				{
					stmt.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				stmt = null;
			}
			
			if(con != null)
			{
				try
				{
					con.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				con = null;
			}
		}
		
		return closed;
	}
	
	public static boolean updateLastRefreshed(int id)
	{
		boolean success = false;
		Connection con = null;
		Statement stmt = null;
		
		try
		{
			con = connect();
			stmt = con.createStatement();
			int rs = stmt.executeUpdate("UPDATE sabre_connection_pool SET last_refreshed = CURRENT_TIMESTAMP() WHERE id = " + id);
			
			if(rs == 1)
			{
				success = true;
			}
		}
		catch(Exception e)
		{
			e.printStackTrace();
			
		}
		finally
		{
			if(stmt != null)
			{
				try
				{
					stmt.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				stmt = null;
			}
			
			if(con != null)
			{
				try
				{
					con.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				con = null;
			}
		}
		
		return success;
	}
	
	public static int releaseLongtimeReservedBFMConnections(int minutesSinceLastReserved, String source)
	{
		int nReleased = 0;
		
		Connection con = null;
		Statement stmt = null;
		
		try
		{
			con = connect();
			stmt = con.createStatement();
			nReleased = stmt.executeUpdate("UPDATE sabre_connection_pool SET connection_status = 'FREE', user_id = 0, last_reserved = NULL, source = '" + source + "' WHERE connection_type = " + InitSessions.CONNECTION_TYPE_BFM + " AND connection_status = 'INUSE' AND last_reserved IS NOT NULL AND TIMESTAMPDIFF(MINUTE, last_reserved, CURRENT_TIMESTAMP()) > " + minutesSinceLastReserved);
		}
		catch(Exception e)
		{
			e.printStackTrace();
			
		}
		finally
		{
			if(stmt != null)
			{
				try
				{
					stmt.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				stmt = null;
			}
			
			if(con != null)
			{
				try
				{
					con.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				con = null;
			}
		}
		
		return nReleased;
	}
	
	public static int releaseLongtimeReservedBookingConnections(int minutesSinceLastReserved, String source)
	{
		int nReleased = 0;
		
		Connection con = null;
		Statement stmt = null;
		
		try
		{
			con = connect();
			stmt = con.createStatement();
			nReleased = stmt.executeUpdate("UPDATE sabre_connection_pool SET connection_status = 'FREE', user_id = 0, last_reserved = NULL, source = '" + source + "' WHERE connection_type = " + InitSessions.CONNECTION_TYPE_BOOKING + " AND connection_status = 'INUSE' AND last_reserved IS NOT NULL AND TIMESTAMPDIFF(MINUTE, last_reserved, CURRENT_TIMESTAMP()) > " + minutesSinceLastReserved);
		}
		catch(Exception e)
		{
			e.printStackTrace();
			
		}
		finally
		{
			if(stmt != null)
			{
				try
				{
					stmt.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				stmt = null;
			}
			
			if(con != null)
			{
				try
				{
					con.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				con = null;
			}
		}
		
		return nReleased;
	}
	
	public static int releaseLongtimeReservedConnections(int minutesSinceLastReserved, String source)
	{
		int nReleased = 0;
		
		Connection con = null;
		Statement stmt = null;
		
		try
		{
			con = connect();
			stmt = con.createStatement();
			nReleased = stmt.executeUpdate("UPDATE sabre_connection_pool SET connection_status = 'FREE', user_id = 0, last_reserved = NULL, source = '" + source + "' WHERE connection_status = 'INUSE' AND last_reserved IS NOT NULL AND TIMESTAMPDIFF(MINUTE, last_reserved, CURRENT_TIMESTAMP()) > " + minutesSinceLastReserved);
		}
		catch(Exception e)
		{
			e.printStackTrace();
			
		}
		finally
		{
			if(stmt != null)
			{
				try
				{
					stmt.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				stmt = null;
			}
			
			if(con != null)
			{
				try
				{
					con.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				con = null;
			}
		}
		
		return nReleased;
	}
	
	public static boolean releaseConnection(int id, String source)
	{
		boolean released = false;
		
		Connection con = null;
		Statement stmt = null;
		
		try
		{
			con = connect();
			stmt = con.createStatement();
			int rs = stmt.executeUpdate("UPDATE sabre_connection_pool SET connection_status = 'FREE', user_id = 0, last_reserved = NULL, source = '" + source + "' WHERE id = " + id);
			if(rs == 1)
			{
				released = true;
			}
		}
		catch(Exception e)
		{
			e.printStackTrace();
			
		}
		finally
		{
			if(stmt != null)
			{
				try
				{
					stmt.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				stmt = null;
			}
			
			if(con != null)
			{
				try
				{
					con.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				con = null;
			}
		}
		
		return released;
	}
	
	public static boolean releaseToken(String token, String source)
	{
		boolean released = false;
		
		Connection con = null;
		Statement stmt = null;
		
		try
		{
			con = connect();
			stmt = con.createStatement();
			
			int rs = stmt.executeUpdate("UPDATE sabre_connection_pool SET connection_status = 'FREE', user_id = 0, last_reserved = NULL, source = '" + source + "' WHERE security_token = '" + token + "'");
			
			if(rs == 1)
			{
				released = true;
			}
		}
		catch(Exception e)
		{
			e.printStackTrace();
			
		}
		finally
		{
			if(stmt != null)
			{
				try
				{
					stmt.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				stmt = null;
			}
			
			if(con != null)
			{
				try
				{
					con.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				con = null;
			}
		}
		
		return released;
	}
	
	public static boolean lockToken(String token)
	{
		boolean locked = false;
		
		Connection con = null;
		Statement stmt = null;
		
		try
		{
			con = connect();
			stmt = con.createStatement();
			
			int rs = stmt.executeUpdate("UPDATE sabre_connection_pool SET locked = true WHERE security_token = '" + token + "'");
			
			if(rs == 1)
			{
				locked = true;
			}
		}
		catch(Exception e)
		{
			e.printStackTrace();
			
		}
		finally
		{
			if(stmt != null)
			{
				try
				{
					stmt.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				stmt = null;
			}
			
			if(con != null)
			{
				try
				{
					con.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				con = null;
			}
		}
		
		return locked;
	}
	
	public static boolean lockConnection(int id)
	{
		boolean locked = false;
		
		Connection con = null;
		Statement stmt = null;
		
		try
		{
			con = connect();
			stmt = con.createStatement();
			
			int rs = stmt.executeUpdate("UPDATE sabre_connection_pool SET locked = true WHERE id = " + id);
			
			if(rs == 1)
			{
				locked = true;
			}
		}
		catch(Exception e)
		{
			e.printStackTrace();
			
		}
		finally
		{
			if(stmt != null)
			{
				try
				{
					stmt.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				stmt = null;
			}
			
			if(con != null)
			{
				try
				{
					con.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				con = null;
			}
		}
		
		return locked;
	}
	
	public static boolean lockConnections(ArrayList<Integer> ids)
	{
		boolean locked = false;
		
		if (ids == null || ids.isEmpty())
			return false;
		
		StringBuffer ids_csv = new StringBuffer("");
		
		for (short indx = 0;indx < ids.size();indx++)
		{
			ids_csv.append((indx == 0?"":", ") + ids.get(indx));
		}
		
		Connection con = null;
		Statement stmt = null;
		
		try
		{
			con = connect();
			stmt = con.createStatement();
			
			int rs = stmt.executeUpdate("UPDATE sabre_connection_pool SET locked = true WHERE id IN (" + ids_csv.toString() + ")");
			
			if(rs == 1)
			{
				locked = true;
			}
		}
		catch(Exception e)
		{
			e.printStackTrace();
			
		}
		finally
		{
			if(stmt != null)
			{
				try
				{
					stmt.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				stmt = null;
			}
			
			if(con != null)
			{
				try
				{
					con.close();
				}
				catch(SQLException e)
				{
					e.printStackTrace();
				}
				
				con = null;
			}
		}
		
		return locked;
	}
}
