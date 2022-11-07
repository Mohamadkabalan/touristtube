
CREATE TABLE flight_direction 
(id TINYINT(1) PRIMARY KEY, 
name VARCHAR(10) NOT NULL, 
flight_direction_key VARCHAR(50) NOT NULL,  
CONSTRAINT unique_flight_direction UNIQUE (name), 
CONSTRAINT unique_flight_direction_key UNIQUE (flight_direction_key));

INSERT INTO flight_direction (id, name, flight_direction_key) VALUES (1, 'leaving', 'FLIGHT_DIRECTION_LEAVING');

INSERT INTO flight_direction (id, name, flight_direction_key) VALUES (2, 'returning', 'FLIGHT_DIRECTION_RETURNING');

INSERT INTO flight_direction (id, name, flight_direction_key) VALUES (3, 'both', 'FLIGHT_DIRECTION_BOTH');
