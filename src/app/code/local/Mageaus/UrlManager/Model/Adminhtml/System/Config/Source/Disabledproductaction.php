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
 * Disabled Product Action Source Model
 *
 * @category   Mageaus
 * @package    Mageaus_UrlManager
 */
class Mageaus_UrlManager_Model_Adminhtml_System_Config_Source_Disabledproductaction
{
    public const ACTION_NONE = '';
    public const ACTION_REDIRECT_CATEGORY = 'redirect_category';
    public const ACTION_SHOW_SUGGESTIONS = 'show_suggestions';

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        return [
            ['value' => self::ACTION_NONE, 'label' => Mage::helper('mageaus_urlmanager')->__('No Action (Show 404)')],
            ['value' => self::ACTION_REDIRECT_CATEGORY, 'label' => Mage::helper('mageaus_urlmanager')->__('Redirect to Category')],
            ['value' => self::ACTION_SHOW_SUGGESTIONS, 'label' => Mage::helper('mageaus_urlmanager')->__('Show Product Suggestions via Meilisearch')],
        ];
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            self::ACTION_NONE => Mage::helper('mageaus_urlmanager')->__('No Action (Show 404)'),
            self::ACTION_REDIRECT_CATEGORY => Mage::helper('mageaus_urlmanager')->__('Redirect to Category'),
            self::ACTION_SHOW_SUGGESTIONS => Mage::helper('mageaus_urlmanager')->__('Show Product Suggestions via Meilisearch'),
        ];
    }
}
