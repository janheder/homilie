/***  LAZY LOAD  ***/

$("img").unveil(200, function() {
  $(this).on(function() {
    this.style.opacity = 1;
  });
});


/***  SCROLL NAV  ***/

$(function() {
    //caches a jQuery object containing the header element
    var header = $(".navbar");
         header.addClass("no-scroll");
    $(window).scroll(function() {
        var scroll = $(window).scrollTop();

        if (scroll >= 0) {
            header.removeClass('no-scroll').addClass("scroll");
        }         if (scroll <= 0) {
            header.removeClass("scroll").addClass('no-scroll');
        }
    });
});

$(function() {
    //caches a jQuery object containing the header element
    var header2 = $(".no-scroll");

        var scroll2 = $(window).scrollTop();

        if (scroll2 > 0) {
            header2.removeClass('no-scroll').addClass("scroll");
        }         if (scroll < 0) {
            header2.removeClass("scroll").addClass('no-scroll');
        }
});




var styletotest = "object-fit";

if (styletotest in document.body.style)
{
    /*alert("The " + styletotest + " property is supported");*/

} else {

    /*alert("The " + styletotest + " property is NOT supported"); */

      $('.post__image-container').each(function () {
    var $container = $(this),
        imgUrl = $container.find('img').attr('data-src');
    if (imgUrl) {
        $(this).css('backgroundImage', 'url(' + imgUrl + ')')
        /*$('.post__image-container').addClass('compat-object-fit');*/
        $('.post__featured-image').css('display','none');
    }  
  });

}


  $(".navbar-toggler").click(function() { 
    $("body").toggleClass("active");
    $(".navbar-toggler").toggleClass("collapsed");
    $("#dark-overlay").toggleClass("active");
  });

  $("#dark-overlay").click(function() { 
    $("body").toggleClass("active");
    $(".navbar-toggler").toggleClass("collapsed");
    $(".navbar-toggler").attr("aria-expanded","false");
    $("#dark-overlay").toggleClass("active");
  });

  $("#homilie-toggler").click(function() { 
    $(".homilie").toggleClass("full");
  });

  $("#search-toggler, #search-toggler-responsive").click(function() { 
    $(".search-form-wrap").fadeToggle(200);
    $("#search-toggler, #search-toggler-responsive").toggleClass("open");
  });



  // Select all links with hashes
$('a[href*="#"]')
  // Remove links that don't actually link to anything
  .not('[href="#"]')
  .not('[href="#0"]')
  .click(function(event) {
    // On-page links
    if (
      location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') 
      && 
      location.hostname == this.hostname
    ) {
      // Figure out element to scroll to
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
      // Does a scroll target exist?
      if (target.length) {
        // Only prevent default if animation is actually gonna happen
        event.preventDefault();
        $('html, body').animate({
          scrollTop: target.offset().top - 80 
        }, 1000, function() {
          // Callback after animation
          // Must change focus!
          var $target = $(target);
          $target.focus();
          if ($target.is(":focus")) { // Checking if the target was focused
            return false;
          } else {
            $target.attr('tabindex','-1'); // Adding tabindex for elements not focusable
            $target.focus(); // Set focus again
          };
        });
      }
    }
  });
  
  
  
  
  if ( $(".print-area").length ) {
    $("<style>")
    .prop("type", "text/css")
    .html("\
    @media print {\
        body {\
            position: relative;\
            margin: 45mm 0mm 45mm 0mm;\
        }\
        body * {\
            visibility: hidden;\
            position: absolute;\
            left: 0px;\
            right: 0px;\
            top: 0px;\
            width: 100%;\
        }\
        .print-area, .print-area *{\
            visibility: visible;\
            position: relative;\
            width: 100%;\
        }\
        @page {size: 100%;  margin: 20mm 0mm;}\
    }")
    .appendTo("body");
} 
