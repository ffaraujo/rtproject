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
    
    public function getChampionSquareImg($id, $realm) {
        $champion = $this->find($id);
        if (!$champion) {
            return '';
        } else {
            $img = $champion['image']['full'];
            return $realm->getCdn() . '/' . $realm->getVersion() . "/img/champion/$img";
        }
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
    
    public function saveImage($id, $n = false) {
        $path = PATH_UPLOAD . "skins/";
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
        
        $champ = $this->find($id);
        if ($champ === false)
            return $id . '_0' . ': FAIL';
        
        if (!$n)
            $n = rand(0, (count($champ['skins']) - 1));
        
        $file = $champ['key'] . '_' . $n . '.jpg';
        $handle = @fopen("http://ddragon.leagueoflegends.com/cdn/img/champion/loading/$file", 'rb');
        if ($handle) {
            $data = stream_get_contents($handle);
            fclose($handle);
            $handle2 = @fopen($path . $file, 'wb');
            if ($handle2) {
                fwrite($handle2, $data);
            }
            fclose($handle2);
            return $champ['key'] . '_' . $n . ': SUCCESS';
        } else {
            return $champ['key'] . '_' . $n . ': FAIL';
        }
    }
    
    public function saveThumb($id, $v = false) {
        $path = PATH_UPLOAD . "c_thumbs/";
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
        
        $champ = $this->find($id);
        if ($champ === false)
            return $id . ': FAIL';
        
        if (!$v)
            $v = "5.2.1";
        
        $file = $champ['key'] . '_' . $v . '.png';
        $handle = @fopen("http://ddragon.leagueoflegends.com/cdn/$v/img/champion/{$champ['key']}.png", 'rb');
        if ($handle) {
            $data = stream_get_contents($handle);
            fclose($handle);
            $handle2 = @fopen($path . $file, 'wb');
            if ($handle2) {
                fwrite($handle2, $data);
            }
            fclose($handle2);
            return $champ['key'] . '_' . $v . ': SUCCESS';
        } else {
            return $champ['key'] . '_' . $v . ': FAIL';
        }
    }

}
