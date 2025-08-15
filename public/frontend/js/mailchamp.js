jQuery(document).ready(function ($) {
    
    "use strict";


    $('.mailchimps_subscribe').on('click',function(e) {
    e.preventDefault();
    
        var subc_email = $("#mailchimp_email").val();
        var apikey = $("#mailchimp_api_key").val();
        var listid = $("#mailchimp_list_id").val();
        var ajax_url = ajaxobject.ajaxurl;
        
        // Basic email validation
        if (!valid_email_address(subc_email)) {
            $(".mailchimp_message").html('Please make sure you enter a valid email address.');
            return; // Stop execution if email is invalid
        }
    
        // Disable the submit button to prevent multiple submissions
        $(".el_btn").prop('disabled', true);
    
        // Send AJAX request
        jQuery.ajax({
            type: "post",
            url: ajax_url,
            data: {
                'action': "traininginstitute_mailchimp_subcription",
                apikey: apikey,
                listid: listid,
                subc_email: subc_email
            },
            success: function(response) {
                let res = response.replace(/\s+/g, "");
                console.log(res);
                if (res == "200") {
                    $(".mailchimp_message").html('<span style="color:green;">You have successfully subscribed to our mailing list.</span>');
                } else if (res == "204") {
                    $(".mailchimp_message").html('<span style="color:red;">Your email is already subscribed.</span>');
                } else {
                    $(".mailchimp_message").html('<span style="color:red;">There was an issue with your email address. Please try again.</span>');
                }
            },
            error: function(xhr, status, error) {
                $(".mailchimp_message").html('<span style="color:red;">There was a problem with the request. Please try again later.</span>');
            },
            complete: function() {
                // Re-enable the submit button after the request completes
                $(".el_btn").prop('disabled', false);
            }
        });
    });

    /**/
    $('.portfolio-cat-filter').on('click',function(e) {
        e.preventDefault();
       
        $(".el_course_filter ").find('a').removeClass("active");
        $(this).addClass('active');
       $('.el_course_loader').css('display', 'flex');

        var showp = $('.shp-all').attr('data-show');
	    var courses_cat = $(this).attr('data-filter');
	    
	    var ajax_url = ajaxobject.ajaxurl;
	    jQuery.ajax({ 
		    type : "post",
            url : ajax_url,
            data : {'action': "traininginstitute_courses_category_shortcode", courses_cat : courses_cat,showp:showp}, 
            
			success: function(response) {
                $('#learnpress_gallery_load').html(response);
                var courseSwiper = new Swiper('.el_course_slider .swiper-container', {
                    slidesPerView: 3,
                    spaceBetween: 30,
                    speed: 3000,
                    breakpoints: {
                        0: {
                            slidesPerView: 1,
                        },
                        767: {
                            slidesPerView: 2,
                        },
                        1200: {
                            slidesPerView: 3,
                        }
                    },
                    navigation: {
                        nextEl: '.el_course_slider .swiper-button-next',
                        prevEl: '.el_course_slider .swiper-button-prev',
                    },
                
                });
                $('.el_course_loader').css('display', 'none');
			}
		});
		
	});
    /***/
});
function valid_email_address(email){
 var pattern = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
 return pattern.test(email);
} 