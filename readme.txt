=== Berri Youtube Gallery ===
Contributors: Artberri
Donate link: http://www.berriart.com/donate/
Tags: sidebar, widget, youtube, gallery
Requires at least: 2.1
Tested up to: 2.3
Stable tag: trunk

This Widget will display the YouTube videos that you want and the only data needed are the video URLs. There is no need to get YouTube API. 

== Description ==

Berri YouTube Gallery gives you possibility to show a YouTube video gallery with customized appearance on your sidebar and all you need is to write the video URLs in the widget control panel. There is no need to register or to get YouTube API. Easy to customize and you can insert an image in the video thumbnail.

Comments, questions and bug reports are welcome: [http://www.berriart.com/berri-youtube-gallery/](http://www.berriart.com/berri-youtube-gallery/ "Berri YouTube Gallery")

== Installation ==

1. Extract and upload to the `/wp-content/plugins/` directory
1. Give write permissions to `/wp-content/plugins/berri-youtube-gallery/images/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Add YouTube Gallery to the Widgets Sidebar
1. Configure the gallery in the widget panel and save configuration
1. That's all

CONFIGURATION

* **Title:** The title of the widget
* **Show YouTube watermark?:** Check to display a YouTube logo in the thumbnails
* **Show play image?:** Check to display an image in the center of the thumbnails
* **Play Image URL:** Write the URL of a PNG image to personalize the play image. If is not PNG or if you leave the text-field empty the default play image will be displayed
* **Video URLs:** Write the URLs of the videos that you want to show. One URL for each line
* **Display template:** The template for each video. HTML code is allowed. You can use `%%title%%` to display the title of the video, `%%url%%` to display the video URL and `%%img%%` to display the thumbnail URL

REQUIREMENTS

* Widget enabled
* Widgetized Theme
* PHP GD Library
* `allow_url_fopen` option enabled

== Frequently Asked Questions ==

= Why doesn't it show anything? =

You have to give write permissions to `/wp-content/plugins/berri-youtube-gallery/images/` directory, configure the gallery in the widget panel and save configuration.

= I configured it! It doesn't work! =

Have you the requirements? You must have widget enabled, a widgetized theme, PHP GD Library installed and `allow_url_fopen` option enabled.

== Screenshots ==

1. The widget panel
2. The youtube gallery

