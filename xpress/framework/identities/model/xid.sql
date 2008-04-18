CREATE TABLE `Xiuser` (
	`objId`  int(10) unsigned   NOT NULL     auto_increment   COMMENT ' XPress unique identifier for this object ' ,
	`ObjectClass`  varchar(30)   NOT NULL     ,
	`ObjectId`  int(10) unsigned   NOT NULL     ,
	`UserName`  varchar(18)   NOT NULL     ,
	`Password`  varchar(255)   NOT NULL     ,
	`EmailAddress`  varchar(80)   NOT NULL     ,
	`Created` datetime   NOT NULL     ,
	`Status` ENUM('Pending Confirmation','Active','Disabled')   NOT NULL     ,
	`LastLogin` datetime   NOT NULL     ,
	PRIMARY KEY (`objId`),
	UNIQUE KEY `UserName` (`UserName`),
	UNIQUE KEY `EmailAddress` (`EmailAddress`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1; 

CREATE TABLE `Xigroup` (
	`objId`  int(10) unsigned   NOT NULL     auto_increment   COMMENT ' XPress unique identifier for this object ' ,
	`Name`  varchar(30)   NOT NULL     ,
	`ObjectClass`  varchar(30)   NOT NULL     ,
	PRIMARY KEY (`objId`),
	UNIQUE KEY `Name` (`Name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1; 

CREATE TABLE `Xirole` (
	`objId`  int(10) unsigned   NOT NULL     auto_increment   COMMENT ' XPress unique identifier for this object ' ,
	`Name`  varchar(30)   NOT NULL     ,
	PRIMARY KEY (`objId`),
	UNIQUE KEY `Name` (`Name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1; 

CREATE TABLE xr_Xiuser_Xigroup (
	`XiuserID` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'the unique ID of the Xiuser',
	`XigroupID` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'the unique ID of the Xigroup',
	`XiuserVar` enum('null','Groups') DEFAULT 'null' COMMENT 'the Xiuser variable for this relationship',
	`XigroupVar` enum('null','Users') DEFAULT 'null' COMMENT 'the Xigroup variable for this relationship',
	PRIMARY KEY (`XiuserID`,`XigroupID`,`XiuserVar`,`XigroupVar`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ;

CREATE TABLE xr_Xiuser_Xirole (
	`XiuserID` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'the unique ID of the Xiuser',
	`XiroleID` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'the unique ID of the Xirole',
	`XiuserVar` enum('null','Roles') DEFAULT 'null' COMMENT 'the Xiuser variable for this relationship',
	`XiroleVar` enum('null','Users') DEFAULT 'null' COMMENT 'the Xirole variable for this relationship',
	PRIMARY KEY (`XiuserID`,`XiroleID`,`XiuserVar`,`XiroleVar`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ;

