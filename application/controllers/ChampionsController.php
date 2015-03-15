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
            $this->view->sortById = false;
        } else {
            $this->view->sortById = true;
        }
    }

    public function detailAction() {
        /*
         * @TODO adicionar itens recomendados
         * @TODO adicionar dicas
         */
        if ($this->_hasParam('id')) {
            $id = $this->getIdUrl($this->_getParam('id'));
        } else {
            $this->_redirect('/champions');
        }

        $mapper = $this->view->championMapper = new Application_Model_ChampionMapper();
        $this->view->champion = $mapper->find($id);

        $sumSession = new Zend_Session_Namespace('summoner');
        if (isset($sumSession->id) && isset($sumSession->region)) {
            $gamesMapper = new Application_Model_GamesMapper();
            $games = $gamesMapper->fetchRecent($sumSession->id, $sumSession->region);

            if ($games)
                $this->view->lastGames = $games;
            else
                $this->view->lastGames = array();
            
            $this->view->region = $sumSession->region;
            $this->view->sumId = $sumSession->id;
        } else {
            $this->view->lastGames = array();
        }

        // @TODO verificar duplicacao de spell mapper
        $this->view->spellMapper = new Application_Model_SpellMapper();
    }

}
