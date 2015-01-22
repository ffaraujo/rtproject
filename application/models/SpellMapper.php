<?php

class Application_Model_SpellMapper {

    public function find($id) {
        $handle = fopen("https://br.api.pvp.net/api/lol/static-data/br/v1.2/summoner-spell/$id?spellData=all&api_key=" . API_KEY, 'rb');
        if ($handle) {
            $data = stream_get_contents($handle);
            $spell = json_decode($data, true);
            //exit(var_dump($spell));
            fclose($handle);
            return $spell;
        } else {
            return false;
        }
    }

    public function fetchAll() {
        $handle = fopen('https://br.api.pvp.net/api/lol/static-data/br/v1.2/summoner-spell?spellData=all&api_key=' . API_KEY, 'rb');
        if ($handle) {
            $data = stream_get_contents($handle);
            $spells = json_decode($data, true);
            fclose($handle);
            ksort($spells['data'], SORT_STRING);
            return $spells['data'];
        } else {
            return false;
        }
    }

    public function getImage($id) {
        $spell = $this->find($id);
        if (!$spell) {
            return '';
        } else {
            return $spell['image']['full'];
        }
    }

}
