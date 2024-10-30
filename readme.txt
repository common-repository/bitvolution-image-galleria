=== Bitvolution Image Galleria ===
Contributors: Tom Fotherby
Donate link: http://www.amazon.co.uk/gp/registry/wishlist/5EU9QS88QKOI
Tags: gallery, image-gallery, galleria, photos, photo-gallery, image-portfolio
Requires at least: 2.8
Tested up to: 3.0.5
Stable tag: 0.1.1

This plugin replaces the default Wordpress gallery feature with a more fancy image gallery inspired by the "Galleria" JQuery Image gallery.

== Description ==

This plugin changes the way the in-built Wordpress Gallery is displayed to the user. The Wordpress author uploads images to their pages/posts as normal and uses the usual [gallery] shortcode to display the gallery but instead of the default Wordpress image gallery with rows and columns of images, the user will be shown a more fancy image gallery inspired by the [Galleria JQuery Image gallery](http://devkick.com/lab/galleria/).

This plugin is rather specific and custom - it has no configurable settings other than the default Wordpress gallery options. It is designed so that once set up, it is invisible to the Wordpress author - they will not need to learn or do anything different from normal Wordpress gallery usage. Documentation for the Wordpress gallery can be found at http://en.support.wordpress.com/images/gallery/.

In order to be efficient, the plugin uses the Wordpress in-built image scaling feature - Whenever a Wordpress author uploads a image, Wordpress automatically creates 3 different sizes of the image (A thumbnail size, a Medium size and a Large size). In order to avoid wasteing bandwidth by scaling the image on the front-end, this plugin uses the "thumbnail" size and the "Medium" size images. The disadvantage of this is that the default Wordpress image dimensions aren't really suitable for Galleria and **will need to be altered**. See the plugin [installation instructions](http://wordpress.org/extend/plugins/bitvolution-image-galleria/installation/) for details.

Users should follow the Codex on using the `[gallery]` shortcode: http://codex.wordpress.org/Using_the_gallery_shortcode

Developers can learn more about the WordPress shortcode API: http://codex.wordpress.org/Shortcode_API

Current features:

* Displays one main 'medium' sized image and a grid of 'thumbnail' sized images
* Image captioning.
* Next/Previous links to cycle through the gallery
* User can click on main image to see 'full-size' version.
* Zero additional configuration options (plugin uses existing Wordpress image options, i.e. image sizes)
* Efficient: No front-end image resizing via CSS

Current limitations:

* Doesn't work "out of the box" on a default Wordpress installation - requires a manual change of the default Wordpress image sizes otherwise galleries aren't formatted correctly - See [installation instructions](http://wordpress.org/extend/plugins/bitvolution-image-galleria/installation/).
* By default the gallery requires a width of 850px or more - many themes have a smaller content width so the gallery will wrap onto seperate lines and won't look at all good.
* No image pre-loading (nice for your server but not so snappy for your users).

== Installation ==

Here we go:

1. Upload the `bitvolution-image-galleria` folder to the `/wp-content/plugins/` directory.
1. In the Wordpress Dashboard, activate the plugin through the 'Plugins' menu.
1. In the Wordpress Dashboard, go Settings->Media and modify the Thumbnail size to 80x80 (Wordpress default is 150x150) and the Medium size to 500x375 (Wordpress default is 300x300). If this is a brand new site with no images uploaded you can go to the next step. However, if there are already existing images uploaded, Wordpress will not resize them to your new dimensions and so this plugin will not function all that well. To fix this is easy - Install the [Regenerate-Thumbnails](http://www.viper007bond.com/wordpress-plugins/regenerate-thumbnails/) plugin and use it to re-generate all images to your new dimensions. Once done you can remove the Regenerate-Thumbnails plugin.
1. Upload a couple of images to a post and use the normal gallery shortcode `[gallery]` to display the image gallery.
1. Optional for a trivial increase in performance - Copy&Paste the CSS code in bitvolution-image-galleria.css to your themes style.css file and then comment out the line that contains `"bitvol_galleria_css"` in `bitvolution-image-galleria.php`.

== Frequently Asked Questions ==

= Can I see a demo of this plugin before I install it on my own site? =

Sure, I'm using this plugin for the image galleries on the following websites: www.greekislandpropertyfinders.co.uk/what-we-are-finding, http://www.stokerowaccommodation.com/gallery.

= How do I display a image gallery in my post or page? =

Upload two or more images using the normal Wordpress authoring tools for posts or pages. Once you have some images associated with a post or page, you can use the "Insert gallery" button in the Gallery tab. Alternatively, use the `[gallery]` shortcode within your post or page.

= Where is the Plugin Options page? =

There isn't a new settings page or any new configurable options however you will need to set the options in "Settings -> Media -> Thumbnail Size". See [installation instructions](http://wordpress.org/extend/plugins/bitvolution-image-galleria/installation/) for more details. If you want to customise how the gallery looks you will need to edit the gallery CSS file.

= How many images is this gallery designed for? =

By default, the gallery works best for 9 images - i.e. a 3x3 grid of thumbnails. But it depends on how you have set it up and whether you mind the user scrolling. I wouldn't recommend having too many images on a webpage - over 50 and your asking for trouble.

= How do I display more than one gallery in a post or page? =

Sorry, Wordpress is set up to only provide one gallery per post or page. Either split your gallery over multiple posts or search Google for tricks/hacks.

= I have specified X "Gallery columns" in the "Wordpress Gallery Settings" but they are being ignored =

This plugin ignores the Gallery columns setting - it uses a fluid layout instead. i.e. the thumnails fit to the container and wrap to the next line as needed. To work out the number of columns the image gallery grid will be you can use the size of the thumbnails and the size of the gallery "ul" element. I recommend setting the thumbnails to 80x80px and the gallery ul element is 310px wide by default - therefore you will get 3 images per column (taking into account margin and border widths).

= Can this plugin work with a lightbox? =

That would be cool, but sorry I didn't figure out how to do it yet.

= Can I override the style of the plugin in my theme? =

Yes, if your theme uses this plugin, I suggest you copy&paste the style in the plugins css into your themes, change it as needed and then tell your theme to block this plugin from outputing a stylesheet (you will get better website performance by minimising the number of CSS resources included in the HEAD section). You can deregister the bitvol-galleria stylesheet by including this in your functions.php:

`add_action( 'wp_print_styles', 'my_deregister_styles', 100 );`
`function my_deregister_styles() {`
`  wp_deregister_style( 'bitvol_galleria_css' );`
`}`

== Screenshots ==

1. Image gallery with default style.

== Changelog ==

= 0.1.1 =
* Bug fix: Made the main image link adhere to the gallery option called "Link thumbnails to" - So now the user can choose to link to either the Wordpress attachement page or directly to the full size uploaded image. This bug was reported by [Colby Fayock](http://www.colbyfayock.com).

= 0.1 =
* Added prev/next link to allow users to cycle through the gallery.
* Added captions (for images where the caption is defined).
* Made the main image link to the Wordpress attachment page (to allow commenting on individual gallery images).
* Removed width and height attributes on main gallery image because was causing UI problems.

= 0.0.3 =
* Set width and height attributes on main gallery image.
* Fix border on main gallery image so it re-sizes depending on the image.
* Change title attribute on main gallery image so it matches currently selected thumbnail.
* Refactor Javascript code.

= 0.0.2 =
* Corrected plugin URL in readme.

= 0.0.1 =
* This is the first version released. Features: 1) Efficient usage of images (no front-end resizing) no pre-downloading images that aren't needed, 2) Displays loading spinner image when image is loading, 3) Uses CSS classes so you can style the galleria in your own way, 4) Can click on the main image to cycle through the gallery, 5) Uses jQuery for cross-browser hover fade effects, 6) Small JS footprint: 2854 bytes, 7) outputs valid XHTML (unlike the default Wordpress gallery!).
