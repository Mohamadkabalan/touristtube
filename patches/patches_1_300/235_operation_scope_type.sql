
CREATE TABLE operation_scope_type 
(id TINYINT(1) PRIMARY KEY, 
name VARCHAR(10) NOT NULL, 
op_scope_type_key VARCHAR(50) NOT NULL, 
CONSTRAINT unique_operation_scope_type UNIQUE (name), 
CONSTRAINT unique_operation_scope_type_key UNIQUE (op_scope_type_key));


INSERT INTO operation_scope_type (id, name, op_scope_type_key) VALUES (1, 'full', 'OPERATION_SCOPE_TYPE_FULL');

INSERT INTO operation_scope_type (id, name, op_scope_type_key) VALUES (2, 'partial', 'OPERATION_SCOPE_TYPE_PARTIAL');
