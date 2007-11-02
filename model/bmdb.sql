CREATE TABLE `biomarker` (
	`ID`  int(10) unsigned   NOT NULL     auto_increment   COMMENT 'Auto Generated unique MySQL ID' ,
	`EKE_ID`  varchar(80)   NOT NULL      COMMENT '...' ,
	`BiomarkerID`  varchar(80)   NOT NULL      COMMENT '...' ,
	`PanelID`  varchar(80)   NOT NULL      COMMENT '...' ,
	`Title`  varchar(80)   NOT NULL      COMMENT '...' ,
	`Description`  text   NOT NULL      COMMENT '...' ,
	`QAState`  varchar(50)   NOT NULL      COMMENT 'CHANGEME: to type enum' ,
	`Phase`  varchar(50)   NOT NULL      COMMENT 'CHANGEME: to type enum' ,
	`Security`  varchar(50)   NOT NULL      COMMENT 'CHANGEME: to type enum' ,
	`Type`  varchar(60)   NOT NULL      COMMENT '...' ,
	PRIMARY KEY (`ID` ),
	UNIQUE KEY `Title` (`Title`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1; 

CREATE TABLE `biomarker_alias` (
	`BiomarkerID`  int(10) unsigned   NOT NULL     ,
	`Alias`  varchar(80)   NOT NULL     ,
	PRIMARY KEY (`BiomarkerID`,`Alias` )) ENGINE=MyISAM DEFAULT CHARSET=latin1; 

CREATE TABLE `study` (
	`ID`  int(10) unsigned   NOT NULL     auto_increment  ,
	`EDRNID`  int(10) unsigned   NOT NULL     ,
	`FHCRC_ID`  int(10) unsigned   NOT NULL      COMMENT 'from the fhcrc website url' ,
	`DMCC_ID`  varchar(80)   NOT NULL      COMMENT 'from fhcrc website' ,
	`Title`  varchar(80)   NOT NULL     ,
	`Abstract`  text   NOT NULL     ,
	`BiomarkerPopulationCharacteristics`  text   NOT NULL     ,
	`Design`  varchar(50)   NOT NULL      COMMENT 'fhcrc StudyDesign field' ,
	`BiomarkerStudyType` ENUM('REGISTERED','UNREGISTERED')   NOT NULL  DEFAULT 'UNREGISTERED'     COMMENT 'whether or not the study is an EDRN study' ,
	PRIMARY KEY (`ID` ),
	UNIQUE KEY `Title` (`Title`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1; 

CREATE TABLE `biomarker_study` (
	`BiomarkerID`  int(10) unsigned   NOT NULL     ,
	`StudyID`  int(10) unsigned   NOT NULL     ,
	`Sensitivity` float   NOT NULL     ,
	`Specificity` float   NOT NULL     ,
	`PPV` float   NOT NULL     ,
	`NPV` float   NOT NULL     ,
	`Assay`  int(10) unsigned   NOT NULL     ,
	`Technology`  int(10) unsigned   NOT NULL     ,
	PRIMARY KEY (`BiomarkerID`,`StudyID` )) ENGINE=MyISAM DEFAULT CHARSET=latin1; 

CREATE TABLE `organ` (
	`ID`  int(10) unsigned   NOT NULL     auto_increment   COMMENT 'A unique ID for this organ' ,
	`Name`  varchar(40)   NOT NULL      COMMENT 'The text of this alias' ,
	PRIMARY KEY (`ID` ),
	UNIQUE KEY `Name` (`Name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1; 

CREATE TABLE `biomarker_organ` (
	`BiomarkerID`  int(10) unsigned   NOT NULL     ,
	`OrganSite`  int(10) unsigned   NOT NULL     ,
	`SensitivityMin` float   NOT NULL     ,
	`SensitivityMax` float   NOT NULL     ,
	`SensitivityComment`  text   NOT NULL     ,
	`SpecificityMin` float   NOT NULL     ,
	`SpecificityMax` float   NOT NULL     ,
	`SpecificityComment`  text   NOT NULL     ,
	`PPVMin` float   NOT NULL     ,
	`PPVMax` float   NOT NULL     ,
	`PPVComment`  text   NOT NULL     ,
	`NPVMin` float   NOT NULL     ,
	`NPVMax` float   NOT NULL     ,
	`NPVComment`  text   NOT NULL     ,
	PRIMARY KEY (`BiomarkerID`,`OrganSite` )) ENGINE=MyISAM DEFAULT CHARSET=latin1; 

CREATE TABLE `publication` (
	`ID`  int(10) unsigned   NOT NULL     auto_increment  ,
	`PubMedID`  varchar(40)   NOT NULL     ,
	`Title`  varchar(120)   NOT NULL     ,
	`Author`  varchar(120)   NOT NULL     ,
	`Journal`  varchar(120)   NOT NULL     ,
	`Volume`  varchar(40)   NOT NULL     ,
	`Issue`  varchar(40)   NOT NULL     ,
	`Year`  int(10) unsigned   NOT NULL     ,
	PRIMARY KEY (`ID` ),
	UNIQUE KEY `PubMedID` (`PubMedID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1; 

CREATE TABLE `biomarker_publication` (
	`PublicationID`  int(10) unsigned   NOT NULL     ,
	`BiomarkerID`  int(10) unsigned   NOT NULL     ,
	PRIMARY KEY (`PublicationID`,`BiomarkerID` )) ENGINE=MyISAM DEFAULT CHARSET=latin1; 

CREATE TABLE `biomarker_organ_publication` (
	`PublicationID`  int(10) unsigned   NOT NULL     ,
	`BiomarkerID`  int(10) unsigned   NOT NULL     ,
	`OrganSite`  int(10) unsigned   NOT NULL     ,
	PRIMARY KEY (`PublicationID`,`BiomarkerID`,`OrganSite` )) ENGINE=MyISAM DEFAULT CHARSET=latin1; 

CREATE TABLE `study_publication` (
	`PublicationID`  int(10) unsigned   NULL     ,
	`StudyID`  int(10) unsigned   NULL     ,
	PRIMARY KEY (`PublicationID`,`StudyID` )) ENGINE=MyISAM DEFAULT CHARSET=latin1; 

CREATE TABLE `resource` (
	`ID`  int(10) unsigned   NOT NULL     auto_increment   COMMENT '...' ,
	`Name`  varchar(60)   NOT NULL      COMMENT '...' ,
	`URL`  varchar(128)   NOT NULL     ,
	PRIMARY KEY (`ID` ),
	UNIQUE KEY `Name` (`Name`),
	UNIQUE KEY `URL` (`URL`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1; 

CREATE TABLE `biomarker_resource` (
	`BiomarkerID`  int(10) unsigned   NOT NULL      COMMENT '...' ,
	`ResourceID`  int(10) unsigned   NOT NULL      COMMENT '...' ,
	PRIMARY KEY (`BiomarkerID`,`ResourceID` )) ENGINE=MyISAM DEFAULT CHARSET=latin1; 

CREATE TABLE `biomarker_organ_resource` (
	`BiomarkerID`  int(10) unsigned   NOT NULL     ,
	`OrganSite`  int(10) unsigned   NOT NULL     ,
	`ResourceID`  int(10) unsigned   NOT NULL     ,
	PRIMARY KEY (`BiomarkerID`,`OrganSite`,`ResourceID` )) ENGINE=MyISAM DEFAULT CHARSET=latin1; 

CREATE TABLE `study_resource` (
	`StudyID`  int(10) unsigned   NOT NULL     ,
	`ResourceID`  int(10) unsigned   NOT NULL     ,
	PRIMARY KEY (`StudyID`,`ResourceID` )) ENGINE=MyISAM DEFAULT CHARSET=latin1; 

CREATE TABLE `person` (
	`ID`  int(10) unsigned   NOT NULL     auto_increment   COMMENT '...' ,
	`FirstName`  varchar(20)   NOT NULL     ,
	`LastName`  varchar(20)   NOT NULL     ,
	PRIMARY KEY (`ID` )) ENGINE=MyISAM DEFAULT CHARSET=latin1; 

CREATE TABLE `site` (
	`ID`  int(10) unsigned   NOT NULL     auto_increment   COMMENT '...' ,
	`Name`  varchar(120)   NOT NULL     ,
	PRIMARY KEY (`ID` )) ENGINE=MyISAM DEFAULT CHARSET=latin1; 

CREATE TABLE `person_site` (
	`PersonID`  int(10) unsigned   NOT NULL      COMMENT '...' ,
	`SiteID`  int(10) unsigned   NOT NULL     ,
	`Title`  varchar(80)   NOT NULL     ,
	`Specialty`  varchar(80)   NOT NULL     ,
	`Phone`  varchar(20)   NOT NULL     ,
	`Fax`  varchar(20)   NOT NULL     ,
	`Email`  varchar(80)   NOT NULL     ,
	PRIMARY KEY (`PersonID`,`SiteID` )) ENGINE=MyISAM DEFAULT CHARSET=latin1; 

