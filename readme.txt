=== Plugin Name ===
Contributors: rafdizzle86
Donate link: http://www.rafilabs.com/
Tags: tags, quick tags, autocomplete, jquery ui
Stable tag: 0.0.3
Requires at least: 3.0.1
Tested up to: 3.4.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows for front-end tagging of posts via a simple widget.

== Description ==

Allows for front-end tagging of posts. It uses jQuery UI autocomplete to search the WP DB 
and return the closest matching tag based on the search term. If no tag was found, 
you can add a new tag by simply hitting the enter key.

== Installation ==

1. Download the quicktags.zip and uncompress it
2. Place the QuickTags directory in the wp-content/plugins folder
3. Enable the plugin
4. Add the QuickTags widget from the widgets section of the dashboard!

== Screenshots ==

1. This screenshot shows the search text field and two tags: "Bioengineering" and "Construction"
that have been added to the post.

== Changelog ==

= 0.0.3 =
* Added jquery-ui extended library to resolve some JS bugs

= 0.0.2 =
* Fixed issue with postID not being properly sent via AJAX
* Fixed issue with widget rendering outside single posts (it's now only displayed when is_single==true)
* Minor code enhancements

= 0.0.1 =
First version release!

== Upgrade Notice ==

= 0.0.3 =
* Fixes showstopper bugs. Users should upgrade to this working version immediately.