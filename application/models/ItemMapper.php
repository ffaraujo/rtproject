<?php

class Application_Model_ItemMapper {

    public function find($id) {
        $cacheManager = new Cache(3600 * 24 * 8);
        $item = $cacheManager->getJson("findItem$id");
        if (!$item) {
            $handle = fopen("https://global.api.pvp.net/api/lol/static-data/br/v1.2/item/$id?itemData=all&api_key=" . API_KEY, 'rb');
            if ($handle) {
                $data = stream_get_contents($handle);
                $item = json_decode($data, true);
                fclose($handle);
                $cacheManager->saveJson("findItem$id", $item);
                return $item;
            } else {
                return false;
            }
        } else {
            return $item;
        }
    }

    public function fetchAll() {
        $cacheManager = new Cache(3600 * 24 * 8);
        $items = $cacheManager->getJson("fetchAllItems");
        if (!$items) {
            $handle = fopen("https://global.api.pvp.net/api/lol/static-data/br/v1.2/item?itemListData=all&api_key=" . API_KEY, 'rb');
            if ($handle) {
                $data = stream_get_contents($handle);
                $items = json_decode($data, true);
                fclose($handle);
                ksort($items['data'], SORT_STRING);
                $cacheManager->saveJson("fetchAllItems", $items);
                return $items['data'];
            } else {
                return false;
            }
        } else {
            return $items;
        }
    }

    public function getImage($id, $realm) {
        $item = $this->find($id);
        if (!$item)
            return '';
        if (!file_exists(PATH_UPLOAD . "items/{$item['image']['full']}")) {
            if (!$this->saveImage($item, $realm)) {
                return '';
            }
        }
        return "/upload/items/{$item['image']['full']}";
    }

    public function saveImage($item, $realm) {
        set_time_limit(120);
        $path = PATH_UPLOAD . "items/";
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        if (!$realm) {
            $realm = new Application_Model_Realm();
        }

        $file = $item['image']['full'];
        //http://ddragon.leagueoflegends.com/cdn/5.2.1/img/item/1001.png
        $handle = @fopen($realm->getCdn() . '/' . $realm->getVersion() . "/img/item/$file", 'rb');
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
