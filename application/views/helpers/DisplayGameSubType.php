<?php

class Zend_View_Helper_DisplayGameSubType {

    public function DisplayGameSubType($subType = NULL) {
        if (empty($subType))
            return '';
        switch ($subType) {
            case 'BOT':
            case 'BOT_3x3':
                $typeForm = 'Coop vs AI';
                break;
            case 'RANKED_SOLO_5x5':
            case 'RANKED_TEAM_3x3':
            case 'RANKED_TEAM_5x5':
                $typeForm = 'Ranked';
                break;
            default:
                $typeForm = 'Normal';
                break;
        }
        
        return $typeForm;
    }

}

?>
