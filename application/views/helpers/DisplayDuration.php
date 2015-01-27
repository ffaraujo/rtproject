<?php

class Zend_View_Helper_DisplayDuration {

    public function DisplayDuration($time = NULL) {
        if(is_numeric($time))
            return round($time / 60) . ' min';
        else
            return '';
    }

}

?>
