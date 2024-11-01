=== ShipEngine Shipping Quotes ===
Contributors: enituretechnology
Tags: eniture. ShipEngine,parcel rates, parcel quotes, shipping estimates
Requires at least: 6.4
Tested up to: 6.6
Stable tag: 1.0.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Real-time small package (parcel) shipping rates from ShipEngine. Fifteen day free trial.

== Description ==

Dynamically retrieves your discounted shipping rates and displays the results in the WooCommerce shopping cart.

**Key Features**

* Includes negotiated shipping rates in the shopping cart and on the checkout page.
* Ability to control which UPS small package services to display
* Support for variable products.
* Option to mark up shipping rates by a set dollar amount or by a percentage.

**Requirements**

* WooCommerce 6.4 or newer.
* A license from Eniture Technology.

== Installation ==

**Installation Overview**

A more comprehensive and graphically illustrated set of instructions can be found on the *Documentation* tab at
[eniture.com](https://eniture.com/woocommerce-shipengine-shipping-rates/).

**1. Install and activate the plugin**
In your WordPress dashboard, go to Plugins => Add New. Search for "ShipEngine Shipping Quotes", and click Install Now.
After the installation process completes, click the Activate Plugin link to activate the plugin.

**2. Get a license from Eniture Technology**
Go to [Eniture Technology](https://eniture.com/woocommerce-shipengine-shipping-rates/) and pick a
subscription package. When you complete the registration process you will receive an email containing your license key and
your login to eniture.com. Save your login information in a safe place. You will need it to access your customer dashboard
where you can manage your licenses and subscriptions. A credit card is not required for the free trial. If you opt for the free
trial you will need to login to your [Eniture Technology](http://eniture.com) dashboard before the trial period expires to purchase
a subscription to the license. Without a paid subscription, the plugin will stop working once the trial period expires.

**3. Establish the connection**
Go to WooCommerce => Settings => ShipEngine. Use the *Connection* link to verify connection.

**5. Select the plugin settings**
Go to WooCommerce => Settings => ShipEngine. Use the *Quote Settings* link to enter the required information and choose
the optional settings.

**6. Enable the plugin**
Go to WooCommerce => Settings => Shipping. Click edit for shipping zone you want to add ShipEngine Shipping Rates. Click Add Shipping Method. Select ShipEngine Shipping Rates from dropdown. Click "Add Shipping Method" button. 

== External Service Usage ==

This plugin relies on a 3rd party service provided by Eniture Technology to fetch live shipping rates and perform other related functionalities. Please review the following information regarding the usage of this service:

1. **Functionality**:
   - This plugin displays live shipping rates of multiple shipping carriers such as UPS, FedEx, DHL, etc., on the cart and checkout pages of your WooCommerce store.
   - To achieve this functionality, the plugin sends cart data and API credentials provided by the user on the connection settings page to Eniture's Webservices.

2. **Data Transmission**:
   - During the live shipping rate calculation process, the plugin sends essential cart information, such as product details, quantities, and shipping destination, to Eniture Webservices. This data is used to accurately quote shipping rates from various carriers.
   - The data transmission occurs during the execution of a specific function within the plugin's code. Specifically, the request is sent on line 41 of the `shipengine-eniture/http/en-curl.php` file.
   - Additionally, the same function mentioned above is used to test the API credentials and retrieve the lowest distance between multiple warehouses.

4. **Service Provider**:
   - The external service is provided by Eniture Technology.
   - https://www.eniture.com/

It's important to note that the usage of external services is properly documented here to ensure transparency and legal compliance. Users are encouraged to review the terms of use and privacy policy of the service provider for further information.


== Changelog ==

= 1.0.7 =
* Update: Updated connection tab according to WordPress requirements 

= 1.0.6 =
* Fix: Fixed a conflict with Small Package Quotes - USPS Edition.

= 1.0.5 =
* Update: Introduced FDO and VA tabs functionality.

= 1.0.4 =
* Update: Add new settings "Package rating method when Standard Box Sizes isn't in use"

= 1.0.3 =
* Update: Introduced an option enabling the merchant to input their ship-engine Carrier ID and API key.

= 1.0.2 =
* Update: Enhance the code to meet the standards set by wordpress.org.

= 1.0.1 =
* Update: Compatibility with "Standard Box Sizes - for WooCommerce"

= 1.0 =
* Initial release.

== Upgrade Notice ==

