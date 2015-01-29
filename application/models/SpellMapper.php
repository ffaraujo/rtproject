<?php

class Application_Model_SpellMapper {

    public function find($id) {
        $cacheManager = new Cache(172800);
        $spell = $cacheManager->getJson("findSpell$id");
        if (!$spell) {
            $handle = fopen("https://br.api.pvp.net/api/lol/static-data/br/v1.2/summoner-spell/$id?spellData=all&api_key=" . API_KEY, 'rb');
            if ($handle) {
                $data = stream_get_contents($handle);
                $spell = json_decode($data, true);
                fclose($handle);
                $cacheManager->saveJson("findSpell$id", $spell);
                return $spell;
            } else {
                return false;
            }
        } else {
            return $spell;
        }
    }

    public function fetchAll() {
        $cacheManager = new Cache(172800);
        $spells = $cacheManager->getJson("fetchAllSpells");
        if (!$spells) {
            $handle = fopen('https://br.api.pvp.net/api/lol/static-data/br/v1.2/summoner-spell?spellData=all&api_key=' . API_KEY, 'rb');
            if ($handle) {
                $data = stream_get_contents($handle);
                $spells = json_decode($data, true);
                fclose($handle);
                ksort($spells['data'], SORT_STRING);
                $cacheManager->saveJson("fetchAllSpells", $spells);
                return $spells['data'];
            } else {
                return false;
            }
        } else {
            return $spells;
        }
    }

    /*
     * @TODO corrigir para o modelo que foi feito em champ
     * 
     */

    public function getImage($id, $realm) {
        if (!file_exists(PATH_UPLOAD . "spells/$id.png")) {
            if (!$this->saveImage($id, $realm)) {
                return '';
            }
        }
        return "/upload/spells/$id.png";
    }
    
    public function saveImage($id, $realm) {
        set_time_limit(120);
        $path = PATH_UPLOAD . "spells/";
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        $spell = $this->find($id);
        if ($spell === false)
            return false;

        if (!$realm) {
            $realm = new Application_Model_Realm();
        }

        $file = $spell['image']['full'];
        $handle = @fopen($realm->getCdn() . '/' . $realm->getVersion() . "/img/spell/$file", 'rb');
        if ($handle) {
            $data = stream_get_contents($handle);
            fclose($handle);
            $handle2 = @fopen($path . $id . '.png', 'wb');
            if ($handle2) {
                fwrite($handle2, $data);
            }
            fclose($handle2);
            return true;
        } else {
            return false;
        }
    }

}
