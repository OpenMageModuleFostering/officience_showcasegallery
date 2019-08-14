<?php

class Officience_ShowcaseGallery_Block_Gallery extends Mage_Core_Block_Template {

    protected   $_albumDefaultId = null;
    protected   $_isowner = false;
    protected   $_customerId = null;
    
    public function getAlbumsByCustomerId(){
        if($albumId = $this->getRequest()->getParam('id')){
            $albumObj = Mage::getModel('showcasegallery/album')->load($albumId);
            if($albumObj->getId()){
                $customerId = $albumObj->getCustomerId();
                if($customerId == Mage::getSingleton('customer/session')->getCustomer()->getId()){
                    $this->_isowner = true;
                }
                else{
                    $this->_customerId = $customerId;
                }
                $albumCollection = Mage::getModel('showcasegallery/album')->getCollection()->addFieldToFilter('customer_id', $customerId );
                if($this->getRequest()->getParam('id'))
                        $this->_albumDefaultId = $this->getRequest()->getParam('id');
                else
                        $this->_albumDefaultId = $albumCollection->getLastItem()->getId();
                return $albumCollection;
            }else{
                //if album not exist
                return false;
            }
        }
        else{
            $customerId = Mage::getSingleton('customer/session')->getCustomer()->getId();
            $albumCollection = Mage::getModel('showcasegallery/album')->getCollection()->addFieldToFilter('customer_id', $customerId );
            if(count($albumCollection)){
                $this->_albumDefaultId = $albumCollection->getLastItem()->getId();
                $this->_isowner = true;
                return $albumCollection;
            }
            return false;        
        }
    }
    
    public function IsOwner(){
        return $this->_isowner;
    }
    
    public function getDefaultAlbumId(){
        return $this->_albumDefaultId ;
    }
    
    public function getCustomerName()
    {
        if($this->_customerId){
            $cusObj = Mage::getModel('customer/customer')->load($this->_customerId);
            
            return $cusObj->getName();
        }
        return Mage::getSingleton('customer/session')->getCustomer()->getName();
    }
    
    public function getAlbumThumb($albumId){
        $imageObj = Mage::getModel('showcasegallery/image');
        $imageCollection = $imageObj->getCollection()->addFieldToFilter('album_id',$albumId);
        $lastImage = $imageCollection->getLastItem();
        $imageDir = $this->getImageDir($lastImage->getId(),'thumb');
        return $imageDir;
    }
    
    public function getAlbumDefaultImage($albumId){
        $imageObj = Mage::getModel('showcasegallery/image');
        $imageCollection = $imageObj->getCollection()->addFieldToFilter('album_id',$albumId);
        $lastImage = $imageCollection->getLastItem();
        $imageDir = $this->getImageDir($lastImage->getId());
        return $imageDir;
    }
    
    public function getImageDir($filename, $type = null){
        $path = Mage::getBaseDir('media') . DS . 'gallery';
        $dispretion = Mage_Core_Model_File_Uploader::getDispretionPath($filename.'.jpg');
        $dispretion = str_replace('\\', '/', $dispretion);
        if($type == 'thumb'){
            $dir = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'showcasegallery'.$dispretion.'/'.$filename.'_thumb.jpg';
        }
        else{
            $dir = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'showcasegallery'.$dispretion.'/'.$filename.'.jpg';
        }
        return $dir;
    }
    
    public function getAlbumContent($albumId){
        $albumObj = Mage::getModel('showcasegallery/album')->load($albumId);
        if($albumObj->getId()){
            $albumArray = array(
                    'album_id'=>$albumObj->getId(),
                    'album_title'=> $albumObj->getAlbumTitle(),
                    'album_description' => $albumObj->getAlbumDescription(),
                    'album_main_image'=> $this->getAlbumDefaultImage($albumObj->getId())
                );
            return $albumArray;
        }
        else{
            return false;
        }
    }
    
    public function getAlbumImagesList($albumId){
        $imageCollection = Mage::getModel('showcasegallery/image')->getCollection()->addFieldToFilter('album_id',$albumId);
        //check image in album
        if(count($imageCollection)){
            $imageList = array();
            foreach ($imageCollection as $image){
                //$imageId = $image->getId();
                $imageList[$image->getId()]['id'] = $image->getId();
                $imageList[$image->getId()]['thumb'] = Mage::getBlockSingleton('showcasegallery/gallery')->getImageDir($image->getId(),'thumb');
            }
            return $imageList;
        }
        else{
            return false;
        }
    }    
    
    public function getAlbumProductList($albumId){
        $productCollection = Mage::getModel('showcasegallery/product')->getCollection()->addFieldToFilter('album_id',$albumId);
        $productUsedList = array();
        if(count($productCollection)){
            foreach($productCollection as $product){
                $productId = $product->getProductId();
                $model = Mage::getModel('catalog/product')->load($productId);
                if($model->getId()){
                    $productUsedList[$productId]['name']= $model->getName();
                    $productUsedList[$productId]['url']= $model->getProductUrl();
                    $productUsedList[$productId]['image']= $model->getSmallImageUrl();
                }
            }
            if(count($productUsedList)){
                return $productUsedList;
            }
        }
        return false;
    }
        
}

?>