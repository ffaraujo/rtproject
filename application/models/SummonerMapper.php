<?php

class Application_Model_SummonerMapper {

    public function find($id, $region = 'BR') {
        $cacheManager = new Cache(3600 * 24 * 1);
        $summoner = $cacheManager->getJson("findSummoner$id");

        if (!$summoner) {
            $regionalEndpoints = RiotConstants::$regionalEndpoints;

            $handle = fopen("https://{$regionalEndpoints[$region]}/api/lol/br/v1.4/summoner/$id?api_key=" . API_KEY, 'rb');
            if ($handle) {
                $data = stream_get_contents($handle);
                $summoner = json_decode($data, true);
                fclose($handle);
                $summoner = array_shift($summoner);
                $cacheManager->saveJson("findSummoner$id", $summoner);
                //exit(var_dump($summoner));
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
        $summoner = $cacheManager->getJson("findSummoner$name");

        if (!$summoner) {
            $regionalEndpoints = RiotConstants::$regionalEndpoints;

            $handle = fopen("https://{$regionalEndpoints[$region]}/api/lol/br/v1.4/summoner/by-name/" . rawurlencode($name) . "?api_key=" . API_KEY, 'rb');
            if ($handle) {
                $data = stream_get_contents($handle);
                $summoner = json_decode($data, true);
                fclose($handle);
                $summoner = array_shift($summoner);
                $cacheManager->saveJson("findSummoner$name", $summoner);
                //exit(var_dump($summoner));
                return $summoner;
            } else {
                return false;
            }
        } else {
            return $summoner;
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
