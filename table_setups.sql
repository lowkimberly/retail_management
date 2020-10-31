Kimberly Low

create schema `rbms`;

use `rbms`;

create TABLE `Employees` (
  `eid` INT NOT NULL AUTO_INCREMENT,
  `ename` VARCHAR(64) NOT NULL,
  `telephone#` INT NOT NULL,
  PRIMARY KEY (`eid`));

create table Products (
`pid` int not null auto_increment,
`pname` varchar(64) not null,
`qoh` int not null,
`qoh_threshold` int not null,
`original_price` decimal(5,2) not null,
`discnt_rate` int not null,
primary key (`pid`));

create table Suppliers (
`sid` int not null auto_increment,
`sname` varchar(64) not null,
`city` varchar(64) not null,
`telephone#` int not null unique,
`email_address` int unique,
primary key (`sid`));

create table Supplies (
`sup#` int not null auto_increment,
`pid` int not null,
`date` datetime not null,
`quantity` int not null,
primary key(`sup#`),
unique key(`pid`, `date`),
foreign key (`pid`) references Products(`pid`));

create table Pro_sup (
`pid` int not null,
`sid` int not null,
primary key (`pid`),
foreign key (`pid`) references Supplies(`pid`),
foreign key (`sid`) references Suppliers(`sid`));

create table Customers (
`cid` int not null auto_increment,
`cname` varchar(64) not null,
`telephone#` int not null,
`visits_made` int not null,
`last_visit_date` datetime not null,
primary key(`cid`));

create table Purchases (
`pur#` int not null auto_increment,
`cid` int not null,
`eid` int not null,
`pid` int not null,
`qty` int not null,
`pdate` datetime not null,
`total_price` decimal(5,2) not null,
primary key(`pur#`),
unique key(`cid`, `eid`, `pid`, `pdate`),
foreign key (`cid`) references Customers(`cid`),
foreign key (`eid`) references Employees(`eid`),
foreign key (`pid`) references Supplies(`pid`));
