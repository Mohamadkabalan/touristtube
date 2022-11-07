package scm;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.OutputStreamWriter;
import java.io.PrintWriter;
import java.io.StringReader;
import java.net.Socket;
import java.util.ArrayList;
import java.util.Iterator;

import javax.json.Json;
import javax.json.JsonArray;
import javax.json.JsonArrayBuilder;
import javax.json.JsonException;
import javax.json.JsonObject;
import javax.json.JsonObjectBuilder;
import javax.json.JsonReader;
import javax.json.JsonStructure;
import javax.json.JsonValue;
import javax.json.JsonWriter;

// import scm.configuration.PropertyValues;
import scm.data_handler.BasicConnectionParameters;

class ClientThread extends Thread
{
	private short RESPONSE_STATUS_SUCCESS = 0;
	private short RESPONSE_STATUS_ERROR = 1;
	
	private Socket clientSocket;
	
	ClientThread(Socket clientSocket)
	{
		super("ClientServiceThread");
		
		this.clientSocket = clientSocket;
	}
	
	public void run()
	{
		if (!MultiThreadedSocketServer.keepListening)
			return;
		
		synchronized(this)
		{
			MultiThreadedSocketServer.nActiveConnections++;
		}
		
		String contextInfo = "ClientThread:: run()";
		
		BufferedReader in = null;
		PrintWriter out = null;
		
		MultiThreadedSocketServer.scmLogger.info(contextInfo, "Accepted client address:: " + clientSocket.getInetAddress().getHostName());
		
		short responseStatus = RESPONSE_STATUS_SUCCESS;
		String responseMessage = "success";
		
		JsonWriter jsonWriter = null;
		JsonObjectBuilder outJsonObjectBuilder = null;
		JsonObject outJsonObject = null;
		JsonArrayBuilder jarray = null;
		ArrayList<Integer> ids = null;
		
		try
		{
			in = new BufferedReader(new InputStreamReader(clientSocket.getInputStream()));
			out = new PrintWriter(new OutputStreamWriter(clientSocket.getOutputStream()));
			jsonWriter = Json.createWriter(out);
			outJsonObjectBuilder = Json.createObjectBuilder();
			
			String inputString, clientCommand, source = "none";
			String token = null;
			boolean lockToken = false;
			boolean cleanToken = false;
			
			if((inputString = in.readLine()) != null)
			{
				MultiThreadedSocketServer.scmLogger.info(contextInfo, "Received command from " + clientSocket.getInetAddress().getHostName() + ":: " + inputString);
				
				JsonReader jsonReader = Json.createReader(new StringReader(inputString));
				JsonStructure jsonStructure = jsonReader.read();
				JsonObject rootJsonObject = (JsonObject) jsonStructure;
				
				if (rootJsonObject.containsKey("command"))
				{
					clientCommand = rootJsonObject.getString("command").toLowerCase();
					
					if (rootJsonObject.containsKey("source"))
					{
						source = rootJsonObject.getString("source");
					}
					
					switch(clientCommand)
					{
						case "init_connections":
							
							if (clientSocket.getInetAddress().getHostName().equals("localhost"))
							{
								InitSessions.initConnections();
							}
							else
							{
								responseStatus = RESPONSE_STATUS_ERROR;
								responseMessage = "Operation not allowed";
							}
							
							break;
						case "forced_refresh":
							
							if (clientSocket.getInetAddress().getHostName().equals("localhost"))
							{
								if (rootJsonObject.containsKey("arguments"))
								{
									if (rootJsonObject.getJsonObject("arguments").containsKey("value"))
									{
										/*
										// The following code only allows a String type for "value"
										String debug_string = rootJsonObject.getJsonObject("arguments").getString("value", "true").toLowerCase();
										
										if (debug_string.equals("1") || debug_string.equals("true") || debug_string.equals("t") || debug_string.equals("yes") || debug_string.equals("y"))
											InitSessions.periodicConnectionChecker.setForcedMode(true);
										else if (debug_string.equals("0") || debug_string.equals("false") || debug_string.equals("f") || debug_string.equals("no") || debug_string.equals("n"))
											InitSessions.periodicConnectionChecker.setForcedMode(false);
										else
										{
											responseStatus = RESPONSE_STATUS_ERROR;
											responseMessage = "value must be one of 1/true/t/yes/y or 0/false/f/no/n (non-case sensitive)";
										}
										*/
										
										// The following code allows NUMBER/STRING/TRUE/FALSE values for "value"
										InitSessions.periodicConnectionChecker.setForcedMode(this.getJsonBooleanValue(rootJsonObject.getJsonObject("arguments"), "value", InitSessions.periodicConnectionChecker.inForcedRefreshMode()));
									}
									else
									{
										responseStatus = RESPONSE_STATUS_ERROR;
										responseMessage = "Missing arguments.value";
									}
								}
								else
								{
									responseStatus = RESPONSE_STATUS_ERROR;
									responseMessage = "Missing argument:: value (boolean)";
								}
							}
							else
							{
								responseStatus = RESPONSE_STATUS_ERROR;
								responseMessage = "Operation not allowed";
							}
							
							break;
						case "is_logging_enabled":
							
							outJsonObjectBuilder.add("response", Json.createObjectBuilder().add("value", MultiThreadedSocketServer.scmLogger.isEnabled()));
							
							break;
						case "is_logging_disabled":
							
							outJsonObjectBuilder.add("response", Json.createObjectBuilder().add("value", MultiThreadedSocketServer.scmLogger.isDisabled()));
							
							break;
						case "get_active_connections_count":
							
							int activeConnectionsCount = 0;
							
							synchronized(this)
							{
								activeConnectionsCount = MultiThreadedSocketServer.nActiveConnections;
							}
							
							outJsonObjectBuilder.add("response", Json.createObjectBuilder().add("value", --activeConnectionsCount));
							
						break;
						case "get_current_logging_level":
						case "get_logging_level":
							
							outJsonObjectBuilder.add("response", Json.createObjectBuilder().add("logging_level", MultiThreadedSocketServer.scmLogger.getCurrentLevelName()));
							
							break;
						case "set_current_logging_level":
						case "set_logging_level":
							
							if (clientSocket.getInetAddress().getHostName().equals("localhost"))
							{
								String loggingLevelName = null;
								
								if (rootJsonObject.containsKey("arguments"))
								{
									if (rootJsonObject.getJsonObject("arguments").containsKey("logging_level"))
									{
										loggingLevelName = rootJsonObject.getJsonObject("arguments").getString("logging_level").trim();
									}
									else if (rootJsonObject.getJsonObject("arguments").containsKey("level"))
									{
										loggingLevelName = rootJsonObject.getJsonObject("arguments").getString("level").trim();
									}
								}
								
								if (loggingLevelName == null)
								{
									responseStatus = RESPONSE_STATUS_ERROR;
									responseMessage = "Missing logging_level / level, use one of ALL, TRACE, DEBUG, INFO, WARN, ERROR, FATAL, OFF";
								}
								else if (!MultiThreadedSocketServer.scmLogger.getCurrentLevelName().equalsIgnoreCase(loggingLevelName))
								{
									if (MultiThreadedSocketServer.scmLogger.isLoggingLevelRegistered(loggingLevelName))
									{
										MultiThreadedSocketServer.scmLogger.setCurrentLevel(loggingLevelName);
									}
									else
									{
										responseStatus = RESPONSE_STATUS_ERROR;
										responseMessage = "Logging Level (" + loggingLevelName + ") not registered";
									}
								}
							}
							else
							{
								responseStatus = RESPONSE_STATUS_ERROR;
								responseMessage = "Operation not allowed";
							}
							
							break;
						case "set_debug":
							
							if (rootJsonObject.containsKey("arguments"))
							{
								if (rootJsonObject.getJsonObject("arguments").containsKey("value"))
								{
									/*
									// The following code only allows a String type for "value"
									String debug_string = rootJsonObject.getJsonObject("arguments").getString("value", "true").toLowerCase();
									
									if (debug_string.equals("1") || debug_string.equals("true") || debug_string.equals("t") || debug_string.equals("yes") || debug_string.equals("y"))
										PropertyValues.debug = true;
									else if (debug_string.equals("0") || debug_string.equals("false") || debug_string.equals("f") || debug_string.equals("no") || debug_string.equals("n"))
										PropertyValues.debug = false;
									else
									{
										responseStatus = RESPONSE_STATUS_ERROR;
										responseMessage = "value must be one of 1/true/t/yes/y or 0/false/f/no/n (non-case sensitive)";
									}
									*/
									
									// The following code allows NUMBER/STRING/TRUE/FALSE values for "value"
									// PropertyValues.debug = this.getJsonBooleanValue(rootJsonObject.getJsonObject("arguments"), "value", PropertyValues.debug);
									if (this.getJsonBooleanValue(rootJsonObject.getJsonObject("arguments"), "value", MultiThreadedSocketServer.scmLogger.isOn()))
										MultiThreadedSocketServer.scmLogger.enable();
									else
										MultiThreadedSocketServer.scmLogger.disable();
								}
								else
								{
									responseStatus = RESPONSE_STATUS_ERROR;
									responseMessage = "Missing arguments.value";
								}
							}
							else
							{
								responseStatus = RESPONSE_STATUS_ERROR;
								responseMessage = "Missing argument:: value (boolean)";
							}
							
							break;
						case "end":
						case "quit":
						case "bye":
							
							if (clientSocket.getInetAddress().getHostName().equals("localhost"))
							{
								this.stopServer();
							}
							else
							{
								responseStatus = RESPONSE_STATUS_ERROR;
								responseMessage = "Operation not allowed";
							}
							
							break;
						case "close_all_connections":
							
							if (clientSocket.getInetAddress().getHostName().equals("localhost"))
							{
								ids = InitSessions.closeAllOpenedFreeConnections(source);
								
								jarray = Json.createArrayBuilder();
								
								if (!ids.isEmpty())
								{
									for (short indx = 0;indx < ids.size();indx++)
									{
										jarray.add(ids.get(indx));
									}
								}
								
								outJsonObjectBuilder.add("response", Json.createObjectBuilder().add("arguments", jarray));
							}
							else
							{
								responseStatus = RESPONSE_STATUS_ERROR;
								responseMessage = "Operation not allowed";
							}
							
							break;
						case "refresh_all_connections":
							
							ids = InitSessions.refreshAllOpenedConnections(source);
							
							jarray = Json.createArrayBuilder();
							
							if (!ids.isEmpty())
							{
								for (short indx = 0;indx < ids.size();indx++)
								{
									jarray.add(ids.get(indx));
								}
							}
							
							outJsonObjectBuilder.add("response", Json.createObjectBuilder().add("arguments", jarray));
							
							break;
						case "close_connections":
						case "refresh_connections":
							
							if (clientCommand.equals("close_connections") && !clientSocket.getInetAddress().getHostName().equals("localhost"))
							{
								responseStatus = RESPONSE_STATUS_ERROR;
								responseMessage = "Operation not allowed";
							}
							
							if (responseStatus == RESPONSE_STATUS_SUCCESS)
							{
								if (rootJsonObject.containsKey("arguments"))
								{
									ArrayList<Integer> idList = new ArrayList<Integer>(0);
									
									JsonArray jsonIds = rootJsonObject.getJsonArray("arguments");
									Iterator<JsonValue> jsonIdsIterator = jsonIds.iterator();
									
									try
									{
										while (jsonIdsIterator.hasNext())
										{
											idList.add(Integer.valueOf(jsonIdsIterator.next().toString()));
										}
									}
									catch (NumberFormatException e)
									{
										idList.clear();
										
										responseStatus = RESPONSE_STATUS_ERROR;
										responseMessage = "Error in argument:: arguments (expected as array of numbers)";
									}
									
									if (responseStatus == RESPONSE_STATUS_SUCCESS)
									{
										if (idList.isEmpty())
										{
											responseStatus = RESPONSE_STATUS_ERROR;
											responseMessage = "Missing argument:: ids (array)";
										}
										else
										{
											if (clientCommand.equals("refresh_connections"))
												ids = InitSessions.refreshConnections(idList, source);
											else
												ids = InitSessions.closeConnections(idList, source);
											
											jarray = Json.createArrayBuilder();
											
											if (!ids.isEmpty())
											{
												for (short indx = 0;indx < ids.size();indx++)
												{
													jarray.add(ids.get(indx));
												}
											}
											
											outJsonObjectBuilder.add("response", Json.createObjectBuilder().add("arguments", jarray));
										}
									}
								}
								else
								{
									responseStatus = RESPONSE_STATUS_ERROR;
									responseMessage = "Missing argument:: arguments (array)";
								}
							}
							
							break;
						case "get_connection":
							
							int connection_type = InitSessions.CONNECTION_TYPE_BFM;
							int user_id = 0;
							
							if (rootJsonObject.containsKey("arguments"))
							{
								if (rootJsonObject.getJsonObject("arguments").containsKey("connection_type"))
								{
									connection_type = rootJsonObject.getJsonObject("arguments").getInt("connection_type", connection_type);
								}
								
								if (rootJsonObject.getJsonObject("arguments").containsKey("user_id"))
								{
									user_id = rootJsonObject.getJsonObject("arguments").getInt("user_id", user_id);
								}
							}
							
							// BasicConnectionParameters basicParameters = InitSessions.provideConnection(connection_type, user_id);
							BasicConnectionParameters basicParameters = null;
							
							if (connection_type == InitSessions.CONNECTION_TYPE_BFM)
								basicParameters = InitSessions.provideBFMConnection(user_id, source);
							else
								basicParameters = InitSessions.provideBookingConnection(user_id, source);
							
							if(basicParameters == null)
							{
								responseStatus = RESPONSE_STATUS_ERROR;
								responseMessage = "No connection is currently available";
							}
							else
							{
								outJsonObjectBuilder.add("response", Json.createObjectBuilder().add("id", basicParameters.getId()).add("security_token", basicParameters.getToken()).add("conversation_id", basicParameters.getConversationId()));
							}
							
							break;
						case "done_with_connection":
							
							int id = 0;
							
							if (rootJsonObject.containsKey("arguments"))
							{
								if (rootJsonObject.getJsonObject("arguments").containsKey("id"))
								{
									id = rootJsonObject.getJsonObject("arguments").getInt("id", id);
								}
							}
							
							if (id == 0)
							{
								responseStatus = RESPONSE_STATUS_ERROR;
								responseMessage = "id is required, please use a non-zero value";
							}
							else
							{
								if (!InitSessions.setConnectionFree(id, source))
								{
									responseStatus = RESPONSE_STATUS_ERROR;
									responseMessage = "Error setting free id:: " + id;
								}
							}
							
							break;
						case "release_token":
							
							boolean forcedClean = false;
							
							if (rootJsonObject.containsKey("arguments"))
							{
								if (rootJsonObject.getJsonObject("arguments").containsKey("token"))
								{
									token = rootJsonObject.getJsonObject("arguments").getString("token", token);
								}
								
								if (rootJsonObject.getJsonObject("arguments").containsKey("lock"))
								{
									lockToken = rootJsonObject.getJsonObject("arguments").getBoolean("lock", lockToken);
								}
								
								if (rootJsonObject.getJsonObject("arguments").containsKey("clean"))
								{
									forcedClean = true;
									
									cleanToken = rootJsonObject.getJsonObject("arguments").getBoolean("clean", cleanToken);
								}
							}
							
							if (token == null)
							{
								responseStatus = RESPONSE_STATUS_ERROR;
								responseMessage = "token is required, please use a non-null value";
							}
							else
							{
								if (rootJsonObject.containsKey("source"))
								{
									source = rootJsonObject.getString("source");
								}
								
								if (cleanToken || (!forcedClean && InitSessions.properties.autoCleanOnClose))
									InitSessions.cleanToken(token, source);
								
								if (!InitSessions.setTokenFree(token, source, lockToken))
								{
									responseStatus = RESPONSE_STATUS_ERROR;
									responseMessage = "Error setting free token:: " + token;
								}
							}
							
							break;
						case "clean_token":
						
							if (rootJsonObject.containsKey("arguments") && rootJsonObject.getJsonObject("arguments").containsKey("token"))
							{
								token = rootJsonObject.getJsonObject("arguments").getString("token", token);
							}
							
							if (token == null)
							{
								responseStatus = RESPONSE_STATUS_ERROR;
								responseMessage = "token is required, please use a non-null value";
							}
							else
							{
								if (rootJsonObject.containsKey("source"))
								{
									source = rootJsonObject.getString("source");
								}
								
								if (!InitSessions.cleanToken(token, source))
								{
									responseStatus = RESPONSE_STATUS_ERROR;
									responseMessage = "Error cleaning free token:: " + token;
								}
							}
							
							break;
						case "list_reloadable_values":
						case "reloadable_values":
							
							ArrayList<String> reloadableValues = InitSessions.properties.reloadableValues;
							
							if (reloadableValues == null || reloadableValues.isEmpty())
							{
								responseStatus = RESPONSE_STATUS_ERROR;
								responseMessage = "No reloadable config values are defined";
							}
							else
							{
								jarray = Json.createArrayBuilder();
								
								Iterator<String> rvalues = reloadableValues.iterator();
								
								while (rvalues.hasNext())
								{
									jarray.add(rvalues.next());
								}
								
								outJsonObjectBuilder.add("response", Json.createObjectBuilder().add("arguments", jarray));
							}
							
							break;
						case "reload_config":
							
							if (clientSocket.getInetAddress().getHostName().equals("localhost"))
							{
								InitSessions.properties.fetchReloadableValues();
							}
							else
							{
								responseStatus = RESPONSE_STATUS_ERROR;
								responseMessage = "Operation not allowed";
							}
							
							break;
						case "ping":							
							break;
						default:
							
							responseStatus = RESPONSE_STATUS_ERROR;
							responseMessage = "Unknown operation";
							
							break;
					}
					
					outJsonObjectBuilder.add("status", responseStatus).add("message", responseMessage);
					
					outJsonObject = outJsonObjectBuilder.build();
					MultiThreadedSocketServer.scmLogger.info(contextInfo, "Response to " + clientSocket.getInetAddress().getHostName() + ":: " + outJsonObject.toString());
					jsonWriter.writeObject(outJsonObject);
					jsonWriter.close();
				}
				
				out.flush();
			}
		}
		catch(JsonException e)
		{
			responseStatus = RESPONSE_STATUS_ERROR;
			responseMessage = "Wrong JSON format";
			
			if (outJsonObjectBuilder != null)
			{
				outJsonObjectBuilder.add("status", responseStatus).add("message", responseMessage);
				
				outJsonObject = outJsonObjectBuilder.build();
				MultiThreadedSocketServer.scmLogger.info(contextInfo, "Response to " + clientSocket.getInetAddress().getHostName() + ":: " + outJsonObject.toString());
				jsonWriter.writeObject(outJsonObject);
				jsonWriter.close();
			}
			else
			{
				out.println("{\"status\":\"" + RESPONSE_STATUS_ERROR + "\",\"message\":\"" + responseMessage + "\"}");
			}
			
			out.flush();
		}
		catch(Exception e)
		{
			e.printStackTrace();
		}
		finally
		{
			if(in != null)
			{
				try
				{
					in.close();
				}
				catch(IOException e)
				{
					e.printStackTrace();
				}
				
				in = null;
			}
			
			if(out != null)
			{
				out.close();
				
				out = null;
			}
			
			try
			{
				clientSocket.close();
			}
			catch(IOException e)
			{
				e.printStackTrace();
			}
			
			
			synchronized(this)
			{
				MultiThreadedSocketServer.nActiveConnections--;
			}
		}
	}
	
	private boolean getJsonBooleanValue(JsonObject rootJsonObject, String elementName, boolean defaultValue)
	{
		boolean booleanValue = defaultValue;
		
		switch (rootJsonObject.get(elementName).getValueType())
		{
			case STRING:
				
				String stringValue = rootJsonObject.getString(elementName).toLowerCase();
				
				if (stringValue.equals("1") || stringValue.equals("yes") || stringValue.equals("y") || stringValue.equals("true") || stringValue.equals("t"))
					booleanValue = true;
				else if (stringValue.equals("0") || stringValue.equals("no") || stringValue.equals("n") || stringValue.equals("false") || stringValue.equals("f"))
					booleanValue = false;
				
				break;
			case NUMBER:
				
				int intValue = rootJsonObject.getInt(elementName);
				
				if (intValue == 0)
					booleanValue = false;
				else if (intValue == 1)
					booleanValue = true;
				
				break;
			case FALSE:
				
				booleanValue = false;
				
				break;
			case TRUE:
				
				booleanValue = true;
				
				break;
			default:
				break;	
		}
		
		return booleanValue;
	}
	
	private void stopServer()
	{
		MultiThreadedSocketServer.keepListening = false;
		
		try
		{
			MultiThreadedSocketServer.serverSocket.close();
		}
		catch (IOException e)
		{
			
		}
		
		if(InitSessions.dailyConnectionChecker != null)
		{
			InitSessions.dailyConnectionChecker.cancel();
		}
		
		if(InitSessions.periodicConnectionChecker != null)
		{
			InitSessions.periodicConnectionChecker.cancel();
		}
	}
}