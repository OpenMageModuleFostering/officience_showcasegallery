<?php

/*
  Modules Magento Gallery - Zoom NGUYEN - University Bordeaux I - France
  Email : frtlupsvn@gmail.com
 */

class Officience_ShowcaseGallery_IndexController extends Mage_Core_Controller_Front_Action {

    public function indexAction() {
            $this->loadLayout();
            $this->getLayout()->getBlock('head')->setTitle($this->__('Showcase Gallery'));
            $this->renderLayout();
    }

}

?>
