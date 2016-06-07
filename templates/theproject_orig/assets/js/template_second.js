/* Theme Name: The Project - Responsive Website Template
 * Author:HtmlCoder
 * Author URI:http://www.htmlcoder.me
 * Author e-mail:htmlcoder.me@gmail.com
 * Version:1.0.0
 * Created:March 2015
 * License URI:http://support.wrapbootstrap.com/
 * File Description: Initializations of plugins 
 */

(function($){
	$(document).ready(function(){

		if ($('.boxed .fullscreen-bg').length>0) {
			$("body").addClass("transparent-page-wrapper");
		};

		$(window).load(function() {
			$("body").removeClass("no-trans");
		});
		// Enable Smooth Scroll only on Chrome and only on Win and Linux Systems
		var platform = navigator.platform.toLowerCase();
		if ((platform.indexOf('win') == 0 || platform.indexOf('linux') == 0) && !Modernizr.touch) {
			if ($.browser.webkit) {
				$.webkitSmoothScroll();
				console.log("hello webkit");
			}
		};
		//Show dropdown on hover only for desktop devices
		//-----------------------------------------------
		var delay=0, setTimeoutConst;
		if ((Modernizr.mq('only all and (min-width: 768px)') && !Modernizr.touch) || $("html.ie8").length>0) {
			$('.main-navigation:not(.onclick) .navbar-nav>li.dropdown, .main-navigation:not(.onclick) li.dropdown>ul>li.dropdown').hover(
			function(){
				var $this = $(this);
				setTimeoutConst = setTimeout(function(){
					$this.addClass('open').slideDown();
					$this.find('.dropdown-toggle').addClass('disabled');
				}, delay);

			},	function(){ 
				clearTimeout(setTimeoutConst );
				$(this).removeClass('open');
				$(this).find('.dropdown-toggle').removeClass('disabled');
			});
		};

		//Show dropdown on click only for mobile devices
		//-----------------------------------------------
		if (Modernizr.mq('only all and (max-width: 767px)') || Modernizr.touch || $(".main-navigation.onclick").length>0 ) {
			$('.main-navigation [data-toggle=dropdown], .header-top [data-toggle=dropdown]').on('click', function(event) {
			// Avoid following the href location when clicking
			event.preventDefault(); 
			// Avoid having the menu to close when clicking
			event.stopPropagation(); 
			// close all the siblings
			$(this).parent().siblings().removeClass('open');
			// close all the submenus of siblings
			$(this).parent().siblings().find('[data-toggle=dropdown]').parent().removeClass('open');
			// opening the one you clicked on
			$(this).parent().toggleClass('open');
			});
		};

		//Transparent Header Calculations
		var timer_tr;
		if ($(".transparent-header").length>0) {
			$(window).load(function() {
				trHeaderHeight = $("header.header").outerHeight();
				$(".transparent-header .tp-bannertimer").css("marginTop", (trHeaderHeight)+"px");
			});
			$(window).resize(function() {
				if ($(this).scrollTop()  < headerTopHeight + headerHeight -5) {
					trHeaderHeight = $("header.header").outerHeight();
					$(".transparent-header .tp-bannertimer").css("marginTop", (trHeaderHeight)+"px");
				}
			});
			$(window).scroll(function() {
				if ($(this).scrollTop() == 0 ) { 
					if(timer_tr) {
						window.clearTimeout(timer_tr);
					};
					timer_tr = window.setTimeout(function() {
						trHeaderHeight = $("header.header").outerHeight();
						$(".transparent-header .tp-bannertimer").css("marginTop", (trHeaderHeight)+"px");
					}, 300);
				};
			});
		}

		if ($(".transparent-header .slideshow").length>0) { 
			$(".header-container header.header").addClass("transparent-header-on");
		} else {
			$(".header-container header.header").removeClass("transparent-header-on");
		}

		//Full Width Slider with Transparent Header Calculations
		if ($(".transparent-header .slider-banner-fullwidth-big-height").length>0) {
			if (Modernizr.mq('only all and (max-width: 991px)')) {
				$("body").removeClass("transparent-header");
				$(".header-container header.header").removeClass("transparent-header-on");
				$(".tp-bannertimer").css("marginTop", "0px");
				$("body").addClass("slider-banner-fullwidth-big-height-removed");
			} else {
				$("body").addClass("transparent-header");
				$(".header-container header.header").addClass("transparent-header-on");
				$("body").removeClass("slider-banner-fullwidth-big-height-removed");
			}
		};

		if ($(".transparent-header .slider-banner-fullwidth-big-height").length>0 || $(".slider-banner-fullwidth-big-height-removed").length>0) {
			$(window).resize(function() { 
				if (Modernizr.mq('only all and (max-width: 991px)')) {
					$("body").removeClass("transparent-header");
					$(".header-container header.header").removeClass("transparent-header-on");
					$(".tp-bannertimer").css("marginTop", "0px");
				} else {
					$("body").addClass("transparent-header");
					$(".header-container header.header").addClass("transparent-header-on");
				}
			});
		};




		// Fixed header
		//-----------------------------------------------
		headerTopHeight = $(".header-top").outerHeight(),
		headerHeight = $("header.header.fixed").outerHeight();
		$(window).resize(function() {
			if(($(this).scrollTop() < headerTopHeight + headerHeight -5 ) && ($(window).width() > 767)) {
				headerTopHeight = $(".header-top").outerHeight(),
				headerHeight = $("header.header.fixed").outerHeight();
			}
		});
		
		$(window).scroll(function() {
			if (($(".header.fixed").length > 0)  && !($(".transparent-header .slideshow").length>0)) {
				if (($(this).scrollTop() > headerTopHeight + headerHeight) && ($(window).width() > 767)) {
					$("body").addClass("fixed-header-on");
					$(".header.fixed").addClass('animated object-visible fadeInDown');
					$(".header-container").css("paddingBottom", (headerHeight)+"px");
				} else {
					$("body").removeClass("fixed-header-on");
					$(".header-container").css("paddingBottom", (0)+"px");
					$(".header.fixed").removeClass('animated object-visible fadeInDown');
				}
			} else if ($(".header.fixed").length > 0) {
				if (($(this).scrollTop() > headerTopHeight + headerHeight) && ($(window).width() > 767)) {
					$("body").addClass("fixed-header-on");
					$(".header.fixed").addClass('animated object-visible fadeInDown');
				} else {
					$("body").removeClass("fixed-header-on");
					$(".header.fixed").removeClass('animated object-visible fadeInDown');
				}
			};
		});


		// Animations
		//-----------------------------------------------
		if (($("[data-animation-effect]").length>0) && !Modernizr.touch) {
			$("[data-animation-effect]").each(function() {
				if(Modernizr.mq('only all and (min-width: 768px)') && Modernizr.csstransitions) {
					var waypoints = $(this).waypoint(function(direction) {
						var appearDelay = $(this.element).attr("data-effect-delay"),
						animatedObject = $(this.element);
						setTimeout(function() {
							animatedObject.addClass('animated object-visible ' + animatedObject.attr("data-animation-effect"));
						}, appearDelay);
						this.destroy();
					},{
						offset: '90%'
					});
				} else {
					$(this).addClass('object-visible');
				}
			});
		};


		//Scroll totop
		//-----------------------------------------------
		$(window).scroll(function() {
			if($(this).scrollTop() != 0) {
				$(".scrollToTop").fadeIn();	
			} else {
				$(".scrollToTop").fadeOut();
			}
		});
		
		$(".scrollToTop").click(function() {
			$("body,html").animate({scrollTop:0},800);
		});
		




		// Offcanvas side navbar
		//-----------------------------------------------
		if ($("#offcanvas").length>0) {
			$('#offcanvas').offcanvas({
				canvas: "body",
				disableScrolling: false,
				toggle: false
			});
		};

		if ($("#offcanvas").length>0) {
			$('#offcanvas [data-toggle=dropdown]').on('click', function(event) {
			// Avoid following the href location when clicking
			event.preventDefault(); 
			// Avoid having the menu to close when clicking
			event.stopPropagation(); 
			// close all the siblings
			$(this).parent().siblings().removeClass('open');
			// close all the submenus of siblings
			$(this).parent().siblings().find('[data-toggle=dropdown]').parent().removeClass('open');
			// opening the one you clicked on
			$(this).parent().toggleClass('open');
			});
		};

		// Parallax section
		//-----------------------------------------------
		if (($(".parallax").length>0)  && !Modernizr.touch ){
			$(".parallax").parallax("50%", 0.2);
		};
		if (($(".parallax-2").length>0)  && !Modernizr.touch ){
			$(".parallax-2").parallax("50%", 0.3);
		};		
		if (($(".parallax-3").length>0)  && !Modernizr.touch ){
			$(".parallax-3").parallax("50%", 0.4);
		};

		// Notify Plugin
		//-----------------------------------------------
		if (($(".main-navigation.onclick").length>0) && !Modernizr.touch ){
			$.notify({
				// options
				message: 'The Dropdowns of the Main Menu, are now open with click on Parent Items. Click "Home" to checkout this behavior.'
			},{
				// settings
				type: 'info',
				delay: 10000,
				offset : {
					y: 150,
					x: 20
				}
			});
		};
		if (!($(".main-navigation.animated").length>0) && !Modernizr.touch && $(".main-navigation").length>0){
			$.notify({
				// options
				message: 'The animations of main menu are disabled.'
			},{
				// settings
				type: 'info',
				delay: 10000,
				offset : {
					y: 150,
					x: 20
				}
			});
		};
		if ($(".btn-alert").length>0){
			$(".btn-alert").on('click', function(event) {
				$.notify({
					// options
					message: 'Great! you have just created this message :-) you can configure this into the template.js file'
				},{
					// settings
					type: 'info',
					delay: 4000,
					offset : {
						y: 100,
						x: 20
					}
				});
				return false;
			});
		};		

		// Remove Button
		//-----------------------------------------------
		$(".btn-remove").click(function() {
			$(this).closest(".remove-data").remove();
		});

		// Shipping Checkbox
		//-----------------------------------------------
		if ($("#shipping-info-check").is(':checked')) {
			$("#shipping-information").hide();
		}
		$("#shipping-info-check").change(function(){
			if ($(this).is(':checked')) {
				$("#shipping-information").slideToggle();
			} else {
				$("#shipping-information").slideToggle();
			}
		});

		// Full Width Image Overlay
		//-----------------------------------------------
		if ($(".full-image-overlay").length>0) {
			overlayHeight = $(".full-image-overlay").outerHeight();
			$(".full-image-overlay").css("marginTop",-overlayHeight/2);
		};

		//This will prevent the event from bubbling up and close the dropdown when you type/click on text boxes (Header Top).
		//-----------------------------------------------
		$('.header-top .dropdown-menu input').click(function(e) {
			e.stopPropagation(); 
		});

	}); // End document ready

})(this.jQuery);

if (jQuery(".btn-print").length>0) {
	function print_window() {
		var mywindow = window;
		mywindow.document.close();
		mywindow.focus();
		mywindow.print();
		mywindow.close();
	}
}