/*global wp, confirm, jQuery, cwAjax,alert,console,jQuery,ajaxurl */
/*jshint browser:true */

var file_frame, cwForms, cwSetupForms;
jQuery(document).ready(function($){
    var rebuild = function($parent){
        var widgetData = $parent.find('.cw-widget-data').val(),
            widgetDataObject = widgetData ? JSON.parse(widgetData) : {},
            $items = $parent.find('.cw-items');
        if (JSON.stringify(widgetDataObject) === '{}'){
            return;
        }

        $items.html('');
        widgetDataObject.forEach(function(item) {
            var dataAttributes = '';
            for(var propertyName in item) {
                if (item.hasOwnProperty(propertyName)) {
                    var key = 'data-' + propertyName.replace(/([a-z])([A-Z])/g, '$1-$2').toLowerCase(),
                        value = item[propertyName];
                    dataAttributes += key + '="' + value + '"';
                }
            }
        $items.append('<div ' + dataAttributes + '>' + item.title + '</div>');
        });
    };
    
    var addInstance = function($parent){
         var $form = $parent.find('.cw-inner-form'),
            $currentInstance = $form.find('.cw-current-data'),
            currentInstance = $currentInstance.val(),
            currentInstanceObject = currentInstance ? JSON.parse(currentInstance) : {},
            $widgetData = $parent.find('.cw-widget-data'),
            widgetData = $widgetData.val(),
            widgetObject = widgetData ? JSON.parse(widgetData) : [],
            $errorNotice = $form.find('.cw-error');
        if (JSON.stringify(currentInstanceObject) === '{}'){
            return;
        }
        currentInstanceObject.key=Math.round((new Date()).getTime());

        widgetObject.push(currentInstanceObject);
        $widgetData.val(JSON.stringify(widgetObject));
        clearForm($parent);
        rebuild($parent);

    };

    var clearForm = function($parent){
        var $form = $parent.find('.cw-inner-form'),
            $imagePreview = $form.find('.image-preview'),
            $removeImage = $form.find('.cw-remove-image'),
            $title = $form.find('.cw-instance-title'),
            $url = $form.find('.cw-instance-url'),
            $currentInstance = $form.find('.cw-current-data');
        $removeImage.hide();
        $title.val('');
        $url.val('');
        $currentInstance.val('');
        $imagePreview.html('');
            
    };

    var updateCurrentInstance = function($parent, cb){
         if ($parent.length === 0) {
            return;
        }
        var $form = $parent.find('.cw-inner-form'),
            $currentInstance = $form.find('.cw-current-data'),
            currentInstance = $currentInstance.val(),
            $title = $form.find('.cw-instance-title'),
            $url = $form.find('.cw-instance-url'),
            $imageData = $parent.find('.cw-attach-image'),
            imageUrl = $imageData.data('imageUrl'),
            imageId = $imageData.data('imageId'),
            title = $title.val(),
            url = $url.val(),
            returnObject = currentInstance ? JSON.parse(currentInstance) : {};
        
        returnObject.imageId = imageId;
        returnObject.imageUrl = imageUrl;
        if ($.trim(title) === ''){
            var errorMsg = "Title is required.";
            $form.find('.cw-error').html(errorMsg).delay(5000).fadeOut(function(){
                $(this).html('');
                $(this).show();
            });
            return;
        }
        returnObject.title = title;
        if (url !== ''){
            returnObject.url = url;
        }
        $currentInstance.val(JSON.stringify(returnObject));
        if (typeof(cb) === 'function') {
            cb();
        }
    };

    $('body').on('keypress', '.cw-instance-title, .cw-instance-url', function(e) {
        if(e.which === 13) {
            e.preventDefault();
            $(this).closest('.widget').find('.cw-add-instance').click();
        }
    });
    $('body').on('click', '.cw-add-instance', function(e) {
        e.preventDefault();
        var $parent = $(this).closest('.widget');
        updateCurrentInstance($parent, function(){
            addInstance($parent);
        });
        

        
    });
    $('body').on('click', '.cw-remove-image', function(e) {
        e.preventDefault();
        var $parent = $(this).closest('.widget'),
            $form = $parent.find('.cw-inner-form'),
            $imagePreview = $form.find('.image-preview'),
            $imageData = $form.find('.cw-attach-image'),
            $removeImage = $form.find('.cw-remove-image'),
            verify = confirm("Are you sure you wish to remove this image?");
        if (!verify){
            return;
        }
        $imageData.removeData('image');
        $imagePreview.html('');
        $removeImage.hide();
    });


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
                $form = $parent.find('.cw-inner-form'),
                $imagePreview = $form.find('.image-preview'),
                $removeImage = $form.find('.cw-remove-image'),
                $attachImageButton = $form.find('.cw-attach-image'),
                imageHtml = '<img src="' + attachmentUrl + '">';

            $imagePreview.html(imageHtml);
            $removeImage.show();
            $attachImageButton.data('imageId', postId);
            $attachImageButton.data('imageUrl', attachmentUrl);
        });

        file_frame.open();        
        
    });    
    $(window).load(function(){
        $('.cw-inner-form').each(function(){
            var $parent = $(this).closest('.widget');
                rebuild($parent);
        });    
    });
});