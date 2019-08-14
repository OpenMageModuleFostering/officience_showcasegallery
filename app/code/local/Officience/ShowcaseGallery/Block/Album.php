<?php

class Officience_ShowcaseGallery_Block_Album extends Mage_Core_Block_Template {

    public function getProducts(){
        $visibility = array(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH,
                            Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG);
        
        $model = Mage::getModel('catalog/product');
        $collection = $model->getCollection();
        $collection->addAttributeToFilter('visibility', $visibility);
        $collection->addAttributeToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED);
        $products = array();
        if(count($collection)){
            foreach($collection as $product){
                $model->load($product->getId());
                $products[$product->getId()] = $model->getName();
            }
            return $products;
        }
        return false;
    }
}


?>