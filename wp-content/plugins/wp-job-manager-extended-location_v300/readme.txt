=== WP Job Manager - Extended Location ===
Contributors: Astoundify
Requires at least: 4.4
Tested up to: 4.6
Stable tag: 3.0.0
License: GNU General Public License v3.0

Use Google Places to auto suggest locations when submitting a listing or searching.

= Documentation =

Install the plugin and the Google Places suggestion feature will display on the submission form under the Location field.

= Support Policy =

We will happily patch any confirmed bugs with this plugin, however, we will not offer support for:

1. Customisations of this plugin or any plugins it relies upon
2. CSS Styling (this is customisation work)

If you need help with customisation you will need to find and hire a developer capable of making the changes.

== Installation ==

To install this plugin, please refer to the guide here: [http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation)

== Changelog ==

= 3.0.0: August 24, 2016 =

* New: Separate "Location" metabox when editing a listing in the WordPress dashboard.
* New: Hide map if no API key is added.
* Fix: Various improvements and stability fixes.

= 2.6.1: August 3, 2016 =

* Fix: Simplify logic on submission form map.

= 2.6.0: August 1, 2016 =

* New: Use all available IP location data to prefill search field.
* Fix: Ensure pin location remains the same after editing a listing.

= 2.5.2: June 22, 2016 =

* Fix: Avoid PHP error on empty() call.

= 2.5.1: June 21, 2016 =

* Fix: Avoid PHP error on empty() call.

= 2.5.0: June 21, 2016 =

* New: Fallback to Default Location when no IP location data is found.
* Fix: Pin location when previewing or editing a listing.

= 2.4.0: May 6, 2016 =

* Fix: Update IP address lookup service to http://ip-api.com/docs/api:json
* Fix: Don't set a null value if location cannot be found or set.
* Fix: Prevent form from submitting listing when enter is used to select a location.
* Fix: Don't geocode when a coordinate is entered.
* Fix: Update compatibility with the Client Side Geocoder plugin.
* Fix: Remove `sensor` parameter from the Google Maps API library asset.

= 2.3.0

* New: "Lock" the pin in place while the address remains updatable.
* Fix: Backend view in Firefox.
* Fix: Pin moving when other fields are edited.
* Fix: Use country if only available data for auto suggest.

= 2.2.3 = 

* Fix: Remove debug code.

= 2.2.2 = 

* Fix: Use proper IP address.

= 2.2.1 =

* Fix: Use wp_remote_get() to try many options of retrieving IP data.

= 2.2.0 =

* New: City Suggest - automatically recommend the users current city.
* Fix: Avoid error if no default is set in admin.
* Tweak: String changes.

= 2.1.0 =

* New: Add autolocation to all location inputs.
* Fix: Avoid breaking other plugins using tabs on their settings.
* Fix: Pin's geolocation lat/long will be used instead of geocoded address location.
* Tweak: Improve javascript mapping.
* Tweak: Move license input field to the correct tab.

= 2.0.0 =

* New: Rename plugin for future expansion
* New: Add a map and draggable pin to the submission form (frontend and backend). The default point can be set in "Listing > Settings > Extended Locations"

= 1.0.0 =

Initial Release!
