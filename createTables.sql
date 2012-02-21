-- HARVEST CLUB DDL --
-- For simplicity, some default values are inserted --
-- Tables are dropped before inserting for easy debugging --

CREATE DATABASE
IF NOT EXISTS theharvestclub
CHARACTER SET utf8 COLLATE utf8_general_ci;


SET FOREIGN_KEY_CHECKS = 0; -- remember to enable fk constraints at the end!


DROP TABLE IF EXISTS property_types;
CREATE TABLE property_types (
	id INT AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(255) NOT NULL
) ENGINE=innodb;

INSERT INTO property_types (name) VALUES
	('Residence'),
	('Open Space / Vacant lot'),
	('Business'),
	('Public Property'),
	('Other');

DROP TABLE IF EXISTS property_relationships;
CREATE TABLE property_relationships (
	id INT AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(255) NOT NULL
) ENGINE=innodb;

INSERT INTO property_relationships (name) VALUES
	('Owner & Occupant'),
	('Renter'),
	('Renter property owner (landlord)'),
	('Other');

DROP TABLE IF EXISTS tree_types;	
CREATE TABLE tree_types (
	id INT AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(255) NOT NULL
) ENGINE=innodb;

INSERT INTO tree_types (name) VALUES
	('Orange'),
	('Tangelo'),
	('Tangerine'),
	('Grapefruit'),
	('Lemon'),
	('Lime'),
	('Avocado'),
	('Persimmon'),
	('Guava'),
	('Apple'),
	('Peach'),
	('Plum'),
	('Nectarine'),
	('Other');

DROP TABLE IF EXISTS tree_heights;	
CREATE TABLE tree_heights (
	id INT AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(255) NOT NULL
) ENGINE=innodb;

INSERT INTO tree_heights (name) VALUES
	('Under 5 feet'),
	('5 to 10 feet'),
	('10 to 15 feet'),
	('15 to 20 feet'),
	('20 to 30 feet'),
	('30 to 40 feet'),
	('Over 40 feet');

DROP TABLE IF EXISTS months;
CREATE TABLE months (
	id INT AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(255) NOT NULL
) ENGINE=innodb;

INSERT INTO months (name) VALUES 
	('January'),
	('February'),
	('March'),
	('April'),
	('May'),
	('June'),
	('July'),
	('August'),
	('September'),
	('October'),
	('November'),
	('December'),
	('None');

DROP TABLE IF EXISTS days;
CREATE TABLE days (
id INT AUTO_INCREMENT PRIMARY KEY,
name nvarchar(255) NOT NULL
) ENGINE=innodb;

INSERT INTO days (name) VALUES 
('Monday'),
('Tuesday'),
('Wednesday'),
('Thursday'),
('Friday'),
('Saturday'),
('Sunday');


DROP TABLE IF EXISTS sources;	
CREATE TABLE sources (
	id INT AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(255) NOT NULL
) ENGINE=innodb;

INSERT INTO sources (name) VALUES
	('Flyer'),
	('Facebook'),
	('Twitter'),
	('Family or Friend'),
	('Newspaper/Local Magazine'),
	('Website/Search Engine'),
	('Village Harvest'),
	('Other');



DROP TABLE IF EXISTS growers;
CREATE TABLE growers (
	id			INT AUTO_INCREMENT PRIMARY KEY,
	first_name	NVARCHAR(255) NOT NULL,
	middle_name	NVARCHAR(255) NOT NULL,
	last_name	NVARCHAR(255) NOT NULL,
	phone		VARCHAR(17) NOT NULL, -- maybe (http://stackoverflow.com/q/75105/266535)
	email		VARCHAR(255) NOT NULL, -- max is actually 320, but so rare	
	preferred	VARCHAR(6)	 NOT NULL, -- preferred contact method (phone/email)
	street		NVARCHAR(255) NOT NULL,
	city		VARCHAR(255) NOT NULL,
	state		CHAR(2)		NOT NULL, -- this makes sense right?
	zip			VARCHAR(5)	NOT NULL, -- can it be bigger?
	tools		TINYTEXT,
	source_id	INT, -- this should probably be another INT type and a FK to a table
	notes		TEXT,
  	pending TINYINT(1) DEFAULT 1, -- 1-Yes 0-No     
	property_type_id INT NULL,
	property_relationship_id INT NULL,
	CONSTRAINT fk_property_type FOREIGN KEY (property_type_id) REFERENCES property_types(id) ON DELETE CASCADE,
	CONSTRAINT fk_property_relationship FOREIGN KEY (property_relationship_id) REFERENCES property_relationships(id) ON DELETE CASCADE
) ENGINE=innodb;

-- start temp insert (for debugging front end)
INSERT INTO growers (first_name, middle_name, last_name, phone, email, preferred, street, city, state, zip, property_type_id, property_relationship_id, pending) VALUES
('Steven','', 'Sommers', '(949) 334-1234', 'sommers@uci.edu', 'email', '123 Fake St', 'Irvine', 'CA', '91234', 1, 2, 0),
('Lawrence','','Nanners', '(949) 633-1234', 'nanners@uci.edu', 'email', '313 Fake St', 'Laguna', 'CA', '97234', 3, 2 ,0),
('Fernando','', 'Vegas', '(949) 514-1234', 'vegas@uci.edu', 'phone', '23 Fake St', 'Irvine', 'CA', '91234', 3, 1, 0),
('Fernanda','', 'Vargas', '(949) 533-1234', 'vargas@aol.com', 'phone', '23 Real St', 'Irvine', 'CA', '93731', 3, 1, 0),
('Billy','', 'Bob', '(800) 555-1234', 'bob@aol.com', 'email', '123 Spooner St', 'Springfield', 'IL', '65134', 4, 4, 0)
;

-- end temp insert


DROP TABLE IF EXISTS grower_trees;
CREATE TABLE grower_trees (
	id INT PRIMARY KEY AUTO_INCREMENT,
	grower_id INT,
	tree_type INT,
  	varietal TEXT,  
	number INT,
	avgHeight_id INT, 
	chemicaled TINYINT(1) NULL, -- 1 Yes -- 0 No	   
	CONSTRAINT fk_grower_trees_grower FOREIGN KEY (grower_id) REFERENCES growers(id),
	CONSTRAINT fk_grower_trees_tree FOREIGN KEY (tree_type) REFERENCES tree_types(id),
	CONSTRAINT fk_grower_trees_height FOREIGN KEY (avgHeight_id) REFERENCES tree_heights(id)
) ENGINE=innodb;


DROP TABLE IF EXISTS month_harvests;
CREATE TABLE month_harvests (
	tree_id INT,
	month_id INT,
	CONSTRAINT pk_month_harvests_month_harvest PRIMARY KEY (tree_id, month_id),
	CONSTRAINT fk_month_harvests_tree_type_id FOREIGN KEY (tree_id) REFERENCES grower_trees(id),
	CONSTRAINT fk_month_harvests_month_id FOREIGN KEY (month_id) REFERENCES months(id)
) ENGINE=innodb;


-- temp insert
INSERT INTO grower_trees VALUES
	(NULL, 1,1,'reallyorange',2,5,1);

INSERT INTO month_harvests VALUES
	(1,12);

INSERT INTO grower_trees VALUES
	(NULL, 2,3,'fernandoavocado',2,5,1);

INSERT INTO month_harvests VALUES
	(2,1);

INSERT INTO grower_trees VALUES
	(NULL, 3,6,'apple pie',2,5,1);

INSERT INTO month_harvests VALUES
	(3,1);

INSERT INTO grower_trees VALUES
	(NULL, 4,5,'peach',2,5,1);

INSERT INTO month_harvests VALUES
	(4,11);
-- end temp insert

DROP TABLE IF EXISTS volunteer_types;
CREATE TABLE volunteer_types (
	id INT AUTO_INCREMENT PRIMARY KEY,
	type nvarchar(255) NOT NULL
) ENGINE=innodb;

INSERT INTO volunteer_types (type) VALUES
	('Harvester'),
	('Harvest Captain'),
	('Driver'),
	('Ambassador'),
	('Tree Scout')
;
    
DROP TABLE IF EXISTS privileges;
CREATE TABLE privileges (
	id INT AUTO_INCREMENT PRIMARY KEY,
	name 			NVARCHAR(255),
	can_login 		TINYINT(1)	DEFAULT 0,

	view_volunteer	TINYINT(1)	DEFAULT 0,
	edit_volunteer	TINYINT(1)	DEFAULT 0,
	del_volunteer	TINYINT(1)	DEFAULT 0,
	exp_volunteer	TINYINT(1)	DEFAULT 0,

	view_grower		TINYINT(1)	DEFAULT 0, -- grower implies trees too
	edit_grower		TINYINT(1)	DEFAULT 0,
	del_grower		TINYINT(1)	DEFAULT 0,
	exp_grower		TINYINT(1)	DEFAULT 0,

	view_event		TINYINT(1)	DEFAULT 0,
	edit_event		TINYINT(1)	DEFAULT 0,
	del_event		TINYINT(1)	DEFAULT 0,
	exp_event		TINYINT(1)	DEFAULT 0,

	view_distrib	TINYINT(1)	DEFAULT 0,
	edit_distrib	TINYINT(1)	DEFAULT 0,
	del_distrib		TINYINT(1)	DEFAULT 0,
	exp_distrib		TINYINT(1)	DEFAULT 0,

	view_donor		TINYINT(1)	DEFAULT 0,
	edit_donor		TINYINT(1)	DEFAULT 0,
	del_donor		TINYINT(1)	DEFAULT 0,
	exp_donor		TINYINT(1)	DEFAULT 0,

	send_email		TINYINT(1)	DEFAULT 0,
	recv_email		TINYINT(1)	DEFAULT 0,

	change_priv		TINYINT(1)	DEFAULT 0	-- can change this table

) ENGINE=innodb;

INSERT INTO privileges (name, can_login, view_volunteer, view_grower, send_email, recv_email, exp_grower, exp_volunteer, view_event, edit_event, view_distrib, edit_distrib) VALUES
	("Pending",			0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	("Volunteer",		0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	("Harvest Captain",	1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1)
	-- Admin
	-- Executive
;

-- TODO: this copies permissions from harvest captain to admin, but we still need to update new perms
INSERT INTO privileges
(name, can_login, view_volunteer, view_grower, send_email, recv_email, exp_grower, exp_volunteer, view_event, edit_event, view_distrib, edit_distrib)
	(SELECT 'Admin', can_login, view_volunteer, view_grower, send_email, recv_email, exp_grower, exp_volunteer, view_event, edit_event, view_distrib, edit_distrib
	FROM privileges WHERE name = 'Harvest Captain');

INSERT INTO privileges VALUES
	(NULL, 'Executive', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);

DROP TABLE IF EXISTS volunteers;
CREATE TABLE volunteers (
	id INT AUTO_INCREMENT PRIMARY KEY,
	first_name nvarchar(255) NOT NULL,
	middle_name nvarchar(255) NULL,
	last_name nvarchar(255) NOT NULL,
	organization nvarchar(255),
	phone varchar(17) NOT NULL, 
	email nvarchar(255) NOT NULL, 
	password nvarchar(255) NULL, 
	status TINYINT(1) DEFAULT 1, -- 1-Active, 0-Inactive
	street nvarchar(255) NOT NULL,
	city varchar(255) NOT NULL,
	state CHAR(2) NOT NULL, 
	zip varchar(5) NOT NULL, 
	privilege_id INT DEFAULT 1,
	signed_up DATE,
	notes TEXT,
	source_id	INT,
	CONSTRAINT fk_privilege_id FOREIGN KEY (privilege_id) REFERENCES privileges(id)
) ENGINE=innodb;


INSERT INTO volunteers (first_name, middle_name, last_name, phone, email, password, status, street, city, state, zip, privilege_id, signed_up,notes) VALUES
('Peter','', 'Anteater', '(123) 456-7890', 'admin@uci.edu', password('password'), 1, '456 Fake St', 'Irvine', 'CA', '91234', 4,'2010-05-01',"")
;

-- A volunteer can have many rolls
DROP TABLE IF EXISTS volunteer_roles;
CREATE TABLE volunteer_roles (
	volunteer_id INT NOT NULL,
	volunteer_type_id INT NOT NULL,
	CONSTRAINT pk_volunteer_roles PRIMARY KEY (volunteer_id, volunteer_type_id),
	CONSTRAINT fk_volunteer_roles_volunteer_id FOREIGN KEY (volunteer_id) REFERENCES volunteers(id),
	CONSTRAINT fk_volunteer_roles_volunteer_type_id FOREIGN KEY (volunteer_type_id) REFERENCES volunteer_types(id)
) ENGINE=innodb;


DROP TABLE IF EXISTS volunteer_prefers;
CREATE TABLE volunteer_prefers (
	volunteer_id INT NOT NULL,
	day_id INT NOT NULL,
	CONSTRAINT pk_volunteer_prefers PRIMARY KEY (volunteer_id, day_id),
	CONSTRAINT fk_volunteer_prefers_volunteer_id FOREIGN KEY (volunteer_id) REFERENCES volunteers(id),
	CONSTRAINT fk_volunteer_prefers_day_id FOREIGN KEY (day_id) REFERENCES days(id)
) ENGINE=innodb;


DROP TABLE IF EXISTS events;
CREATE TABLE events (
	id INT AUTO_INCREMENT PRIMARY KEY,
	name nVARCHAR(255) NOT NULL,
	grower_id INT NOT NULL,
	captain_id INT NOT NULL,
	date datetime,
	CONSTRAINT fk_event_grower_id FOREIGN KEY (grower_id) REFERENCES growers(id),
	CONSTRAINT fk_event_captain_id FOREIGN KEY (captain_id) REFERENCES volunteers(id)
) ENGINE=innodb;


-- This table associated each volunteer with each event.
DROP TABLE IF EXISTS volunteer_events;
CREATE TABLE volunteer_events (
	event_id INT NOT NULL,
	volunteer_id INT NOT NULL,
	driver TINYINT NOT NULL,
	CONSTRAINT pk_volunteer_events PRIMARY KEY (volunteer_id, event_id),
	CONSTRAINT fk_volunteer_events_volunteer_id FOREIGN KEY (volunteer_id) REFERENCES volunteers(id),
	CONSTRAINT fk_volunteer_events_event_id FOREIGN KEY (event_id) REFERENCES events(id)
) ENGINE=innodb;

DROP TABLE IF EXISTS harvests;
CREATE TABLE harvests (
	event_id INT NOT NULL,
	tree_id INT NOT NULL,
	pound INT NOT NULL,
	CONSTRAINT pk_harvests PRIMARY KEY (tree_id, event_id),
	CONSTRAINT fk_harvests_tree_id FOREIGN KEY (tree_id) REFERENCES tree_types(id),
	CONSTRAINT fk_harvests_event_id FOREIGN KEY (event_id) REFERENCES events(id)
) ENGINE=innodb;

DROP TABLE IF EXISTS distributions;
CREATE TABLE distributions (
	id INT AUTO_INCREMENT PRIMARY KEY,
	name nvarchar(255) NOT NULL,
	phone nvarchar(17) NOT NULL, 
	email nvarchar(255) NULL, 
	street nvarchar(255) NOT NULL,
	city nvarchar(255) NOT NULL,
	state CHAR(2) NOT NULL, 
	zip nvarchar(5) NULL ,	
  notes TEXT
) ENGINE=innodb;

INSERT INTO distributions (name, phone, email, street, city, state, zip) VALUES
	("Rock Star",'123-546-8797','rockstar@yahoo.com',' 123 ABC St', 'ABC', 'CA', '91000')
;

DROP TABLE IF EXISTS distribution_hours;
CREATE TABLE distribution_hours (
	distribution_id INT NOT NULL,
	day_id INT NOT NULL,
	open TIME , 
	close TIME,	
  CONSTRAINT pk_distribution_hours PRIMARY KEY (distribution_id, day_id),
	CONSTRAINT fk_distribution_hours_distribution_id FOREIGN KEY (distribution_id) REFERENCES distributions(id), 
  CONSTRAINT fk_distribution_hours_day_id FOREIGN KEY (day_id) REFERENCES days(id)
) ENGINE=innodb;


DROP TABLE IF EXISTS drivings;
CREATE TABLE drivings (
	event_id INT NOT NULL,
	tree_id INT NOT NULL,
	volunteer_id INT NOT NULL,
	distribution_id INT NOT NULL,
	pound INT NOT NULL,
	CONSTRAINT pk_drivings PRIMARY KEY (tree_id, event_id, volunteer_id, distribution_id),
	CONSTRAINT fk_drivings_tree_id FOREIGN KEY (tree_id) REFERENCES tree_types(id),
	CONSTRAINT fk_drivings_event_id FOREIGN KEY (event_id) REFERENCES events(id),
	CONSTRAINT fk_drivings_volunteer_id FOREIGN KEY (volunteer_id) REFERENCES volunteers(id),
	CONSTRAINT fk_drivings_distribution_id FOREIGN KEY (distribution_id) REFERENCES distributions(id)
) ENGINE=innodb;


DROP TABLE IF EXISTS donations;
CREATE TABLE donations (
	id INT AUTO_INCREMENT PRIMARY KEY,
	donation nVARCHAR(255) NOT NULL,
	donor nVARCHAR(255) DEFAULT "Anonymous",
	value double,
	date datetime
) ENGINE=innodb;





-- Please follow naming conventions above (plural table names)



-- Sample Queries:
-- 	General Table Queries:
-- 	SELECT g.first_name, g.last_name, g.phone, g.email, g.prefer_contact, g.street, g.city, g.state, g.zip, pt.name, pr.name 
--		FROM growers g LEFT JOIN property_types pt ON g.property_type_id = pt.id
-- 		LEFT JOIN property_relationships pr ON g.property_relationship_id = pr.id;
-- 	SELECT donor, donation, value 
-- 		FROM donations
-- 	SELECT ...
-- 		FROM volunteers
-- 	SELECT ...
-- 		FROM events
--	SELECT ...
-- 		FROM staff
--	SELECT ...
--		FROM distribution_sites
--
-- 	Statistical Queries:
-- 	SELECT tt.name, sum(h.pounds_harvested)
-- 		FROM harvested h LEFT JOIN tree_types tt ON h.tree_type_id = tt.id
-- 		GROUP BY tree_type_id
-- 	SELECT sum(pounds_harvested)*2.66667 AS "portions"
-- 		FROM harvested h
-- 	SELECT g.zip, sum(h.pounds_harvested)
-- 		FROM growers g LEFT JOIN events e ON g.id = e.grower_id
-- 		LEFT JOIN harvested h ON e.harvested_id = h.id
-- 		GROUP BY g.zip
-- 	SELECT sum(values)
-- 		FROM donations
-- 	SELECT count(*)
-- 		FROM volunteers


SET FOREIGN_KEY_CHECKS = 1; -- enable fk constraints!
