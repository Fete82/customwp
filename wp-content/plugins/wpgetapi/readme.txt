=== WPGetAPI - Connect to external API's ===
Contributors: wpgetapi
Tags: api, external api, rest-api, connect, custom-endpoints, endpoint, rest
Requires at least: 4.0
Requires PHP: 5.6
Tested up to: 6.0
Stable tag: 1.4.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Connect to external API's and display API data using a shortcode or template tag. Easily get data from external REST API's. 

== Description ==

WPGetAPI is the easiest way to connect WordPress with external API's, allowing you to easily get (or send) data to/from 3rd party API's and then display the returned data on your WordPress website using a shortcode or a template tag.

The API data can be output as either a JSON string or as a PHP array, allowing you to easily format the data.  You could create tables or charts from the data plus many other possibilities.

### View the Demo

[Live Demo - Connecting WordPress to External API](https://wpgetapi.com/demo-connecting-wordpress-to-external-api/?utm_campaign=Demo&utm_medium=wporg&utm_source=readme)

### Major Features

 * Connect your website to REST API's
 * No coding required
 * Unlimited API's & endpoints
 * GET requests or POST requests
 * Output API data using a template tag
 * Output API data using a shortcode
 * Set query string parameters
 * Set header parameters
 * Set body parameters

### Documentation

View [the docs](https://wpgetapi.com/docs?utm_campaign=Docs&utm_medium=wporg&utm_source=readme) or jump to these articles below to get started:

 * [Quick Start Guide](https://wpgetapi.com/docs/quick-start-guide/?utm_campaign=Docs&utm_medium=wporg&utm_source=readme)
 * [Step by Step Example](https://wpgetapi.com/docs/step-by-step-example/?utm_campaign=Docs&utm_medium=wporg&utm_source=readme)
 * [Using the Shortcode](https://wpgetapi.com/docs/using-the-shortcode/?utm_campaign=Docs&utm_medium=wporg&utm_source=readme)
 * [Using the Template Tag](https://wpgetapi.com/docs/using-the-template-function/?utm_campaign=Docs&utm_medium=wporg&utm_source=readme)

### Extending WPGetAPI

The **[Pro Plugin](https://wpgetapi.com/downloads/pro-plugin/?utm_campaign=Pro&utm_medium=wporg&utm_source=readme)** extends the functionality and provides several handy features.

 *  Cache API calls
 *  Base64 encoded authorization
 *  Connect to API's using XML format
 *  Retrieve nested data in shortcode
 *  Format number in shortcode
 *  Dynamic variables in query string
 *  Dynamic variables in endpoints
 *  Dynamic variables in headers
 *  Dynamic variables in body

The **[Woocommerce Import](https://wpgetapi.com/downloads/woocommerce-import/?utm_campaign=Woocommerce&utm_medium=wporg&utm_source=readme)** extension allows you to import items/products/listings from your API and create Woocommerce products from these items.

 *  Import with click of a button
 *  Run imports automatically in the background
 *  Set interval of auto imports
 *  Map API data to standard Woocomemrce fields, custom fields, categories, attributes, tags
 *  Import & upload images
 *  Automatic deletion old/expired products
 *  Works with any theme

== Installation ==
= Requirements =
* WordPress version 4.0 and later
* PHP 5.6, Tested with PHP 8.0
* cURL

= Usage =

1. Go to the `WPGetAPI -> Setup` menu to add your API's.
2. Once your API's are saved, a new tab is created allowing you to add endpoints.
3. Once your endpoints are saved, you can use the template tag or shortcode to connect to your API and view the data.

== Frequently Asked Questions ==

= Where can I find docs? =

All of our [documentation can be found here](https://wpgetapi.com/docs/?utm_campaign=Docs&utm_medium=wporg&utm_source=faq).

= Can I connect to any REST API? =

It will depend. You should be able to connect to any REST API that uses either no authentication or uses some such combination of authentication that uses query string parameters or headers. You can add unlimited query string parameters and headers. We currently do not have the ability to use OAuth or any other multi-step authentication methods. 

If your API's method of authentication was to have your 'api_key' appended to the URL, you would simply add 'api_key' into the Name field and then your actual api key string into the Value field of a query paramter. This would then append these values to the URL and authenticate your API.

= Can I use an XML based API? =

We support XML with our [Pro Plugin](https://wpgetapi.com/downloads/pro-plugin/?utm_campaign=Pro&utm_medium=wporg&utm_source=faq)

= How do I cache API calls? =

We support caching with our [Pro Plugin](https://wpgetapi.com/downloads/pro-plugin/?utm_campaign=Pro&utm_medium=wporg&utm_source=faq)

= Will you help me if I am having trouble? =

Yes! Please [contact us](https://wpgetapi.com/contact/?utm_campaign=Contact&utm_medium=wporg&utm_source=faq) and we will get your API up and running.

= How do I connect WordPress to an external API? =

A good start is to visit our [Quick Start Guide](https://wpgetapi.com/docs/quick-start-guide/?utm_campaign=Quick-Start&utm_medium=wporg&utm_source=faq) as well as the rest of our docs.




== Screenshots ==

1. The Setup screen where you can add your external API's
2. Once an external API has been added, a new page will be created to setup the API endpoints
2. A live demo of the output when debug mode is set to true


== Changelog ==

= 1.4.7 (2022-05-25) =
- Fix - change the redirect after saving to a javascript solution

= 1.4.6 (2022-05-24) =
- Enhancement - add new attribute 'format' within shortcode that allows formatting of a number in the Pro Plugin.

= 1.4.5 (2022-05-18) =
- Fix - error in admin-options file.

= 1.4.4 (2022-05-18) =
- Enhancement - add some better, and clearer help in the admin area. Tidy up some styling.
- Fix - error displaying correct endpoint ID within admin area shortcode and template tag helpers. Happening when multiple endpoints added.

= 1.4.3 (2022-05-15) =
- Fix - body was not being set correctly.

= 1.4.2 (2022-05-13) =
- Enhancement - readme updates and plugin links within plugin page.

= 1.4.1 (2022-05-05) =
- Fix - new tab was not appearing on intitial save on setup page.
- Enhancement - add new filter 'wpgetapi_admin_pages' to allow adding extra tabs.

= 1.4.0 (2022-03-17) =
- Fix - refactor the building of request args. Body was not working correctly.
- Fix - change naming convention from Template Function to Template Tag within admin.
- Enhancement - modify output of debug to show more info and to show whether or not shortcode is used.

= 1.3.4 (2022-03-17) =
- Enhancement - add ability to use headers and body variables in Pro Plugin.

= 1.3.3 (2022-03-03) =
- Enhancement - style the debug output to make it easier to understand and provide links to docs.

= 1.3.2 (2022-02-22) =
- Bug fix - change paramater value fields to textarea. This allows the proper use of JSON strings within these fields.

= 1.3.1 (2022-02-16) =
- Bug fix - error with class property name that was not allowing proper $args to be sent to remote request

= 1.3.0 (2022-02-08) =
- Fix - rewrite headers parameters section

= 1.2.3 (2021-12-14) =
- Enhancement - add ability for query_variables to be used in shortcode with the Pro Plugin

= 1.2.2 (2021-11-09) =
- Enhancement - add args to debug info. Will be useful for endpoint_variables in Pro Plugin

= 1.2.1 (2021-11-05) =
- Bug fixes with encrypting values

= 1.2.0 (2021-11-04) =
- Enhancement - add option to JSON encode body parameters
- Enhancement - allow simple arrays to be sent in body

= 1.1.0 (2021-11-03) =
- Enhancement - reconfigure debug info
- Bug fixes

= 1.0.2 (2021-11-02) =
- Bug fixes

= 1.0.1 (2021-11-02) =
- Bug fixes

= 1.0.0 (2021-10-27) =
- Initial Release
