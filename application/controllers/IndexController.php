<?php

class IndexController extends GeneralController {
    /*
     * @TODO calcular proporcao KDA / W-L
	 * K:2, D:-0.5, A:1.5, CS:0.01, 10+ K/A:2, 3K/4K/5K: 2/5/10
	 * KDA: (K+A) / D
     * Summoner ID: 3799295, 4365847, 13160847, 400463, 13294089
     * Games IDs: 486598065, 446081949, 455132204
     * @TODO total de kills por time
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
        /*$versions = array(
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
        exit();*/

        $cm = new Application_Model_ChampionMapper();
        $cs = $cm->fetchAll();
        $realm = new Application_Model_Realm();
        $allowedIds = array(
            266,103,84,12,32,
            34,1,22,268,53,
            63,201,51,69,31,
            42,122,131,36,119,
            60,28,81,9,114,
            105,3,41,86,150,
            79,104,120,74,39,
            40,59,24,126,222,
            429,43,30,38,55,
            10,85,121,96,7,
            64,89,127,236,117,
            99,54,90,57,11,
            21,62,82,25,267,
            75,111,76,56,20,
            2,61,80,78,133,
            33,421,58,107,92,
            68,13,113,35,98,
            102,27,14,15,72,
            37,16,50,134,91,
            44,17,412,18,48,
            23,4,29,77,6,
            110,67,45,161,254,
            112,8,106,19,101,
            5,157,83,154,238,
            115,26,143,
            22,53,103,51,9,
            114,79,55,89,11,
            21,25,76,92,15,
            13,98,37,16,17,
            18,29,77,23,67,
            106,5,238,26,
        );
        exit(var_dump($cs));
        $i = 1;
        foreach ($cs as $c) {
            if (in_array($c['id'], $allowedIds)) {
                $qsk = count($c['skins']);
                for($i = 1; $i < $qsk; $i++)
                    var_dump($cm->getChampionLoadImg($c['id'], $i, $realm));
                if (($i % 5) == 0)
                    flush();
                $i++;
            }
        }
        exit();

        //$im = $cm->saveImage($this->_getParam('champ', 11), $this->_getParam('img', false));
        /* foreach ($versions as $version) {
          set_time_limit(60);
          echo $cm->saveThumb($this->_getParam('champ', 11), $version) . '<br />';
          } */
        //exit(var_dump($im));

        /*if ($this->_hasParam('info')) {
            $this->view->master = $cm->find(21);
            $this->view->masterItems = $cm->fetchItemsByChampion(21);
            $this->view->info = true;
        } else {
            $this->view->info = false;
        }*/
    }

}
