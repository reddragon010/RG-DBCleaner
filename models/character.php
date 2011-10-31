<?php

class Character {

    private $data = array();

    function __construct($data = array()) {
        $this->data = $data;
    }

    public static function select($where) {
        $result = DatabaseManager::get('char')->query_and_fetch_all("SELECT `guid` FROM `characters` WHERE $where;");
        $accounts = false;
        if (count($result) > 0) {
            $accounts = array();
            foreach ($result as $row) {
                $accounts[] = new Character($row);
            }
        }
        return $accounts;
    }

    function delete_pets() {
        if (empty($this->data['guid']))
            return false;

        $db = DatabaseManager::get('char');
        $queries = array();
        $charGUID = $this->data['guid'];
        foreach ($db->query_and_fetch_all("SELECT `id` FROM `character_pet` WHERE `owner` = $charGUID;") as $pet) {
            $petGUID = $pet['guid'];
            $queries[] = "DELETE FROM `character_pet` WHERE `id`= $petGUID;";
            $queries[] = "DELETE FROM `pet_aura` WHERE `guid`= $petGUID;";
            $queries[] = "DELETE FROM `pet_spell` WHERE `guid`= $petGUID;";
            $queries[] = "DELETE FROM `pet_spell_cooldown` WHERE `guid`= $petGUID;";
        }
        $db->queue->add_range($queries);
        return true;
    }

    function delete() {
        if (empty($this->data['guid']))
            return false;
        
        $queries = array();
        $charGUID = $this->data['guid'];
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
        $db = DatabaseManager::get('char')->queue->add_range($queries);
        
        return $this->delete_pets();
    }

}

