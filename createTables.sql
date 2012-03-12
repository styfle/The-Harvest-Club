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
	('December');

DROP TABLE IF EXISTS days;
CREATE TABLE days (
	id INT AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(255) NOT NULL
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
	('Other'),
	('Flyer'),
	('Facebook/Twitter'),
	('Family or Friend'),
	('Newspaper/Local Magazine'),
	('Website/Search Engine'),
	('Village Harvest')
;
DROP TABLE IF EXISTS growers;
CREATE TABLE growers (
	id			INT AUTO_INCREMENT PRIMARY KEY,
	first_name	NVARCHAR(255) NOT NULL,
	middle_name	NVARCHAR(255),
	last_name	NVARCHAR(255) NOT NULL,
	phone		VARCHAR(17) NOT NULL, -- maybe (http://stackoverflow.com/q/75105/266535)
	email		NVARCHAR(255) NOT NULL, -- max is actually 320, but so rare	
	preferred	VARCHAR(6), -- preferred contact method (phone/email)
	street		NVARCHAR(255) NOT NULL,
	city		NVARCHAR(255) NOT NULL,
	state		CHAR(2)	NOT NULL, -- this makes sense right?
	zip			VARCHAR(5) NOT NULL, -- can it be bigger?
	tools		TINYTEXT,
	source_id	INT DEFAULT 1,
	notes		TEXT,
  	pending TINYINT(1) DEFAULT 1, -- 1-Yes 0-No     
	property_type_id INT NULL,
	property_relationship_id INT NULL,
	CONSTRAINT fk_property_type FOREIGN KEY (property_type_id) REFERENCES property_types(id),
	CONSTRAINT fk_property_relationship FOREIGN KEY (property_relationship_id) REFERENCES property_relationships(id)
) ENGINE=innodb;

-- start temp insert (for debugging front end)
INSERT INTO growers (first_name, middle_name, last_name, phone, email, preferred, street, city, state, zip, property_type_id, property_relationship_id, pending) VALUES
('Steven', 'The', 'Sommers', '(949) 334-1234', 'sommers@uci.edu', 'email', '123 Fake St', 'Irvine', 'CA', '91234', 1, 2, 0),
('Lawrence', 'O','Nanners', '(949) 633-1234', 'nanners@uci.edu', 'email', '313 Fake St', 'Laguna', 'CA', '97234', 3, 2 ,0),
('Fernando', 'Las', 'Vegas', '(949) 514-1234', 'vegas@uci.edu', 'phone', '23 Fake St', 'Irvine', 'CA', '91234', 3, 1, 0),
('Fernanda', '', 'Vargas', '(949) 533-1234', 'vargas@aol.com', 'phone', '23 Real St', 'Irvine', 'CA', '93731', 3, 1, 0),
('Billy', '', 'Bob', '(800) 555-1234', 'bob@aol.com', 'email', '123 Spooner St', 'Springfield', 'IL', '65134', 4, 4, 0);
-- end temp insert

DROP TABLE IF EXISTS grower_trees;
CREATE TABLE grower_trees (
	id INT PRIMARY KEY AUTO_INCREMENT,
	grower_id INT,
	tree_type INT,
  	varietal TEXT,  
	number INT,
	avgHeight_id INT, 
	chemicaled TINYINT(1), -- 1 Yes -- 0 No	   
	CONSTRAINT fk_grower_trees_grower FOREIGN KEY (grower_id) REFERENCES growers(id) ON DELETE CASCADE,
	CONSTRAINT fk_grower_trees_tree FOREIGN KEY (tree_type) REFERENCES tree_types(id) ON DELETE CASCADE,
	CONSTRAINT fk_grower_trees_height FOREIGN KEY (avgHeight_id) REFERENCES tree_heights(id) ON DELETE CASCADE
) ENGINE=innodb;

DROP TABLE IF EXISTS month_harvests;
CREATE TABLE month_harvests (
	tree_id INT,
	month_id INT,
	CONSTRAINT pk_month_harvests_month_harvest PRIMARY KEY (tree_id, month_id),
	CONSTRAINT fk_month_harvests_tree_type_id FOREIGN KEY (tree_id) REFERENCES grower_trees(id) ON DELETE CASCADE,
	CONSTRAINT fk_month_harvests_month_id FOREIGN KEY (month_id) REFERENCES months(id) ON DELETE CASCADE
) ENGINE=innodb;

-- start temp insert
INSERT INTO grower_trees VALUES
	(1, 1,1,'reallyorange',2,5,1);

INSERT INTO month_harvests VALUES
	(1,12);

INSERT INTO grower_trees VALUES
	(2, 2,3,'fernandoavocado',2,5,1);

INSERT INTO month_harvests VALUES
	(2,1);

INSERT INTO grower_trees VALUES
	(3, 3,6,'apple pie',2,5,1);

INSERT INTO month_harvests VALUES
	(3,1);

INSERT INTO grower_trees VALUES
	(4, 4,5,'peach',2,5,1);

INSERT INTO month_harvests VALUES
	(4,11);
	
INSERT INTO grower_trees VALUES
	(5, 4,8,'banana',2,5,1);
	
INSERT INTO month_harvests VALUES
	(5,4);
-- end temp insert

DROP TABLE IF EXISTS volunteer_types;
CREATE TABLE volunteer_types (
	id INT AUTO_INCREMENT PRIMARY KEY,
	type nvarchar(255) NOT NULL,
	description varchar(255)
) ENGINE=innodb;

INSERT INTO volunteer_types (type, description) VALUES
	('Harvester', 'Pick and sort fruit at Harvest Events'),
	('Harvest Captain', 'Lead harvest crews during Harvest Events'),
	('Driver', 'Deliver harvested produce to local distribution sites'),
	('Ambassador', 'Distributes flyers in neighborhoods with visible fruit trees'),
	('Tree Scout', 'Meet with growers to inspect property prior to Harvest Events')
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
	appr_volunteer	TINYINT(1)	DEFAULT 0,

	view_grower		TINYINT(1)	DEFAULT 0, -- grower implies trees too
	edit_grower		TINYINT(1)	DEFAULT 0,
	del_grower		TINYINT(1)	DEFAULT 0,
	exp_grower		TINYINT(1)	DEFAULT 0,
	appr_grower		TINYINT(1)	DEFAULT 0,

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

INSERT INTO privileges
(name, can_login, view_volunteer, view_grower, send_email, recv_email, exp_grower, exp_volunteer, view_event, edit_event, view_distrib, edit_distrib)
	(SELECT 'Admin', can_login, view_volunteer, view_grower, send_email, recv_email, exp_grower, exp_volunteer, view_event, edit_event, view_distrib, edit_distrib
	FROM privileges WHERE name = 'Harvest Captain');

UPDATE privileges
	SET view_grower=1, edit_grower=1, appr_grower=1,
	view_volunteer=1, edit_volunteer=1, appr_volunteer=1
	WHERE name = 'Admin';

INSERT INTO privileges VALUES
	(NULL, 'Executive', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1 ,1 ,1);

DROP TABLE IF EXISTS volunteers;
CREATE TABLE volunteers (
	id INT AUTO_INCREMENT PRIMARY KEY,
	first_name nvarchar(255) NOT NULL,
	middle_name nvarchar(255),
	last_name nvarchar(255) NOT NULL,
	organization nvarchar(255),
	phone varchar(17) NOT NULL, 
	email nvarchar(255) NOT NULL, 
	password nvarchar(255) NULL, 
	active_id TINYINT(1) DEFAULT 1, -- 1-Active, 0-Inactive
	street nvarchar(255) NOT NULL,
	city nvarchar(255) NOT NULL,
	state CHAR(2) NOT NULL, 
	zip varchar(5) NOT NULL, 
	privilege_id INT DEFAULT 1,
	signed_up DATE,
	notes TEXT,
	source_id INT DEFAULT 1,
	CONSTRAINT fk_privilege_id FOREIGN KEY (privilege_id) REFERENCES privileges(id),
	CONSTRAINT fk_source_id_volunteers FOREIGN KEY (source_id) REFERENCES sources(id)
) ENGINE=innodb;

-- start temp insert
INSERT INTO volunteers (first_name, middle_name, last_name, phone, email, password, active_id, street, city, state, zip, privilege_id, signed_up, notes) VALUES
('Peter','', 'Anteater', '(123) 456-7890', 'admin@uci.edu', SHA1('password'), 1, '456 Fake St', 'Irvine', 'CA', '91234', 5,'2010-05-01', 'Fearless mascot'),
('Joanne','', 'Lolcatz', '(949) 555-3418', 'joanne@uci.edu', SHA1('password'), 1, '1 Harvest Cir', 'Irvine', 'CA', '91234', 5,'2012-03-01', 'Executive Power'),
('Gillian','', 'Pwn', '(555) 555-1090', 'gillian@uci.edu', SHA1('password'), 1, '2 Harvest Cir', 'Irvine', 'CA', '91234', 4,'2012-03-01', 'Administrative skillz'),
('Captain','Jack', 'Sparrow', '(949) 555-1337', 'captain@uci.edu', SHA1('password'), 1, '4 Black Pearl Rd', 'Pacific Ocean', 'CA', '99999', 3,'2012-03-01', 'Cursed'),
('Victor','', 'Van', '(949) 555-9168', 'victor@uci.edu', SHA1('password'), 1, '2121 Ball Rd', 'Anaheim', 'CA', '92806', 2,'2012-03-01', 'Donut owner'),
('Peter','', 'Pending', '(949) 555-0001', 'peter@uci.edu', SHA1('password'), 1, '6 Blue Nowhere', 'San Clemente', 'CA', '96539', 1,'2012-03-01', 'I am waiting...');
-- end temp insert

-- A volunteer can have many rolls
DROP TABLE IF EXISTS volunteer_roles;
CREATE TABLE volunteer_roles (
	volunteer_id INT NOT NULL,
	volunteer_type_id INT NOT NULL,
	CONSTRAINT pk_volunteer_roles PRIMARY KEY (volunteer_id, volunteer_type_id),
	CONSTRAINT fk_volunteer_roles_volunteer_id FOREIGN KEY (volunteer_id) REFERENCES volunteers(id) ON DELETE CASCADE,
	CONSTRAINT fk_volunteer_roles_volunteer_type_id FOREIGN KEY (volunteer_type_id) REFERENCES volunteer_types(id)
) ENGINE=innodb;

DROP TABLE IF EXISTS volunteer_prefers;
CREATE TABLE volunteer_prefers (
	volunteer_id INT NOT NULL,
	day_id INT NOT NULL,
	CONSTRAINT pk_volunteer_prefers PRIMARY KEY (volunteer_id, day_id),
	CONSTRAINT fk_volunteer_prefers_volunteer_id FOREIGN KEY (volunteer_id) REFERENCES volunteers(id) ON DELETE CASCADE,
	CONSTRAINT fk_volunteer_prefers_day_id FOREIGN KEY (day_id) REFERENCES days(id)
) ENGINE=innodb;

DROP TABLE IF EXISTS events;
CREATE TABLE events (
	id INT AUTO_INCREMENT PRIMARY KEY,
	grower_id INT NOT NULL,
	captain_id INT NOT NULL,
	date date,
	time text,
	notes text,
	CONSTRAINT fk_event_grower_id FOREIGN KEY (grower_id) REFERENCES growers(id),
	CONSTRAINT fk_event_captain_id FOREIGN KEY (captain_id) REFERENCES volunteers(id)
) ENGINE=innodb;

-- This table associated each volunteer with each event.
DROP TABLE IF EXISTS volunteer_events;
CREATE TABLE volunteer_events (
	event_id INT NOT NULL,
	volunteer_id INT NOT NULL,
	driver TINYINT(1) DEFAULT 0, -- 1 if driver -- 0 if not driver
	hour DOUBLE,
	CONSTRAINT pk_volunteer_events PRIMARY KEY (volunteer_id, event_id),
	CONSTRAINT fk_volunteer_events_volunteer_id FOREIGN KEY (volunteer_id) REFERENCES volunteers(id),
	CONSTRAINT fk_volunteer_events_event_id FOREIGN KEY (event_id) REFERENCES events(id)
) ENGINE=innodb;

DROP TABLE IF EXISTS harvests;
CREATE TABLE harvests (
	event_id INT NOT NULL,
	tree_id INT NOT NULL,
	number INT,
	pound DOUBLE,
	CONSTRAINT pk_harvests PRIMARY KEY (tree_id, event_id),
	CONSTRAINT fk_harvests_tree_id FOREIGN KEY (tree_id) REFERENCES tree_types(id),
	CONSTRAINT fk_harvests_event_id FOREIGN KEY (event_id) REFERENCES events(id)
) ENGINE=innodb;

DROP TABLE IF EXISTS distributions;
CREATE TABLE distributions (
	id INT AUTO_INCREMENT PRIMARY KEY,
	name nvarchar(255) NOT NULL,
	contact nvarchar(255),
	phone varchar(17) NOT NULL, 
	contact2 nvarchar(255),
	phone2 varchar(17),
	email nvarchar(255), 
	street nvarchar(255) NOT NULL,
	city nvarchar(255) NOT NULL,
	state CHAR(2) NOT NULL, 
	zip varchar(5),	
  	notes TEXT
) ENGINE=innodb;

-- start temp insert
INSERT INTO distributions (name, phone, email, street, city, state, zip) VALUES
	("Rock Star",'123-546-8797','rockstar@yahoo.com',' 123 ABC St', 'ABC', 'CA', '91000');
-- end temp insert

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
	pound DOUBLE,
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
	date DATE
) ENGINE=innodb;

-- start temp insert
INSERT INTO donations(donation, donor, value, date) VALUES
	("Bucket o' Money", "Donald Bren", 999.99, CURDATE());
-- end temp insert




-- Please follow naming conventions above (plural table names)





SET FOREIGN_KEY_CHECKS = 1; -- enable fk constraints!
