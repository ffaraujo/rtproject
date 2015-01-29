<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cache
 *
 * @author fabio.araujo
 */
class Cache {

    private $cacheObj;

    function __construct($lifetime = false) {
        $cacheDir = APPLICATION_PATH . '/../cache/';
        if (!file_exists($cacheDir))
            mkdir($cacheDir, 0755, true);

        if (!$lifetime)
            $lifetime = 3600;

        $frontendOptions = array(
            'lifetime' => $lifetime, // cache lifetime of 2 hours
            'automatic_serialization' => true
        );

        $backendOptions = array(
            'cache_dir' => $cacheDir // Directory where to put the cache files
        );

        // getting a Zend_Cache_Core object
        $this->cacheObj = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
        $this->clearAllCache();
    }

    function saveJson($name, $json) {
        $this->cacheObj->save($json, $name);
    }

    function getJson($name) {
        if (($json = $this->cacheObj->load($name)) !== false) {
            return $json;
        } else {
            return false;
        }
    }

    function clearCache($name) {
        $this->cacheObj->remove($name);
    }

    function clearAllCache($old = true) {
        if ($old)
            $this->cacheObj->clean(Zend_Cache::CLEANING_MODE_OLD);
        else
            $this->cacheObj->clean(Zend_Cache::CLEANING_MODE_ALL);
    }

}
