<?php
/**
 * Maho
 *
 * @category   Mageaus
 * @package    Mageaus_UrlManager
 * @copyright  Copyright (c) 2025 Maho (https://mahocommerce.com)
 */

/**
 * Redirect List Block
 *
 * @category   Mageaus
 * @package    Mageaus_UrlManager
 */
class Mageaus_UrlManager_Block_List extends Mage_Core_Block_Template
{
    protected $_collection;

    /**
     * Get collection
     *
     * @return Mageaus_UrlManager_Model_Resource_Redirect_Collection
     */
    public function getCollection()
    {
        if ($this->_collection === null) {
            $this->_collection = Mage::getResourceModel('mageaus_urlmanager/redirect_collection');

            // Filter by status if field exists
            $this->_collection->addFieldToFilter('status', 1);
            // Order by created_at descending
            $this->_collection->setOrder('created_at', 'DESC');

            // Setup pagination
            $this->_collection->setPageSize($this->getItemsPerPage());
            $this->_collection->setCurPage($this->getCurrentPage());
        }

        return $this->_collection;
    }

    /**
     * Get items per page
     *
     * @return int
     */
    public function getItemsPerPage(): int
    {
        return (int) Mage::getStoreConfig('mageaus_urlmanager/general/items_per_page') ?: 10;
    }

    /**
     * Get current page number
     *
     * @return int
     */
    public function getCurrentPage(): int
    {
        return (int) $this->getRequest()->getParam('p', 1);
    }

    /**
     * Get item URL
     *
     * @param Mageaus_UrlManager_Model_Redirect $item
     * @return string
     */
    public function getItemUrl($item): string
    {
        return $this->getUrl('*/*/view', ['id' => $item->getId()]);
    }

    /**
     * Prepare layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        // Setup pager
        $pager = $this->getLayout()->createBlock('page/html_pager', 'redirect.pager');
        $pager->setCollection($this->getCollection());
        $this->setChild('pager', $pager);

        return $this;
    }

    /**
     * Get pager HTML
     *
     * @return string
     */
    public function getPagerHtml(): string
    {
        return $this->getChildHtml('pager');
    }
}
