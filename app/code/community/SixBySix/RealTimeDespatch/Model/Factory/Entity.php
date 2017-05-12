<?php

/**
 * Entity Factory.
 */
class SixBySix_RealTimeDespatch_Model_Factory_Entity
{
    const ENTITY_PRODUCT   = 'Product';
    const ENTITY_ORDER     = 'Order';
    const ENTITY_INVENTORY = 'Inventory';
    const ENTITY_SHIPMENT  = 'Shipment';
    const ENTITY_RETURN    = 'Return';

    /**
     * Retrieves an entity.
     *
     * @param string $type
     * @param mixed  $reference
     */
    public function retrieve($type, $reference)
    {
        switch ($type) {
            case self::ENTITY_PRODUCT:
            case self::ENTITY_INVENTORY:
                return Mage::getModel('catalog/product')->loadByAttribute('sku', $reference);
            break;
            case self::ENTITY_ORDER:
                return Mage::getModel('sales/order')->loadByAttribute('increment_id', $reference);
            break;
            case self::ENTITY_SHIPMENT:
                return Mage::getModel('sales/order_shipment')->load($reference, 'increment_id');
            break;
            case self::ENTITY_RETURN:
                return Mage::getModel('enterprise_rma/rma')->load($reference, 'increment_id');
                break;
            default:
                throw new Exception('Invalid Entity Type');
            break;
        }
    }

    /**
     * Retrieves an entity url.
     *
     * @param string $type
     * @param mixed  $reference
     */
    public function retrieveAdminUrl($type, $reference)
    {
        switch ($type) {
            case self::ENTITY_PRODUCT:
            case self::ENTITY_INVENTORY:
                return $this->_retrieveAdminUrl(
                    Mage::helper('adminhtml')->getUrl('adminhtml/catalog_product/edit/') .'id/',
                    $this->retrieve($type, $reference)
                );
            break;
            case self::ENTITY_ORDER:
                return $this->_retrieveAdminUrl(
                    Mage::helper('adminhtml')->getUrl('adminhtml/sales_order/view/') .'order_id/',
                    $this->retrieve($type, $reference)
                );
            break;
            case self::ENTITY_SHIPMENT:
                return $this->_retrieveAdminUrl(
                    Mage::helper('adminhtml')->getUrl('adminhtml/sales_shipment/view/') .'shipment_id/',
                    $this->retrieve($type, $reference)
                );
            break;
            case self::ENTITY_RETURN:
                return $this->_retrieveAdminUrl(
                    Mage::helper('adminhtml')->getUrl('adminhtml/rma/edit/') .'id/',
                    $this->retrieve($type, $reference)
                );
                break;
            default:
                throw new Exception('Invalid Entity Type');
            break;
        }
    }

    /**
     * Returns the admin url.
     *
     * @param string $url
     * @param mixed  $entity
     *
     * @return string
     */
    protected function _retrieveAdminUrl($url, $entity = null)
    {
        if ( ! $entity) {
            return '';
        }

        return $url.$entity->getId();
    }
}