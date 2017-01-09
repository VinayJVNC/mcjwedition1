<h2>Magento Magento_PaymentStaging Module</h2>

## Overview

The Magento_PaymentStaging module is a part of the staging functionality in Magento EE. It extends the Magento_Payment module for the staging preview functionality.

## Implementation Details

The Magento_PaymentStaging module restricts functionality of the Magento_Payment module in the staging preview mode. It shows only offline payment methods that are enabled.

## Dependencies

You can find the list of modules that have dependencies with the Magento_PaymentStaging module in the `require` object of the `composer.json` file. The file is located in the same directory as this `README` file.

## Extension Points

Extension points enable extension developers to interact with the Magento_PaymentStaging module. For more information about Magento extension mechanism, see [Magento plug-ins](http://devdocs.magento.com/guides/v2.1/extension-dev-guide/plugins.html).

[Magento dependency injection mechanism](http://devdocs.magento.com/guides/v2.1/extension-dev-guide/depend-inj.html) enables you to override the functionality of the Magento_PaymentStaging module.

## Additional information

For more Magento 2 developer documentation, see [Magento 2 Developer Documentation](http://devdocs.magento.com). Also, there you can track [backward incompatible changes made in a Magento EE mainline after the Magento 2.0 release](http://devdocs.magento.com/guides/v2.0/release-notes/changes/ee_changes.html).
