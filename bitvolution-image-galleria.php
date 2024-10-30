<?php
/**
 * Plugin Name: Bitvolution Image Galleria
 * Plugin URI: http://www.bitvolution.com/wordpress-image-gallery-plugin
 * Description: This plugin replaces the default Wordpress gallery feature with a more fancy image gallery inspired by the <a href="http://devkick.com/lab/galleria/">Galleria jQuery Image gallery</a>.
 * Version: 0.1.1
 * Author: Tom Fotherby
 * Author URI: http://www.tomfotherby.com
 */

/*  Copyright 2009  Tom Fotherby

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/

/**
 * This plugin is encapsulated in a Class
 */
class BitvolutionImageGalleria {

    // Member variables
    public $bitvol_galleria_version;

    // Class Constructor - initialize the Plugin
    function BitvolutionImageGalleria() {

        $this->bitvol_galleria_version = "0.1.1";

        /** Make sure we get the correct directory for Pre-2.6 compatibility */
        if ( !defined( 'WP_CONTENT_URL' ) ) {
            define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
        }
        if ( !defined( 'WP_CONTENT_DIR' ) ) {
            define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
        }
        if ( !defined( 'WP_PLUGIN_URL' ) ) {
            define('WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' ); // full url, no trailing slash
        }
        if ( !defined( 'WP_PLUGIN_DIR' ) ) {
            // Relative to ABSPATH.  For back compat.
            define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );
        }

        /**
         * Define constant paths to the plugin folder.
         */
        define( BITVOL_GALLERIA, WP_PLUGIN_DIR . '/'. plugin_basename( dirname(__file__) ));
        define( BITVOL_GALLERIA_URL, WP_PLUGIN_URL . '/'. plugin_basename( dirname(__file__) ));

        /**
         * Define static resources used (CSS and JS)
         */
        if ( !is_admin() ) {
            // Enqueue a CSS style file - see http://codex.wordpress.org/Function_Reference/wp_enqueue_style
            wp_enqueue_style('bitvol_galleria_css', BITVOL_GALLERIA_URL . '/bitvolution-image-galleria.css', false, $this->bitvol_galleria_version, 'all' );

            // Enqueue a jQuery (included with Wordpress)
            wp_enqueue_script('jquery');

            // Enqueue a JS file - see http://codex.wordpress.org/Function_Reference/wp_enqueue_style
            wp_enqueue_script('bitvol_galleria_js', BITVOL_GALLERIA_URL . '/bitvolution-image-galleria.js');
        }


        /**
         * Bind our own callback to the "post_gallery" filter to override the default Wordpress gallery template.
         * Original is defined in gallery_shortcode() in wp-includes/media.php
         */
        add_filter( 'post_gallery', array(&$this, 'bitvol_galleria_shortcode'), 10, 2 );
    }


    /**
     * Overwrites the original WordPress gallery shortcode.
     * This is where all the main gallery stuff is pieced together.
     * What we're doing is completely rewriting how the gallery works.
     *
     * The main thing we have to do is clear out the style rules and make it
     * easier to style through an external stylesheet.  The second largest
     * issue is integrating the Lightbox-type scripts.
     *
     */
    function bitvol_galleria_shortcode( $output, $attr )
    {
        global $post;

        /* Make sure posts with multiple galleries have different IDs. */
        static $instance = 0;
        $instance++;

        // Create PHP vars from default Wordpress gallery settings.
        // See settings at: http://codex.wordpress.org/Gallery_Shortcode
        // WARNING: We will ignore a lot of these options - i.e. they won't do anything.
        extract(shortcode_atts(array(
            'order'      => 'ASC',
            'orderby'    => 'menu_order ID',
            'id'         => intval($post->ID),
            'itemtag'    => 'dl',
            'icontag'    => 'dt',
            'captiontag' => 'dd',
            'columns'    => 3,
            'size'       => 'thumbnail',
            'include'    => '',
            'exclude'    => ''
        ), $attr));

        /* Get IMAGE attachments to this post ID. */
        $postImageAttachments = get_children( array(
            'post_parent'    => $id,
            'post_status'    => 'inherit',
            'post_type'      => 'attachment',
            'post_mime_type' => 'image',
            'order'          => $order,
            'orderby'        => $orderby,
            'exclude'        => $exclude,
            'include'        => $include
        ) );

        /* If is feed, leave the default WP settings. We're only worried about on-site presentation. */
        if ( empty( $postImageAttachments ) || is_feed() ) {
            return '';
        }

        $list = "";

        /* Loop through each image attachment in current post */
        $i = 0;
        foreach ( $postImageAttachments as $attachmentID => $attachment ) {

            // Get image attachment URL (with correct dimensions)
            $img_main      = wp_get_attachment_image_src( $attachmentID, 'medium');
            $img_thumb     = wp_get_attachment_image_src( $attachmentID, 'thumbnail');
            $imgCaption    = $attachment->post_excerpt; // Get image caption text (image description would be $attachment->post_content)

            // Link to what the user set for 'Link thumnbnails to' setting for this gallery
	    if (isset($attr['link']) && 'file' == $attr['link']) {
	      // Link images directly to file
	      $imgWPPageLink = wp_get_attachment_url($attachmentID);
	    } else {
	      // Link images to Wordpress attachment page
	      $imgWPPageLink = get_attachment_link($attachmentID);
	    }

            // Get the attachment title (if the user has entered one)
            $att_title = ($attachment->post_title == '') ? "" : "title=\"{$attachment->post_title}\"";

            // Set which image to initilise the gallery with
            if (0 == $i) {
                $mainImageSrc     = $img_main[0];
                $mainImageTitle   = $att_title;
                $mainImageWPLink  = $imgWPPageLink;
                $mainImageCaption = $imgCaption;
                $selectedClass    = "class=\"selected\" ";
            } else {
                $selectedClass = "";
            }

            $list .= " <li $selectedClass>";
            $list .= "<a class='bitVolThumb' $att_title href=\"" . $img_main[0] . '">';
            $list .= '<img '.$selectedClass.'src="' . $img_thumb[0] . '" width="'.$img_thumb[1].'" height="'.$img_thumb[2].'" alt="thumb'.$i.'" />';
            $list .= '</a>';
            $list .= "<a class='wpAttLink' style='display:none' href='".$imgWPPageLink."'>".$imgCaption."</a>";
            $list .= "</li>\n";

            $i++;
        }

        $output = "<div id='gallery-{$instance}' class='gallery galleryid-{$id} bitVolClearAfter'>\n";
	// Output the single main image
        $output .= " <div class=\"mainImageDiv\">";
        $output .= "  <a href='$mainImageWPLink'><img class=\"mainImage\" src=\"$mainImageSrc\" alt=\"main gallery image $instance\" $mainImageTitle /></a>";
        $output .= " </div>\n";
	// Output the multiple thumbnail images
        $output .= " <ul>\n";
        $output .= $list;
        $output .= " </ul>\n";
        $output .= " <div class=\"bvControlDiv\">";
        $output .= "  <a class='bvPrev' href=''>&lt; Previous</a>";
        $output .= "  <a class='bvNext' href=''>Next &gt;</a>";
        $output .= "  <span class='bvCaption'>$mainImageCaption</span>";
        $output .= " </div>\n";
        $output .= "</div>";
        return $output;
    }
}

// Start the plugin up when page is initilised
if ( !is_admin() ) {
    add_action( 'init', 'bitvolutionImageGalleria_init', 7 );
}

// Start the plugin up
function bitvolutionImageGalleria_init() {
    global $bitvolutionImageGalleria;
    $bitvolutionImageGalleria = new BitvolutionImageGalleria();
}

?>
