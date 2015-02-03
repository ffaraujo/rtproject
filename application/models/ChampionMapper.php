<?php

class Application_Model_ChampionMapper {

    // @TODO possibilidade de salvar em banco ao inves de cache
    
    public function find($id) {
        $cacheManager = new Cache(3600 * 24 * 8);
        $champion = $cacheManager->getJson("findChampion$id");
        if (!$champion) {
            $handle = fopen("https://br.api.pvp.net/api/lol/static-data/br/v1.2/champion/$id?champData=all&api_key=" . API_KEY, 'rb');
            if ($handle) {
                $data = stream_get_contents($handle);
                $champion = json_decode($data, true);
                fclose($handle);
                $cacheManager->saveJson("findChampion$id", $champion);
                return $champion;
            } else {
                return false;
            }
        } else {
            return $champion;
        }
    }

    public function fetchAll() {
        $cacheManager = new Cache(3600 * 24 * 8);
        $champions = $cacheManager->getJson('fetchAllChampions');
        if (!$champions) {
            $handle = fopen('https://br.api.pvp.net/api/lol/static-data/br/v1.2/champion?champData=image&api_key=' . API_KEY, 'rb');
            if ($handle) {
                $data = stream_get_contents($handle);
                $champions = json_decode($data, true);
                fclose($handle);
                ksort($champions['data'], SORT_STRING);
                $cacheManager->saveJson('fetchAllChampions', $champions);
                return $champions['data'];
            } else {
                return false;
            }
        } else {
            return $champions['data'];
        }
    }

    public function getChampionSquareImg($id, $realm) {
        $champ = $this->find($id);
        if (!$champ)
            return '';
        if (!file_exists(PATH_UPLOAD . "c_thumbs/{$champ['image']['full']}")) {
            if (!$this->saveThumb($champ, $realm)) {
                return '';
            }
        }
        return "/upload/c_thumbs/{$champ['image']['full']}";
    }

    public function getChampionLoadImg($id, $n, $realm) {
        $champ = $this->find($id);
        if (!$champ)
            return '';
        if (!file_exists(PATH_UPLOAD . "skins/" . $champ['key'] . "_$n.jpg")) {
            if (!$this->saveImage($champ, $n, $realm)) {
                return '';
            }
        }
        return "/upload/skins/" . $champ['key'] . "_$n.jpg";
    }

    public function fetchItemsByChampion($id) {
        $champ = $this->find($id);
        $items = array();
        foreach ($champ['recommended'][1]['blocks'] as $block) {
            foreach ($block['items'] as $item) {
                $handle = @fopen("https://br.api.pvp.net/api/lol/static-data/br/v1.2/item/{$item['id']}?api_key=" . API_KEY, 'rb');
                if ($handle) {
                    $data = stream_get_contents($handle);
                    $itemData = json_decode($data, true);
                    fclose($handle);
                    $items[] = $itemData['name'] . "(x{$item['count']})";
                } else {
                    continue;
                }
            }
        }
        return $items;
    }

    public function saveImage($champ, $n = false, $realm = false) {
        set_time_limit(120);
        $path = PATH_UPLOAD . "skins/";
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        if (!$n)
            $n = 0;

        $file = $champ['key'] . "_$n.jpg";
        $handle = @fopen($realm->getCdn() . "/img/champion/loading/$file", 'rb');
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

    public function saveThumb($champ, $realm = false) {
        set_time_limit(120);
        $path = PATH_UPLOAD . "c_thumbs/";
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        if (!$realm) {
            $realm = new Application_Model_Realm();
        }

        $file = $champ['image']['full'];
        $handle = @fopen($realm->getCdn() . '/' . $realm->getVersion() . "/img/champion/$file", 'rb');
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

}
