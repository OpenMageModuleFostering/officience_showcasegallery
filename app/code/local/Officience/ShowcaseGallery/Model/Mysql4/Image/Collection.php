<?php
class Officience_ShowcaseGallery_Model_Mysql4_Image_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
	
	public function _construct(){
		parent::_construct();
		$this->_init('showcasegallery/image');
	}
}