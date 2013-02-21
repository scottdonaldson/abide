$(document).ready(function(){

	// Declare variables
	var html = $('html'),
		body = $('body'),
		header = $('header'),
		headerHeight = $('.header-height'),
		masthead = $('#masthead'),
		logo = header.find('a').first(),
		menuItem = header.find('li a'), active,
		bar = $('.bar'),
		compass = $('.compass_container'),
		needle = $('.needle'),
		needleX, needleY,
		mouseX, mouseY,
		angle, isRight,
		scrollPos;

	/* ----- header ----- */

	var isScrolling = false;
	var posBar = function() {
		scrollPos = $(window).scrollTop();

		if (scrollPos > 450) {
			header.addClass('miniature');
		} else {
			header.removeClass('miniature');
		}
		
		if (isScrolling == false) { // only guess where we're at if the bar isn't already scrolling.
								// this prevents stopping at any menu items along the way when clicking
								// and skipping a few items
			$('.module').each(function(){
				$this = $(this);
				if (Math.abs($this.offset().top - scrollPos) < 250) {
					active = menuItem.filter('[href="#'+$this.attr('ID')+'"]');
					active.addClass('active').closest('li').siblings('li').find('a').removeClass('active');
				}
			});
		}
	}
	posBar();
	$(window).scroll(function(){ posBar(); });

	setInterval(function(){
		active = $('header .active');
		if (!isScrolling && header.hasClass('miniature') && active.length > 0) {
			bar.animate({
				'left': active.offset().left + 10,
				'width': active.width()
			});
		}
	}, 500);

	// masthead needs a margin equal to the height of the header + 32 for good measure
	$(window).load(function(){
		if (html.hasClass('modern')) {
			masthead.css('margin-top', $('header').outerHeight() + 32);
		}
	});
	
	$(window).resize(function(){
		if (html.hasClass('modern')) {
			masthead.css('margin-top', $('header').outerHeight() + 32);
		}
	});

	// scrolling links / logo
	menuItem.click(function(e){
		isScrolling = true; // this prevents bar from stopping at any menu items along the way
		menuItem.removeClass('active');

		$this = $(this);
		var newActive = $this;
		$this.addClass('active');
		if ($('header').hasClass('miniature')) {
			$('html, body').stop().animate({
				scrollTop: $($this.attr('href')).offset().top - $('header').outerHeight()
			}, 1800, 'easeOutQuart');
			e.preventDefault();

			// Move the bar
			bar.animate({
				'left': $this.offset().left + 10, // +10 for padding on link
				'width': $this.width()
			});
		} else {
			$('html, body').stop().animate({
				scrollTop: $($this.attr('href')).offset().top - $('header').outerHeight()
			}, 800, 'linear');
			e.preventDefault();

			setTimeout(function(){
				$('html, body').stop().animate({
					scrollTop: $(newActive.attr('href')).offset().top - $('header').outerHeight()
				}, 1000, 'easeOutQuart');
				bar.animate({
					'left': newActive.offset().left + 10,
					'width': newActive.width()
				});
			}, 800);
		}

		
		setTimeout(function(){
			isScrolling = false;
		}, 1800);

	});
	logo.click(function(e){
		$('html,body').stop().animate({
			scrollTop: 0
		}, 1800, 'easeOutQuart');
		e.preventDefault();

		bar.animate({
			'left': -100,
			'width': 0
		});
		setTimeout(function(){
			isScrolling = false;
		}, 1800);
	});

	/* ----- compass ----- */

	
	// Our setFixed variable is most important
	var setFixed = compass.offset().top - $(window).scrollTop() - 0.5 * $(window).height() + 50;

	if (!html.hasClass('touch')) {
		$(window).scroll(function(){
			if (!compass.hasClass('fixed') && compass.offset().top - $(window).scrollTop() < 0.5 * $(window).height() - 50) {

				compass.addClass('fixed').css({
					'top': 0.5 * $(window).height() - 50
				});
			}

			if ($(window).scrollTop() <= setFixed) {
				compass.removeClass('fixed').css({ 'top': -250 });
			} else {
				compass.addClass('fixed').css({
					'top': 0.5 * $(window).height() - 50
				});
			}

			if ($(window).scrollTop() == 0) {
				compass.removeClass('fixed').css({ 'top': -250 });
			}
		});
		$(window).resize(function(){
			setFixed = compass.offset().top - $(window).scrollTop() - 0.5 * $(window).height() + 50;
			if (compass.hasClass('fixed')) {
				compass.css({
					'top': 0.5 * $(window).height() - 50
				});
			}
		});
	}

	// Find mouse position
	$(document).mousemove(function(e){
		mouseX = e.pageX;
		mouseY = e.pageY - $(window).scrollTop(); // need to subtract scrollTop to get relative to window
		needleX = needle.offset().left + needle.width()/2;
		needleY = needle.offset().top - $(window).scrollTop() + needle.height()/2;
		if (mouseX > needleX) { 
			angle = Math.atan((mouseY - needleY)/(mouseX - needleX));
			angle = 180 * angle/Math.PI + 90;  // convert radians to degrees and add 90 
										   	   // (to correct for initial position of needle)
		} else { 
			angle = Math.atan((mouseY - needleY)/(mouseX - needleX));
			angle = 180 * angle/Math.PI + 270; // convert radians to degrees and add 90 
										   	   // (to correct for initial position of needle)
		}
		// Don't let the mouse get too close to the needle - if so, reset it
		if (Math.abs(mouseX - needleX) < 80 && Math.abs(mouseY - needleY) < 80) {
			angle = $('.needle').attr('data-rotate');
		}

		angle = 3 * Math.round(angle/3); // round to nearest 3 degrees

		needle.attr('data-rotate', angle);
	});

	/* ----- specialties and clients----- */

	var items = $('.module li'),
		content = $('.module .content'),
		infobox = $('.infobox'),
		target;

	infobox.each(function(){ // This is also used for client quotes and after submitting an idea
		$this = $(this);
		$this.prepend('<div class="utilities clearfix">'+
					   	   '<div class="icon-prev"></div>'+
					   	   '<div class="icon-next"></div>'+
					   	   '<div class="icon-close"></div>'+
					  '</div>');
		$this.attr('data-height', $this.outerHeight());
	});
	$(window).resize(function(){
		infobox.each(function(){
			$this = $(this);
			$this.attr('data-height', $this.outerHeight());
		});
	});

	items.click(function(){
		$this = $(this);
		$this.removeClass('inactive').siblings('li').addClass('inactive');
		
		infobox = $this.closest('.module').find('.infobox');
		content = $this.closest('.module').find('.content');

		target = infobox.eq($this.index());
		target.fadeIn().siblings().fadeOut();

		// Since we hide the utilities buttons in IE 8 and below when closing,
		// we need to make sure that we show them here, just in case
		if (html.hasClass('lt-ie9')) {
			infobox.find('.utilities div').show();	
		}
		content.addClass('faded');

		$this.glide(target);
	});

	// ----- Next and previous for both Specialties and Clients
	var prev = $('.icon-prev'), prevItem,
		next = $('.icon-next'), nextItem,
		close = $('.icon-close');
	prev.click(function(){
		$this = $(this);
		infobox = $this.closest('.module').find('.infobox');
		items = $this.closest('.module').find('li');

		target = $this.closest('.infobox').prev().length > 0 ? $this.closest('.infobox').prev() : infobox.last();
		$this.closest('.infobox').fadeOut();
		target.fadeIn();
		
		prevItem = items.not('.inactive').prev().length > 0 ? items.not('.inactive').prev() : items.last();
		items.not('.inactive').addClass('inactive');
		prevItem.removeClass('inactive');

		$this.glide(target);
	});
	next.click(function(){
		$this = $(this);
		infobox = $this.closest('.module').find('.infobox');
		items = $this.closest('.module').find('li');

		target = $this.closest('.infobox').next().length > 0 ? $this.closest('.infobox').next() : infobox.first();
		$this.closest('.infobox').fadeOut();
		target.fadeIn();

		nextItem = items.not('.inactive').next().length > 0 ? items.not('.inactive').next() : items.first();
		items.not('.inactive').addClass('inactive');
		nextItem.removeClass('inactive');

		$this.glide(target);
	});
	close.click(function(){
		$this = $(this);
		infobox = $this.closest('.module').find('.infobox');
		items = $this.closest('.module').find('li');

		$this.closest('.module').find('.inactive').removeClass('inactive'); // un-inactivate all
		
		// Hide font icon in IE8 and below rather than trying to fade out with the box.
		// IE just can't cut the mustard!
		if (html.hasClass('lt-ie9')) {
			infobox.find('.utilities div').hide();	
		}
		infobox.fadeOut();
		content = $this.closest('.module').find('.content').removeClass('faded');

		$this.closest('.module').removeAttr('style');
	});

	// ----- module resizing
	//		 a.k.a. "elegant gliding"
	var module;
	$.fn.extend({
		glide: function(target){
			module = $(this).closest('.module');
			content = module.find('.content');

			// We will never want to glide in IE8 or below. NEVER.
			if (!html.hasClass('lt-ie9')) {
				// Make sure that the new bottom padding for the module is not less than 0
				var padding = target.attr('data-height') - content.height() + 80 >= 0 ? target.attr('data-height') - content.height() + 80 : 0;
				module.css({
					'padding-bottom': padding
				});
			}
		}
	});
	$(window).resize(function(){
		$('.infobox:visible').each(function(){
			$this = $(this);
			$this.glide($this);
		});
	});

	// ----- submitting ideas
	var form = $('form'),
		types = $('.types span'),
		type = $('#type'),
		link;

	types.click(function(){
		$this = $(this);
		$this.addClass('chosen').siblings().removeClass('chosen');
		type.val($this.attr('data-type'));
	});
	form.submit(function(e){
		// Must submit a link
		link = $('#link');
		if (link.val().length == 0 || (link.val().substring(0, 7) == 'http://' && link.val().substring(8).length == 0)) {
			link.addClass('invalid');
			setTimeout(function(){
				link.removeClass('invalid');
			}, 1000);
		}

		// Must choose a type	
		if ($('.chosen').length == 0) {
			types.addClass('invalid');
			setTimeout(function(){
				types.removeClass('invalid');
			}, 1000);
			return false;
		}
	});
	// After submitting...
	var closeThanks = $('.thanks .icon-close');
	closeThanks.click(function(){
		$(this).closest('.infobox').fadeOut();
	});

	/* ----- icomoon for IE7 ----- */
	if (html.hasClass('lt-ie8')) {
		window.onload = function() {
			function addIcon(el, entity) {
				var html = el.innerHTML;
				el.innerHTML = '<span style="font-family: \'icomoon\'">' + entity + '</span>' + html;
			}
			var icons = {
					'icon-video' : '&#xe000;',
					'icon-image' : '&#xe001;',
					'icon-article' : '&#xe002;',
					'icon-prev' : '&#xe003;',
					'icon-next' : '&#xe004;',
					'icon-close' : '&#xe005;',
					'icon-plus' : '&#xe006;'
				},
				els = document.getElementsByTagName('*'),
				i, attr, html, c, el;
			for (i = 0; i < els.length; i += 1) {
				el = els[i];
				attr = el.getAttribute('data-icon');
				if (attr) {
					addIcon(el, attr);
				}
				c = el.className;
				c = c.match(/icon-[^\s'"]+/);
				if (c && icons[c[0]]) {
					addIcon(el, icons[c[0]]);
				}
			}
		};
	}

});