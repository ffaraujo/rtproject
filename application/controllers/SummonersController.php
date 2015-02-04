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
        $gamesMapper = new Application_Model_GamesMapper();
        $this->view->games = $gamesMapper->fetchRecent($this->view->summoner['id']);
        if (!$this->view->games)
            $this->view->games = array();
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
