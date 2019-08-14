<?php

/*
  Modules Magento Gallery - Zoom NGUYEN - University Bordeaux I - France
  Email : frtlupsvn@gmail.com
 */

class Officience_ShowcaseGallery_AlbumController extends Mage_Core_Controller_Front_Action {
    
    public function addAction(){
        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
            $this->loadLayout();
            $this->getLayout()->getBlock('head')->setTitle($this->__('Showcase Gallery'));
            $this->renderLayout();
        } else {
            $this->_redirect('customer/account/login/');
        }
    }
    
    public function saveAction() {
        $errors = null;
        $session = Mage::getSingleton('core/session');
        if ($this->getRequest()->isPost() && count($_FILES)) {
            //check file input
            $allowed_extensions = array("image/jpg", "image/jpeg", "image/gif", 'image/png');
            foreach ($_FILES as $key=>$value){
                if(!in_array(strtolower($_FILES[$key]['type']),$allowed_extensions)){
                    $errors = 'The uploaded file is not supported file type.';
                }
            }
            if(!$errors){
                $albumObj = Mage::getModel('showcasegallery/album');
                $albumObj->setData(array(
                        'album_title'=>$_POST['album_title'],
                        'album_description'=>$_POST['album_description'],
                        'album_status'=>1,
                        'customer_id' => Mage::getSingleton('customer/session')->getCustomer()->getId()
                        ));
                $albumObj->setCreatedAt(now())
                            ->setUpdatedAt(now());
                $albumObj->save();
                //save product
                foreach($_POST['products'] as $product){
                    $productObj = Mage::getModel('showcasegallery/product');
                    $productObj->setData(array(
                            'album_id'=>$albumObj->getId(),
                            'product_id'=> $product));
                    $productObj->save();
                }
            }
            else{
                $session->addError($this->__($errors));
            }
        }else {
            $errors = 'Please input required fields';
            $session->addError($this->__($errors));
        }
        if($errors){
            $this->_redirect('showcasegallery/album/albummanagement');
        }
        $this->_redirect('showcasegallery/index');
    }
    
    public function loadAction(){
        $results = array();
        $error = null;
        $albumId = $this->getRequest()->getParam('id');
        $albumObj = Mage::getModel('showcasegallery/album')->load($albumId);
        //check album exist
        if($albumObj->getId()){
            $albumArray = array(
                'album_id'=>$albumObj->getId(),
                'album_title'=> $albumObj->getAlbumTitle(),
                'album_description' => $albumObj->getAlbumDescription(),
                'album_main_image'=> Mage::getBlockSingleton('showcasegallery/gallery')->getAlbumDefaultImage($albumObj->getId())
            );
            $results['album'] = $albumArray;
            //get images list
            $imageCollection = Mage::getModel('showcasegallery/image')->getCollection()->addFieldToFilter('album_id',$albumId);
            if(count($imageCollection)){
                $imageList = array();
                foreach ($imageCollection as $image){
                    //$imageId = $image->getId();
                    $imageList[$image->getId()]['id'] = $image->getId();
                    $imageList[$image->getId()]['thumb'] = Mage::getBlockSingleton('showcasegallery/gallery')->getImageDir($image->getId(),'thumb');
                }
                $results['imageList'] = $imageList;
            }
            else{
                $error = Mage::helper('showcasegallery')->__('No Image in this album');
            }
            // get produc used list
            $productList = Mage::getBlockSingleton('showcasegallery/gallery')->getAlbumProductList($albumObj->getId());
            if($productList){
                $results['productList'] = $productList;
            }
            else{
                $error = Mage::helper('showcasegallery')->__('No Product in this album');
            }
            
        }
        else{
            $error = Mage::helper('showcasegallery')->__('Album does not exist');
        }
        if(!$error){
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($results));
        }
        else{
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode(array('error'=> $error)));
        }
    }

    public function deleteAction(){
        $albumId = $this->getRequest()->getParam('id');
        $albumObj = Mage::getModel('showcasegallery/album')->load($albumId);
        if($albumObj->getId()){
            $albumObj->delete();
        }
        else{
            Mage::getSingleton('core/session')->addError(Mage::helper('showcasegallery')->__('Album does not exist'));
        }
        $this->_redirect('showcasegallery/index/index');
    }
}