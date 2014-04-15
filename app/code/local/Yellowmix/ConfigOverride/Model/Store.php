<?php
/**
 * Override Config Value Retrieve Method
 *
 * @author      Valérie Tching <valerie.tching@yellowmix.com>
 * @category    Yellowmix
 * @package     Yellowmix_ConfigOverride
 * @copyright   Copyright (c) 2014 Yellowmix (http://www.yellowmix.com)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Yellowmix_ConfigOverride_Model_Store extends Mage_Core_Model_Store
{

    private $_active        = 1;
    private $_environment   = 'development';
    private $_config        = null;

    protected function _loadOverrideConfigFile()
    {
        $configFilePath = Mage::getBaseDir('etc') . DS . 'local.csv';
        $this->_config = array();
        if (file_exists($configFilePath)) {
            if ($handle = fopen($configFilePath, "r")) {
                $header = fgetcsv($handle);
                while ($data = fgetcsv($handle)) {

                    $path = $data[0];
                    $environment = $data[1];
                    $website = $data[2];
                    $store = $data[3];
                    $value = $data[4];

                    if ($path == "active") { 
                        // Special value "active"
                        $this->_active = (int)$value;
                    } else if ($path == "environment") {
                        // Special value "environment"
                        $this->_environment = $value;
                    } else if (empty($environment) || $environment == $this->_environment) {

                        if ((empty($website) || $website == $this->getWebsiteId()) &&
                            (empty($store) || $store == $this->getStoreId())) {
                            // For global or specific environment
                            if (preg_match('/^(.*){(.*)}(.*)$/', $value, $matches)
                                       && isset($this->_config[$matches[2]])) {
                                // If the value is a variable
                                $this->_config[$path] = $matches[1] . $this->_config[$matches[2]] . $matches[3];
                            } else {
                                $this->_config[$path] = $value;
                            }
                        }
                    }
                }
                fclose($handle);
            }
        }
    }

    /**
     * Retrieve store configuration data
     *
     * @param   string $path
     * @param   string $scope
     * @return  string|null
     */
    public function getConfig($path)
    {
        if ($this->_config == null)
            $this->_loadOverrideConfigFile();

        if (isset($this->_config[$path]))
            return $this->_config[$path];

        return parent::getConfig($path);
    }
}
