/* ================================= */
/* WAMI LIGHTBOX PLUGIN 1.2 */
/* ================================= */



$(document).ready(function(){
    
    $.fn.wlightbox=function(options){
        
        // define default options
        var defauts = {
            
            "customClassName": '',
            
            // box sizes
            "windowPadding": '5%',            // the safe zone around image wrapper(px, vw, %...)
            "fullScreenMode": 'none',            // Safe zone for mobile
            
            // styles
            "overlayColor": 'rgba(0,0,0,0.9)',  // hexa, rgba, red...
            "googleImageStyle" : false,
            "googleImageWrap" : '.w-lightbox-wrap',
            
            // animations
            "openAnimation": 'none',            // none, scale, fade
            "changeAnimation" : 'none',         // none, swipe, rotate, fade
            "dotNavigationAnimation" : 'none',  // none, swipe, fade, scale
            
            // controls options
            "looping" : true,                   // looping the previews
            "touchEvents" : false,              // depend on jquery-mobile-events.js
            "arrowKeys" : true,                 // use keyboard arrow keys to change image
            
            "dotNavigation" : false,            // add dot navigation
            
            "cornerNavigation" : true,          // navigation by clicking on image corner (left or right)
            "cornerFontAwesome" : false,        // controls buttons use fontAwesome icons
            "cornerPrevText" : 'prev',          // text for 'previous' button
            "cornerNextText" : 'next',          // text for 'next' button
            
            "controls" : true,                  // add 'previous', 'next' and 'close' buttons
            "controlFontAwesome" : false,       // controls buttons use fontAwesome icons
            "controlPrevText" : 'précédent',    // text for 'previous' button
            "controlNextText" : 'suivant',      // text for 'next' button
            "controlsClose" : true,             // show close button
            "closeText" : 'fermer'              // text for 'close' button
            

        };
        
        
        var wlbOption=$.extend(defauts, options);
        
        
        /* ============================== */
        /* INIT                           */
        /* ============================== */
        
        var $currentLight = '';
        var currentId = '';
        var isAnimate = false;
        var isOpen = false;
        
        // Define link
        var $link = $(this);
        var total = $link.length;
        
        
        // cancel link on click event
        $link.on('click',function(e){e.preventDefault();});
        
        
        var $lightbox = $('<div/>', {
            id: 'w_lightbox',
            class: wlbOption.customClassName
        });
        
        $('body').append($lightbox);
        
        // Add lightbox html on page
        $lightbox.append('<div class="w_lightbox-overlay"></div><ul class="img-wrapper"></ul>');
        
        
        // Define lightbox Object variables and styles
        $lightboxContent = $lightbox.find('.img-wrapper');
        
        if(wlbOption.fullScreenMode=='none')
        {
            $lightboxContent.css({
                top: wlbOption.windowPadding,
                right: wlbOption.windowPadding,
                bottom: wlbOption.windowPadding,
                left: wlbOption.windowPadding
            });
        }else
        {
            if($(window).outerWidth() > parseInt(wlbOption.fullScreenMode))
            {
                $lightboxContent.css({
                    top: wlbOption.windowPadding,
                    right: wlbOption.windowPadding,
                    bottom: wlbOption.windowPadding,
                    left: wlbOption.windowPadding
                });
            }
            else
            {
                $lightboxContent.css({
                    top: 0,
                    right: 0,
                    bottom: 0,
                    left: 0
                });
            }
        }
        
        
        
        // if true, add controls on lightbox
        if(wlbOption.controlFontAwesome)
        {
            var prevText = '<i class="fa fa-angle-left"></i>';
            var nextText = '<i class="fa fa-angle-right"></i>';
            var closeText = '<i class="fa fa-close"></i>';
        }
        if(wlbOption.controls)
        {
            if(!wlbOption.controlFontAwesome)
            {    
                var prevText = wlbOption.controlPrevText;
                var nextText = wlbOption.controlNextText;
                var closeText = wlbOption.closeText;
            }
            if(wlbOption.controlsClose){
                $lightbox.append('<div id="controls"><a id="w_prev-light" href="#">'+prevText+'</a><a id="w_next-light" href="#">'+nextText+'</a></div><a id="w_close-light" href="#">'+closeText+'</a>');
            }else{
                $lightbox.append('<div id="controls"><a id="w_prev-light" href="#">'+prevText+'</a><a id="w_next-light" href="#">'+nextText+'</a></div>');
            }
            
        }else{
            if(wlbOption.controlsClose){
                $lightbox.append('<a id="w_close-light" href="#">'+closeText+'</a>')
            }
        }
        
        
        
        // if true, add dot nav on lightbox
        if(wlbOption.dotNavigation)
        {
            $lightbox.append('<div id="dotnav"><ul></ul></div>');
            for( var i = 1; i <= total; i++){
                $lightbox.find('#dotnav ul').append('<li><a id="goto-'+i+'" class="dot-item" href="#">'+i+'</a></li>');
            }
        }
        
        
        
        // if true, add arrow key navigation
        if(wlbOption.arrowKeys)
        {
            $(document).on('keydown', function(e){
                
                if(isOpen){
                    
                    e.preventDefault();
                    
                    var code = e.keyCode || e.which;
                    if(code == 37) { 
                      $lightbox.prevLight()
                    }
                    if(code == 39) {
                      $lightbox.nextLight()
                    }
                    
                }
                
            });
        }
        
        
        // add overlay color option
        $lightbox.find('.w_lightbox-overlay').css('background-color', wlbOption.overlayColor);
        
        
        
        
        // ========= LOOP =========
        
        // for each link
        $link.each(function(index,elem){
            
            index = parseInt(index+1);
            var href = $(elem).attr('href');
            var lightTitle = '';
            var lightDescription = '';
            
            if(typeof($(elem).attr('data-title'))!='undefined'){
                lightTitle = $(elem).attr('data-title');
            }
            if(typeof($(elem).attr('data-description'))!='undefined'){
                lightDescription = $(elem).attr('data-description');
            }
            
            if($(this).hasClass('w-media'))
            {
                href = $(this).attr('data-src');
                $lightboxContent.append('<li id="light-item-'+index+'" class="w-media">'+href+'</li>');
            }else if($(this).hasClass('ajax-light'))
            {
                href = $(this).attr('href');
                $lightboxContent.append('<li id="light-item-'+index+'" class="w-ajax-wrap" data-href="'+href+'"><div class="w-ajax-container"></div></li>');
            }
            else
            {
                // add full size img in html list
                if(wlbOption.cornerNavigation){
                    
                    if(wlbOption.cornerFontAwesome){
        
                        $lightboxContent.append('<li id="light-item-'+index+'"><img src="'+href+'">'+lightTitle+lightDescription+'<a id="w_prev-light" href="#"><span class="fa fa-angle-left"></span></a><a id="w_next-light" href="#"><span class="fa fa-angle-right"></span></a></li>');
                        
                    }else{
                        
                        $lightboxContent.append('<li id="light-item-'+index+'"><img src="'+href+'">'+lightTitle+lightDescription+'<a id="w_prev-light" href="#"><span>'+wlbOption.cornerPrevText+'</span></a><a id="w_next-light" href="#"><span>'+wlbOption.cornerNextText+'</span></a></li>');
                        
                    }
                    
                }else{
                    
                    $lightboxContent.append('<li id="light-item-'+index+'"><img src="'+href+'">'+lightTitle+lightDescription+'</li>');
                    
                }
            }
            $(elem).attr('data-lightbox', 'light-item-'+index);
        });
        
        
        
        /* ============================== */
        /* EVENTS TRIGGER                 */
        /* ============================== */
        
        // click on thumbs
        $link.on('click',function(e){
            
            e.preventDefault();
           
            var openImg = parseInt($(this).attr('data-lightbox').replace('light-item-',''));
            
            $lightbox.gotoLight(openImg);
            $lightbox.openLightbox();
            
            $currentLight = openImg;
            
        });
        
        $lightbox.find('.w_lightbox-overlay, #w_close-light').on('click',function(e){
            e.preventDefault();
            $lightbox.closeLightbox();
        });
        
        $lightbox.on('click', '#w_next-light',function(e){
            if(!isAnimate){    
                e.preventDefault();
                $lightbox.nextLight();
            }
        });
        
        $lightbox.on('click', '#w_prev-light',function(e){
            if(!isAnimate){
                e.preventDefault();
                $lightbox.prevLight();
            }
        });
        
        
        $lightbox.on('click', '#dotnav .dot-item', function(){
            if(!isAnimate){
                var openId = parseInt($(this).attr('id').replace('goto-',''));
                $lightbox.gotoLight(openId);
                $lightbox.trigger('dotNavClick', openId);
            }
        });
        
        
        $lightbox.on('changeLight',function(event, current){
           
            if(!wlbOption.looping)
            {
                
                $lightbox.find('#w_prev-light, #w_next-light').removeClass('disabled');
                
                if(current == $lightbox.total())
                {
                    $lightbox.find('#controls #w_next-light, .img-wrapper #w_next-light').addClass('disabled');
                }

                if(current == 1)
                {
                    $lightbox.find('#controls #w_prev-light, .img-wrapper #w_prev-light').addClass('disabled');
                }
            }
            
            if($lightbox.find('#dotnav').length){
                $lightbox.find('#dotnav li a').removeClass('current-dot');
                $lightbox.find('#dotnav #goto-'+current).addClass('current-dot');
            }
            
        });
        
        $lightbox.on('openLight',function(){
           isOpen = true; 
        });
        
        $lightbox.on('closeLight',function(){
           isOpen = false; 
        });
        
        
        
        
        /* ============================== */
        /* FUNCTIONS                      */
        /* ============================== */
        
        $lightbox.openLightbox = function(){

            $('body').on('touchmove', function(e) {
                    e.preventDefault();
            }, false);
            
            $lightbox.addClass('open');
            $lightbox.addClass('open-'+wlbOption.openAnimation);
            
            
            setTimeout(function(){
                $lightbox.addClass(wlbOption.openAnimation);
                $lightbox.trigger('openLight', $currentLight);
            },2);
        }
        
        
        $lightbox.closeLightbox = function(){
            
            $lightbox.removeClass(wlbOption.openAnimation);
            
            $lightbox.find('.img-wrapper').on('transitionend webkitTransitionEnd oTransitionEnd otransitionend MSTransitionEnd',function(){
                
                $('body').unbind('touchmove')
                
                $lightbox.removeClass('open').removeClass('open-'+wlbOption.openAnimation);
                
                $lightbox.find('.current').removeClass('current');
                
                $lightbox.find('.img-wrapper').unbind('transitionend webkitTransitionEnd oTransitionEnd otransitionend MSTransitionEnd')
            });
            
            
            
            $lightbox.trigger('closeLight', $currentLight);
        }
        
        
        
        
        $lightbox.gotoLight = function(id){
            
            isAnimate = true;
            $lightbox.trigger('changeLight', id);
            
            $lightbox.find('li').removeClass(wlbOption.changeAnimation+' current old-current');
            
            var currentMove = $lightbox.find('li.current');
            var currentDirection = '';
            var newCurrentDirection = '';
            
            if(currentMove.length)
            {
                var currentValue = parseInt(currentMove.attr('id').replace('light-item-',''));
                
                if(currentValue>=id)
                {
                    currentDirection = 'rtl';
                    newCurrentDirection = 'ltr';
                }
                else
                {
                    currentDirection = 'ltr';
                    newCurrentDirection = 'rtl';
                }
                currentMove.addClass(wlbOption.dotNavigationAnimation+' '+currentDirection+' old-current').removeClass('current');
                
                
            }
            else
            {
                $lightbox.find('#light-item-'+id).addClass('current');
                isAnimate = false;
            }

            $lightbox.ajaxLoad(id);
            
            if(wlbOption.dotNavigationAnimation!='none'){
                
                if(currentMove.length){
                    var iteration = 1;
                    $(currentMove[0]).on('transitionend webkitTransitionEnd oTransitionEnd otransitionend MSTransitionEnd', 
                    function() {
                        
                        if(iteration <2){
                            
                            $lightbox.find('#light-item-'+id).addClass(wlbOption.dotNavigationAnimation+' '+newCurrentDirection);
                            
                            currentMove.removeClass(wlbOption.dotNavigationAnimation+' '+currentDirection+' old-current');
                            
                            setTimeout(function(){
                                $lightbox.find('#light-item-'+id).addClass('current').removeClass(wlbOption.dotNavigationAnimation+' '+newCurrentDirection);
                            },10);
                            
                            isAnimate = false;
                            
                            $(currentMove[0]).unbind('transitionend webkitTransitionEnd oTransitionEnd otransitionend MSTransitionEnd')
                            
                            iteration++;
                        }
                        
                    });
                }

            }else{
                
                $(this).removeClass(wlbOption.dotNavigationAnimation+' old-current');
                $lightbox.find('#light-item-'+id).addClass('current').removeClass(wlbOption.dotNavigationAnimation);
                isAnimate = false;  
            }
        }
        
        
        
        
        
        $lightbox.nextLight = function(){
            
            if(!$lightbox.find('#w_next-light').hasClass('disabled')){
                
                isAnimate = true;
                var currentId = "";
            
                currentId = $lightbox.find('.current');
                $currentLight = parseInt(currentId.attr('id').replace('light-item-',''));

                if($currentLight >= total)
                {
                    $currentLight = 1;
                }else{
                    $currentLight = parseInt($currentLight+1);
                }

                currentId.removeClass('current').addClass(wlbOption.changeAnimation+' old-current ltr');
                $lightbox.find('#light-item-'+$currentLight).addClass(wlbOption.changeAnimation+' rtl');


                if(wlbOption.changeAnimation!='none'){

                    currentId.on('transitionend webkitTransitionEnd oTransitionEnd otransitionend MSTransitionEnd', 
                    function() {
                        $(this).removeClass(wlbOption.changeAnimation+' old-current ltr');
                        $lightbox.find('#light-item-'+$currentLight).addClass('current').removeClass(wlbOption.changeAnimation+' rtl');
                        isAnimate = false;
                        
                        currentId.unbind('transitionend webkitTransitionEnd oTransitionEnd otransitionend MSTransitionEnd');
                        
                    });

                }else{

                    $(this).removeClass(wlbOption.changeAnimation+' old-current ltr');
                    $lightbox.find('#light-item-'+$currentLight).addClass('current').removeClass(wlbOption.changeAnimation+' rtl');
                    isAnimate = false;
                }
                
                
                $lightbox.ajaxLoad($currentLight);


                $lightbox.trigger('nextLight', $currentLight);
                $lightbox.trigger('changeLight', $currentLight);
            
            }
            
        }
        
        
        $lightbox.prevLight = function(){
            
            if(!$lightbox.find('#w_prev-light').hasClass('disabled')){
                
                isAnimate = true;
                var currentId = "";
            
                currentId = $lightbox.find('.current');
                console.log(currentId);
                $currentLight = parseInt(currentId.attr('id').replace('light-item-',''));

                if($currentLight <= 1){
                    $currentLight = total;
                }else{
                    $currentLight = parseInt($currentLight-1);
                }

                currentId.removeClass('current').addClass(wlbOption.changeAnimation+' old-current rtl');

                if(wlbOption.changeAnimation!='none'){

                    currentId.on('transitionend webkitTransitionEnd oTransitionEnd otransitionend MSTransitionEnd', 
                    function() {
                        
                        $lightbox.find('#light-item-'+$currentLight).addClass(wlbOption.changeAnimation+' ltr');
                        
                        $(this).removeClass(wlbOption.changeAnimation+' old-current rtl');
                        setTimeout(function(){

                            $lightbox.find('#light-item-'+$currentLight).addClass('current').removeClass(wlbOption.changeAnimation+' ltr');
                        },10);
                        isAnimate = false;
                        
                        currentId.unbind('transitionend webkitTransitionEnd oTransitionEnd otransitionend MSTransitionEnd');
                    });

                }else{

                    $(this).removeClass(wlbOption.changeAnimation+' old-current rtl');
                    $lightbox.find('#light-item-'+$currentLight).addClass('current').removeClass(wlbOption.changeAnimation+' ltr');
                    isAnimate = false;
                }
                
                
                $lightbox.ajaxLoad($currentLight);

                $lightbox.trigger('prevLight', $currentLight);
                $lightbox.trigger('changeLight', $currentLight);
                
            }
            
        }
        
        $lightbox.ajaxLoad = function(id){
            
            $lightbox.trigger('ajaxLight');
                            
            if($lightbox.find('#light-item-'+id).hasClass('w-ajax-wrap')){
                var ajaxHref = $lightbox.find('#light-item-'+id).attr('data-href');

               $.get( ajaxHref, function( data ) {
                $( $lightbox.find('#light-item-'+id+' .w-ajax-container') ).html( data );
                if (typeof callback !== 'undefined') {
                 callback();
                }
               });
            }
            
        }
        
        // IMPLEMENTATION DE jquery-mobile-event.js
        if(wlbOption.touchEvents){
            
            try{

                $lightbox.swipeleft(function(){
                    $lightbox.nextLight();
                })

                $lightbox.swiperight(function(){
                    $lightbox.prevLight();
                })

                $lightbox.swipedown(function(){
                    $lightbox.closeLightbox();
                })

                $lightbox.swipeup(function(){
                    $lightbox.closeLightbox();
                })

            }catch(error){
                
                console.log("Don't forget to include jquery-mobile-event.js before lightbox's javascript file.")
                console.log(error);

            }
            
        }
        
        
        
        /* GOOGLE IMAGE POPIN  STYLE */
        
        if(wlbOption.googleImageStyle){
            
            $lightbox.addClass('gstyle');
            var $parentContainer = $(wlbOption.googleImageWrap);
            var $parentWrapper;
            
            $lightbox.on('changeLight',function(event, id){

                $('#wlightbox-split').remove();

                /* Define which elem is clicked */
                var $elemParent = '';
                var selectedElemPosX = false;
                var winner = false;
                var $breakLine = false;

                /* Find where to split lines */
                $parentContainer.each(function(i){
                    console.log($(this).parent().attr('id'));

                    if(!selectedElemPosX){
                       if(i == id-1){
                           $elemParent = $(this);
                           selectedElemPosX = $(this).offset().top;
                           $parentWrapper = $(this).parent();
                       }
                    }else{
                        if(!winner){
                            if($(this).offset().top > selectedElemPosX){
                                $breakLine = $(this);
                                winner = true;
                            }
                        }
                    }
                });

                /* If it's the last line */
                if(!$breakLine){

                    $parentWrapper.find($parentContainer).last().after('<div id="wlightbox-split"></div>');
                    $('#wlightbox-split').addClass('open');

                }else{

                    $parentWrapper.find($breakLine).before('<div id="wlightbox-split"></div>');
                    $('#wlightbox-split').addClass('open');
                }
                
                $lightbox.css('top',$('#wlightbox-split').offset().top);
                $('html, body').animate({scrollTop : $('#wlightbox-split').offset().top -300},550);

            });
            
            $lightbox.on('closeLight',function(event, id){
                $('#wlightbox-split').css('height', $('#wlightbox-split').height());
                $('#wlightbox-split').animate({'height': '0px', padding : 0, margin : 0},250, function(){
                    //$(this).remove();
                });
            });
        }
        
        
        
        
        
        
        // RETURNS
        
        $lightbox.total = function(param){
            return total;
        }
     
        return $lightbox;
        
    }
    
    
    
    
});