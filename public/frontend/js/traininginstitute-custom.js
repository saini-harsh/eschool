! function(t) {
   
    "use strict";
      jQuery(document).ready(function() {
            jQuery('.el_search_res').on("click", function() {
                jQuery('.el_header_form').addClass('show');
            });
            jQuery('.closeBtn').on("click", function() {
                jQuery('.el_header_form').removeClass('show');
            });
            jQuery('.el_header_form').on("click", function() {
                jQuery('.el_header_form').removeClass('show');
            });
            jQuery(".el_header_serch").on('click', function() {
                event.stopPropagation();
            });
    }),
    
    jQuery(document).ready(function() {
    	var w = window.innerWidth;
    	if (w <= 767) {
    		jQuery(".ed_menu > ul > li").find("a").click(function() {
    			jQuery(this).parent().siblings().children(".sub-menu").slideUp('slow');
    			jQuery(this).parent().children(".sub-menu").slideToggle('slow');
    		});
    	}
    }),
		jQuery(document).ready(function(t) {
           
        function e(t) {
            try {
                var e = t.split("-"),courses_load
                    a = parseInt(e[0], 10),
                    n = parseInt(e[1], 10),
                    i = e.length > 2 ? parseInt(e[2], 10) : 1;
                return a > 0 && n >= 0 ? new Date(a, n - 1, i) : null
            } catch (o) {
                return null
            }
        }

        function a(t) {
            var e = t.getFullYear(),
                a = t.getMonth();
            return e + "-" + (a + 1) + "-" + t.getDate()
        }
        t(window), t(".ed_gallery_wrapper").magnificPopup({
            
            delegate: ".ed_gallery_member_img>a",
            type: "image",
            tLoading: "Loading image #%curr%...",
            mainClass: "mfp-img-mobile",
            gallery: {
                enabled: !0,
                navigateByImgClick: !0,
                preload: [0, 1]
            },
            image: {
                tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
                titleSrc: function(t) {
                    return t.el.attr("title") + "<small></small>"
                }
            }
            
        }),
        
        t(".ed_gallery_wrapper").click(function () {
            setTimeout(function () {
                t('#elementor-lightbox-slideshow-single-img').css('display','none');
                console.log("hello");
            }, 100);
        }),
        
        t(".ed_book_portfolio").magnificPopup({
            
            delegate: ".ed_shop_box_img>a",
            type: "image",
            tLoading: "Loading image #%curr%...",
            mainClass: "mfp-img-mobile",
            gallery: {
                enabled: !0,
                navigateByImgClick: !0,
                preload: [0, 1]
            },
            image: {
                tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
                titleSrc: function(t) {
                    return t.el.attr("title") + "<small></small>"
                }
            }
        }),

		t(".ed_book_portfolio").click(function () {
            setTimeout(function () {
                t('#elementor-lightbox-slideshow-single-img').css('display','none');
                console.log("hello");
            }, 100);
        }),

		t(".timer").appear(function() {
            t(this).countTo()
        }), t(".ed_event_wrapper_item_map").hide(), t(".on_map").on("click", function() {
            t(".ed_event_wrapper_item_map").show()
        }), t(".on_map").on("click", function() {
            t(".ed_event_wrapper_item_img").hide()
        }), jQuery(function(t) {
            t("#pro_video").css("display", "none"), t(".ed_video_section .ed_img_overlay a i").on("click", function(e) {
                e.preventDefault(),t("#pro_video").css("display", "block"), t(".ed_video_section .ed_video").hide(), t("#pro_video").attr("src", t("#pro_video").attr("src") + "?rel=0&autoplay=1&mute=1")
            })
        }), t("input[name$='checkout']").on("click", function() {
            var e = t(this).val();
            t(".payment_box").hide("slow"), t(".payment_box[data-period='" + e + "']").show("slow")
        });
        var n = "";
        t("input,textarea").focus(function() {
            n = t(this).attr("placeholder"), t(this).attr("placeholder", "")
        }).blur(function() {
            t(this).attr("placeholder", n)
        }), t(window).scroll(function() {
            var e = t(window).scrollTop() + 1;
            e > 500 ? t(".ed_header_bottom").addClass("menu_fixed animated fadeInDown") : t(".ed_header_bottom").removeClass("menu_fixed animated fadeInDown")
        });
        var i = 0;
        t(".ed_menu_btn").on("click", function() {
            "0" == i ? (t(".ed_main_menu_wrapper").addClass("ed_main_menu_hide"), t(this).children().removeAttr("class"), t(this).children().attr("class", "fa fa-close"), i++, i++) : (t(".ed_main_menu_wrapper").removeClass("ed_main_menu_hide"), t(this).children().removeAttr("class"), t(this).children().attr("class", "fa fa-bars"), i--)
        }), t("#login_button").on("click", function(e) {
            t("#login_one").slideToggle(1e3), e.stopPropagation()
        }), t(document).on("click", function(e) {
            e.target.closest("#login_one") || t("#login_one").slideUp("slow")
        }), t("#ed_share_wrapper").on("click", function() {
            t("#ed_social_share").slideToggle(1e3)
        }), t("#invitation_form").on("shown.bs.modal", function() {
            t("#myInput").focus()
        }), t(".section_one_slider .owl-carousel, .ed_populer_areas_slider .owl-carousel").owlCarousel({
            items: 4,
            dots: !1,
            nav: !0,
            animateIn: "fadeIn",
            animateOut: "fadeOut",
            autoHeight: !0,
            touchDrag: !1,
            mouseDrag: !1,
            margin: 30,
            loop: !0,
            autoplay: !1,
            navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
            responsiveClass: !0,
            responsive: {
                0: {
                    items: 1,
                    nav: !0
                },
                600: {
                    items: 1,
                    nav: !0
                },
                992: {
                    items: 2,
                    nav: !0
                },
                1200: {
                    items: 2,
                    nav: !0
                }
            }
        }), t(".section_four_slider .owl-carousel, .ed_mostrecomeded_course_slider .owl-carousel").owlCarousel({
            items: 4,
            dots: !1,
            nav: !0,
            animateIn: "fadeIn",
            animateOut: "fadeOut",
            autoHeight: !0,
            touchDrag: !1,
            mouseDrag: !1,
            margin: 30,
            loop: !0,
            autoplay: !1,
            navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
            responsiveClass: !0,
            responsive: {
                0: {
                    items: 1,
                    nav: !0
                },
                600: {
                    items: 2,
                    nav: !0
                },
                992: {
                    items: 3,
                    nav: !0
                },
                1200: {
                    items: 4,
                    nav: !0
                }
            }
        }), t(".section_five_slider .owl-carousel, .ed_latest_news_slider .owl-carousel").owlCarousel({
            items: 3,
            dots: !1,
            nav: !0,
            animateIn: "fadeIn",
            animateOut: "fadeOut",
            autoHeight: !0,
            touchDrag: !1,
            mouseDrag: !1,
            margin: 30,
            loop: !0,
            autoplay: !1,
            navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
            responsiveClass: !0,
            responsive: {
                0: {
                    items: 1,
                    nav: !0
                },
                600: {
                    items: 2,
                    nav: !0
                },
                992: {
                    items: 3,
                    nav: !0
                }
            }
        }), t(".ed_clientslider .owl-carousel").owlCarousel({
            items: 5,
            dots: !1,
            nav: !1,
            animateIn: "fadeIn",
            animateOut: "fadeOut",
            autoHeight: !0,
            touchDrag: !1,
            mouseDrag: !1,
            margin: 0,
            loop: !0,
            autoplay: !0,
            responsiveClass: !0,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 5
                },
                1e3: {
                    items: 5
                }
            }
        }), 
		t(".ed_modern_clientslider .owl-carousel").owlCarousel({
            items: 6,
            dots: !1,
            nav: !1,
            animateIn: "fadeIn",
            animateOut: "fadeOut",
            autoHeight: !0,
            touchDrag: !1,
            mouseDrag: !1,
            margin: 0,
            loop: !0,
            autoplay: !0,
            responsiveClass: !0,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 6
                },
                1e3: {
                    items: 6
                }
            }
        }), 
		t(".ed_sidebar_slider .owl-carousel").owlCarousel({
            items: 1,
            dots: !1,
            nav: !0,
            loop: !0,
            animateIn: "fadeIn",
            animateOut: "fadeOut",
            autoHeight: !0,
            touchDrag: !1,
            mouseDrag: !1,
            margin: 30,
            autoplay: !1,
            navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
            responsiveClass: !0,
            responsive: {
                0: {
                    items: 1,
                    nav: !0
                },
                600: {
                    items: 1,
                    nav: !0
                },
                1e3: {
                    items: 1,
                    nav: !0
                }
            }
        }), t(".ed_shop_bookslider .owl-carousel").owlCarousel({
            items: 4,
            dots: !1,
            nav: !0,
            animateIn: "fadeIn",
            animateOut: "fadeOut",
            autoHeight: !1,
            touchDrag: !1,
            mouseDrag: !0,
            margin: 30,
            loop: !0,
            autoplay: !0,
            responsiveClass: !0,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 2
                },
                1e3: {
                    items: 3
                }
            }
        }), t("#ed_submit").on("click", function() {
            var e = t("#uname").val(),
                a = t("#umail").val(),
                n = t("#sub").val(),
                i = t("#msg").val();
            t.ajax({
                type: "POST",
                url: "ajaxmail.php",
                data: {
                    username: e,
                    useremail: a,
                    useresubject: n,
                    mesg: i
                },
                success: function(o) {
                    var s = o.split("#");
                    "1" == s[0] ? (t("#uname").val(""), t("#umail").val(""), t("#sub").val(""), t("#msg").val(""), t("#err").html(s[1])) : (t("#uname").val(e), t("#umail").val(a), t("#sub").val(n), t("#msg").val(i), t("#err").html(s[1]))
                }
            })
        }), smoothScroll.init({
            speed: 1e3,
            easing: "easeInOutCubic",
            offset: 0,
            updateURL: !0,
            callback: function(t, e) {}
        });
        var o = function(e) {
                e.preventDefault();
                var a = t(this),
                    n = a.next(".ed_custom_select_box_options");
                n.toggle()
            },
            s = function(e) {
                e.preventDefault();
                var a = t(this),
                    n = a.parent().parent(),
                    i = n.prev(".ed_custom_select_box_button"),
                    o = i.children("span"),
                    s = o.text(),
                    r = i.data("selectValue"),
                    l = a.text(),
                    c = a.data("selectValue");
                s != l && o.empty().text(l), r != c && i.attr("data-select-value", c), n.toggle()
            };
        t(".ed_custom_select_box_button").on("click", o), t(".ed_custom_select_box_options li a").on("click", s), t.fn.calendar = function(n) {
            function i(t) {
                var e = new Date(t);
                return e.setMonth(e.getMonth() + 1), e.setDate(0), e.getDate()
            }

            function o(t) {
                var a = e(s.find(".month").text());
                a.setMonth(a.getMonth() + t), s.update(a)
            }
            var s = this,
                r = t.extend({}, t.fn.calendar.defaults, n),
                l = ["Mo", "Tu", "We", "Th", "Fr", "Sa", "Su"],
                c = l.map(function(t) {
                    return "<th>" + t + "</th>"
                }).join("");
            s.init = function() {
                var e = '<table class="cal"><caption>	<span class="prev"><a href="javascript:void(0);">&larr;</a></span>	<span class="next"><a href="javascript:void(0);">&rarr;</a></span>	<span class="month"><span></caption><thead><tr>' + c + "</tr></thead><tbody></tbody></table>",
                    a = t(e);
                s.append(a)
            }, s.update = function(e) {
                function n(n) {
                    var i = t('<td><a href="javascript:void(0);"></a></td>'),
                        o = i.find("a");
                    return o.text(n.getDate()), o.data("date", a(n)), e.getMonth() != n.getMonth() ? i.addClass("off") : s.data("date") == o.data("date") && (i.addClass("active"), s.data("date", a(n))), i
                }
                var o = new Date(e);
                o.setDate(1);
                var r = o.getDay();
                o.setDate(o.getDate() - r);
                var l = s.find("tbody");
                l.empty();
                for (var c = Math.ceil((r + i(e)) / 7), u = 0; c > u; u++) {
                    for (var d = t("<tr></tr>"), m = 0; 7 > m; m++, o.setDate(o.getDate() + 1)) d.append(n(o));
                    l.append(d)
                }
                var _ = a(e).replace(/-\d+$/, "");
                s.find(".month").text(_)
            }, s.getCurrentDate = function() {
                return s.data("date")
            }, s.init();
            var u = r.date ? r.date : new Date;
            return (r.date || !r.picker) && s.data("date", a(u)), s.update(u), s.delegate("tbody td", "click", function() {
                var a = t(this);
                s.find(".active").removeClass("active"), a.addClass("active"), s.data("date", a.find("a").data("date")), a.hasClass("off") && s.update(e(s.data("date"))), r.picker && s.hide()
            }), s.find(".next").on("click", function() {
                o(1)
            }), s.find(".prev").on("click", function() {
                o(-1)
            }), this
        }, t.fn.calendar.defaults = {
            date: new Date,
            picker: !1
        }, t.fn.datePicker = function() {
            var a = this,
                n = t("<div></div>").addClass("picker-container").hide().calendar({
                    date: e(a.val()),
                    picker: !0
                });
            return a.after(n), t("body").on("click", function() {
                n.hide()
            }), a.on("click", function() {
                return n.show(), !1
            }), n.on("click", function() {
                return a.val(n.getCurrentDate()), !1
            }), this
        }, t(window).load(function() {
            t(".jquery-calendar").each(function() {
                t(this).calendar()
            })
        }), t("#style-switcher .bottom a.settings").on("click", function(e) {
            e.preventDefault();
            var a = t("#style-switcher");
            "-180px" === a.css("right") ? t("#style-switcher").animate({
                right: "0px"
            }) : t("#style-switcher").animate({
                right: "-180px"
            })
        }), jQuery(".clsw_colorchange").on("click", function() {
            var e = t("input[name=traininginstitute_template_url]").val(),
                a = t(this).attr("id"),
                n = e + "/assets/css/color/" + a + ".css";
            jQuery("#traininginstitute-theme-change-css").attr("href", n)
        }), t(window).scroll(function() {
            t(this).scrollTop() > 500 ? t(".scrollup").fadeIn() : t(".scrollup").fadeOut()
        }), t(".scrollup").on("click", function() {
            return t("html, body").animate({
                scrollTop: 0
            }, 600), !1
        }), t("#gallery_load").click(function() {
            var e = t(".ajx_auto_incriment1").val(),
                a = t(".ajx_traininginstitute_number1").val(),
                n = t(".ajx_traininginstitute_showmore1").val(),
                i = n * e,
                o = +a + i;
            e++, t(".ajx_auto_incriment1").val(e);
            var s = "click_value=" + o;
            console.log(s);
            s += "&action=traininginstitute_gallery_shortcode", t.ajax({
                type: "post",
                url: t("#traininginstitute_gallery_ajaxurl_id_shortcode1").val(),
                data: s,
                success: function(e) {
                    console.log(e);
                    t("#ajax_traininginstitute_shortcode").html(e);
                }
            })
        }), t("#courses_load").click(function() {
           
            var e = t(".ajx_auto_incriment1").val(),
                a = t(".ajx_traininginstitute_number1").val(),
                n = t(".ajx_traininginstitute_showmore1").val(),
                i = n * e,
                o = +a + i;
            e++, t(".ajx_auto_incriment1").val(e);
            var s = "click_value=" + o;
            s += "&action=traininginstitute_courses_shortcode", t.ajax({
                type: "post",
                url: t("#traininginstitute_courses_ajaxurl_id_shortcode1").val(),
                data: s,
                success: function(e) {
                    t("#ajax_traininginstitute_courses_shortcode").html(e);
                }
            })
        }), t("#blog_load").click(function() {
            var e = t(".ajx_auto_incriment3").val(),
                a = t(".ajx_traininginstitute_number_blog").val(),
                n = t(".ajx_traininginstitute_showmore_bog").val(),
                i = n * e,
                o = +a + i;
            e++, t(".ajx_auto_incriment4").val(e);
            var s = "click_value=" + o;
            s += "&action=traininginstitute_blog_shortcode", t.ajax({
                type: "post",
                url: t("#traininginstitute_blog_ajaxurl_id_shortcode1").val(),
                data: s,
                success: function(e) {
                    t("#ajax_traininginstitute_blog_shortcode").html(e)
                }
            })
        }), t("#event_load").click(function() {
           
            var e = t(".ajx_auto_incriment4").val(),
                a = t(".ajx_traininginstitute_number_event").val(),
                n = t(".ajx_traininginstitute_showmore_event").val(),
                i = n * e,
                o = +a + i;
            e++, t(".ajx_auto_incriment4").val(e);
            
            var s = "click_value=" + o;
            
            s += "&action=traininginstitute_event_shortcode", t.ajax({
                type: "post",
                url: t("#traininginstitute_event_ajaxurl_id_shortcode").val(),
                data: s,
                success: function(e) {
                    t("#ajax_traininginstitute_event_shortcode").html(e)
                }
                
            })
            console.log(s);
        }), t(".filter_list ul li a").length > 0 && (t(".filter_list ul li a").on("click", function(t) {
            t.preventDefault()
        }), t("#portfolio").mixItUp()),
		t('.ti_counseling_slider .owl-carousel').owlCarousel({
			loop:true,
			margin:10,
			items:1,
			nav:false,
			dots:true,
			smartSpeed:800,
			responsive:{
				0:{
					margin:0,
				}
			}
		}),
		t('.ti_blog_slider .owl-carousel').owlCarousel({
			loop:true,
			items:3,
			margin:30,
			autoplay:true,
			autoplayTimeout:4000,
			autoplayHoverPause:true,
			nav:false,
			dots:false,
			smartSpeed:800,
			responsive:{
				0:{
					items:1
				},
				768:{
					items:2
				},
				1000:{
					items:3 
				}
			}
		})
    });
	jQuery(document).ready(function(){
			if(jQuery('.ti_shop_view').length > 0){
				jQuery('.ti_shop_view').on('click', 'li', function() {
					jQuery('.ti_shop_view ul li.active').removeClass('active');
					jQuery(this).addClass('active');
				});
				jQuery('#list').click(function(event){event.preventDefault();jQuery('.item').addClass('list-group-item');}); 
				jQuery('#grid').click(function(event){event.preventDefault();jQuery('.item').removeClass('list-group-item');jQuery('.item').addClass('grid-group-item');});
			}
		});
		/*-----------------------------------------------------
            New Demo Courses Slider js strt
        -----------------------------------------------------*/


}();
/*-----------------------------------------------------
           Popular Courses
-----------------------------------------------------*/

 var swiper = new Swiper('.ti_course_slider .swiper-container', {
      slidesPerView: 5,
      spaceBetween: 30,
	  speed: 800,
	  loop:true,
	  navigation: {
        nextEl: '.ti_coursenav_prev',
        prevEl: '.ti_coursenav_next',
      }, 
	  centeredSlides:true,
	   breakpoints: {
		640: {
		  slidesPerView: 1
		},
		991: {
		  slidesPerView: 2
		},
		1200: {
			 slidesPerView: 3
		}
	  }
    });
   
var courseSwiper = new Swiper('.el_course_slider .swiper-container', {
    slidesPerView: 3,
    spaceBetween: 30,
    speed: 3000,
        breakpoints: {
        	1920: {
        		slidesPerView: 3,
        		spaceBetween: 30
        	},
        	1028: {
        		slidesPerView: 2,
        		spaceBetween: 30
        	},
        	480: {
        		slidesPerView: 1,
        		spaceBetween: 10
        	}
        },
    navigation: {
        nextEl: '.el_course_slider .swiper-button-next',
        prevEl: '.el_course_slider .swiper-button-prev',
    },

});

/*-----------------------------------------------------
            Team Slider
-----------------------------------------------------*/
var swiper = new Swiper('.el_tutors_slider .swiper-container', {
    slidesPerView: 4,
    spaceBetween: 30,
    loop: true,
    speed: 3000,
    pagination: {
        el: ".el_tutors_slider .swiper-pagination",
        clickable: true,
    },
    breakpoints: {
        1199: {
            slidesPerView: 4,
            spaceBetween: 30,
        },
        992: {
            slidesPerView: 3,
            spaceBetween: 30,
        },
        768: {
            slidesPerView: 2,
            spaceBetween: 30,
        },
        480: {
            slidesPerView: 1,
            spaceBetween: 15,
        },
        320: {
            slidesPerView: 1,
            spaceBetween: 15,
        },
        0: {
            slidesPerView: 1,
            spaceBetween: 15,
        }
    }
});
/*-----------------------------------------------------
            Testimonials Slider
-----------------------------------------------------*/

    var swiper = new Swiper('.el_testimonial_slider .swiper-container', {
        loop: true,
        spaceBetween: 5,
        speed: 3000,
        autoplay: {
            delay: 1500,
            disableOnInteraction: false,
        },
        navigation: {
            nextEl: ".el_testi_nav .swiper-button-next",
            prevEl: ".el_testi_nav .swiper-button-prev",
        },
    });
 /*-----------------------------------------------------
            Blogs Slider
        -----------------------------------------------------*/
    var courseSwiper = new Swiper('.el_blog_slider .swiper-container', {
        slidesPerView: 3,
        spaceBetween: 30,
        loop: true,
        speed: 3000,
        autoplay: {
            delay: 1500,
            disableOnInteraction: false,
        },
        breakpoints: {
            1200: {
                slidesPerView: 3,
                spaceBetween: 30,
            },
            768: {
                slidesPerView: 2,
                spaceBetween: 30,
            },
            480: {
                slidesPerView: 1,
                spaceBetween: 15,
            },
            320: {
                slidesPerView: 1,
                spaceBetween: 15,
            },
            0: {
                slidesPerView: 1,
                spaceBetween: 15,
            }
        },
        navigation: {
            nextEl: '.el_blog_slider .swiper-button-next',
            prevEl: '.el_blog_slider .swiper-button-prev',
        },

    });