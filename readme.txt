=== TCP Referrer Shortcode ===
Contributors:      tcpteam
Plugin Name:       TCP Referrer Shortcode
Plugin URI:        https://www.thecartpress.com
Tags:              referer, referrer, shortcode, woocommerce, thecartpress, blog
Author URI:        https://www.thecartpress.com
Author:            TCP Team
Requires PHP:      5.6
Requires at least: 5.5
Tested up to:      5.8.4
Stable tag:        1.1.0
Version:           1.1.0
License:           GPLv3
License URI:       https://www.gnu.org/licenses/gpl-3.0.html

== Description ==
TCP Referrer Shortcode used to display the referer link inside your blog post or product description. This is useful when you try to bring back the user to your referrer site after user finish reading/browsing the on landing page.

KEY FEATURES

- [tcp_referer]
display the referrer domain. Referrer domain will be saved inside the cookie when detected utm_source or http_referer.
- [tcp_referer default=www.thecartpress.com]
set a default link. It will display the default text if no referrer found.
- [tcp_referer linkify=1]
display and make the referrer text clickable.

= Plugin doesn't fit your requirement? =

Find out more plugins in [TheCartPress](https://www.thecartpress.com/) or

We have added a welcome page to display all plugins from [TheCartPress](https://www.thecartpress.com/) inside the plugin menu. You can easily preview and choose the plugin that might fit your requirement inside the admin page. All plugins information displayed inside the menu are getting from TheCartPress server.

== Installation ==
Unzip and Upload Folder to the /wp-content/plugins/ directory.
Activate through WordPress plugin dashboard page.

Save Changes.

== Upgrade Notice ==

== Changelog ==

= 1.1.0 =
* Add plugin link to TheCartPress sidebar menu
* Use tcp.php

= 1.0 =
* First release

== Screenshots ==

== Frequently Asked Questions ==
=Why is the shortcode display empty/blank=
This plugin will get and save referrer link when detected utm_source or http_referer. If the link doesn't include utm_source or [http_referer](https://www.php.net/manual/en/reserved.variables.server.php) doesn't set by user agent then nothing will be displayed. You can set the default link inside the shortcode in case above condition happened. eg: [tcp_referer default=www.thecartpress.com]

=Why is the referrer link not hyperlink/non-clickable=
pass linkify=1 inside the shortcode to make it clickable. [tcp_referer linkify=1]