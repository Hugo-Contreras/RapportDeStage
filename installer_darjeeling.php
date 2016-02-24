<?php
/**
 * Update type 360 attribute
 *
 * @category  Darjeeling
 * @package   Darjeeling_Core
 * @copyright 2013 Darjeeling
 */
/* @var $this Smile_DarjeelingCore_Model_Resource_Eav_Mysql4_Setup */
$installer = $this;
$installer->startSetup();

$entityTypeId     = $installer->getEntityTypeId('catalog_category');
$attributeSetId   = $installer->getDefaultAttributeSetId($entityTypeId);
$attributeGroupId = $installer->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);
// Declaration of the needed attribute with data
$installer->addAttribute('catalog_category', 'promo_img_link',  array(
    'type'     => 'varchar',
    'label'    => 'Link for promotion image',
    'input'    => 'text',
    'global'   => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false
));

$attributeId = $installer->getAttributeId($entityTypeId, 'promo_img_link');
// Insertion in the database
$installer->run("
INSERT INTO `{$installer->getTable('catalog_category_entity_varchar')}`
(`entity_type_id`, `attribute_id`, `entity_id`, `value`)
    SELECT '{$entityTypeId}', '{$attributeId}', `entity_id`, NULL
        FROM `{$installer->getTable('catalog_category_entity')}`;
");


//this will set data of your custom attribute for root category
Mage::getModel('catalog/category')
    ->load(1)
    ->setImportedCatId(0)
    ->setInitialSetupFlag(true)
    ->save();

//this will set data of your custom attribute for default category
Mage::getModel('catalog/category')
    ->load(2)
    ->setImportedCatId(0)
    ->setInitialSetupFlag(true)
    ->save();

$installer->endSetup();
