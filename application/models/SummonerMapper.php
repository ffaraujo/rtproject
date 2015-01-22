<?php

class Application_Model_SummonerMapper {
    public function find($id) {
        $handle = fopen("https://br.api.pvp.net/api/lol/br/v1.4/summoner/$id?api_key=" . API_KEY, 'rb');
        if ($handle) {
            $data = stream_get_contents($handle);
            $summoner = json_decode($data, true);
            fclose($handle);
            exit(var_dump($summoner));
            return $summoner;
        } else {
            return false;
        }
    }
    
    public function findByName($name) {
        $handle = fopen("https://br.api.pvp.net/api/lol/br/v1.4/summoner/by-name/$name?api_key=" . API_KEY, 'rb');
        if ($handle) {
            $data = stream_get_contents($handle);
            $summoner = json_decode($data, true);
            fclose($handle);
            exit(var_dump($summoner));
            return $summoner;
        } else {
            return false;
        }
    }
}
