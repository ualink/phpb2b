/**
 * jQuery-smoothConfirm
 * Project page - http://yes2.me/smoothconfirm-project 
 * Copyright (c) 2012 Yes2Me, http://yes2.me
 * Licensed under the MIT license (http://en.wikipedia.org/wiki/MIT_License)
 * Version 0.11 (18.3.2012)
 */


(function($) {

var html = 
 '<div class="smoothConfirm">'
+   '<span class = "bg"></span>'
+   '<div class = "content">'
+       '<div class="dialog-inner"></div>'
+       '<div class="btns">'
+           '<a class="button green ok" href="javascript:void(0);"><span></span></a>'
+           '<a class="button normal cancel" href="javascript:void(0);"><span></span></a>'
+       '</div>'
+   '</div>'
+'</div>';

var getPositon = function($this, settings) {
    var windows_width = document.body.scrollWidth;
    var windows_height = document.body.scrollHeight;
    var position = $this.offset()
    var pos = new Object;
    switch (settings.direction) {
        case "top": 
            pos.x = position.left + $this.width() / 2.0 - settings.width / 2.0;
            pos.y = position.top - settings.height - settings.offset;
            break;
        case "bottom":
            pos.x = position.left + $this.width() / 2.0 - settings.width / 2.0;
            pos.y = position.top + $this.height() + settings.offset;
            break;
    }    
    pos.x = pos.x + settings.width > windows_width ? windows_width - settings.width - 20 : pos.x;
    pos.x = pos.x < 10 ? 10 : pos.x;
    pos.y = pos.y + settings.height > windows_height ? windows_height - settings.height - 20 : pos.y;
    pos.y = pos.y < 10 ? 10 : pos.y;
    return pos;
}

var showDialog = function($html, pos, settings) {
    var final_pos = new Object;
    switch (settings.direction) {
        case "top": 
            $html.css("top", pos.y + 40 + "px");
            final_pos.y = pos.y + 40
            break;
        case "bottom": 
            $html.css("top", pos.y - 40 + "px");
            final_pos.y = pos.y - 40
            break;
    }
    $html.animate({top: pos.y, opacity: "show"}, settings.speed);
    $html.find('.ok').unbind('click').click(function() {
        $html.animate({top: final_pos.y ,opacity: "hide"}, settings.speed, function() {
            if (settings.ok != null) {
                settings.ok();
            }
            $html.remove();               
        });
    });

    $html.find('.cancel').unbind('click').click(function() {
        $html.animate({top: final_pos.y,opacity: "hide"}, settings.speed, function(){
            if (settings.cancel != null) {
                settings.cancel();
            }
            $html.remove();
        });

    });
}

var setDialog = function($html, pos, content, settings) {
    $html.attr("id", settings.id);
    $html.height(settings.height);
    $html.width(settings.width);
    $html.css("left", pos.x + "px");
    $html.css("top",  pos.y + "px");
    $html.find(".content").height(settings.height - 30 - 10);
    $html.find(".content").width(settings.width - 40 - 10);
    $html.find(".dialog-inner").html(content);
    if (settings.okVal) {
        $html.find(".ok>span").html(settings.okVal);        
    } else {
        $html.find(".ok").hide();
    }
    if (settings.cancelVal) {
        $html.find(".cancel>span").html(settings.cancelVal);        
    } else {
        $html.find(".cancel").hide();
    }
    return $html
}

$.fn.smoothConfirm = function(content, options) {
    var defaluts = {
        'id'            :   'smoothConfirm',
        'className'     :   'smoothConfirm',
        'direction'     :   'top',
        'okVal'         :   'OK',
        'cancelVal'     :   'Cancel',
        'height'        :   100,
        'width'         :   200,
        'offset'        :   10,
        'speed'         :   100,
        'ok'            :   null,
        'cancel'        :   null
    };
    var settings = $.extend(defaluts, options);
    return this.each(function() {
        var $this = $(this);         
        $("#" + settings.id).remove();
        $("body").append(html);
        var $html = $("body").find("." + settings.className + ":last");
        var pos = getPositon($this, settings);
        setDialog($html, pos, content, settings);
        showDialog($html, pos, settings);
        return true;
    });
}

})(jQuery);

