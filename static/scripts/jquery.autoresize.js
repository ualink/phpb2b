jQuery.fn.autoResize = function(options)
{
    var opts = {
        'width' : 400,
        'height': 300
    }
    var opt = $.extend(true, {},opts,options || {});
    width = opt.width;
    height = opt.height;
    $('img',this).each(function(){
        var image = new Image();
        image.src = $(this).attr('src');
        //¿ªÊ¼¼ì²éÍ¼Æ¬
        if(image.width > 0 && image.height > 0 ){
            var image_rate = 1;
            if( (width / image.width) < (height / image.height)){
                image_rate = width / image.width ;
            }else{
                image_rate = height / image.height ;
            }
            if ( image_rate <= 1){
                $(this).width(image.width * image_rate);
                $(this).height(image.height * image_rate);
            }
        }
    });
}