<?php

class Application_Model_Realm {

    private $version;
    private $dd;
    private $cdn;

    function __construct() {
        $handle = fopen('https://br.api.pvp.net/api/lol/static-data/br/v1.2/realm?api_key=' . API_KEY, 'rb');
        if ($handle) {
            $data = stream_get_contents($handle);
            $info = json_decode($data, true);
            fclose($handle);
            $this->version = $info['v'];
            $this->version = '0.151.2';
            $this->dd = $info['dd'];
            $this->cdn = $info['cdn'];
        }
    }

    function toArray() {
        return get_object_vars($this);
    }

    public function getVersion() {
        return $this->version;
    }

    public function getDd() {
        return $this->dd;
    }

    public function getCdn() {
        return $this->cdn;
    }

    public function setVersion($version) {
        $this->version = $version;
    }

    public function setDd($dd) {
        $this->dd = $dd;
    }

    public function setCdn($cdn) {
        $this->cdn = $cdn;
    }

}
