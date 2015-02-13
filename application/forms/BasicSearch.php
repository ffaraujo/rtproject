<?php

class Application_Form_BasicSearch extends Zend_Form {

    private $elementDecorators = array('ViewHelper', 'Label');

    function __construct($options = NULL, $generateHash = false) {
        parent::__construct($options);
        if ($generateHash) {
            $hash = new Zend_Form_Element_Hash('csrf');
            $hash->setDecorators($this->elementDecorators);
            $hash->setIgnore(true);
            $this->addElement($hash);
        }
        $this->customInit();
    }

    public function customInit() {
        $this->setMethod('post')->setEnctype('multipart/form-data')->setAttrib('class', 'form-top')->setAction('/summoners/search');
        $this->setTranslator(Zend_Registry::get('translate'));

        $name = new Zend_Form_Element_Text('name');
        $name->addFilters(array('StripTags', 'StringTrim'))
                ->addValidator('NotEmpty')
                ->addValidator('StringLength', false, array(4, 30))
                ->setDecorators($this->elementDecorators)
                ->setAttribs(array(
                    'style' => '',
                    'class' => '',
                    'maxlength' => '30',
                ))
                ->setLabel('Summoner:')
                ->setRequired(true);

        $regions = array(
            'BR' => 'BR', 'NA' => 'NA', 'EUW' => 'EUW', 'EUNE' => 'EUNE', 'KR' => 'KR',
            'LAS' => 'LAS', 'LAN' => 'LAN', 'OCE' => 'OCE', 'TR' => 'TR', 'RU' => 'RU',
        );
        $region = new Zend_Form_Element_Select('region', array('multiOptions' => $regions));
        $region->setLabel('Region:')
                ->setAttrib('class', '')
                ->setDecorators($this->elementDecorators)
                ->setRequired(true);

        $submit = new Zend_Form_Element_Submit('OK');
        $submit->setDecorators($this->elementDecorators)
                ->setAttribs(array(
                    'style' => '',
                    'class' => '',
                ))
                ->setRequired(true)
                ->removeDecorator('Label');

        $this->addElements(array($name, $region, $submit));
    }

}
