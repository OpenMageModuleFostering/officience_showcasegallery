<?php
/**
 * @category   	Officience
 * @package		Officience_ShowcaseGallery
 * @author 		Officience <itdev.officience@gmail.com>
 */ 
class Officience_ShowcaseGallery_Model_Mysql4_Album extends Mage_Core_Model_Mysql4_Abstract {

    public function _construct() {
        $this->_init('showcasegallery/album', 'album_id');
    }
    protected function _afterSave(Mage_Core_Model_Abstract $object){
        $album_id = $object->getAlbumId();
        try{
            foreach ($_FILES as $key=>$value){
                $imageObj = Mage::getModel('showcasegallery/image');
                $imageObj->setData(array("album_id" => $album_id,
                            "image_status" => 1,
                            "created_at" => now(),
                            "updated_at" => now()));
                $imageObj->save();
                $image_id = $imageObj->getId();
                $uploadresult = $this->uploadImage($image_id,$key);
                if(!$uploadresult){
                    $object->delete();
                    Mage::getSingleton('core/session')->addError(Mage::helper('showcasegallery')->__('Error when upload files'));
                }
            }
        } catch (Exception $e){
            Mage::getSingleton('core/session')->addError($e);
        }
        return parent::_afterSave($object);
    }
    
    protected function _afterDelete(Mage_Core_Model_Abstract $object){
        $album_id = $object->getAlbumId();
        $this->_getReadAdapter()->delete($this->getTable('showcasegallery/image'),'album_id ='. $album_id);
        $this->_getReadAdapter()->delete($this->getTable('showcasegallery/product'),'album_id ='. $album_id);
        return parent::_afterDelete($object);
    }
    
    public function uploadImage($filename, $key){
            $urlroot = Mage::getUrl('media/gallery');
            $ImageName = $filename;
            $ImageSize = $_FILES[$key]['size']; // Obtain original image size
            $TempSrc = $_FILES[$key]['tmp_name']; // Tmp name of image file stored in PHP tmp folder
            $ImageType = $_FILES[$key]['type']; //Obtain file type, returns "image/png", image/jpeg, text/plain etc.
            
            list($CurWidth, $CurHeight) = getimagesize($TempSrc);
            
            if (isset($ImageName) and (file_exists($TempSrc))) {
                $upload = new Varien_File_Uploader($key);
                $upload->setAllowedExtensions(array('jpg', 'jpeg', 'png', 'gif'));
                $upload->setAllowRenameFiles(true);
                $upload->setFilesDispersion(true);
                $path = Mage::getBaseDir('media') . DS . 'showcasegallery';
                $upload->save($path, str_replace(' ', '', $ImageName.'.jpg'));

                $uploadedFile = $path.$upload->getUploadedFileName();
                switch (strtolower($ImageType)) {
                    case 'image/png':
                        $UsedImg = imagecreatefrompng($uploadedFile);
                        break;
                    case 'image/gif':
                        $UsedImg = imagecreatefromgif($uploadedFile);
                        break;
                    case 'image/jpg':
                    case 'image/jpeg':
                        $UsedImg = imagecreatefromjpeg($uploadedFile);
                        break;
                }
                $DesImg = str_replace('.jpg','_thumb.jpg', $uploadedFile);
                $thumb = $this->rsImage($CurWidth, $CurHeight,75,$DesImg,$UsedImg);
                if(!$thumb){
                    return false;
                }
                return true;
            }
            return false;
    
    }
    public function rsImage($CurWidth, $CurHeight,$MaxSize,$destImg,$srcImg){
        //Check Image size is not 0
        if ($CurWidth <= 0 || $CurHeight <= 0) {
            return false;
        }
        $ratio = max($MaxSize / $CurWidth, $MaxSize / $CurHeight);
        $CurHeight = ceil($MaxSize / $ratio);
        $x = ($CurWidth - $MaxSize / $ratio) / 2;
        $CurWidth = ceil($MaxSize / $ratio);
        
        $NewImage = imagecreatetruecolor($MaxSize, $MaxSize);
        $backgroundColor = imagecolorallocate($NewImage, 255, 255, 255);
        imagefill($NewImage, 0, 0, $backgroundColor);
        if (imagecopyresampled($NewImage, $srcImg, 0, 0, $x, 0, $MaxSize, $MaxSize, $CurWidth, $CurHeight)){
        //if (imagecopyresampled($NewImage, $srcImg, 0, 0, 0, 0, 75, 75, $CurWidth, $CurHeight)){
            imagejpeg($NewImage, $destImg);
            //Destroy image, frees up memory	
            if (is_resource($NewImage)) {
                imagedestroy($NewImage);
            }
            return true;
        }
        return false;
    }

}