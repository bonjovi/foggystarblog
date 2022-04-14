$(function() {
	// SLIDER
	$(".owl-carousel").owlCarousel({
		responsive:{
			0:{
				items:1,
				nav:false,
				mouseDrag: true,
				stagePadding: 20,
				margin: 2
			},
			768:{
				items:3,
				nav:false,
				mouseDrag: true,
				stagePadding: 20,
				margin: 2
			},
			992:{
				items:4,
				nav:true,
				mouseDrag: false
			}
		}
	});

	var slidesQty = $('.frontpage__slide').length;

	if($(window).width() < 767) {
		if(slidesQty == 1) $('.owl-nav').hide();
	} else if($(window).width() > 767 && $(window).width() < 992) {
		if(slidesQty <= 3) $('.owl-nav').hide();
	} else {
		if(slidesQty <= 4) $('.owl-nav').hide();
	}


	// TABS
	$('.tabs__link').on('click', function(e) {
		e.preventDefault();
		$('.tabs__item').removeClass('active');
		$(this).parent().addClass('active');

		var dataTab = $(this).attr('data-tab');

		if(dataTab != 'all-items') {
			$('.rubrics__item').hide();
			$('.rubrics__item[data-tab="'+ dataTab +'"]').show();
		} else {
			$('.rubrics__item').show();
		}
	});

	$('.tabs .tabs__item:nth-child(1) .tabs__link').trigger('click');



	// CHANGE PROPORTION-HEIGHT FOR RUBRICS AND SLIDER ITEMS
	function changeHeight() {
		setTimeout(function() {
			$('.rubrics__post-pic').map(function() {
				var width = $(this).width();
				$(this).css('height', (width / 1.78) + 'px');
				$(this).find('img').css('height', (width / 1.78) + 'px');

			});

			$('.frontpage__slide-pic').map(function() {
				var width = $(this).width();
				$(this).css('height', (width / 1.78) + 'px');
				$(this).find('img').css('height', (width / 1.78) + 'px');
			});

			$('.frontpage__slider').css('opacity', '1');
		}, 1000);
	}

	changeHeight();

	$(window).on('resize', function() {
		changeHeight();	
	});


	

});