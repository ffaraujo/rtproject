<?php

class Application_Model_ChampionMapper {

    public function find($id) {
        $handle = fopen("https://br.api.pvp.net/api/lol/static-data/br/v1.2/champion/$id?champData=all&api_key=" . API_KEY, 'rb');
        if ($handle) {
            $data = stream_get_contents($handle);
            $champion = json_decode($data, true);
            fclose($handle);
            return $champion;
        } else {
            return false;
        }
    }

    public function fetchAll() {
        $handle = fopen('https://br.api.pvp.net/api/lol/static-data/br/v1.2/champion?champData=image&api_key=' . API_KEY, 'rb');
        if ($handle) {
            $data = stream_get_contents($handle);
            $champions = json_decode($data, true);
            fclose($handle);
            ksort($champions['data'], SORT_STRING);
            return $champions['data'];
        } else {
            return false;
        }
    }

}
