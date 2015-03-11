<?php

class SummonersController extends GeneralController {

    public function init() {
        parent::init();
        $this->_iniUrl = '/summoners';
    }

    public function indexAction() {
        // action body
    }

    public function detailAction() {
        if (!$this->_hasParam('id') || !$this->_hasParam('region')) {
            $this->addFlashMessage(array('Não foi informado um invocador', ERROR), '/');
        }

        $this->view->sumMapper = $mapper = new Application_Model_SummonerMapper();
        $this->view->summoner = $mapper->find($this->getIdUrl($this->_getParam('id')), $this->_getParam('region'));
        if (!$this->view->summoner) {
            $this->addFlashMessage(array('Invocador ' . $this->_getParam('id') . ' não encontrado.', ERROR), '/');
        }
        
        $sumSession = new Zend_Session_Namespace('summoner');
        $sumSession->setExpirationSeconds(60 * 15);
        $sumSession->id = $this->view->summoner['id'];
        $sumSession->region = $this->_getParam('region');
        
        $gamesMapper = new Application_Model_GamesMapper();
        $this->view->games = $gamesMapper->fetchRecent($this->view->summoner['id'], $this->_getParam('region'));
        if (!$this->view->games)
            $this->view->games = array();
        $this->view->league = $mapper->fetchLeague($this->view->summoner['id'], $this->_getParam('region'));
        
        var_dump($this->view->games['games'][0]);
        echo "=============<br/>";
        $g = $gamesMapper->find(486598065, 'BR');
        var_dump($g);
        echo "=============<br/>";
        var_dump($g['participants'][0]);
        exit();
    }

    public function lastGamesAction() {
        // action body
    }

    public function searchAction() {
        $request = $this->getRequest();
        if ($request->isPost()) {
            if ($this->_basicSearchForm->isValid($request->getPost())) {
                $data = $this->_basicSearchForm->getValues();
                $mapper = new Application_Model_SummonerMapper();
                //'VanBelt,Vito Corleonne,Nappa00,D Lazuli,Jayob yetz,Jon Yetz', 'BR'
                // @TODO fazer diferenciacao nome de invocador nao encontrado para outros erros
                $data['name'] = str_replace(',', '', $data['name']);
                $summoner = $mapper->findByName($data['name'], $data['region']);
                if ($summoner) {
                    $sumSession = new Zend_Session_Namespace('summoner');
                    $sumSession->setExpirationSeconds(60 * 15);
                    $sumSession->id = $summoner['id'];
                    $sumSession->region = $data['region'];
                    $this->_redirect('/summoner/' . $data['region'] . '/' . $mapper->standardizeSumName($summoner['name']) . '-' . $summoner['id']);
                } else {
                    $this->addFlashMessage(array('Invocador ' . $data['name'] . ' não encontrado.', ERROR), '/');
                }
            }
        } else {
            $this->_redirect('/');
        }
    }

}
