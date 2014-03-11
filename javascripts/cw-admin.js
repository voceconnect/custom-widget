/*global wp, confirm, jQuery, cwAjax,alert,console,jQuery,ajaxurl */
/*jshint browser:true */

var file_frame, cwForms, cwSetupForms;
jQuery(document).ready(function($){
    


    $('body').on('click', '.cw-attach-image', function(e) {
        e.preventDefault();
        var $parent = $(this).closest('.widget');

     
        file_frame = wp.media.frames.file_frame = wp.media({
          title: $( this ).data( 'uploader-title' ),
          button: {
            text: $( this ).data( 'uploader-button-text' ),
          },
          multiple: false 
        });
     
        file_frame.on( 'select', function() {
            var attachment = file_frame.state().get('selection').first().toJSON(),
                postId = attachment.id,
                attachmentUrl = attachment.url,
                
                $imagePreview = $parent.find('.image-preview'),
                $attachImageButton = $parent.find('.cw-attach-image'),
                $imageData = $parent.find('.cw-image'),
                imageHtml = '<img src="' + attachmentUrl + '">';

            $imagePreview.html(imageHtml);
            $imageData.val(postId);
            
        });

        file_frame.open();        
        
    });    
    
});