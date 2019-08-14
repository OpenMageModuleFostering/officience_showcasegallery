<?php

/**
 * @category   	Officience
 * @package		Officience_ShowcaseGallery
 * @author 		Officience <itdev.officience@gmail.com>
 */ 

class Officience_ShowcaseGallery_IndexController extends Mage_Core_Controller_Front_Action {

    public function indexAction() {
            $this->loadLayout();
            $this->getLayout()->getBlock('head')->setTitle($this->__('Showcase Gallery'));
            $this->renderLayout();
    }

}

?>
