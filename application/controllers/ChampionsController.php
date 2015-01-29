<?php

class ChampionsController extends GeneralController {

    public function init() {
        parent::init();
        $this->_iniUrl = '/champions';
        $this->view->realm = new Application_Model_Realm();
    }

    public function indexAction() {
        $mapper = $this->view->championMapper = new Application_Model_ChampionMapper();
        $this->view->champions = $mapper->fetchAll();

        if ($this->_hasParam('id')) {
            $cs = $this->view->champions;
            $newCs = array();
            foreach ($cs as $c) {
                $newCs[$c['id']] = $c;
            }
            ksort($newCs, SORT_NUMERIC);
            $cs = $newCs;
            $this->view->champions = $cs;
        }
    }

    public function detailAction() {
        if ($this->_hasParam('id')) {
            $id = $this->getIdUrl($this->_getParam('id'));
        } else {
            $this->_redirect('/champions');
        }

        $mapper = $this->view->championMapper = new Application_Model_ChampionMapper();
        $this->view->champion = $mapper->find($id);

        $gamesMapper = new Application_Model_GamesMapper();
        //400463, 3799295
        if ($this->_hasParam('sum'))
            $games = $gamesMapper->fetchRecent($this->_getParam('sum'));
        else
            $games = $gamesMapper->fetchRecent('400463');
        
        if ($games)
            $this->view->lastGames = $games;
        else
            $this->view->lastGames = array();

        $this->view->spellMapper = new Application_Model_SpellMapper();
    }

}
