<?php

/**
  Modules Magento Gallery - Officience - Zoom NGUYEN - University Bordeaux I - France
  Website:  www.officience.com  
  Email : frtlupsvn@gmail.com
 */
$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("
    
    DROP TABLE IF EXISTS {$this->getTable('offy_album')};
    CREATE TABLE {$this->getTable('offy_album')} (
    `album_id` int(11) unsigned NOT NULL auto_increment,    
    `customer_id` int(11) unsigned NOT NULL,    
    `album_title` varchar(255) NOT NULL default '',
    `album_description` text NOT NULL default '',
    `album_status` smallint(6) NOT NULL default '0',
    `created_at` datetime NULL,
    `updated_at` datetime NULL,
    PRIMARY KEY (`album_id`)
    )
    ENGINE=InnoDB DEFAULT CHARSET=utf8;
    
    DROP TABLE IF EXISTS {$this->getTable('offy_image')};
    CREATE TABLE {$this->getTable('offy_image')} (
    `image_id` int(11) unsigned NOT NULL auto_increment,    
    `album_id` int(11) unsigned NOT NULL,
    `image_status` smallint(6) NOT NULL default '0',
    `created_at` datetime NULL,
    `updated_at` datetime NULL,
    PRIMARY KEY (`image_id`)
    )
    ENGINE=InnoDB DEFAULT CHARSET=utf8;
    
    DROP TABLE IF EXISTS {$this->getTable('offy_album_product')};
    CREATE TABLE {$this->getTable('offy_album_product')} (
    `album_id` int(11) unsigned NOT NULL,    
    `product_id` int(11) unsigned NOT NULL,    
    PRIMARY KEY (`album_id`,`product_id`)
    )
    ENGINE=InnoDB DEFAULT CHARSET=utf8;
    
    ");
$installer->endSetup();
?>