<?php

class Zend_View_Helper_DisplayCS {

    public function DisplayCS($mk = NULL, $nk = NULL) {
        if (!empty($mk) && !empty($nk))
            return $mk + $nk;
        elseif (!empty ($mk))
            return $mk;
        elseif (!empty ($nk))
            return $nk;
        else
            return '';
    }

}

?>
