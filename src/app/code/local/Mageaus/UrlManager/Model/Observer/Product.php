<?php
/**
 * Maho
 *
 * @category   Mageaus
 * @package    Mageaus_UrlManager
 * @copyright  Copyright (c) 2025 Maho (https://mahocommerce.com)
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 */

/**
 * Product Observer
 *
 * Handles disabled/not visible product redirects
 *
 * @category   Mageaus
 * @package    Mageaus_UrlManager
 */
class Mageaus_UrlManager_Model_Observer_Product
{
    /**
     * Check if product is disabled or not visible and redirect accordingly
     *
     * @param Varien_Event_Observer $observer
     * @return void
     */
    public function checkProductAccess(Varien_Event_Observer $observer): void
    {
        /** @var Mage_Core_Controller_Varien_Action $controllerAction */
        $controllerAction = $observer->getEvent()->getControllerAction();
        $productId = (int)$controllerAction->getRequest()->getParam('id');

        if (!$productId) {
            return;
        }

        /** @var Mageaus_UrlManager_Helper_Data $helper */
        $helper = Mage::helper('mageaus_urlmanager');

        try {
            // Load product
            $product = Mage::getModel('catalog/product')->load($productId);

            if (!$product->getId()) {
                return; // Product doesn't exist, let normal 404 handling work
            }

            // Check disabled products action
            $disabledProductAction = Mage::getStoreConfig('mageaus_urlmanager/auto_redirects/disabled_products');

            // Handle disabled products
            if ($product->getStatus() != Mage_Catalog_Model_Product_Status::STATUS_ENABLED) {
                if ($disabledProductAction === 'redirect_category') {
                    $this->redirectToCategory($product, $controllerAction);
                    return;
                } elseif ($disabledProductAction === 'show_suggestions') {
                    // Let it go to 404 page where suggestions will be shown
                    $controllerAction->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
                    $controllerAction->getResponse()->setHttpResponseCode(404);
                    return;
                }
            }

            // Handle not visible products
            $notVisibleAction = Mage::getStoreConfig('mageaus_urlmanager/auto_redirects/not_visible_products');
            if ($notVisibleAction == 1 && $product->getVisibility() == Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE) {
                $this->redirectToCategory($product, $controllerAction);
                return;
            }
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }

    /**
     * Redirect to product's category
     *
     * @param Mage_Catalog_Model_Product $product
     * @param Mage_Core_Controller_Varien_Action $controllerAction
     * @return void
     */
    protected function redirectToCategory(Mage_Catalog_Model_Product $product, Mage_Core_Controller_Varien_Action $controllerAction): void
    {
        // Get product's categories
        $categoryIds = $product->getCategoryIds();

        if (empty($categoryIds)) {
            return; // No category, let normal 404 handling work
        }

        // Get the deepest category (last one in the array is usually the most specific)
        $categoryId = end($categoryIds);

        try {
            /** @var Mage_Catalog_Model_Category $category */
            $category = Mage::getModel('catalog/category')->load($categoryId);

            if ($category->getId() && $category->getIsActive()) {
                $categoryUrl = $category->getUrl();

                // Strip query parameters from redirect URL
                $categoryUrl = strtok($categoryUrl, '?');

                Mage::log(sprintf(
                    'URL Manager: Redirecting disabled product #%d to category #%d: %s',
                    $product->getId(),
                    $category->getId(),
                    $categoryUrl
                ), Zend_Log::INFO, 'mageaus_urlmanager.log');

                // Perform 301 redirect
                $controllerAction->getResponse()
                    ->setRedirect($categoryUrl, 301)
                    ->sendResponse();
                exit;
            }
        } catch (Exception $e) {
            Mage::log('Failed to redirect to category: ' . $e->getMessage(), Zend_Log::ERR, 'mageaus_urlmanager.log');
        }
    }
}
