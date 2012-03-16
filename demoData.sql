
-- start temp insert (for debugging front end)
INSERT INTO growers (first_name, middle_name, last_name, phone, email, preferred, street, city, state, zip, property_type_id, property_relationship_id, pending) VALUES
('Steven', 'The', 'Sommers', '(949) 334-1234', 'sommers@uci.edu', 'email', '123 Fake St', 'Irvine', 'CA', '91234', 1, 2, 0),
('Lawrence', 'O','Nanners', '(949) 633-1234', 'nanners@uci.edu', 'email', '313 Fake St', 'Laguna', 'CA', '97234', 3, 2 ,0),
('Fernando', 'Las', 'Vegas', '(949) 514-1234', 'vegas@uci.edu', 'phone', '23 Fake St', 'Irvine', 'CA', '91234', 3, 1, 0),
('Fernanda', '', 'Vargas', '(949) 533-1234', 'vargas@aol.com', 'phone', '23 Real St', 'Irvine', 'CA', '93731', 3, 1, 0),
('Billy', '', 'Bob', '(800) 555-1234', 'bob@aol.com', 'email', '123 Spooner St', 'Springfield', 'IL', '65134', 4, 4, 0);
-- end temp insert

-- start temp insert
INSERT INTO grower_trees VALUES
	(1, 1,1,'Naval',2,5,1);

INSERT INTO month_harvests VALUES
	(1,12);

INSERT INTO grower_trees VALUES
	(2, 2,3,'Tiny',2,5,1);

INSERT INTO month_harvests VALUES
	(2,1);

INSERT INTO grower_trees VALUES
	(3, 3,6,'Key',2,5,1);

INSERT INTO month_harvests VALUES
	(3,1);

INSERT INTO grower_trees VALUES
	(4, 4,5,'Yellow',2,5,1);

INSERT INTO month_harvests VALUES
	(4,11);
	
INSERT INTO grower_trees VALUES
	(5, 4,8,'Red',2,5,1);
	
INSERT INTO month_harvests VALUES
	(5,4);
-- end temp insert

-- start temp insert
INSERT INTO volunteers (first_name, middle_name, last_name, phone, email, password, active_id, street, city, state, zip, privilege_id, signed_up, notes, surplus_hours) VALUES
('Peter','', 'Anteater', '(123) 456-7890', 'admin@uci.edu', SHA1('password'), 1, '456 Fake St', 'Irvine', 'CA', '91234', 5,'2010-05-01', 'Fearless mascot','1'),
('Joanne','', 'Lolcatz', '(949) 555-3418', 'joanne@uci.edu', SHA1('password'), 1, '1 Harvest Cir', 'Irvine', 'CA', '91234', 5,'2012-03-01', 'Executive Power','2'),
('Gillian','', 'Pwn', '(555) 555-1090', 'gillian@uci.edu', SHA1('password'), 1, '2 Harvest Cir', 'Irvine', 'CA', '91234', 4,'2012-03-01', 'Administrative skillz','3'),
('Captain','Jack', 'Sparrow', '(949) 555-1337', 'captain@uci.edu', SHA1('password'), 1, '4 Black Pearl Rd', 'Pacific Ocean', 'CA', '99999', 3,'2012-03-01', 'Cursed','4'),
('Victor','', 'Van', '(949) 555-9168', 'victor@uci.edu', SHA1('password'), 1, '2121 Ball Rd', 'Anaheim', 'CA', '92806', 2,'2012-03-01', 'Donut owner','5'),
('Peter','', 'Pending', '(949) 555-0001', 'peter@uci.edu', SHA1('password'), 1, '6 Blue Nowhere', 'San Clemente', 'CA', '96539', 1,'2012-03-01', 'I am waiting...','6'),
('Do','The', 'Dew', '(949) 555-0001', 'dietpepsi@soda.coke', SHA1('password'), 1, '7 Up', 'San Clemente', 'CA', '96539', 1,'2012-03-01', 'Do The Dew. Dont Do The Drug','7');

-- end temp insert

-- start temp insert
INSERT INTO distributions (name, phone, email, street, city, state, zip) VALUES
	('Distributing Sustenance','123-546-8797','distrib@yahoo.com','123 ABC St', 'ABC', 'CA', '91000');
-- end temp insert

-- start temp insert
INSERT INTO donations(donation, donor, value, date) VALUES
	("Bucket o' Money", "Donald Bren", 999.99, CURDATE());
-- end temp insert
