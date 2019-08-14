<?php
class Officience_ShowcaseGallery_Model_Mysql4_Product extends Mage_Core_Model_Mysql4_Abstract {

    public function _construct() {
        $this->_init('showcasegallery/product', array('album_id','product_id'));
    }
   

}