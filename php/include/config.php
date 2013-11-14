<?php
//

// Configration file for non Web Interface
//												by Fumi.Iseki
//
//

// Please set this hepler script URL and directory
if (!defined('ENV_HELPER_URL'))  define('ENV_HELPER_URL',  'http://www.opensim.tuis.ac.jp/currency/helper/');
if (!defined('ENV_HELPER_PATH')) define('ENV_HELPER_PATH', '/home/apache/htdocs/currency/helper/');
define('SYSURL', ENV_HELPER_URL);


define('CMS_DB_HOST','');
define('CMS_DB_NAME','');
define('CMS_DB_USER','');
define('CMS_DB_PASS','');
define('OPENSIM_DB_HOST','');
define('OPENSIM_DB_NAME','');
define('OPENSIM_DB_USER','');
define('OPENSIM_DB_PASS','');


define('USE_CURRENCY_SERVER', 1);
define('USER_SERVER_URI', 'http://opensim.nsl.tuis.ac.jp:8002/'); 	// not use localhost or 127.0.0.1

define('MONEY_DB_HOST','');
define('MONEY_DB_NAME','');
define('MONEY_DB_USER','');
define('MONEY_DB_PASS','');

// Money Server Access Key
// Please set same key with MoneyScriptAccessKey in MoneyServer.ini
define('CURRENCY_SCRIPT_KEY', '123456789');

// Group Module Access Keys
// Please set same keys with at [Groups] section in OpenSim.ini (case of Aurora-Sim, it is Groups.ini)
define('XMLGROUP_RKEY', '1234');	// Read Key
define('XMLGROUP_WKEY', '1234');	// Write key

define('USE_UTC_TIME',		  1);
$GLOBALS['xmlrpc_internalencoding'] = 'UTF-8';
if (USE_UTC_TIME) date_default_timezone_set('UTC');

//////////////////////////////////////////////////////////////////////////////////
//
// External NSL Modules
//





// Currency DB for helpers.php
if (USE_CURRENCY_SERVER) {
	define('CURRENCY_DB_HOST',			MONEY_DB_HOST);
	define('CURRENCY_DB_NAME',			MONEY_DB_NAME);
	define('CURRENCY_DB_USER',			MONEY_DB_USER);
	define('CURRENCY_DB_PASS',			MONEY_DB_USER);
	define('CURRENCY_MONEY_TBL',	 	'balances');
	define('CURRENCY_TRANSACTION_TBL', 	'transactions');
}
else {
	define('CURRENCY_DB_HOST',			CMS_DB_HOST);
	define('CURRENCY_DB_NAME',			CMS_DB_NAME);
	define('CURRENCY_DB_USER',			CMS_DB_USER);
	define('CURRENCY_DB_PASS',			CMS_DB_PASS);
	define('CURRENCY_MONEY_TBL',	 	'economy_transactions');
	define('CURRENCY_TRANSACTION_TBL', 	'economy_transactions');
}


// Offline Message
define('OFFLINE_DB_HOST',				CMS_DB_HOST);
define('OFFLINE_DB_NAME',				CMS_DB_NAME);
define('OFFLINE_DB_USER',				CMS_DB_USER);
define('OFFLINE_DB_PASS',				CMS_DB_PASS);
define('OFFLINE_MESSAGE_TBL', 			'offline_message');


// MuteList 
define('MUTE_DB_HOST',  				CMS_DB_HOST);
define('MUTE_DB_NAME',  				CMS_DB_NAME);
define('MUTE_DB_USER',  				CMS_DB_USER);
define('MUTE_DB_PASS',  				CMS_DB_PASS);
define('MUTE_LIST_TBL', 			'mute_list');




//////////////////////////////////////////////////////////////////////////////////
//
// External other Modules
//

// XML Group.  see also xmlgroups_config.php 
define('XMLGROUP_ACTIVE_TBL', 		'group_active');
define('XMLGROUP_LIST_TBL',		'group_list');
define('XMLGROUP_INVITE_TBL',		'group_invite');
define('XMLGROUP_MEMBERSHIP_TBL',   	'group_membership');
define('XMLGROUP_NOTICE_TBL',		'group_notice');
define('XMLGROUP_ROLE_MEMBER_TBL', 	'group_rolemembership');
define('XMLGROUP_ROLE_TBL',  		'group_role');




// Avatar Profile. see also profile_config.php
define('PROFILE_CLASSIFIEDS_TBL',  	'prof_classifieds');
define('PROFILE_USERNOTES_TBL',  	'prof_usernotes');
define('PROFILE_USERPICKS_TBL',  	'prof_userpicks');
define('PROFILE_USERPROFILE_TBL',  	'prof_userprofile');
define('PROFILE_USERSETTINGS_TBL',	'prof_usersettings');


// Search the In World. see also search_config.php 
define('SEARCH_ALLPARCELS_TBL',	 	'search_allparcels');
define('SEARCH_EVENTS_TBL',		'search_events');
define('SEARCH_HOSTSREGISTER_TBL',  	'search_hostsregister');
define('SEARCH_OBJECTS_TBL',		'search_objects');
define('SEARCH_PARCELS_TBL',		'search_parcels');
define('SEARCH_PARCELSALES_TBL',	'search_parcelsales');
define('SEARCH_POPULARPLACES_TBL',  	'search_popularplaces');
define('SEARCH_REGIONS_TBL',		'search_regions');
define('SEARCH_CLASSIFIEDS_TBL',	PROFILE_CLASSIFIEDS_TBL);




//////////////////////////////////////////////////////////////////////////////////
//
// for Avatar State for CMS/LMS
//
define('AVATAR_STATE_NOSTATE',		'0');		// 0x00
define('AVATAR_STATE_SYNCDB',  		'1');		// 0x01

define('AVATAR_STATE_INACTIVE',		'4');		// 0x04

define('AVATAR_STATE_NOSYNCDB',		'254');		// 0xfe
define('AVATAR_STATE_ACTIVE',  		'251');		// 0xfb


// Editable
define('AVATAR_NOT_EDITABLE',		'0');
define('AVATAR_EDITABLE',	 		'1');
define('AVATAR_OWNER_EDITABLE',		'2');

// Lastname
define('AVATAR_LASTN_INACTIVE', 	'0');
define('AVATAR_LASTN_ACTIVE',   	'1');






//
if (!defined('ENV_READED_CONFIG')) define('ENV_READED_CONFIG', 'YES');
?>
