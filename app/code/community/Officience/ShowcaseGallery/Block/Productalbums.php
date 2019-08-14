<?php
/**
 * @category   	Officience
 * @package		Officience_ShowcaseGallery
 * @author 		Officience <itdev.officience@gmail.com>
 */ 
class Officience_ShowcaseGallery_Block_Productalbums extends Mage_Core_Block_Template {

    public function getProductId()
    {
        if ($product = Mage::registry('current_product')) {
            return $product->getId();
        }
        return false;
    }
    
    public function getAlbums(){
        if($productId = $this->getProductId()){
            $albumCollection = Mage::getModel('showcasegallery/product')->getCollection()->addFieldToFilter('product_id',$productId);
            $albums = array();
            if(count($albumCollection)){
                foreach($albumCollection as $album){
                    $albums[$album->getAlbumId()]['image'] = Mage::getBlockSingleton('showcasegallery/gallery')->getAlbumThumb($album->getAlbumId());
                    $albums[$album->getAlbumId()]['title'] = $this->getAlbumTitlebyId($album->getAlbumId());
                }
                if(count($albums)){
                    return $albums;
                }
            }
        }
        return false;
    }
    
    public function getAlbumTitlebyId($albumId){
        $model = Mage::getModel('showcasegallery/album')->load($albumId);
        if($model->getId()){
            return $model->getAlbumTitle();
        }
        return null;
    }
}


?>