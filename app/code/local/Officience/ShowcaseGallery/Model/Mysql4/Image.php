<?php

class Officience_ShowcaseGallery_Model_Mysql4_Image extends Mage_Core_Model_Mysql4_Abstract {

    public function _construct() {

        $this->_init('showcasegallery/image', 'image_id');
    }
}

