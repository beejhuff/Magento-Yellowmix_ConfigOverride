Magento-Yellowmix_ConfigOverride
================================

This Magento module overrides the values from core_config_data table without modifying the database.
Useful when you often dump the database from production environment and import it in your local environment without having to change entries in core_config_data such as 'web/unsecure/base_url' and so on.

The module simply rewrites Mage_Core_Model_Store::getConfig and read values from a CSV file instead (app/etc/local.csv).

Use with modman
 
```modman clone https://github.com/vtching/Magento-Yellowmix_ConfigOverride.git```

After clone, there is no local.csv file located at app/etc, but a local.csv.sample to help you start override config values.

```cp app/etc/local.csv.sample app/etc/local.csv```

The syntax is a path/value style, and you can also specify the target store or website.
There's also the possibility to create environments (development, integration, etc...).
