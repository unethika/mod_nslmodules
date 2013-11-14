<?php

define('OPENSIM_V06',   'opnesim_0.6');
define('OPENSIM_V07',   'opnesim_0.7');
define('SIMIANGRID',    'simiangrid');
define('AURORASIM',     'aurora-sim');

$OpenSimVersion = null;

function  opensim_new_db($timeout=60)
{
        $db = new DB(OPENSIM_DB_HOST, OPENSIM_DB_NAME, OPENSIM_DB_USER, OPENSIM_DB_PASS, $timeout);

        return $db;
}




function  opensim_get_db_version(&$db=null)
{
        global $OpenSimVersion;

        if (!is_object($db)) $db = opensim_new_db();

        $ver = null;
        if ($db->exist_table('GridUser')) {
                $ver = OPENSIM_V07;
        }
        else if ($db->exist_table('Users')) {
                $ver = SIMIANGRID;
        }
        else if ($db->exist_table('users')) {
                $ver = OPENSIM_V06;
        }
        else if ($db->exist_field('userinfo', 'UserID')) {
                $ver = AURORASIM;
        }

        $OpenSimVersion = $ver;
        return $ver;
}


function  opensim_check_secure_session($uuid, $regionid, $secure, &$db=null)
{
        global $OpenSimVersion;

        if (!isGUID($uuid) or !isGUID($secure)) return false;

        if (!is_object($db)) $db = opensim_new_db();
        if ($OpenSimVersion==null) opensim_get_db_version($db);

        //
        if ($OpenSimVersion==OPENSIM_V07) {
                $sql = "SELECT UserID FROM Presence WHERE UserID='".$uuid."' AND SecureSessionID='".$secure."'";
                if (isGUID($regionid)) $sql = $sql." AND RegionID='".$regionid."'";
        }

        else if ($OpenSimVersion==OPENSIM_V06) {
                $sql = "SELECT UUID FROM agents WHERE UUID='".$uuid."' AND secureSessionID='".$secure."' AND agentOnline='1'";
                if (isGUID($regionid)) $sql = $sql." AND currentRegion='".$regionid."'";
        }
		
		else if ($OpenSimVersion==SIMIANGRID) {
                $sql = "SELECT UserID FROM Sessions WHERE UserID='".$uuid."' AND secureSessionID='".$secure."'";
                if (isGUID($regionid)) $sql = $sql." AND SceneID='".$regionid."'";
        }

        else if ($OpenSimVersion==AURORASIM) {
                $sql = "SELECT UUID FROM tokens,userinfo WHERE UUID='".$uuid."' AND UUID=UserID AND token='".$secure."' AND IsOnline='1'";
                if (isGUID($regionid)) $sql = $sql." AND CurrentRegionID='".$regionid."'";
        }

        else return false;


        $db->query($sql);
        if ($db->Errno!=0) return false;

        list($UUID) = $db->next_record();
        if ($UUID!=$uuid) return false;
        return true;
}

function  opensim_check_region_secret($uuid, $secret, &$db=null)
{
        global $OpenSimVersion;

        if (!isGUID($uuid)) return false;

        if (!is_object($db)) $db = opensim_new_db();
        if ($OpenSimVersion==null) opensim_get_db_version($db);

        //
        if ($OpenSimVersion==OPENSIM_V07 or $OpenSimVersion==OPENSIM_V06) {
                $sql = "SELECT UUID FROM regions WHERE UUID='".$uuid."' AND regionSecret='".$db->escape($secret)."'";

                $db->query($sql);
                if ($db->Errno==0) {
                        list($UUID) = $db->next_record();
                        if ($UUID==$uuid) return true;
                }
        }
		else if ($OpenSimVersion==SIMIANGRID) {
				
				$sql = "SELECT ExtraData FROM Scenes WHERE ID='".$uuid."'";
				$db->query($sql);
                if ($db->Errno==0) {
                        list($ExtraData) = $db->next_record();
                }
				$needle = '".$db->escape($secret)."';
				$ExtraData = strpos($ExtraData,$needle);
				
				$sql = "SELECT ID FROM Scenes WHERE ID='".$uuid."' AND regionSecret=$ExtraData";

                $db->query($sql);
                if ($db->Errno==0) {
                        list($ID) = $db->next_record();
                        if ($ID==$uuid) return true;
                }
        }
        else if($OpenSimVersion==AURORASIM) {
                $sql = "SELECT RegionInfo FROM userinfo,simulator ";
                $sql.= "WHERE UserID='".$userid."' AND CurrentRegionID=simulator.RegionID";

                $db->query($sql);
                if ($db->Errno==0) {
                        list($regioninfo) = $db->next_record();
                        $info = split_key_value($regioninfo);           // from tools.func.php
                        if ($secret==$info["password"]) return true;
                }
        }

        return false;
}


function  opensim_get_server_info($userid, &$db=null)
{
        global $OpenSimVersion;

        if (!isGUID($userid)) return $ret;

        if (!is_object($db)) $db = opensim_new_db();
        if ($OpenSimVersion==null) opensim_get_db_version($db);

        $ret = array();

        //
        if ($OpenSimVersion==OPENSIM_V07) {
                $sql = "SELECT serverIP,serverHttpPort,serverURI,regionSecret FROM GridUser ";
                $sql.= "INNER JOIN regions ON regions.uuid=GridUser.LastRegionID WHERE GridUser.UserID='".$userid."'";
                $db->query($sql);
                if ($db->Errno==0) list($serverip, $httpport, $serveruri, $secret) = $db->next_record();
        }

        else if ($OpenSimVersion==OPENSIM_V06) {
                $sql = "SELECT serverIP,serverHttpPort,serverURI,regionSecret FROM agents ";
                $sql.= "INNER JOIN regions ON regions.uuid=agents.currentRegion WHERE agents.UUID='".$userid."'";
                $db->query($sql);
                if ($db->Errno==0) list($serverip, $httpport, $serveruri, $secret) = $db->next_record();
        }
		else if ($OpenSimVersion==SIMIANGRID) {

                $db->query("SELECT SceneID FROM Sessions WHERE UserID = '".$userid."'");
                list($sceneid) = $db->next_record();

                $db->query("SELECT Address,ExtraData FROM Scenes WHERE ID = '".$sceneid."'");
                list($serveruri,$extradata) = $db->next_record();
                list($httpip,$portslash) = explode(":", $serveruri);
                list($serverip) = explode("http://",$httpip);
                list($httpport) = explode("/",$portslash);
				list($exdataa,$exdatab) = explode('RegionSecret":"',$extradata);
				list($secret) = substr($extradatab,0,36);
				
				
				if ($db->Errno==0) list($serverip, $httpport, $serveruri, $secret) = $db->next_record();
        }

        else if ($OpenSimVersion==AURORASIM) {

                $sql = "SELECT gridregions.Info FROM userinfo,gridregions ";
                $sql.= "WHERE UserID='".$userid."' AND userinfo.CurrentRegionID=gridregions.RegionUUID";

                //$sql = "SELECT RegionInfo FROM userinfo,simulator ";
                //$sql.= "WHERE UserID='".$userid."' AND CurrentRegionID=simulator.RegionID";

                $db->query($sql);
                if ($db->Errno==0) {
                        list($regioninfo) = $db->next_record();
                        $info = split_key_value($regioninfo);           // from tools.func.php
                        $serverip  = gethostbyname($info["serverIP"]);
                        $httpport  = $info["serverHttpPort"];
						$serveruri = $info["serverURI"];
                        $secret = null;
                }
        }

        else return $ret;


        if ($db->Errno==0) {
                $ret["serverIP"]           = $serverip;
                $ret["serverHttpPort"] = $httpport;
                $ret["serverURI"]          = $serveruri;
                $ret["regionSecret"]   = $secret;
        }
        return $ret;
}

function  opensim_get_estate_owner($region, &$db=null)
{
        global $OpenSimVersion;

        if (!isGUID($region)) return null;

        $firstname = null;
        $lastname  = null;
        $fullname  = null;
        $owneruuid = null;

        if (!is_object($db)) $db = opensim_new_db();
        if ($OpenSimVersion==null) opensim_get_db_version($db);

        if ($db->exist_table('UserAccounts')) {
                $rqdt = 'PrincipalID,FirstName,LastName';
                $tbls = 'UserAccounts,estate_map,estate_settings';
                $cndn = "RegionID='$region' AND estate_map.EstateID=estate_settings.EstateID AND EstateOwner=PrincipalID";
				
				$db->query('SELECT '.$rqdt.' FROM '.$tbls.' WHERE '.$cndn);
			list($owneruuid, $firstname, $lastname) = $db->next_record();

			$fullname = $firstname.' '.$lastname;
        }
		else if ($db->exist_table('Users')) {
			
			$db->query("SELECT ExtraData FROM Scenes WHERE ID = '".$region."'");
                list($extradata) = $db->next_record();
				list($estateown) = substr($extradata,-38,36);
				
			$db->query("SELECT Name FROM Users WHERE ID = '".$estateown."'");
				list($fullname) = $db->next_record();
				list($firstname,$lastname) = explode(' ',$fullname);
		}
        else if ($db->exist_table('users')) {
                $rqdt = 'UUID,username,lastname';
                $tbls = 'users,estate_map,estate_settings';
                $cndn = "RegionID='$region' AND estate_map.EstateID=estate_settings.EstateID AND EstateOwner=UUID";
				
				$db->query('SELECT '.$rqdt.' FROM '.$tbls.' WHERE '.$cndn);
				list($owneruuid, $firstname, $lastname) = $db->next_record();

				$fullname = $firstname.' '.$lastname;	
        }
        else {
                return null;
        }

        
        if ($fullname==' ') $fullname = null;

        $name['firstname']  = $firstname;
        $name['lastname']   = $lastname;
        $name['fullname']   = $fullname;
        $name['owner_uuid'] = $owneruuid;

        return $name;
}

function  opensim_get_region_info($region, &$db=null)
{
        global $OpenSimVersion;

        if (!isGUID($region)) return null;
        if ($region=='00000000-0000-0000-0000-000000000000') return null;

        if (!is_object($db)) $db = opensim_new_db();
        if ($OpenSimVersion==null) opensim_get_db_version($db);
		
		if ($OpenSimVersion == SIMIANGRID){
		  $sql = "SELECT Name,MinX,MinY FROM Scenes WHERE ID='".$region."'";
        $db->query($sql);
		list($regionName,$locX,$locY) = $db->next_record();
		list($regionHandle) = ($locX*65536)+$locY;
		list($locX) = $locX/256;
		list($locY) = $locY/256;
		
		$db->query("SELECT Address,ExtraData FROM Scenes WHERE ID = '".$region."'");
                list($serverURI,$extradata) = $db->next_record();
                list($httpip,$portslash) = explode(":", $serveruri);
                list($serverIP) = explode("http://",$httpip);
                list($serverHttpPort) = explode("/",$portslash);
				list($exdataa,$exdatab) = explode('RegionSecret":"',$extradata);
				list($regionSecret) = substr($extradatab,0,36);
		}
		else {
        $sql = "SELECT regionHandle,regionName,regionSecret,serverIP,serverHttpPort,serverURI,locX,locY FROM regions WHERE uuid='$region'";
        $db->query($sql);

        list($regionHandle, $regionName, $regionSecret, $serverIP, $serverHttpPort, $serverURI, $locX, $locY) = $db->next_record();
        }
		$rginfo = opensim_get_estate_owner($region, $db);

        $rginfo['regionHandle']   = $regionHandle;
        $rginfo['regionName']     = $regionName;
        $rginfo['regionSecret']   = $regionSecret;
        $rginfo['serverIP']       = $serverIP;
        $rginfo['serverHttpPort'] = $serverHttpPort;
        $rginfo['serverURI']      = $serverURI;
        $rginfo['locX']                   = $locX;
        $rginfo['locY']                   = $locY;

        return $rginfo;
}

function  opensim_get_avatar_session($uuid, &$db=null)
{
        global $OpenSimVersion;

        if (!isGUID($uuid)) return null;

        if (!is_object($db)) $db = opensim_new_db();
        if ($OpenSimVersion==null) opensim_get_db_version($db);

        $avssn = array();

        //
        if ($OpenSimVersion==OPENSIM_V07) {
                $sql = "SELECT RegionID,SessionID,SecureSessionID FROM Presence WHERE UserID='".$uuid."'";
                $db->query($sql);
                if ($db->Errno==0) list($RegionID, $SessionID, $SecureSessionID) = $db->next_record();
        }

        else if ($OpenSimVersion==OPENSIM_V06) {
                $sql = "SELECT currentRegion,sessionID,secureSessionID FROM agents WHERE UUID='".$uuid."'";
                $db->query($sql);
                if ($db->Errno==0) list($RegionID, $SessionID, $SecureSessionID) = $db->next_record();
        }

        else if ($OpenSimVersion==AURORASIM) {
                $sql = "SELECT CurrentRegionID,token FROM tokens,userinfo WHERE UUID='".$uuid."' AND UUID=UserID AND IsOnline='1'";
                $db->query($sql);
                if ($db->Errno==0) {
                        while (list($rg, $ss) = $db->next_record()) {           // Get Last Record
                                $RegionID  = $rg;
                                $SessionID = null;
                                $SecureSessionID = $ss;
                        }
                }
        }
		
        else if ($OpenSimVersion==SIMIANGRID) {
                $sql = "SELECT SceneID,SessionID,SecureSessionID FROM Sessions WHERE UserID='".$uuid."'";
                $db->query($sql);
                if ($db->Errno==0) list($RegionID, $SessionID, $SecureSessionID) = $db->next_record();
        }

        else return $avssn;
		
		if ($db->Errno==0) {
                $avssn['regionID']  = $RegionID;
                $avssn['sessionID'] = $SessionID;
                $avssn['secureID']  = $SecureSessionID;
        }

        return $avssn;
}

function opensim_set_currency_transaction($sourceId, $destId, $amount, $type, $flags, $description, $userip, &$db=null)
{
        if (!isNumeric($amount)) return;
        if (!isGUID($sourceId))  $sourceId = '00000000-0000-0000-0000-000000000000';
        if (!isGUID($destId))    $destId   = '00000000-0000-0000-0000-000000000000';

        if (!is_object($db)) $db = opensim_new_db();

        $handle   = 0;
        $secure   = '00000000-0000-0000-0000-000000000000';
        $client   = $sourceId;
        $UUID    = make_random_guid();
        $sourceID = $sourceId.'@'.$userip;
        $destID   = $destId.'@'.$userip;
        if ($client=='00000000-0000-0000-0000-000000000000') $client = $destId;

        $avt = opensim_get_avatar_session($client);
        if ($avt!=null) {
                $region = $avt['regionID'];
                $secure = $avt['secureID'];

                $rgn = opensim_get_region_info($region);
                if ($rgn!=null) $handle = $rgn["regionHandle"];
        }

        $sql = "INSERT INTO ".CURRENCY_TRANSACTION_TBL." (UUID,sender,receiver,amount,objectUUID,".
                                                                                                        "regionHandle,type,time,secure,status,description) ".
                        "VALUES ('".
                                $UUID."','".
                                $sourceID."','".
                                $destID."','".
                                $amount."','".
                                "00000000-0000-0000-0000-000000000000','".
                                $handle."','".
                                $db->escape($type)."','".
                                time()."','".
                                $secure."','".
                                $db->escape($flags)."','".
                                $db->escape($description)."')";
        $db->query($sql);
}

function opensim_set_currency_balance($agentid, $userip, $amount, &$db=null)
{
        if (!isGUID($agentid) or !isNumeric($amount)) return;

        if (!is_object($db)) $db = opensim_new_db();

        $userid = $db->escape($agentid.'@'.$userip);

        $db->lock_table(CURRENCY_MONEY_TBL);

        $db->query("SELECT balance FROM ".CURRENCY_MONEY_TBL." WHERE user='".$userid."'");
        if ($db->Errno==0) {
                list($cash) = $db->next_record();
                $balance = (integer)$cash + (integer)$amount;

                $db->query("UPDATE ".CURRENCY_MONEY_TBL." SET balance='".$balance."' WHERE user='".$userid."'");
                if ($db->Errno==0) $db->next_record();
        }

        $db->unlock_table();
}



function opensim_get_currency_balance($agentid, $userip, &$db=null)
{
        if (!isGUID($agentid)) return;

        if (!is_object($db)) $db = opensim_new_db();
        if ($OpenSimVersion==null) opensim_get_db_version($db);

        $userid = $db->escape($agentid.'@'.$userip);
        $db->query("SELECT balance FROM ".CURRENCY_MONEY_TBL." WHERE user='".$userid."'");

        $cash = 0;
        if ($db->Errno==0) list($cash) = $db->next_record();

        return (integer)$cash;
}

function  opensim_get_servers_ip(&$db=null)
{
        global $OpenSimVersion;

        if (!is_object($db)) $db = opensim_new_db();
        if ($OpenSimVersion==null) opensim_get_db_version($db);

        $ips = array();
        if ($db->exist_table('GridUser')) {
                $db->query("SELECT DISTINCT serverIP FROM regions");
        }
        else if ($db->exist_table('Users')) {
                $db->query("SELECT DISTINCT Address FROM Scenes");
                list($serverURI) = $db->next_record();
                list($httpip,$portslash) = explode(":", $serverURI);
                list($serverIP) = explode("http://",$httpip);
        }
		else if ($db->exist_table('users')) {
                $db->query("SELECT DISTINCT serverIP FROM regions");
        }
        if ($db->Errno==0) {
                $count = 0;
                while (list($serverIP) = $db->next_record()) {
                        $ips[$count] = $serverIP;
                        $count++;
                }
        }

        return $ips;
}

function  opensim_is_access_from_region_server()
{
        $ip_match = false;
        $remote_addr = $_SERVER['REMOTE_ADDR'];
        $server_addr = $_SERVER['SERVER_ADDR'];

        if ($remote_addr==$server_addr or $remote_addr=="127.0.0.1") return true;

        $ips = opensim_get_servers_ip();

        foreach($ips as $ip) {
                if ($ip == $remote_addr) {
                        $ip_match = true;
                        break;
                }
        }

        return $ip_match;
}

?>
