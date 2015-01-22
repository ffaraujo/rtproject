<?php

class ChampionsController extends GeneralController {

    public function init() {
        parent::init();
        $this->_iniUrl = '/champions';
        $this->view->realm = new Application_Model_Realm();
    }

    public function indexAction() {
        $mapper = new Application_Model_ChampionMapper();
        $this->view->champions = $mapper->fetchAll();
    }

    public function detailAction() {
        if ($this->_hasParam('id')) {
            $id = $this->getIdUrl($this->_getParam('id'));
        } else {
            $this->_redirect('/champions');
        }

        $mapper = new Application_Model_ChampionMapper();
        $this->view->champion = $mapper->find($id);
        
        $gamesMapper = new Application_Model_GamesMapper();
        $games = $gamesMapper->fetchRecent('3799295');
        if ($games)
            $this->view->lastGames = $games;
        else
            $this->view->lastGames = array();
        
        $this->view->spellMapper = new Application_Model_SpellMapper();
        
        //$g = $gMapper->fetchByChampion('3799295', 25);
        //$g = $gMapper->find('446081949');
    }

}
