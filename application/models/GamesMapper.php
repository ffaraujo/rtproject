<?php

class Application_Model_GamesMapper {

    private $_regionalEndpoints;

    function __construct() {
        $this->_regionalEndpoints = RiotConstants::$regionalEndpoints;
    }
    
    public function find($id, $region = 'BR') {
        $cacheManager = new Cache(Cache::$hugeCache);
        $match = $cacheManager->getJson("findGame$region$id");

        if (!$match) {
            $handle = fopen("https://{$this->_regionalEndpoints[$region]}/api/lol/" . strtolower($region) . "/v2.2/match/$id?api_key=" . API_KEY, 'rb');
            if ($handle) {
                $data = stream_get_contents($handle);
                $match = json_decode($data, true);
                fclose($handle);
                $cacheManager->saveJson("findGame$region$id", $match);
                return $match;
            } else {
                return false;
            }
        } else {
            return $match;
        }
    }

    // @TODO verify function
    public function fetchByChampion($sumID, $champID, $region = 'BR') {
        $bIndex = 0;
        $eIndex = 15;
        $handle = fopen("https://{$this->_regionalEndpoints[$region]}/api/lol/" . strtolower($region) . "/v2.2/matchhistory/$sumID?championIds=$champID&beginIndex=$bIndex&endIndex=$eIndex&api_key=" . API_KEY, 'rb');
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

    public function fetchRecent($sumID, $region = 'BR') {
        // @TODO alterar para 2min (120)
        $cacheManager = new Cache(Cache::$smallCache);
        $games = $cacheManager->getJson("fetchRecent$region$sumID");

        if (!$games) {
            $handle = fopen("https://{$this->_regionalEndpoints[$region]}/api/lol/" . strtolower($region) . "/v1.3/game/by-summoner/$sumID/recent?api_key=" . API_KEY, 'rb');
            if ($handle) {
                $data = stream_get_contents($handle);
                $games = json_decode($data, true);
                fclose($handle);
                $cacheManager->saveJson("fetchRecent$region$sumID", $games);
                return $games;
            } else {
                return false;
            }
        } else {
            return $games;
        }
    }

}
