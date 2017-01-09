<h2>Magento_CatalogInventoryStaging module</h2>

## Overview

The Magento_CatalogInventoryStaging module is a part of the staging functionality in Magento EE. It extends the Magento_CatalogInventory module functionality adding the capability to monitor the "Stock Status" field on the Schedule Update form of a product. 

## Implementation Details

The Magento_CatalogInventoryStaging module adds the disabled "Stock Status" field on the Schedule Update form of a product during form rendering.

## Dependencies

You can find the list of modules that have dependencies with the Magento_CatalogInventoryStaging  module in the `require` object of the `composer.json` file. The file is located in the same directory as this `README` file.

## Extension Points

You can interact with the Magento_CatalogInventoryStaging module using the Magento extension mechanism, see [Magento plug-ins](http://devdocs.magento.com/guides/v2.1/extension-dev-guide/plugins.html).

[Magento dependency injection mechanism](http://devdocs.magento.com/guides/v2.1/extension-dev-guide/depend-inj.html) enables you to override the functionality of the Magento_CatalogInventoryStaging module.

## Additional information

For more Magento 2 developer documentation, see [Magento 2 Developer Documentation](http://devdocs.magento.com). Also, there you can track [backward incompatible changes made in a Magento EE mainline after the Magento 2.0 release](http://devdocs.magento.com/guides/v2.0/release-notes/changes/ee_changes.html).