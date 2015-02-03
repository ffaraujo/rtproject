<?php

class IndexController extends GeneralController {
    /*
     * @TODO calcular proporcao KDA / W-L
     * Summoner ID: 3799295, 4365847, 13160847, 400463, 13294089
     * Games IDs: 446081949, 455132204
     */

    public function init() {
        parent::init();
        $this->_iniUrl = '/';
    }

    public function indexAction() {
        //code here...
    }

    public function logonAction() {
        if ($this->_logon->getLogged()) {
            $this->_redirect($this->_iniUrl);
        }

        $form = new Application_Form_Logon(NULL, false);
        $request = $this->getRequest();
        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $login = $this->_getParam(Application_Model_User::PREFIX . 'email');
                $pass = $this->_getParam(Application_Model_User::PREFIX . 'password');

                if (!$login or !$pass) {
                    $this->addFlashMessageDirect(array('Preencha os dados corretamente.', ERROR));
                } else {
                    $result = $this->_logon->doLogin($login, $pass);
                    if ($result) {
                        $this->_redirect($this->_iniUrl);
                    } else {
                        $this->addFlashMessageDirect(array('Usuário ou senha inválidos.', ERROR));
                    }
                }
            } else {
                $this->addFlashMessageDirect(array('Erro na validação.', ERROR));
            }
        }
        $this->view->form = $form;
    }

    public function logoutAction() {
        $this->_logon->doLogout();
        $this->_redirect('/logon');
    }

    public function testeAction() {
        $versions = array(
            "5.2.1", "5.1.2", "5.1.1",
            "4.21.5", "4.21.4", "4.21.3", "4.21.1", "4.20.2", "4.20.1",
            "4.19.3", "4.19.2", "4.18.1", "4.17.1", "4.16.1", "4.15.1",
            "4.14.2", "4.13.1", "4.12.2", "4.12.1", "4.11.3",
            "4.10.7", "4.10.2",
        );
        $sm = new Application_Model_SpellMapper();
        $sp = $sm->fetchAll();
        foreach ($sp['data'] as $s) {
            //exit(var_dump($s));
            var_dump($sm->getImage($s['id'], false));
            if (($s['id'] % 2) == 0)
                flush();
        }
        exit();

        $cm = new Application_Model_ChampionMapper();
        $cs = $cm->fetchAll();
        $realm = new Application_Model_Realm();
        foreach ($cs as $c) {
            var_dump($cm->getChampionLoadImg($c['id'], 0, $realm));
            if (($c['id'] % 5) == 0)
                flush();
        }
        exit();

        //$im = $cm->saveImage($this->_getParam('champ', 11), $this->_getParam('img', false));
        /* foreach ($versions as $version) {
          set_time_limit(60);
          echo $cm->saveThumb($this->_getParam('champ', 11), $version) . '<br />';
          } */
        //exit(var_dump($im));

        if ($this->_hasParam('info')) {
            $this->view->master = $cm->find(21);
            $this->view->masterItems = $cm->fetchItemsByChampion(21);
            $this->view->info = true;
        } else {
            $this->view->info = false;
        }
    }

}
