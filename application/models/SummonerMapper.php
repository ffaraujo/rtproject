<?php

class Application_Model_SummonerMapper {

    private $_regionalEndpoints;

    function __construct() {
        $this->_regionalEndpoints = RiotConstants::$regionalEndpoints;
    }

    public function find($id, $region = 'BR') {
        $cacheManager = new Cache(3600 * 24 * 1);
        $summoner = $cacheManager->getJson("findSummoner$region$id");

        if (!$summoner) {
            $handle = fopen("https://{$this->_regionalEndpoints[$region]}/api/lol/" . strtolower($region) . "/v1.4/summoner/$id?api_key=" . API_KEY, 'rb');
            if ($handle) {
                $data = stream_get_contents($handle);
                $summoner = json_decode($data, true);
                fclose($handle);
                $summoner = array_shift($summoner);
                $cacheManager->saveJson("findSummoner$region$id", $summoner);
                return $summoner;
            } else {
                return false;
            }
        } else {
            return $summoner;
        }
    }

    public function findByName($name, $region = 'BR') {
        $cacheManager = new Cache(3600 * 24 * 1);
        $summoner = $cacheManager->getJson("findSummoner$region" . $this->standardizeSumName($name));

        if (!$summoner) {
            $handle = fopen("https://{$this->_regionalEndpoints[$region]}/api/lol/" . strtolower($region) . "/v1.4/summoner/by-name/" . rawurlencode($name) . "?api_key=" . API_KEY, 'rb');
            if ($handle) {
                $data = stream_get_contents($handle);
                $summoner = json_decode($data, true);
                fclose($handle);
                $summoner = array_shift($summoner);
                $cacheManager->saveJson("findSummoner$region" . $this->standardizeSumName($name), $summoner);
                return $summoner;
            } else {
                return false;
            }
        } else {
            return $summoner;
        }
    }

    public function fetchLeague($id, $region = 'BR') {
        // @TODO maneira de melhorar essa duplicacao
        $cacheManager = new Cache(120);
        $league = $cacheManager->getJson("fetchLeague$region$id");

        if (!$league) {
            $handle = @fopen("https://{$this->_regionalEndpoints[$region]}/api/lol/" . strtolower($region) . "/v2.5/league/by-summoner/$id?api_key=" . API_KEY, 'rb');
            if ($handle) {
                $data = stream_get_contents($handle);
                $league = json_decode($data, true);
                fclose($handle);
                $league = array_shift($league);
                $league = array_shift($league);

                foreach ($league['entries'] as $entry) {
                    if ($entry['playerOrTeamId'] == $league['participantId']) {
                        $league['sumData'] = $entry;
                        break;
                    }
                }
                $cacheManager->saveJson("fetchLeague$region$id", $league);
                return $league;
            } else {
                return false;
            }
        } else {
            return $league;
        }
    }

    public function getProfileIcon($id, $realm) {
        if (!file_exists(PATH_UPLOAD . "profile_icons/$id.png")) {
            if (!$this->saveIcon($id, $realm)) {
                return '';
            }
        }
        return "/upload/profile_icons/$id.png";
    }

    public function saveIcon($id, $realm = false) {
        set_time_limit(120);
        $path = PATH_UPLOAD . "profile_icons/";
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        if (!$realm) {
            $realm = new Application_Model_Realm();
        }

        $file = $id . '.png';
        $handle = @fopen($realm->getCdn() . '/' . $realm->getVersion() . "/img/profileicon/$file", 'rb');
        if ($handle) {
            $data = stream_get_contents($handle);
            fclose($handle);
            $handle2 = @fopen($path . $file, 'wb');
            if ($handle2) {
                fwrite($handle2, $data);
            }
            fclose($handle2);
            return true;
        } else {
            return false;
        }
    }

    public function standardizeSumName($name) {
        if (empty($name))
            return '';
        else
            return strtolower(str_replace(' ', '', $name));
    }

}
