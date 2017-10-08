=== WP Job Manager - Stats ===
Contributors: Astoundify
Requires at least: 4.4 
Tested up to: 4.6.*
Stable tag: 2.1.0
License: GNU General Public License v3.0

Be better than average. Get statistical with WP Job Manager - Stats.

= Documentation =

Everyone loves stats, including your customers! Owners of listings on your WordPress site will love the WP Job Manager Stats plugin, which allows them to view traffic statistics to their listing. And because it gives you an additional opportunity to monetize top-tier listing packages, we reckon you’ll love it too.

When you activate the plugin you will be asked to install a required page for the plugin to function. Click the "Install page" button to complete the setup.

A new page has now been created called "Listings Stats Dashboard" this page is where your listing owners can view the stats for their listings, they will be able to see all of the stats for every listing they have created. The shortcode that is placed on this page is [stats_dashboard] you can then add this page to your menu.

Require Paid Listing to View Stats.

As a site owner, you have the option of toggling listing page stats so they are visible for paid listings only. For example, if you have “Gold”, “Silver” and “Bronze” listing packages (each with different features, and therefore different prices), you could set stats to be available to “Gold” package listing owners only. This gives your top-tier listing packages more value, and your listing owners more features.

If you wish to enable this feature visit the settings area of the Stats plugin (This feature requires WP Job Manager - Paid Listings to be installed). Select the option called "Require Paid Listing".

To limit access to stats for a particular paid listing package visit Products > Edit a package you have previously created > You will see a new option inside of the Product Data window called "Display statistics" select this option to limit access to stats to this paid listing package.

Other Features

Within the plugin settings in the WordPress admin you can also:

Set the default number of days worth of data/statistics to be shown in the front-end dashboard.
Purge statistics data on deletion of the plugin.

= Support Policy =

We will happily patch any confirmed bugs with this plugin, however, we will not offer support for:

1. Customisations of this plugin or any plugins it relies upon
2. CSS Styling (this is customisation work)

If you need help with customisation you will need to find and hire a developer capable of making the changes.

== Installation ==

To install this plugin, please refer to the guide here: [http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation)

== Changelog ==

= 2.1.0: October 19, 2016 =

* Fix: Ensure all days in the current range properly output stat values.
* Fix: A unique visit resets each day.

= 2.0.0: October 11, 2016 =

* New: Overhauled user interface for viewing statistics.
* New: Easily add and remove listings to the current chart view.
* New: Track "Apply" button clicks.
* New: Track "Contact Listing" submissions when using WP Job Manager - Contact Listing
* New: Improved performance for sites with a large number of listings.
* New: View stats for multiple listings in the WordPress dashboard. 
* New: Improved WC Paid Listings integration.

= 1.5.0: June 21, 2016 =

* New: Show "Total Visit" count for current filter period.
* New: Show view count on the [job_dashboard] shortcode.
* New: Add ID and expiration date to graph legend.
* New: Add caching to queries.

= 1.4.0: May 19, 2016 =

* New: Add a link to view stats from the Listings Dashboard.
* Fix: Ensure expired listing stats are still displayed.
* Fix: Ensure filled listing stats are still displayed.
* Fix: Improve license key UX.
* Fix: Date range view on Internet Explorer.

= 1.3.0: January 28, 2016 =

* Fix: Load stats so translations can catch them in time.

= 1.2.0 - 12/08/2015 =

* Fix: String updates
* Fix: Don't hide any listings on the listing dashboard, only the stats page.

= 1.1.1 - 08/14/2015 =

* Fix: Javascript error when viewing the stats page when logged out.

= 1.1.0 =

* New: Add languages pot file so that the plugin can be translated.

= 1.0.1 =

* Fix: Use WordPress date format as default.
* Fix: Don't override query arguments on pages other than page with shortcode.

= 1.0.0 =

Initial Release!