(function ($) {
    var JSNModalResize = function () {}
    JSNModalResize.prototype = {
        resize:function(windowWidth, width, frameId){
            if(windowWidth < 800){
                window.parent.jQuery.noConflict()( frameId).contents().find('body').css('overflow-x', 'hidden')
                window.parent.jQuery.noConflict()( frameId).contents().find('#wpwrap').css('width', width * 0.9);
                //window.parent.jQuery.noConflict()( frameId).contents().find('.jsn-bootstrap3 .form-horizontal .control-label').css('width', '60px');
                //window.parent.jQuery.noConflict()( frameId).contents().find('.jsn-bootstrap3 .form-horizontal .controls').css('margin-left', '80px');
            }
            else{
                window.parent.jQuery.noConflict()( frameId).contents().find('body').css('overflow-x', 'auto')
                window.parent.jQuery.noConflict()( frameId).contents().find('#wpwrap').css('width', '100%');
                //window.parent.jQuery.noConflict()( frameId).contents().find('.jsn-bootstrap3 .form-horizontal .control-label').css('width', '160px');
                //window.parent.jQuery.noConflict()( frameId).contents().find('.jsn-bootstrap3 .form-horizontal .controls').css('margin-left', '180px');
            }
        }
    }

    $(document).ready(function() {
        $(window).resize(function() {
            var modalResize = new JSNModalResize();
            var full_width  = 0.9 * $(window).width();
            var fixed_width = ($(window).width() > 750 ) ? 750 : 0.9 * $(window).width();
            var height      = 0.9 * $(window).height();
            var height      = ( height > 720 ) ? 720 : height;
            // Resize all wr-modal
            $('.wr-dialog').each(function () {
            	if ( $(this).find('#jsn_view_modal').length ) {
            		width = full_width;
            	} else {
            		width = fixed_width;
            	}
            	 $(this).css('width', width + 'px')
                 $(this).css('height', height + 'px')
                 $(this).css({
                     top:'50%',
                     left:'50%',
                     margin:'-'+($(this).height() / 2)+'px 0 0 -'+($(this).width() / 2)+'px'
                 });
                 $(this).find('.ui-dialog-content').css('height', height - 110);
            });

            // adjust some elements
            // modalResize.resize($(window).width(), width, '#jsn_view_modal');
        })
    })
})(jQuery)