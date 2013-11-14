CREATE TABLE IF NOT EXISTS `economy_money` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `CentsPerMoneyUnit` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

INSERT INTO `economy_money` (`id`, `CentsPerMoneyUnit`) VALUES
(1, 0.415);

CREATE TABLE IF NOT EXISTS `economy_transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sourceId` varchar(36) NOT NULL,
  `destId` varchar(36) NOT NULL,
  `amount` int(11) NOT NULL DEFAULT '0',
  `flags` int(11) NOT NULL DEFAULT '0',
  `aggregatePermInventory` int(11) NOT NULL DEFAULT '0',
  `aggregatePermNextOwner` int(11) NOT NULL DEFAULT '0',
  `description` varchar(256) DEFAULT NULL,
  `transactionType` int(11) NOT NULL DEFAULT '0',
  `timeOccurred` int(11) NOT NULL,
  `RegionGenerated` varchar(36) NOT NULL,
  `IPGenerated` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;


CREATE TABLE `offline_message` (
    `to_uuid`       varchar(36) NOT NULL,
    `from_uuid`     varchar(36) NOT NULL,
    `message`       text NOT NULL,
    KEY `to_uuid` (`to_uuid`)
) TYPE=MyISAM;


CREATE TABLE `mute_list` (
    `agentID`       varchar(36)  NOT NULL,
    `muteID`        varchar(36)  NOT NULL,
    `muteName`      varchar(255) NOT NULL,
    `muteType`      int(10) unsigned NOT NULL default '0',
    `muteFlags`     int(10) unsigned NOT NULL default '0',
    `timestamp`     int(11) unsigned NOT NULL default '0',
    PRIMARY KEY  (`AgentID`,`MuteID`,`MuteName`)
) TYPE=MyISAM;


CREATE TABLE `search_allparcels` (
  `regionUUID` varchar(255) NOT NULL,
  `parcelname` varchar(255) NOT NULL,
  `ownerUUID` char(36) NOT NULL default '00000000-0000-0000-0000-000000000000',
  `groupUUID` char(36) NOT NULL default '00000000-0000-0000-0000-000000000000',
  `landingpoint` varchar(255) NOT NULL,
  `parcelUUID` char(36) NOT NULL default '00000000-0000-0000-0000-000000000000',
  `infoUUID` char(36) NOT NULL default '00000000-0000-0000-0000-000000000000',
  `parcelarea` int(11) NOT NULL,
  PRIMARY KEY  (`parcelUUID`),
  KEY `regionUUID` (`regionUUID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
 

CREATE TABLE `search_classifieds` (
  `classifieduuid` char(36) NOT NULL,
  `creatoruuid` char(36) NOT NULL,
  `creationdate` int(20) NOT NULL,
  `expirationdate` int(20) NOT NULL,
  `category` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `parceluuid` char(36) NOT NULL,
  `parentestate` int(11) NOT NULL,
  `snapshotuuid` char(36) NOT NULL,
  `simname` varchar(255) NOT NULL,
  `posglobal` varchar(255) NOT NULL,
  `parcelname` varchar(255) NOT NULL,
  `classifiedflags` int(8) NOT NULL,
  `priceforlisting` int(5) NOT NULL,
  PRIMARY KEY  (`classifieduuid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

 

CREATE TABLE `search_events` (
  `owneruuid` char(40) NOT NULL,
  `name` varchar(255) NOT NULL,
  `eventid` int(11) NOT NULL auto_increment,
  `creatoruuid` char(40) NOT NULL,
  `category` int(2) NOT NULL,
  `description` text NOT NULL,
  `dateUTC` int(10) NOT NULL,
  `duration` int(3) NOT NULL,
  `covercharge` tinyint(1) NOT NULL,
  `coveramount` int(10) NOT NULL,
  `simname` varchar(255) NOT NULL,
  `globalPos` varchar(255) NOT NULL,
  `eventflags` int(1) NOT NULL,
  PRIMARY KEY  (`eventid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

 

CREATE TABLE `search_hostsregister` (
  `host` varchar(255) NOT NULL,
  `port` int(5) NOT NULL,
  `register` int(10) NOT NULL,
  `nextcheck` int(10) NOT NULL,
  `checked` tinyint(1) NOT NULL,
  `failcounter` int(10) NOT NULL,
  PRIMARY KEY (`host`,`port`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



CREATE TABLE `search_objects` (
  `objectuuid` varchar(255) NOT NULL,
  `parceluuid` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `regionuuid` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`objectuuid`,`parceluuid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

 

CREATE TABLE `search_parcels` (
  `regionUUID` varchar(255) NOT NULL,
  `parcelname` varchar(255) NOT NULL,
  `parcelUUID` varchar(255) NOT NULL,
  `landingpoint` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `searchcategory` varchar(50) NOT NULL,
  `build` enum('true','false') NOT NULL,
  `script` enum('true','false') NOT NULL,
  `public` enum('true','false') NOT NULL,
  `dwell` float NOT NULL default '0',
  `infouuid` varchar(255) NOT NULL default '',
  `mature` varchar(10) NOT NULL default 'PG',
  PRIMARY KEY  (`regionUUID`,`parcelUUID`),
  KEY `name` (`parcelname`),
  KEY `description` (`description`),
  KEY `searchcategory` (`searchcategory`),
  KEY `dwell` (`dwell`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

 

CREATE TABLE `search_parcelsales` (
  `regionUUID` varchar(255) NOT NULL,
  `parcelname` varchar(255) NOT NULL,
  `parcelUUID` varchar(255) NOT NULL,
  `area` int(6) NOT NULL,
  `saleprice` int(11) NOT NULL,
  `landingpoint` varchar(255) NOT NULL,
  `infoUUID` char(36) NOT NULL default '00000000-0000-0000-0000-000000000000',
  `dwell` int(11) NOT NULL,
  `parentestate` int(11) NOT NULL default '1',
  `mature` varchar(10) NOT NULL default 'PG',
  PRIMARY KEY  (`regionUUID`,`parcelUUID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

 

CREATE TABLE `search_popularplaces` (
  `parcelUUID` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `dwell` float NOT NULL,
  `infoUUID` char(36) NOT NULL,
  `has_picture` tinyint(1) NOT NULL,
  `mature` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



CREATE TABLE `search_regions` (
  `regionname` varchar(255) NOT NULL,
  `regionuuid` varchar(255) NOT NULL,
  `regionhandle` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `owneruuid` varchar(255) NOT NULL,
  PRIMARY KEY  (`regionuuid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE  IF NOT EXISTS `prof_classifieds` (
  `classifieduuid` 	char(36) NOT NULL,
  `creatoruuid` 	char(36) NOT NULL,
  `creationdate` 	int(20) NOT NULL,
  `expirationdate` 	int(20) NOT NULL,
  `category` 		varchar(20) NOT NULL,
  `name` 			varchar(255) NOT NULL,
  `description` 	text NOT NULL,
  `parceluuid` 		char(36) NOT NULL,
  `parentestate` 	int(11) NOT NULL,
  `snapshotuuid` 	char(36) NOT NULL,
  `simname` 		varchar(255) NOT NULL,
  `posglobal` 		varchar(255) NOT NULL,
  `parcelname` 		varchar(255) NOT NULL,
  `classifiedflags` int(8) NOT NULL,
  `priceforlisting` int(5) NOT NULL,
  PRIMARY KEY (`classifieduuid`)
) ENGINE=InnoDB;


CREATE TABLE `prof_usernotes` (
  `id`              int(11) NOT NULL auto_increment,
  `useruuid`        varchar(36) NOT NULL,
  `targetuuid`      varchar(36) NOT NULL,
  `notes`           text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY  `useruuid` (`useruuid`, `targetuuid`)
) TYPE=MyISAM;



CREATE TABLE `prof_userpicks` (
  `pickuuid` 		varchar(36) NOT NULL,
  `creatoruuid` 	varchar(36) NOT NULL,
  `toppick` 		enum('true','false') NOT NULL,
  `parceluuid` 		varchar(36) NOT NULL,
  `name` 			varchar(255) NOT NULL,
  `description` 	text NOT NULL,
  `snapshotuuid` 	varchar(36) NOT NULL,
  `user` 			varchar(255) NOT NULL,
  `originalname` 	varchar(255) NOT NULL,
  `simname` 		varchar(255) NOT NULL,
  `posglobal` 		varchar(255) NOT NULL,
  `sortorder` 		int(2) NOT NULL,
  `enabled` 		enum('true','false') NOT NULL,
  PRIMARY KEY (`pickuuid`)
) TYPE=MyISAM;


CREATE TABLE `prof_userprofile` (
  `useruuid` 			 varchar(36) NOT NULL,
  `profilePartner` 		 varchar(36) NOT NULL,
  `profileImage` 		 varchar(36) NOT NULL,
  `profileAboutText` 	 text NOT NULL,
  `profileAllowPublish`  binary(1) NOT NULL,
  `profileMaturePublish` binary(1) NOT NULL,
  `profileURL` 			 varchar(255) NOT NULL,
  `profileWantToMask` 	 int(3) NOT NULL,
  `profileWantToText` 	 text NOT NULL,
  `profileSkillsMask` 	 int(3) NOT NULL,
  `profileSkillsText` 	 text NOT NULL,
  `profileLanguagesText` text NOT NULL,
  `profileFirstImage` 	 varchar(36) NOT NULL,
  `profileFirstText` 	 text NOT NULL,
  PRIMARY KEY (`useruuid`)
) TYPE=MyISAM;


CREATE TABLE `prof_usersettings` (
  `useruuid` 		varchar(36) NOT NULL,
  `imviaemail` 		enum('true','false') NOT NULL,
  `visible` 		enum('true','false') NOT NULL,
  `email` 			varchar(254) NOT NULL,
  PRIMARY KEY (`useruuid`)
) TYPE=MyISAM;

CREATE TABLE `group_active` (
  `AgentID` varchar(128) NOT NULL default '',
  `ActiveGroupID` varchar(128) NOT NULL default '',
  PRIMARY KEY  (`AgentID`)
) TYPE=MyISAM;

 

CREATE TABLE `group_list` (
  `GroupID` varchar(128) NOT NULL default '',
  `Name` varchar(255) NOT NULL default '',
  `Charter` text NOT NULL,
  `InsigniaID` varchar(128) NOT NULL default '',
  `FounderID` varchar(128) NOT NULL default '',
  `MembershipFee` int(11) NOT NULL default '0',
  `OpenEnrollment` varchar(255) NOT NULL default '',
  `ShowInList` tinyint(1) NOT NULL default '0',
  `AllowPublish` tinyint(1) NOT NULL default '0',
  `MaturePublish` tinyint(1) NOT NULL default '0',
  `OwnerRoleID` varchar(128) NOT NULL default '',
  PRIMARY KEY  (`GroupID`),
  UNIQUE KEY `Name` (`Name`),
  FULLTEXT KEY `Name_2` (`Name`)
) TYPE=MyISAM;

 

CREATE TABLE `group_invite` (
  `InviteID` varchar(128) NOT NULL default '',
  `GroupID` varchar(128) NOT NULL default '',
  `RoleID` varchar(128) NOT NULL default '',
  `AgentID` varchar(128) NOT NULL default '',
  `TMStamp` timestamp NOT NULL,
  PRIMARY KEY  (`InviteID`),
  UNIQUE KEY `GroupID` (`GroupID`,`RoleID`,`AgentID`)
) TYPE=MyISAM;

 

CREATE TABLE `group_membership` (
  `GroupID` varchar(128) NOT NULL default '',
  `AgentID` varchar(128) NOT NULL default '',
  `SelectedRoleID` varchar(128) NOT NULL default '',
  `Contribution` int(11) NOT NULL default '0',
  `ListInProfile` int(11) NOT NULL default '1',
  `AcceptNotices` int(11) NOT NULL default '1',
  PRIMARY KEY  (`GroupID`,`AgentID`)
) TYPE=MyISAM;

 

CREATE TABLE `group_notice` (
  `GroupID` varchar(128) NOT NULL default '',
  `NoticeID` varchar(128) NOT NULL default '',
  `Timestamp` int(10) unsigned NOT NULL default '0',
  `FromName` varchar(255) NOT NULL default '',
  `Subject` varchar(255) NOT NULL default '',
  `Message` text NOT NULL,
  `BinaryBucket` text NOT NULL,
  PRIMARY KEY  (`GroupID`,`NoticeID`),
  KEY `Timestamp` (`Timestamp`)
) TYPE=MyISAM;



CREATE TABLE `group_rolemembership` (
  `GroupID` varchar(128) NOT NULL default '',
  `RoleID` varchar(128) NOT NULL default '',
  `AgentID` varchar(128) NOT NULL default '',
  PRIMARY KEY  (`GroupID`,`RoleID`,`AgentID`)
) TYPE=MyISAM;

 

CREATE TABLE `group_role` (
  `GroupID` varchar(128) NOT NULL default '',
  `RoleID` varchar(128) NOT NULL default '',
  `Name` varchar(255) NOT NULL default '',
  `Description` varchar(255) NOT NULL default '',
  `Title` varchar(255) NOT NULL default '',
  `Powers` bigint(20) unsigned NOT NULL default '0',
  PRIMARY KEY  (`GroupID`,`RoleID`)
) TYPE=MyISAM;