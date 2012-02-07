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
	prefer_contact CHAR(1), -- P for phone, E for email
	street		VARCHAR(255) NOT NULL,
	city		VARCHAR(255) NOT NULL,
	state		CHAR(2)		NOT NULL, -- this makes sense right?
	zip			VARCHAR(5)	NOT NULL, -- can it be bigger?
	property_type_id INT NOT NULL,
	property_relationship_id INT NOT NULL,
	CONSTRAINT fk_property_type FOREIGN KEY (property_type_id) REFERENCES property_types(id) ON DELETE CASCADE,
	CONSTRAINT fk_property_relationship FOREIGN KEY (property_relationship_id) REFERENCES property_relationships(id) ON DELETE CASCADE
) ENGINE=innodb;

-- start temp insert (for debugging front end)
INSERT INTO growers (first_name, last_name, phone, email, prefer_contact, street, city, state, zip, property_type_id, property_relationship_id) VALUES
('Steven', 'Sommers', '(949) 334-1234', 'sommers@uci.edu', 'P', '123 Fake St', 'Irvine', 'CA', '91234', 1, 2),
('Lawrence', 'Nanners', '(949) 633-1234', 'nanners@uci.edu', 'P', '313 Fake St', 'Laguna', 'CA', '97234', 3, 2),
('Fernando', 'Vegas', '(949) 514-1234', 'vegas@uci.edu', 'E', '23 Fake St', 'Irvine', 'CA', '91234', 3, 1),
('Fernanda', 'Vargas', '(949) 533-1234', 'vargas@aol.com', 'E', '23 Real St', 'Irvine', 'CA', '93731', 3, 1),
('Billy', 'Bob', '(800) 555-1234', 'bob@aol.com', 'E', '123 Spooner St', 'Springfield', 'IL', '65134', 4, 4)
;
-- end temp insert



DROP TABLE IF EXISTS trees;
CREATE TABLE trees (
	id 		INT AUTO_INCREMENT PRIMARY KEY,
	tree_type 	INT NOT NULL FOREIGN KEY REFERENCES tree_types(id),
	amount 		INT NOT NULL,
	height 		INT NOT NULL FOREIGN KEY REFERENCES heights(id),
	ripe_month 	INT NOT NULL FOREIGN KEY REFERENCES months(id), -- May not be necessary: Use 1-12
	issues 		VARCHAR(64) DEFAULT "No",
	pruned 		VARCHAR(64) DEFAULT "No",
	sprayed 	VARCHAR(64) DEFAULT "No",
	notes 		TEXT,
	grower_id	INT NOT NULL FOREIGN KEY REFERENCES growers(id)
) ENGINE=innodb;



-- TODO: volunteers table
-- TODO: events table
-- TODO: staff table
-- TODO: privileges table
-- TODO: distribution_sites table



DROP TABLE IF EXISTS donations;
CREATE TABLE donations (
	id		INT AUTO_INCREMENT PRIMARY KEY,
	donation	VARCHAR(255) NOT NULL,
	donor		VARCHAR(255) DEFAULT "Anonymous",
	value		INT
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
