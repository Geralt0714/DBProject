DROP TABLE IF EXISTS `Friend`;
DROP TABLE IF EXISTS `Following`;
DROP TABLE IF EXISTS `Invitation`;
DROP TABLE IF EXISTS `Message`;
DROP TABLE IF EXISTS `JobNotification`;
DROP TABLE IF EXISTS `Application`;
DROP TABLE IF EXISTS `Job`;
DROP TABLE IF EXISTS `Student`;
DROP TABLE IF EXISTS `Company`;


CREATE TABLE `Student` (
	`sid` int NOT NULL AUTO_INCREMENT,
	`sname` VARCHAR(20) NOT NULL,
	`password` VARCHAR(15)NOT NULL,
	`degree` ENUM('UGrd','Grad','PhD') NOT NULL,
	`university` VARCHAR(25) NOT NULL,
	`major`VARCHAR(25)NOT NULL,
	`gpa` DOUBLE(3,2)NOT NULL,
	`skill`VARCHAR(25)NOT NULL,
	`access` ENUM('0','1') NOT NULL DEFAULT '0',
	`resume` MEDIUMBLOB,
	`rgtime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`sid`)
);
INSERT INTO `Student` VALUES ('03033','Geralt','12345678','PhD','NYU','Computer Science','3.71','SQL','0',NULL,CURRENT_TIMESTAMP);
INSERT INTO `Student` VALUES ('19903','Ciri','12345678','Grad','NYU','Computer Science','3.81','Frontend Design','0',NULL,CURRENT_TIMESTAMP);
INSERT INTO `Student` VALUES ('17801','Triss Merigold','12345678','Grad','NYU','Computer Science','3.11','SQL','0',NULL,CURRENT_TIMESTAMP);
INSERT INTO `Student` VALUES ('11109','Yennefer','12345678','Grad','Columbia University','Computer Science','3.71','SQL','0',NULL,CURRENT_TIMESTAMP);
INSERT INTO `Student` VALUES ('15408','Keira Metz','12345678','Grad','Chicago University','Electrical Engineering','3.45','SQL','0',NULL,CURRENT_TIMESTAMP);
INSERT INTO `Student` VALUES ('18334','Emreis','12345678','Grad','NYU','Social Science','3.71','SQL','0',NULL,CURRENT_TIMESTAMP);
INSERT INTO `Student` VALUES ('27931','Letho','12345678','PhD','NYU','Social Science','3.71','SQL','0',NULL,CURRENT_TIMESTAMP);
INSERT INTO `Student` VALUES ('36666','Eredin','12345678','UGrd','NYU','Computer Science','3.71','SQL','0',NULL,CURRENT_TIMESTAMP);
INSERT INTO `Student` VALUES ('47777','Avallac''h','12345678','UGrd','NYU','Computer Science','3.71','SQL','0',NULL,CURRENT_TIMESTAMP);
INSERT INTO `Student` VALUES ('11024','Radovid V','12345678','PhD','NYU','Computer Science','3.71','SQL','0',NULL,CURRENT_TIMESTAMP);
INSERT INTO `Student` VALUES ('22048','Dandelion','12345678','UGrd','NYU','Computer Science','3.71','SQL','0',NULL,CURRENT_TIMESTAMP);
INSERT INTO `Student` VALUES ('44096','Lambert','12345678','Grad','NYU','Computer Science','3.71','SQL','0',NULL,CURRENT_TIMESTAMP);
INSERT INTO `Student` VALUES ('38192','Vesemir','12345678','UGrd','NYU','Computer Science','3.71','SQL','0',NULL,CURRENT_TIMESTAMP);
INSERT INTO `Student` VALUES ('60512','Priscilla','12345678','UGrd','NYU','Electrical Engineering','3.71','SQL','0',NULL,CURRENT_TIMESTAMP);
INSERT INTO `Student` VALUES ('10256','Vernon Roche','12345678','UGrd','NYU','Computer Science','3.71','SQL','0',NULL,CURRENT_TIMESTAMP);
INSERT INTO `Student` VALUES ('90128','Zoltan Chivay','12345678','UGrd','NYU','Computer Science','3.71','SQL','0',NULL,CURRENT_TIMESTAMP);
INSERT INTO `Student` VALUES ('58888','Roach','12345678','UGrd','NYU','Computer Science','3.71','SQL','0',NULL,CURRENT_TIMESTAMP);
INSERT INTO `Student` VALUES ('34512','Bram','12345678','PhD','NYU','Computer Science','3.71','SQL','0',NULL,CURRENT_TIMESTAMP);
INSERT INTO `Student` VALUES ('11108','Sigismund Dijkstra','12345678','PhD','NYU','Computer Science','3.71','SQL','0',NULL,CURRENT_TIMESTAMP);
INSERT INTO `Student` VALUES ('71993','Ves','12345678','PhD','NYU','Computer Science','3.71','SQL','0',NULL,CURRENT_TIMESTAMP);
INSERT INTO `Student` VALUES ('29903','Geralt Of Rivia','12345678','PhD','NYU','Computer Science','3.71','SQL','0',NULL,CURRENT_TIMESTAMP);


CREATE TABLE `Company` (
	`cid` INT NOT NULL AUTO_INCREMENT,
	`password` VARCHAR(15)NOT NULL,
	`cname` VARCHAR(20) NOT NULL,
	`location` VARCHAR(20) NOT NULL,
	`industry`VARCHAR(25)NOT NULL,
	`rgtime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`cid`)
);

INSERT INTO `Company` VALUES ('00001','12345678','Google','San Francisco','Search Engine',CURRENT_TIMESTAMP);
INSERT INTO `Company` VALUES ('00002','12345678','Microsoft','Seattle','Operating System',CURRENT_TIMESTAMP);
INSERT INTO `Company` VALUES ('00003','12345678','Facebook','San Francisco','Social Network',CURRENT_TIMESTAMP);
INSERT INTO `Company` VALUES ('00004','12345678','LinkedIn','San Francisco','Online Human Resource',CURRENT_TIMESTAMP);
INSERT INTO `Company` VALUES ('00005','12345678','Amazon','San Francisco','Online Retail',CURRENT_TIMESTAMP);
INSERT INTO `Company` VALUES ('00006','12345678','Chase','New York City','Banking',CURRENT_TIMESTAMP);
INSERT INTO `Company` VALUES ('00007','12345678','MTA','New York City','Transportation',CURRENT_TIMESTAMP);
INSERT INTO `Company` VALUES ('00008','12345678','AMC','Los Angeles','Cinema',CURRENT_TIMESTAMP);
INSERT INTO `Company` VALUES ('00009','12345678','Walmart','Bentonville','Retail',CURRENT_TIMESTAMP);
INSERT INTO `Company` VALUES ('00010','12345678','Apple','San Francisco','Electronics',CURRENT_TIMESTAMP);
INSERT INTO `Company` VALUES ('00011','12345678','Exxon Mobil','Irving','Energy',CURRENT_TIMESTAMP);
INSERT INTO `Company` VALUES ('00012','12345678','McKesson','New York City','Healthcare',CURRENT_TIMESTAMP);
INSERT INTO `Company` VALUES ('00013','12345678','CVS Health','Woonsocket','Retail Pharmacy',CURRENT_TIMESTAMP);
INSERT INTO `Company` VALUES ('00014','12345678','General Motors','Detroit','Vehicle Manufacture',CURRENT_TIMESTAMP);

CREATE TABLE `Job` (
	`jid` INT NOT NULL AUTO_INCREMENT,
	`cid` INT NOT NULL,
	`jlocation` VARCHAR(20)NOT NULL,
	`title` VARCHAR(20)NOT NULL,
	`salary` VARCHAR(20) DEFAULT 'Unknown',
	`major`VARCHAR(25)NOT NULL,
	`academicbar` SET('UGrd','Grad','PhD') NOT NULL,
	`posttime` DATETIME DEFAULT CURRENT_TIMESTAMP,
	`posttype` ENUM('Public','Partial') NOT NULL,
	`descrp` VARCHAR(20) NOT NULL,
	PRIMARY KEY (`jid`),
	FOREIGN KEY (`cid`) REFERENCES `Company`(`cid`)
);

INSERT INTO `Job` VALUES ('0001','00001','New York City','Software Developer','100000','Computer Science','UGrd',CURRENT_TIMESTAMP,'Public','This is a simple job');
INSERT INTO `Job` VALUES ('0002','00002','New York City','Software Developer','100000','Computer Science','UGrd',CURRENT_TIMESTAMP,'Public','This is a simple job');
INSERT INTO `Job` VALUES ('0003','00002','New York City','Software Developer','100000','Computer Science','Grad',CURRENT_TIMESTAMP,'Public','This is a simple job');
INSERT INTO `Job` VALUES ('0004','00003','New York City','Software Developer','100000','Computer Science','UGrd,Grad',CURRENT_TIMESTAMP,'Public','This is a simple job');
INSERT INTO `Job` VALUES ('0005','00004','New York City','Accountant','100000','Computer Science','UGrd',CURRENT_TIMESTAMP,'Public','This is a simple job');
INSERT INTO `Job` VALUES ('0006','00004','New York City','Software Developer','100000','Computer Science','Grad,Phd',CURRENT_TIMESTAMP,'Public','This is a simple job');
INSERT INTO `Job` VALUES ('0007','00005','New York City','Software Developer','100000','Computer Science','PhD',CURRENT_TIMESTAMP,'Public','This is a simple job');
INSERT INTO `Job` VALUES ('0008','00002','New York City','Software Developer','100000','Computer Science','Grad','2018-04-10 10:53:22','Public','This is a simple job');


CREATE TABLE `Friend`(
	`sid` INT NOT NULL,
	`fid` INT NOT NULL,
	PRIMARY KEY (`sid`,`fid`),
	FOREIGN KEY (`sid`) REFERENCES `Student`(`sid`),
	FOREIGN KEY (`fid`) REFERENCES `Student`(`sid`)
);

INSERT INTO `Friend` VALUES ('17801','11108');
INSERT INTO `Friend` VALUES ('11108','17801');

INSERT INTO `Friend` VALUES ('03033','11108');
INSERT INTO `Friend` VALUES ('11108','03033');

INSERT INTO `Friend` VALUES ('11108','19903');
INSERT INTO `Friend` VALUES ('19903','11108');

INSERT INTO `Friend` VALUES ('17801','38192');
INSERT INTO `Friend` VALUES ('38192','17801');

INSERT INTO `Friend` VALUES ('58888','22048');
INSERT INTO `Friend` VALUES ('22048','58888');

INSERT INTO `Friend` VALUES ('60512','34512');
INSERT INTO `Friend` VALUES ('34512','60512');

INSERT INTO `Friend` VALUES ('38192','22048');
INSERT INTO `Friend` VALUES ('22048','38192');

INSERT INTO `Friend` VALUES ('90128','11108');
INSERT INTO `Friend` VALUES ('11108','90128');

INSERT INTO `Friend` VALUES ('58888','11108');
INSERT INTO `Friend` VALUES ('11108','58888');

INSERT INTO `Friend` VALUES ('27931','17801');
INSERT INTO `Friend` VALUES ('17801','27931');




CREATE TABLE `Following`(
	`sid` INT NOT NULL,
	`cid` INT NOT NULL,
	PRIMARY KEY (`sid`,`cid`),
	FOREIGN KEY (`sid`) REFERENCES `Student`(`sid`),
	FOREIGN KEY (`cid`) REFERENCES `Company`(`cid`)
);

INSERT INTO `Following` VALUES('11108','00002');
INSERT INTO `Following` VALUES('11108','00003');
INSERT INTO `Following` VALUES('19903','00006');
INSERT INTO `Following` VALUES('19903','00001');
INSERT INTO `Following` VALUES('11109','00001');
INSERT INTO `Following` VALUES('11109','00005');
INSERT INTO `Following` VALUES('17801','00005');
INSERT INTO `Following` VALUES('03033','00002');
INSERT INTO `Following` VALUES('03033','00004');






CREATE TABLE `Invitation`(
	`sd` INT NOT NULL,
	`rcv` INT NOT NULL,
	`itime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`status` ENUM('Accepted','Declined','Awaiting') NOT NULL,
	PRIMARY KEY (`sd`,`rcv`,`itime`),
	FOREIGN KEY (`sd`) REFERENCES `Student`(`sid`),
	FOREIGN KEY (`rcv`) REFERENCES `Student`(`sid`)
);

INSERT INTO `Invitation`(`sd`,`rcv`,`status`) VALUES ('11108','36666','Declined');
INSERT INTO `Invitation`(`sd`,`rcv`,`status`) VALUES ('15408','03033','Awaiting');
INSERT INTO `Invitation`(`sd`,`rcv`,`status`) VALUES ('11108','11024','Awaiting');
INSERT INTO `Invitation`(`sd`,`rcv`,`status`) VALUES ('11108','03033','Accepted');
INSERT INTO `Invitation`(`sd`,`rcv`,`status`) VALUES ('11108','17801','Accepted');
INSERT INTO `Invitation`(`sd`,`rcv`,`status`) VALUES ('11108','58888','Awaiting');
INSERT INTO `Invitation`(`sd`,`rcv`,`status`,`itime`) VALUES ('11108','71993','Awaiting','2018-02-20 13:40:54');



CREATE TABLE `Message`(
	`sd` INT NOT NULL,
	`rcv` INT NOT NULL,
	`mtime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`content` VARCHAR(20) NOT NULL,
	`mstatus` ENUM('Sent','Received') DEFAULT 'Sent',
	PRIMARY KEY (`sd`,`rcv`,`mtime`),
	FOREIGN KEY (`sd`) REFERENCES `Student`(`sid`),
	FOREIGN KEY (`rcv`) REFERENCES `Student`(`sid`)
);

INSERT INTO `Message` (`sd`,`rcv`,`content`,`mstatus`) VALUES('11108','03033','Hello,new here','Sent');
INSERT INTO `Message` (`sd`,`rcv`,`content`,`mstatus`,`mtime`) VALUES('11108','03033','Hello,new here','Sent',DATE_ADD(CURRENT_TIMESTAMP, INTERVAL -2 HOUR));
INSERT INTO `Message` (`sd`,`rcv`,`content`,`mstatus`,`mtime`) VALUES('11108','03033','Hello,new here','Sent',DATE_ADD(CURRENT_TIMESTAMP, INTERVAL -3 HOUR));




CREATE TABLE `JobNotification`(
	`jid` INT NOT NULL,
	`sid` INT NOT NULL,
	`mtime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`source` ENUM('Friend','Company') DEFAULT 'company',
	`status` ENUM('read','unread')DEFAULT 'unread',
	PRIMARY KEY (`jid`,`sid`,`mtime`,`source`),
	FOREIGN KEY (`jid`) REFERENCES `Job` (`jid`),
	FOREIGN KEY (`sid`) REFERENCES `Student` (`sid`)
);

INSERT INTO `JobNotification`(`jid`,`sid`,`source`,`status`) VALUES('00001','03033','Friend','unread');









CREATE TABLE `Application`(
	`jid` INT  NOT NULL,
	`sid` INT  NOT NULL,
	`apptime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`status` ENUM('selected','rejected','received') NOT NULL DEFAULT 'received',
	PRIMARY KEY (`jid`,`sid`),
	FOREIGN KEY (`jid`) REFERENCES `Job` (`jid`),
	FOREIGN KEY (`sid`) REFERENCES `Student` (`sid`)
);

#INSERT INTO `Student` VALUES ('10000','Geralt Of Rivia','12345678','PhD','NYU','Computer Science','3.71','SQL',NULL);

#select sid,sname from friend natural join student where fid ='11108';

#delete from Invitation where status = 'Awaiting' and itime <= NOW() - INTERVAL 1 MONTH;

#select sid,sname from Following natural join student natural join company where university = 'NYU' and cname = 'Microsoft';
#select * from Job where major='Computer Science' and academicbar like '%Grad%' and posttime >= NOW()-INTERVAL 1 WEEK ;


#INSERT INTO `JobNotification` (jid, sid)  SELECT DISTINCT '0001',sid FROM student where gpa>3.5;




