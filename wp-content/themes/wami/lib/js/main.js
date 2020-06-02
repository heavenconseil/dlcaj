jQuery(function($){
    
    $(document).ready(function(){
        
        //TOGGLE RESPONSIVE MENU
        $('#responsive-show-menu').on('click',function(e){
            e.preventDefault();
            $('#header-container, #responsive-show-menu').toggleClass('responsive-show')
        });
        
        
        //HOME SLIDER
        var $homeSlider = $('#home-slider');
        if($homeSlider.length){            
            // init
            var $currentSlide = $homeSlider.find('.item-slider').eq(0);
            var currentSlidePos = 0;
            var totalSlide = $homeSlider.find('.item-slider').length;
            $currentSlide.addClass('active');            
            // launch slider
            setInterval(function(){                
                currentSlidePos = currentSlidePos+1;                
                if(currentSlidePos >= totalSlide){
                    currentSlidePos = 0;
                }                
                $currentSlide.removeClass('active');
                $currentSlide = $homeSlider.find('.item-slider').eq(currentSlidePos);
                $currentSlide.addClass('active');                
            },2500);            
        }   
        

        //FIXED MENU ON HOMEPAGE + parralax effect
        var $homeHeader = $('body.home #header');
        if($homeHeader.length && $(window).width() > 1000){
            var windowHeight = $(window).height();

            $(window).on('scroll',function(e){
                var currentScroll = $(window).scrollTop();
                var percentScroll = Math.floor((100*currentScroll)/windowHeight);
                percentScroll = percentScroll/100;

                if(percentScroll < 0.9){
                    $homeHeader.css('background', 'rgba(94,92,77,'+percentScroll+')');
                    $('#header #header-container').css('border-bottom','1px solid white');
                    $('#enter #home-slider').css('transform','translateY('+percentScroll*550+'px)');
                }else{
                    $homeHeader.css('background', 'rgba(94,92,77,1)');
                    $('#header #header-container').css('border-bottom','1px solid transparent');
                }
            });
        }  
        
        
        // SMOOTH SCROLL HOMEPAGE
        var $enterLink = $('#cta-next a');
        if($enterLink.length){
            $enterLink.on('click',function(e){
                e.preventDefault();
                var windowHeight = $(window).height();
                var headerHeight = $('#header').height();
                var scrollTo = windowHeight-headerHeight
                $('body, html').animate({scrollTop:scrollTo},750);
            })
        }
        
        
        // SLIDER PAGE SINGLE ANNONCE
        var $annonceSliderPhoto = $('#annonce-slider-photos .owl-carousel');
        if($annonceSliderPhoto.length){            
            var annonceSlider = $annonceSliderPhoto.owlCarousel({
                item: 1,
                center: true,
                loop : true, 
                nav: false,                
                responsive : {
                    0 : {
                        autoWidth: false,
                        items: 1
                    },                    
                    900: {
                        autoWidth: true
                    }
                },                
                onInitialized: function(){                    
                    // Add custom prev/next links to dots
                    var $dotsContainer = $('#annonce-slider-photos .owl-carousel .owl-dots');
                    var $customNavContainer = $('#annonce-slider-photos #owl-custom-nav');                    
                    $customNavContainer.append('<a href="#" id="owl-custom-prev">prev</a>');
                    $customNavContainer.append($dotsContainer);
                    //  $dotsContainer.remove();
                    $customNavContainer.append('<a href="#" id="owl-custom-next">next</a>');
                }
            });
            
            // Custom prev link trigger
            $('body').on('click', '#owl-custom-prev', function(e){
                e.preventDefault()
                annonceSlider.trigger('prev.owl.carousel')
            });
            
            // Custom next link trigger
            $('body').on('click', '#owl-custom-next', function(e){
                e.preventDefault()
                annonceSlider.trigger('next.owl.carousel')
            });
            
        };

        //SINGLE ANNONCE LANCER DIAPORAMA
        if($annonceSliderPhoto.length){
            var lightbox = $('.owl-item ').not('.cloned').find('.js-lightbox').wlightbox({
                openAnimation : 'scale',
                changeAnimation : 'swipe',
                looping: false,
                touchEvents: true,
                controlFontAwesome: true,
                cornerFontAwesome: true,
                fullScreenMode: '720'
            });
                     
        }
        // SLIDER PAGE RENDEZ-VOUS
        var $temoignageSlider = $('#ambassadors .owl-carousel');
        if($temoignageSlider.length){            
            var annonceSlider = $temoignageSlider.owlCarousel({
                item: 3,
                center: true,
                loop : true, 
                nav: true,                
                responsive : {
                    0 : {
                        autoWidth: false,
                        items: 1
                    },                    
                    450: {
                        autoWidth: false,
                        items:2,
                        center: false
                    },                    
                    900: {
                        autoWidth: false,
                        items: 3,
                        center: true
                    }
                }
            });            
        };

        
        // PAGE D'UNE ANNONCE : GES et DES
        var $diagnostics = $('#ges');
        if($diagnostics.length){            
            var alreadyAnimated = false;
            
            if($(window).width() > 900){ // Si on est sur Desktop, on anim quand les diagnostics entre dans l'écran
                $(window).on('scroll',function(e){                    
                    var $diagnosticsHeight = $diagnostics.height();
                    var diagnosticsPos = Math.floor($diagnostics.offset().top + $diagnosticsHeight - $(window).height());

                    if($('body').scrollTop() > diagnosticsPos || $('html').scrollTop() > diagnosticsPos){
                        if(!alreadyAnimated){
                            animeDiagnostics();
                            alreadyAnimated = true;
                        };
                    };
                });
            }else{ // sinon, on lance l'aimation directment
                animeDiagnostics();
                alreadyAnimated = true;
            }            
        };
        
        function animeDiagnostics(){            
            var $diagnosticCursor = $('#diagnostics .diagnostic-shem .value');
            $diagnosticCursor.each(function(){                
                var $el = $(this);
                var cursorPalier = $(this).attr('data-palier');
                var cursorValue = $(this).attr('data-value');
                var cursorPos = (cursorPalier-1)*25;                
                // animer la position des curseurs
                $el.css('top',cursorPos+'px');                
                // animer le text des curseurs
                 $({someValue: 0}).animate({someValue: cursorValue}, {
                      duration: 1500,
                     easing: 'swing',
                      step: function() { 
                          $el.text(commaSeparateNumber(Math.round(this.someValue)));
                      }
                  });
            });
        };

        function commaSeparateNumber(val){
            while (/(\d+)(\d{3})/.test(val.toString())){
              val = val.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
            }
            return val;
        };  


        if( $('.forminput.captcha').length ) {
            $('.forminput.captcha').insertBefore($('#submit'));
        }
    
        
    });
    
});