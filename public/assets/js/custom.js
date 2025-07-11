/*-----------------------------------------------------------------------------------

    Template Name: Gym On

    Note: This is Custom Js file

-----------------------------------------------------------------------------------

    Js INDEX

    ===================

        01 marquee_text
        02 Counter Style One
        03 Team Slider
        04 Featured Slider One
        05 f-slider-three
        06 myslider
        07 Client Slider
        08 Client Review Slider
        09 c-slider
        10 blog-slider
        11 Nice Select
        12 P-Slider
        13 Project Detail Slider
        14 c-data
        15 Products List Grid
        16 wwb-ul li
        17 mobile-nav
        18 #mobile-menu
        19 #desktop-menu
        20 #res-cross
        21 li-pd-imgs
        22 Cart Popup
        23 Sticky Header
        24 Preloader
        25. days time
        26 scrollTop

-----------------------------------------------------------------------------------*/



jQuery(document).ready(function ($) {

    /* 01 marquee_text */

    if ( $.isFunction($.fn.marquee) ) {
    $('.marquee_text').marquee({
        direction: 'left',
        duration: 20000,
        gap: 50,
        delayBeforeStart: 0,
        duplicated: true,
        startVisible: true
    });
    }
 
    "use strict";
 
    /* 02 Counter Style One */
    if ($(".counter")[0]){
        $('.counter').counterUp({
            delay: 10,
            time: 1000
        });
    }

    /* 03 Team Slider */ 
    if ($(".team-slider")[0]){
        $('.team-slider.owl-carousel').owlCarousel({
             loop: true,
            nav:true,
            navText: ["<i class='fa-solid fa-arrow-left-long'></i>","<i class='fa-solid fa-arrow-right-long'></i>"],
            dots: false,
            touchDrag  : false,
            mouseDrag  : false, 
            margin: 10,
            navContainer: '.team-slider-nav',
            responsive:{
                0:{
                    items:1
                },
                756:{
                    items:2
                },
                992:{
                    items:3
                },
                1200:{
                    items:4
                }
            }
        }); 
    }
    
    /* 04 Featured Slider One */ 
     if ($(".f-slider-one")[0]){
        $('.f-slider-one.owl-carousel').owlCarousel({
            items:1,
            loop: true,
            margin:0,
            stagePadding: 0,
            dots: true, 
            autoplay:true,   
            animateOut: 'fadeOut', 
            touchDrag  : false,
            mouseDrag  : false
        });
    }
    /* 05 f-slider-three */ 
    if ($(".f-slider-three")[0]){
        $('.f-slider-three').owlCarousel({
            items:1,
            loop: true,
            margin:0,
            stagePadding: 0,
            dots: true,  
            autoplay:true,   
            smartSpeed:2000, 
        });
    }
    /* 06 myslider */ 
    if ($(".myslider")[0]){
    $('.myslider').owlCarousel({
                items:1,
                loop:true,
                dots: true,
                smartSpeed:1000,
                dotsData: true,
                nav: false,
                autoplay:true,
                mouseDrag:false,
        });
    }
    //* 07 Client Slider *//
    if ($(".client-slider")[0]){
        $('.client-slider.owl-carousel').owlCarousel({
            items:5,
            autoplay: true,
            autoplayTimeout: 3000,
            autoplayHoverPause: false,
            dots: false,
            responsive:{
                0:{
                    items:1
                },
                600:{
                    items:2
                },
                800:{
                    items:3
                },
                1000:{
                    items:5
                }
            }
        });
    }
    //* 08 Client Review Slider *// 
    if ($(".client-review-slider")[0]){
        $('.client-review-slider.owl-carousel').owlCarousel({
            items:1,
            autoplay: true,
            autoplayTimeout: 3000,
            autoplayHoverPause: false,
            dots: true,
        });
    }

    //* 09 c-slider *// 
    if ($(".c-slider")[0]){
        $('.c-slider.owl-carousel').owlCarousel({
            loop: true,
            items:1,
            dots: false,
            autoplay: true,
            autoplayTimeout: 3000,
            autoplayHoverPause: false,
            nav:true,
            navText: ["<i class='fa-solid fa-arrow-left-long'></i>","<i class='fa-solid fa-arrow-right-long'></i>"],
            responsive:{
                0:{
                    nav: false,
                },
                768:{
                    nav: true
                }
            }
        });
    }

    //* 10 blog-slider *// 
    if ($(".blog-slider")[0]){
        $('.blog-slider.owl-carousel').owlCarousel({
            items:3,
            center: true,
            loop: true,
            margin:12,
            dots:true,
            autoplay: true,
            autoplayTimeout: 3000,
            autoplayHoverPause: false,
            responsive:{
                0:{
                    items:1
                },
                768:{
                    center: false,
                    items:2
                },
                1000:{
                    items:3
                }
            }
        });
    }
    //* 11 Nice Select *// 
    if ($("select")[0]){
        $('select').niceSelect();
    }

    //* 12  P-Slider *//  
    if ($(".p-slider.owl-carousel")[0]){
        $('.p-slider.owl-carousel').owlCarousel({
            items:3,
            loop: true,
            center: true,
            dots:true,
            autoplay: true,
            autoplayTimeout: 3000,
            autoplayHoverPause: false,
            responsive:{
                0:{
                    items:1
                },
                768:{
                    center: false,
                    items:2,
                },
                1100:{
                    items:3
                }
            }
        });
    }

    //* 13 Project Detail Slider *//  
    if ($(".p-d-slider")[0]){
        $('.p-d-slider.owl-carousel').owlCarousel({
            items:1,
            dots:true,
        });
    }
  
    //* 14 c-data *// 
    $(".contact-us .c-data ul li").click(function(){
        $(".contact-us .c-data a").removeClass("active");
        $(this).children("a").addClass("active");

        var m_index = $(this).index();


        if ( m_index == 0) {
            $(".c-cards .card").removeClass("active")
            $($(".c-cards .card:nth-child(1)")).addClass("active");
            console.log("yes")
        };
        if ( m_index == 1) {
            $(".c-cards .card").removeClass("active")
            $($(".c-cards .card:nth-child(2)")).addClass("active");
            console.log("yes")
        };
        if ( m_index == 2) {
            $(".c-cards .card").removeClass("active")
            $($(".c-cards .card:nth-child(3)")).addClass("active");
            console.log("yes")
        };
        if ( m_index == 3) {
            $(".c-cards .card").removeClass("active")
            $($(".c-cards .card:nth-child(4)")).addClass("active");
            console.log("yes")
        };
        if ( m_index == 4) {
            $(".c-cards .card").removeClass("active")
            $($(".c-cards .card:nth-child(5)")).addClass("active");
            console.log("yes")
        };
    }); 

    // if ($(".blog-posts.grid")[0]){
    //     var elem = document.querySelector('.grid');
    //     var msnry = new Masonry( elem, {
    //       itemSelector: '.grid-item',
    //       gutter: 70,
    //     });
    // }

    //* 15 Products List Grid *//
     if ($(".shop-filter")[0]){
        $(".shop-filter a.list").click(function(){
          $(".p-slider").removeClass("grid");
          $(".p-slider").addClass("list");
        });

        $(".shop-filter a.grid").click(function(){
          $(".p-slider").removeClass("list");
          $(".p-slider").addClass("grid");
        });
    }
 

    //* 16 wwb-ul li *//
    $(".wwb-ul li").hover(function(){
      $(".wwb-ul li").removeClass("active");
      $(this).addClass("active");
    });
    //* 17 mobile-nav *//
    $('.mobile-nav .menu-item-has-children').on('click', function(event) {
      $(this).toggleClass('active');
      event.stopPropagation();
    }); 
    //* 18 #mobile-menu *//
    $('#mobile-menu').click(function(){
        $(this).toggleClass('open');
        $('#mobile-nav').toggleClass('open');
    });
    //* 19 #desktop-menu *//
    $('#desktop-menu').click(function(){
        $(this).toggleClass('open');
        $('.desktop-menu').toggleClass('open');
    });
    //* 20 #res-cross *//
    $('#res-cross').click(function(){
        $('#mobile-nav').removeClass('open');
        $('#mobile-menu').removeClass('open')
    });

    // 21 li-pd-imgs
    $('.li-pd-imgs').on('click', function() {

      var img_src = "";

      $('.li-pd-imgs.nav-active').removeClass('nav-active');

      $(this).addClass('nav-active');

      img_src = $(this).find('img').attr('src');

      $('#NZoomContainer').children('img').attr('src', img_src);

    });

    // 22 Cart Popup
    var boxWidth = $("#lightbox").width();
                $(".white_content").animate({
                    opacity: 0,
                    width:0,
                    right : -10000
            });
            $("#close").on('click',function(){ 
            $(".white_content").animate({
                opacity: 0,
                width:0,
                right : -1000
            });
            });
            $("#show").on('click',function(){ 
            $(".white_content").animate({
                opacity: 1,
                right :0
            });

        }); 

    // 23 Sticky Header
    var new_scroll_position = 0;

        var last_scroll_position;

        var header = document.getElementById("stickyHeader");



        window.addEventListener('scroll', function(e) {

        last_scroll_position = window.scrollY;



        // Scrolling down

        if (new_scroll_position < last_scroll_position && last_scroll_position > 100) {

          // header.removeClass('slideDown').addClass('slideUp');

          header.classList.remove("slideDown");

          header.classList.add("slideUp");



        // Scroll top

        } 

        else if (last_scroll_position < 100) {

          header.classList.remove("slideDown");

        } 

        else if (new_scroll_position > last_scroll_position) {

          header.classList.remove("slideUp");

          header.classList.add("slideDown");

        }
 
          new_scroll_position = last_scroll_position;

        });

}); 

    // 24 Preloader
$(window).on('load', function () {
    $("body").addClass("page-loaded"); 
});

 
if($('.tabs-box').length){
        $('.tabs-box .tab-buttons .tab-btn').on('click', function(e) {
            e.preventDefault();
            var target = $($(this).attr('data-tab'));
            
            if ($(target).is(':visible')){
                return false;
            }else{
                target.parents('.tabs-box').find('.tab-buttons').find('.tab-btn').removeClass('active-btn');
                $(this).addClass('active-btn');
                target.parents('.tabs-box').find('.tabs-content').find('.tab').fadeOut(0);
                target.parents('.tabs-box').find('.tabs-content').find('.tab').removeClass('active-tab');
                $(target).fadeIn(300);
                $(target).addClass('active-tab');
            }
        });
    }
 
    /* 25. days time  */

if(jQuery("#days").length){

    (function () {
        const second = 1000,
        minute = second * 60,
        hour = minute * 60,
        day = hour * 24;
 
      let today = new Date(),
          dd = String(today.getDate()).padStart(2, "0"),
          mm = String(today.getMonth() + 1).padStart(2, "0"),
          yyyy = today.getFullYear(),
          nextYear = yyyy + 1,
          dayMonth = "9/21/",
          birthday = dayMonth + yyyy;
      
      today = mm + "/" + dd + "/" + yyyy;
      if (today > birthday) {
        birthday = dayMonth + nextYear;
      }
      //end
      
      const countDown = new Date(birthday).getTime(),
          x = setInterval(function() {    

            const now = new Date().getTime(),
                  distance = countDown - now;

            document.getElementById("days").innerText = Math.floor(distance / (day)),
              document.getElementById("hours").innerText = Math.floor((distance % (day)) / (hour)),
              document.getElementById("minutes").innerText = Math.floor((distance % (hour)) / (minute)),
              document.getElementById("seconds").innerText = Math.floor((distance % (minute)) / second);

            //do something later when date is reached
            if (distance < 0) {
              document.getElementById("headline").innerText = "event";
              document.getElementById("countdown").style.display = "none";
              document.getElementById("content").style.display = "block";
              clearInterval(x);
            }
            //seconds
          }, 0)
      }());
} 

// 26 scrollTop
function scrollTopPercentage() {
            const scrollPercentage = () => {
                const scrollTopPos = document.documentElement.scrollTop;
                const calcHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
                const scrollValue = Math.round((scrollTopPos / calcHeight) * 100);
                const scrollElementWrap = $("#scroll-percentage");

                scrollElementWrap.css("background", `conic-gradient( #fff ${scrollValue}%, #411111 ${scrollValue}%)`);
                
                // ScrollProgress
                if ( scrollTopPos > 100 ) {
                    scrollElementWrap.addClass("active");
                } else {
                    scrollElementWrap.removeClass("active");
                }

                if( scrollValue < 99 ) {
                    $("#scroll-percentage-value").text(`${scrollValue}%`);
                } else {
                    $("#scroll-percentage-value").html('<i class="fa-solid fa-arrow-up-long"></i>');
                }
            }
            window.onscroll = scrollPercentage;
            window.onload = scrollPercentage;
           // Back to Top
            function scrollToTop() {
                document.documentElement.scrollTo({
                    top: 0,
                    behavior: "smooth"
                });
            }
            $("#scroll-percentage").on("click", scrollToTop);
        } 
        scrollTopPercentage();  

        function reveal1() {
    var reveals = document.querySelectorAll(".reveal1");

    for (var i = 0; i < reveals.length; i++) {
      var windowHeight = window.innerHeight;
      var elementTop = reveals[i].getBoundingClientRect().top;
      var elementVisible = 150;
      if (elementTop < windowHeight - elementVisible) {
        reveals[i].classList.add("active");
      }
      //  else {
      //   reveals[i].classList.remove("active");
      // }
    }
  }
  window.addEventListener("scroll", reveal1); 
 

$(document).ready(function() {
    console.log('Custom JS loaded successfully');
    
    // Dark overlay functionality for hero sliders
    function addHeroSliderOverlay() {
        // Find all potential hero/main slider containers
        var sliderSelectors = [
            '.featured-slider-one',
            '.featured-slider-two', 
            '.myslider',
            '.hero-slider',
            '.banner-slider',
            '.main-slider',
            '[class*="slider"]:has(.owl-carousel)',
            '.owl-carousel:first'
        ];
        
        sliderSelectors.forEach(function(selector) {
            var $slider = $(selector);
            if ($slider.length > 0 && $slider.find('.owl-item').length > 0) {
                console.log('Found slider:', selector);
                
                // Add overlay class to the slider
                $slider.addClass('has-dark-overlay');
                
                // Ensure owl items have relative positioning
                $slider.find('.owl-item').each(function() {
                    var $item = $(this);
                    if ($item.css('position') !== 'relative') {
                        $item.css('position', 'relative');
                    }
                    
                    // Add overlay if it doesn't exist
                    if ($item.find('.dark-overlay').length === 0) {
                        $item.append('<div class="dark-overlay"></div>');
                    }
                });
                
                // Ensure text content has proper z-index
                $slider.find('.f-slider-one-data, .featured-text-two, .slider-content, .slide-content').each(function() {
                    $(this).css({
                        'position': 'relative',
                        'z-index': '2'
                    });
                });
                
                // Ensure navigation elements have proper z-index
                $slider.find('.owl-dots, .owl-nav').css({
                    'position': 'relative',
                    'z-index': '3'
                });
            }
        });
    }
    
    // Initialize overlay on page load
    addHeroSliderOverlay();
    
    // Re-initialize after any dynamic content loads
    setTimeout(function() {
        addHeroSliderOverlay();
    }, 1000);
    
    // Handle owl carousel initialization
    $(document).on('initialized.owl.carousel', function(event) {
        setTimeout(function() {
            addHeroSliderOverlay();
        }, 100);
    });
}); 

// Custom JavaScript for Dark Overlay Enhancement

$(document).ready(function() {
    // Function to add dark overlay to sliders
    function addDarkOverlay() {
        // Target all owl-carousel containers
        $('.owl-carousel').each(function() {
            var $carousel = $(this);
            
            // Add has-dark-overlay class for CSS targeting
            $carousel.addClass('has-dark-overlay');
            
            // Add overlay to each item if not already present
            $carousel.find('.owl-item').each(function() {
                var $item = $(this);
                
                // Check if overlay already exists
                if ($item.find('.dark-overlay').length === 0) {
                    // Add overlay div
                    var $overlay = $('<div class="dark-overlay"></div>');
                    $item.css('position', 'relative').prepend($overlay);
                    
                    // Ensure content is above overlay
                    $item.find('.hero-three, .f-slider-one-data, .featured-text-two').css({
                        'position': 'relative',
                        'z-index': '2'
                    });
                }
            });
        });
        
        // Specifically target f-slider-three for hero slider
        $('.f-slider-three .owl-item').each(function() {
            var $item = $(this);
            
            // Ensure hero-three content is properly positioned
            $item.find('.hero-three').css({
                'position': 'relative',
                'z-index': '2'
            });
            
            // Enhance text visibility
            $item.find('.hero-three h1, .hero-three p, .hero-three span').css({
                'text-shadow': '2px 2px 4px rgba(0, 0, 0, 0.35)',
                'color': '#ffffff'
            });
            
            // Enhance button visibility
            $item.find('.theme-btn').css({
                'position': 'relative',
                'z-index': '3',
                'box-shadow': '0 4px 15px rgba(0, 0, 0, 0.3)'
            });
        });
        
        // Ensure navigation elements are above overlay
        $('.owl-carousel .owl-nav, .owl-carousel .owl-dots').css({
            'position': 'relative',
            'z-index': '10'
        });
    }
    
    // Apply overlay immediately
    addDarkOverlay();
    
    // Reapply after owl carousel is initialized
    $(window).on('load', function() {
        setTimeout(function() {
            addDarkOverlay();
        }, 500);
    });
    
    // Reapply when owl carousel items are created/refreshed
    $('.owl-carousel').on('initialized.owl.carousel refreshed.owl.carousel', function() {
        setTimeout(function() {
            addDarkOverlay();
        }, 100);
    });
    
    // Handle dynamic content loading
    if (typeof MutationObserver !== 'undefined') {
        var observer = new MutationObserver(function(mutations) {
            var shouldReapply = false;
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                    for (var i = 0; i < mutation.addedNodes.length; i++) {
                        var node = mutation.addedNodes[i];
                        if (node.nodeType === 1) { // Element node
                            if ($(node).hasClass('owl-item') || $(node).find('.owl-item').length > 0) {
                                shouldReapply = true;
                                break;
                            }
                        }
                    }
                }
            });
            
            if (shouldReapply) {
                setTimeout(function() {
                    addDarkOverlay();
                }, 100);
            }
        });
        
        // Observe the entire document for changes
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }
});

// Additional fallback for ensuring overlay is applied
$(window).on('resize orientationchange', function() {
    setTimeout(function() {
        $('.owl-carousel .owl-item').each(function() {
            var $item = $(this);
            if ($item.find('.dark-overlay').length === 0) {
                var $overlay = $('<div class="dark-overlay"></div>');
                $item.css('position', 'relative').prepend($overlay);
            }
        });
    }, 300);
}); 

/* Newsletter Form Handler */
$('#newsletter-form').on('submit', function(e) {
    e.preventDefault();
    
    const form = $(this);
    const submitBtn = form.find('button[type="submit"]');
    const emailInput = form.find('input[name="email"]');
    const formData = form.serialize();
    
    // Disable submit button
    submitBtn.prop('disabled', true);
    submitBtn.html('<i class="fa-solid fa-spinner fa-spin"></i>');
    
    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                // Show success message
                form.after('<div class="alert alert-success mt-2 newsletter-message">' + response.message + '</div>');
                emailInput.val(''); // Clear the input
            } else {
                // Show error message
                form.after('<div class="alert alert-danger mt-2 newsletter-message">An error occurred. Please try again.</div>');
            }
        },
        error: function(xhr) {
            let errorMessage = 'An error occurred. Please try again.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                errorMessage = Object.values(xhr.responseJSON.errors).flat().join(', ');
            }
            form.after('<div class="alert alert-danger mt-2 newsletter-message">' + errorMessage + '</div>');
        },
        complete: function() {
            // Re-enable submit button
            submitBtn.prop('disabled', false);
            submitBtn.html('<i class="fa-solid fa-arrow-up-long"></i>');
            
            // Remove any existing messages
            $('.newsletter-message').delay(5000).fadeOut();
        }
    });
}); 