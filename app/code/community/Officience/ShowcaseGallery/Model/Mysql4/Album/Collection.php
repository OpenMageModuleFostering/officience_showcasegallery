<?php
/**
 * @category   	Officience
 * @package		Officience_ShowcaseGallery
 * @author 		Officience <itdev.officience@gmail.com>
 */ 

class Officience_ShowcaseGallery_Model_Mysql4_Album_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
	
	public function _construct(){
		parent::_construct();
		$this->_init('showcasegallery/album');
	}
}