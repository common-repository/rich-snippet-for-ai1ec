=== Ai1ec Rich Snippets ===
Contributors: sooskriszta, abwebgorohovets, topmba
Author URL: http://profiles.wordpress.org/sooskriszta
Donate link: http://tinyurl.com/oc2pspp
Tags: calendar, events, seo, google, google rich snippets, rich snippets, schema.org, semantic web
Requires at least: 3.5
Tested up to: 4.6
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Make your All-in-One Event Calendar events more discoverable in Google (and other) search results by adding rich snippets to them.

== Description ==

This plugin adds [schema.org's event markup](https://schema.org/Event) to All-in-One Event Calendar's events.  

According to [Google](https://developers.google.com/search/docs/guides/intro-structured-data), when you provide structured data markup for your online content, you make that content eligible to appear in two categories of Google Search features:  
* Rich results—Structured data for things like recipes, articles, and videos can appear in Rich Cards, as either a single element or a list of items. Other kinds of structured data can enhance the appearance of your site in Search, such as with Breadcrumbs, or a Sitelinks Search Box.  
* Knowledge Graph cards—If you're the authority for certain content, Google can treat the structured data on your site as factual and import it into the Knowledge Graph, where it can power prominent answers in Search and across Google properties. Knowledge Graph cards appear for authoritative data about organizations, and events. Movie reviews, and movie/music play actions, while based on ranking, can also appear in Knowledge Graph cards once they are reconciled to Knowledge Graph entities.

== Installation ==

Prerequisite: [All-in-One Event Calendar](https://wordpress.org/plugins/all-in-one-event-calendar/)  
Make sure this is installed and active before installing Ai1ec Rich Snippets.  

1. Upload `rich-snippet-for-ai1ec` to the `/wp-content/plugins/` directory, OR `Site Admin > Plugins > New > Search > Ai1ec Rich Snippets > Install`.  
2. Activate the plugin through the Plugins menu item in the WordPress Dashboard.
Enjoy!

== Frequently Asked Questions ==

1. I can't find the settings tab for this plugin!  
There are no settings. As long as both All-in-One Event Calendar and Ai1ec Rich Snippets are installed and active, your events will be wrapped in schema.org markup.  

2. How do I know if this plugin is working?  
Paste an event page URL from your site at Google's Rich Snippets Testing Tool https://search.google.com/structured-data/testing-tool and hit "Run Test". If Google is able to parse and structure event details, congrats, everything's peachy.  

3. I don't see the markup in the page source!  
This plugin uses JSON-LD for the markup. Hence you'll find the markup at the bottom of the page, rather than wrapped around each individual element. This is completely acceptable to, if not recommended by, Google https://developers.google.com/search/docs/guides/intro-structured-data  

== Changelog ==

= 1.0.3 =  
* Added .pot file for easier translations  
* Fix: Escape double quotes (props: @achensee)  

= 1.0.2 =  
* Changed image assets.  
* Changed plugin name.  
* Changed plugin description etc.  
* Added FAQs  

= 1.0.1 =  
* Fixed content type problem.  

= 1.0.0 =  
* Initial commit.