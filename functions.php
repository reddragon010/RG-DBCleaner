<?php

$accountIDList = array();
$characterGUIDList = array();

$queries = array();

$char_dbconfig = array(
    'user' => 'root',
    'pass' => '',
    'dsn' => 'mysql:host=127.0.0.1;dbname=characters'
);

$auth_dbconfig = array(
    'user' => 'root',
    'pass' => '',
    'dsn' => 'mysql:host=127.0.0.1;dbname=auth'
);

function SelectAccounts($where = '`joindate` < @joindate AND `last_login` = @lastlogin')
{
    global $auth_dbconfig, $queries, $accountIDList;
    $db = new Database($auth_dbconfig);

    $accountIDList = $db->query_and_fetch_all("SELECT `id` FROM `account` WHERE $where;");
        
    DeleteLogonAccounts($accountIDList);
    SelectCharacters($accountIDList);
    DeleteAccountCrapCharDB($accountIDList);
}


function DeleteLogonAccounts($accountIDList)
{
    global $accountIDList, $queries;
    
    if (empty($accountIDList))
        return;

    foreach($accountIDList as $acc)
    {
        $id = $acc['id'];
        $queries[] = "DELETE FROM `account` WHERE `id`= $id;";
        $queries[] = "DELETE FROM `account_access` WHERE `id`= $id;";
        $queries[] = "DELETE FROM `account_banned` WHERE `id`= $id;";
        $queries[] = "DELETE FROM `realmcharacters` WHERE `acctid`= $id;";
    }
}

function DeleteAccountCrapCharDB($accountIDList)
{
    global $accountIDList, $queries;
    
    if (empty($accountIDList))
        return;

    foreach($accountIDList as $acc)
    {
        $id = $acc['id'];
        $queries[] = "DELETE FROM `account_data` WHERE `id`= $id;";
        $queries[] = "DELETE FROM `account_instance_times` WHERE `id`= $id;";
        $queries[] = "DELETE FROM `account_tutorial` WHERE `id`= $id;";
    }
}

function SelectCharacters($accountIDList)
{
    global $char_dbconfig, $accountIDList, $characterGUIDList, $queries;
    
    $db = new Database($char_dbconfig);
    $characterGUIDList = array();

    if (empty($accountIDList))
        return;

    foreach($accountIDList as $acc)
    {
        $accid = $acc['id'];
        $characterGUIDList += $db->query_and_fetch_all("SELECT `guid` FROM `characters` WHERE `account` = $accid;");
    }

    DeleteallPets($characterGUIDList);
    DeleteallCharacterCrap($characterGUIDList);
}

function DeleteallPets($characterGUIDList)
{
    global $char_dbconfig, $characterGUIDList, $queries;
    
    $db = new Database($char_dbconfig);
    
    if (empty($characterGUIDList))
        return;

    foreach($characterGUIDList as $char)
    {
        $charGUID = $char['guid'];
        foreach($db->query_and_fetch_all("SELECT `id` FROM `character_pet` WHERE `owner` = $charGUID;") as $pet)
        {
            $petGUID = $pet['guid'];
            $queries[] = "DELETE FROM `character_pet` WHERE `id`= $petGUID;";
            $queries[] = "DELETE FROM `pet_aura` WHERE `guid`= $petGUID;";
            $queries[] = "DELETE FROM `pet_spell` WHERE `guid`= $petGUID;";
            $queries[] = "DELETE FROM `pet_spell_cooldown` WHERE `guid`= $petGUID;";
        }
    }
}


function DeleteallCharacterCrap($characterGUIDList)
{
    global $characterGUIDList, $queries;
    
    if (empty($characterGUIDList))
        return;

    foreach($characterGUIDList as $char)
    {
        $charGUID = $char['guid'];
        $queries[] = "DELETE FROM `arena_team_member` WHERE `guid`= $charGUID;";
        $queries[] = "DELETE FROM `auctionhouse` WHERE `auctioneerguid`= $charGUID;";
        $queries[] = "DELETE FROM `character_account_data` WHERE `guid`= $charGUID;";
        $queries[] = "DELETE FROM `character_achievement` WHERE `guid`= $charGUID;";
        $queries[] = "DELETE FROM `character_achievement_progress` WHERE `guid`= $charGUID;";
        $queries[] = "DELETE FROM `character_action` WHERE `guid`= $charGUID;";
        $queries[] = "DELETE FROM `character_arena_stats` WHERE `guid`= $charGUID;";
        $queries[] = "DELETE FROM `character_aura` WHERE `guid`= $charGUID;";
        $queries[] = "DELETE FROM `character_banned` WHERE `guid`= $charGUID;";
        $queries[] = "DELETE FROM `character_battleground_data` WHERE `guid`= $charGUID;";
        $queries[] = "DELETE FROM `character_battleground_random` WHERE `guid`= $charGUID;";
        $queries[] = "DELETE FROM `character_equipmentsets` WHERE `guid`= $charGUID;";
        $queries[] = "DELETE FROM `character_gifts` WHERE `guid`= $charGUID;";
        $queries[] = "DELETE FROM `character_glyphs` WHERE `guid`= $charGUID;";
        $queries[] = "DELETE FROM `character_homebind` WHERE `guid`= $charGUID;";
        $queries[] = "DELETE FROM `character_instance` WHERE `guid`= $charGUID;";
        $queries[] = "DELETE FROM `character_inventory` WHERE `guid`= $charGUID;";
        $queries[] = "DELETE FROM `character_questatus` WHERE `guid`= $charGUID;";
        $queries[] = "DELETE FROM `character_questatus_daily` WHERE `guid`= $charGUID;";
        $queries[] = "DELETE FROM `character_questatus_rewarded` WHERE `guid`= $charGUID;";
        $queries[] = "DELETE FROM `character_questatus_weekly` WHERE `guid`= $charGUID;";
        $queries[] = "DELETE FROM `character_reputation` WHERE `guid`= $charGUID;";
        $queries[] = "DELETE FROM `character_skills` WHERE `guid`= $charGUID;";
        $queries[] = "DELETE FROM `character_social` WHERE `guid`= $charGUID;";
        $queries[] = "DELETE FROM `character_spell` WHERE `guid`= $charGUID;";
        $queries[] = "DELETE FROM `character_spell_cooldown` WHERE `guid`= $charGUID;";
        $queries[] = "DELETE FROM `character_stats` WHERE `guid`= $charGUID;";
        $queries[] = "DELETE FROM `character_talent` WHERE `guid`= $charGUID;";
        $queries[] = "DELETE FROM `characters` WHERE `guid`= $charGUID;";
        $queries[] = "DELETE FROM `corpse` WHERE `guid`= $charGUID;";
        $queries[] = "DELETE FROM `gm_tickets` WHERE `guid`= $charGUID;";
        $queries[] = "DELETE FROM `group_member` WHERE `memberGuid`= $charGUID;";
        $queries[] = "DELETE FROM `groups` WHERE `leaderGuid`= $charGUID;";
        $queries[] = "DELETE FROM `item_instance` WHERE `owner_guid`= $charGUID;";
        $queries[] = "DELETE FROM `item_refund_instance` WHERE `player_guid`= $charGUID;";
        $queries[] = "DELETE FROM `mail` WHERE `receiver`= $charGUID;";
        $queries[] = "DELETE FROM `mail_items` WHERE `receiver`= $charGUID;";
        $queries[] = "DELETE FROM `petition` WHERE `ownerguid`= $charGUID;";
        $queries[] = "DELETE FROM `petition_sign` WHERE `ownerguid`= $charGUID;";
        $queries[] = "DELETE FROM `rg_characters_data` WHERE `guid`= $charGUID;";
    }
}

?>
