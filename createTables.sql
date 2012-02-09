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

INSERT INTO months (name) VALUES -- May not be necessary, just use 1-12?
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
	('December');

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
	first_name	VARCHAR(255) NOT NULL,
	last_name	VARCHAR(255) NOT NULL,
	phone		VARCHAR(17) NOT NULL, -- maybe (http://stackoverflow.com/q/75105/266535)
	email		VARCHAR(255) NOT NULL, -- max is actually 320, but so rare	
	street		VARCHAR(255) NOT NULL,
	city		VARCHAR(255) NOT NULL,
	state		CHAR(2)		NOT NULL, -- this makes sense right?
	zip			VARCHAR(5)	NOT NULL, -- can it be bigger?
	tools		TINYTEXT,
	hear		TINYTEXT, -- this should probably be another INT type and a FK to a table
	notes		TEXT,
	property_type_id INT NOT NULL,
	property_relationship_id INT NOT NULL,
	CONSTRAINT fk_property_type FOREIGN KEY (property_type_id) REFERENCES property_types(id) ON DELETE CASCADE,
	CONSTRAINT fk_property_relationship FOREIGN KEY (property_relationship_id) REFERENCES property_relationships(id) ON DELETE CASCADE
) ENGINE=innodb;

-- start temp insert (for debugging front end)
INSERT INTO growers (first_name, last_name, phone, email, street, city, state, zip, property_type_id, property_relationship_id) VALUES
('Steven', 'Sommers', '(949) 334-1234', 'sommers@uci.edu','123 Fake St', 'Irvine', 'CA', '91234', 1, 2),
('Lawrence', 'Nanners', '(949) 633-1234', 'nanners@uci.edu', '313 Fake St', 'Laguna', 'CA', '97234', 3, 2),
('Fernando', 'Vegas', '(949) 514-1234', 'vegas@uci.edu', '23 Fake St', 'Irvine', 'CA', '91234', 3, 1),
('Fernanda', 'Vargas', '(949) 533-1234', 'vargas@aol.com', '23 Real St', 'Irvine', 'CA', '93731', 3, 1),
('Billy', 'Bob', '(800) 555-1234', 'bob@aol.com', '123 Spooner St', 'Springfield', 'IL', '65134', 4, 4)
;
-- end temp insert



-- DROP TABLE IF EXISTS trees;
-- CREATE TABLE trees (
--	id 		INT AUTO_INCREMENT PRIMARY KEY,
--	tree_type 	INT NOT NULL FOREIGN KEY REFERENCES tree_types(id),
--	amount 		INT NOT NULL,
--	height 		INT NOT NULL FOREIGN KEY REFERENCES heights(id),
--	ripe_month 	INT NOT NULL FOREIGN KEY REFERENCES months(id), -- May not be necessary: Use 1-12
--	issues 		VARCHAR(64) DEFAULT "No",
--	pruned 		VARCHAR(64) DEFAULT "No",
--	sprayed 	VARCHAR(64) DEFAULT "No",
--	notes 		TEXT,
--	grower_id	INT NOT NULL FOREIGN KEY REFERENCES growers(id)
-- ) ENGINE=innodb;



-- TODO: volunteers table
-- TODO: events table
-- TODO: staff table
-- TODO: privileges table
-- TODO: distribution_sites table

DROP TABLE IF EXISTS grower_tree;
CREATE TABLE grower_tree (
	grower_id INT,
	tree_id INT,
	number INT,
	avgHeight_id INT, 
	sprayed TINYINT(1) NULL, -- 1 Yes -- 0 No
	pruned TINYINT(1) NULL,  -- 1 Yes -- 0 No
	diseased TINYINT(1) NULL,-- 1 Yes -- 0 No
	notes 		TEXT,
	CONSTRAINT fk_grower FOREIGN KEY (grower_id) REFERENCES growers(id),
	CONSTRAINT fk_tree FOREIGN KEY (tree_id) REFERENCES tree_types(id),
	CONSTRAINT fk_height FOREIGN KEY (avgHeight_id) REFERENCES tree_heights(id)
) ENGINE=innodb;


INSERT INTO grower_tree (grower_id, tree_id, number,avgHeight_id, sprayed, pruned, diseased) VALUES
	(1, 2, 5, 2, 0, 0, 0), 
	(2, 3, 10, 5, 0, 1, 0);

DROP TABLE IF EXISTS month_harvest;
CREATE TABLE month_harvest (
	grower_id INT,
	tree_type_id INT,
	month_id INT,
	CONSTRAINT pk_month_harvest PRIMARY KEY (grower_id, tree_type_id, month_id),
	CONSTRAINT fk_grower_id FOREIGN KEY (grower_id) REFERENCES growers(id),
	CONSTRAINT fk_tree_type_id FOREIGN KEY (tree_type_id) REFERENCES tree_types(id),
	CONSTRAINT fk_month_id FOREIGN KEY (month_id) REFERENCES months(id)
) ENGINE=innodb;

INSERT INTO month_harvest (grower_id, tree_type_id, month_id) VALUES
	(1, 2, 4),
	(1, 2, 9),
	(2, 3, 7);

DROP TABLE IF EXISTS volunteer_types;
CREATE TABLE volunteer_types (
	id INT AUTO_INCREMENT PRIMARY KEY,
	type nvarchar(255) NOT NULL
) ENGINE=innodb;

INSERT INTO volunteer_types (type) VALUES
	('Harvester'),
	('Harvest Captain '),
	('Driver '),
	('Ambassador'),
	('Tree Scout');


DROP TABLE IF EXISTS volunteers;
CREATE TABLE volunteers (
	id INT AUTO_INCREMENT PRIMARY KEY,
	first_name nvarchar(255) NOT NULL,
	middle_name nvarchar(255) NULL,
	last_name nvarchar(255) NOT NULL,
	phone nvarchar(17) NOT NULL, 
	email nvarchar(255) NOT NULL, 
	active TINYINT(1), -- 1-Active, 0-Inactive
	street nvarchar(255) NOT NULL,
	city nvarchar(255) NOT NULL,
	state CHAR(2) NOT NULL, 
	zip nvarchar(5) NOT NULL 
) ENGINE=innodb;


INSERT INTO volunteers (first_name, middle_name, last_name, phone, email, active, street, city, state, zip) VALUES
	('Du','The', 'Du', '(123) 456-7890', 'dtdu@uci.edu', 1, '456 Fake St', 'Irvine', 'CA', '91234')
;

-- A volunteer can have many rolls
DROP TABLE IF EXISTS volunteer_roll;
CREATE TABLE volunteer_roll (
	volunteer_id INT NOT NULL,
	volunteer_type_id INT NOT NULL,
	CONSTRAINT pk_volunteer_roll PRIMARY KEY (volunteer_id, volunteer_type_id),
	CONSTRAINT fk_volunteer_id FOREIGN KEY (volunteer_id) REFERENCES volunteers(id),
	CONSTRAINT fk_volunteer_type_id FOREIGN KEY (volunteer_type_id) REFERENCES volunteer_types(id)
) ENGINE=innodb;

INSERT INTO volunteer_roll(volunteer_id, volunteer_type_id) VALUES
	(1, 2),
	(1, 3)
;

DROP TABLE IF EXISTS volunteer_prefer;
CREATE TABLE volunteer_prefer (
	volunteer_id INT NOT NULL,
	day_id INT NOT NULL,
	CONSTRAINT pk_volunteer_prefer PRIMARY KEY (volunteer_id, day_id),
	CONSTRAINT fk_volunteer_prefer_id FOREIGN KEY (volunteer_id) REFERENCES volunteers(id),
	CONSTRAINT fk_day_id FOREIGN KEY (day_id) REFERENCES days(id)
) ENGINE=innodb;

INSERT INTO volunteer_prefer( volunteer_id, day_id) VALUES
	(1, 2),
	(1, 5);


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
DROP TABLE IF EXISTS volunteer_event;
CREATE TABLE volunteer_event (
	event_id INT NOT NULL,
	volunteer_id INT NOT NULL,
	driver TINYINT NOT NULL,
	CONSTRAINT pk_volunteer_event PRIMARY KEY (volunteer_id, event_id),
	CONSTRAINT fk_volunteer_event_volunteer_id FOREIGN KEY (volunteer_id) REFERENCES volunteers(id),
	CONSTRAINT fk_volunteer_event_event_id FOREIGN KEY (event_id) REFERENCES events(id)
) ENGINE=innodb;

DROP TABLE IF EXISTS harvest;
CREATE TABLE harvest (
	event_id INT NOT NULL,
	tree_id INT NOT NULL,
	pound INT NOT NULL,
	CONSTRAINT pk_harvest PRIMARY KEY (tree_id, event_id),
	CONSTRAINT fk_harvest_tree_id FOREIGN KEY (tree_id) REFERENCES tree_types(id),
	CONSTRAINT fk_harvest_event_id FOREIGN KEY (event_id) REFERENCES events(id)
) ENGINE=innodb;

DROP TABLE IF EXISTS distribution;
CREATE TABLE distribution (
	id INT AUTO_INCREMENT PRIMARY KEY,
	name nvarchar(255) NOT NULL,
	phone nvarchar(17) NOT NULL, 
	email nvarchar(255) NOT NULL, 
	street nvarchar(255) NOT NULL,
	city nvarchar(255) NOT NULL,
	state CHAR(2) NOT NULL, 
	zip nvarchar(5) NOT NULL ,
	hours nvarchar(255) NOT NULL
) ENGINE=innodb;


DROP TABLE IF EXISTS driving;
CREATE TABLE driving (
	event_id INT NOT NULL,
	tree_id INT NOT NULL,
	volunteer_id INT NOT NULL,
	distribution_id INT NOT NULL,
	pound INT NOT NULL,
	CONSTRAINT pk_driving PRIMARY KEY (tree_id, event_id, volunteer_id, distribution_id),
	CONSTRAINT fk_driving_tree_id FOREIGN KEY (tree_id) REFERENCES tree_types(id),
	CONSTRAINT fk_driving_event_id FOREIGN KEY (event_id) REFERENCES events(id),
	CONSTRAINT fk_driving_volunteer_id FOREIGN KEY (volunteer_id) REFERENCES volunteers(id),
	CONSTRAINT fk_driving_distribution_id FOREIGN KEY (distribution_id) REFERENCES distributions(id)
) ENGINE=innodb;


DROP TABLE IF EXISTS donations;
CREATE TABLE donations (
	id INT AUTO_INCREMENT PRIMARY KEY,
	donation nVARCHAR(255) NOT NULL,
	donor nVARCHAR(255) DEFAULT "Anonymous",
	value double,
	date datetime
) ENGINE=innodb;

DROP TABLE IF EXISTS admins;
CREATE TABLE admins (
	id INT AUTO_INCREMENT PRIMARY KEY,
	first_name nvarchar(255) NOT NULL,
	middle_name nvarchar(255) NULL,
	last_name nvarchar(255) NOT NULL,
	phone nvarchar(17) NOT NULL, 
	email nvarchar(255) NOT NULL, 
	password nvarchar(255) NOT NULL,
	executive TINYINT(1), -- 1-Yes, 0-- No
	street nvarchar(255) NOT NULL,
	city nvarchar(255) NOT NULL,
	state CHAR(2) NOT NULL, 
	zip nvarchar(5) NOT NULL 
) ENGINE=innodb;

INSERT INTO admins (first_name, middle_name, last_name, phone, email, password, executive, street, city, state, zip) VALUES
	('Peter','', 'Anteater', '(123) 456-7890', 'admin@uci.edu', password('password'), 1, '456 Fake St', 'Irvine', 'CA', '91234')
;


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
