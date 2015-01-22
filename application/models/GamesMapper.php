<?php

class Application_Model_GamesMapper {
    public function find($id) {
        $handle = fopen("https://br.api.pvp.net/api/lol/br/v2.2/match/$id?api_key=" . API_KEY, 'rb');
        if ($handle) {
            $data = stream_get_contents($handle);
            $match = json_decode($data, true);
            fclose($handle);
            exit(var_dump($match));
            return $match;
        } else {
            return false;
        }
    }
    
    public function fetchByChampion($sumID, $champID) {
        $bIndex = 0;
        $eIndex = 15;
        $handle = fopen("https://br.api.pvp.net/api/lol/br/v2.2/matchhistory/$sumID?championIds=$champID&beginIndex=$bIndex&endIndex=$eIndex&api_key=" . API_KEY, 'rb');
        if ($handle) {
            $data = stream_get_contents($handle);
            $games = json_decode($data, true);
            fclose($handle);
            exit(var_dump($games));
            return $games;
        } else {
            return false;
        }
    }
    
    public function fetchRecent($sumID) {
        $handle = fopen("https://br.api.pvp.net/api/lol/br/v1.3/game/by-summoner/$sumID/recent?api_key=" . API_KEY, 'rb');
        if ($handle) {
            $data = stream_get_contents($handle);
            $games = json_decode($data, true);
            fclose($handle);
            exit(var_dump($games['games'][0]));
            return $games;
        } else {
            return false;
        }
    }
}
