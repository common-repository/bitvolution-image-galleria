// Directory path of this plugin
var bitVolPluginDir = "/wp-content/plugins/bitvolution-image-galleria";

// Function to update thumbnails so the correct one is shown as selected
function bitVolImgGal_selectThumbnail(gallery,ATagContainingThumb)
{
    // Remove 'selected' from other thumbnails and add it to current one
    jQuery(gallery).find('img.selected').removeClass('selected').fadeTo('fast','0.3');
    jQuery(ATagContainingThumb).children('img').addClass('selected').fadeTo('fast',1);

    // Remove 'selected' from other list items and add it to current one
    jQuery(gallery).find('li.selected').removeClass('selected');
    jQuery(ATagContainingThumb).closest("li").addClass('selected');
}

// Function to update main image so it shows a large version of the selected thumbnail image
function bitVolImgGal_setmainImg(gallery,ImgTagMain,ATagContainingThumb)
{
    // Display a "loading" image so there is something in view until the proper one has downloaded
    jQuery(ImgTagMain).attr('src', bitVolPluginDir+'/images/icons/loading.gif');

    // Set the new image src, title
    jQuery(ImgTagMain).attr({
        src: jQuery(ATagContainingThumb).attr('href'),
        title: jQuery(ATagContainingThumb).attr('title')
    });

    // Update main image caption and href
    var wrapperLinkObj  = jQuery(ImgTagMain).parent()[0];
    var infoObj         = jQuery(ATagContainingThumb).next('.wpAttLink');
    var wrapperLinkHref = jQuery(infoObj).attr('href');
    var captionTxt      = jQuery(infoObj).html();

    jQuery(wrapperLinkObj).attr('href',wrapperLinkHref);
    jQuery(gallery).find('.bvCaption').html(captionTxt);
}

jQuery(document).ready(function($) {

    // Thumbnail hover fade effects
    $('.gallery li img').hover(
        function() {jQuery(this).fadeTo('fast',1);},
        function() {jQuery(this).not('.selected').fadeTo('fast','0.3');}
    );

    // Allow user to click thumbnails to select main image
    $('a.bitVolThumb').click(function() {

        var ATagContainingThumb = this; // save current link cos we'll change it
        var currentGallery   = jQuery(ATagContainingThumb).parents('div.gallery');

        // Update main image
        bitVolImgGal_setmainImg(currentGallery,jQuery(currentGallery).find('img.mainImage')[0],ATagContainingThumb);

        // Update thumbnail image grid
        bitVolImgGal_selectThumbnail(currentGallery,ATagContainingThumb);

        return false;
    });

    // Allow user to click the next link to go to the next image
    $('.gallery .bvNext').click(function() {

        var currentGallery = jQuery(this).parents('div.gallery');
        var selectedLI = jQuery(currentGallery).find("li.selected")[0];
        var nextLI = jQuery(selectedLI).is(':last-child') ? jQuery(selectedLI).siblings(':first-child') : jQuery(selectedLI).next();
        var nextA  = jQuery(nextLI).find('a')[0];

        // Update main image
        bitVolImgGal_setmainImg(currentGallery,jQuery(currentGallery).find('img.mainImage')[0],nextA);

        // Update thumbnail image grid
        bitVolImgGal_selectThumbnail(currentGallery,nextA);

        return false;
    });

    // Allow user to click the prev link to go to the previous image
    $('.gallery .bvPrev').click(function() {

        var currentGallery = jQuery(this).parents('div.gallery');
        var selectedLI = jQuery(currentGallery).find("li.selected")[0];
        var prevLI = jQuery(selectedLI).is(':first-child') ? jQuery(selectedLI).siblings(':last-child') : jQuery(selectedLI).prev();
        var prevA  = jQuery(prevLI).find('a')[0];

        // Update main image
        bitVolImgGal_setmainImg(currentGallery,jQuery(currentGallery).find('img.mainImage')[0],prevA);

        // Update thumbnail image grid
        bitVolImgGal_selectThumbnail(currentGallery,prevA);

        return false;
    });
});
