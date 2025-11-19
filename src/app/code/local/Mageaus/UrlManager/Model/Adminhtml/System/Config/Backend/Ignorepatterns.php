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
 * Backend model for validating ignore patterns
 *
 * @category   Mageaus
 * @package    Mageaus_UrlManager
 */
class Mageaus_UrlManager_Model_Adminhtml_System_Config_Backend_Ignorepatterns extends Mage_Core_Model_Config_Data
{
    /**
     * Validate and clean the ignore patterns before saving
     *
     * @return Mage_Core_Model_Abstract
     * @throws Mage_Core_Exception
     */
    protected function _beforeSave()
    {
        $value = $this->getValue();

        if (empty($value)) {
            return parent::_beforeSave();
        }

        // Parse patterns (support both comma-separated and newline-separated)
        $patterns = $this->parsePatterns($value);

        // Validate each pattern
        $validatedPatterns = [];
        $errors = [];

        foreach ($patterns as $pattern) {
            // Check if pattern is safe (no control characters, reasonable length)
            if (strlen($pattern) > 255) {
                $errors[] = "Pattern too long (max 255 characters): " . substr($pattern, 0, 50) . "...";
                continue;
            }

            // Check for control characters (except newlines/tabs which are stripped)
            if (preg_match('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', $pattern)) {
                $errors[] = "Pattern contains invalid control characters: " . $pattern;
                continue;
            }

            // Pattern is valid
            $validatedPatterns[] = $pattern;
        }

        // If there are errors, throw exception
        if (!empty($errors)) {
            $errorMessage = "Invalid patterns detected:\n" . implode("\n", $errors);
            Mage::throwException($errorMessage);
        }

        // Store validated patterns (one per line for consistency)
        $cleanValue = implode("\n", $validatedPatterns);
        $this->setValue($cleanValue);

        // Log the configuration change
        Mage::log(
            sprintf('404 Ignore Patterns updated: %d patterns configured', count($validatedPatterns)),
            Zend_Log::INFO,
            'mageaus_urlmanager.log'
        );

        return parent::_beforeSave();
    }

    /**
     * Parse patterns from input (supports comma-separated or newline-separated)
     *
     * @param string $value
     * @return array
     */
    protected function parsePatterns($value)
    {
        // Split by newlines first
        $lines = preg_split('/[\r\n]+/', $value, -1, PREG_SPLIT_NO_EMPTY);

        $patterns = [];
        foreach ($lines as $line) {
            // Check if line contains commas (comma-separated format)
            if (strpos($line, ',') !== false) {
                $parts = explode(',', $line);
                foreach ($parts as $part) {
                    $trimmed = trim($part);
                    if ($trimmed !== '') {
                        $patterns[] = $trimmed;
                    }
                }
            } else {
                // Single pattern per line
                $trimmed = trim($line);
                if ($trimmed !== '') {
                    $patterns[] = $trimmed;
                }
            }
        }

        // Remove duplicates and return
        return array_unique($patterns);
    }
}
