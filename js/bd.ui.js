/*
*************************************************

	SEBASTIAN NITU
	User Interface Functionality for BaseDemo

	Created by Sebastian Nitu
	http://www.sebnitu.com

*************************************************
*/

/*-------------------------------------------
	When Document is Ready (no conflict mode)
---------------------------------------------*/

(function ($) {

	$(document).ready(function () {

		$('.left_menu').each(function () {

			var tabs = $(this).find('.tabs a'),
					panels = $(this).find('.panel').hide(),
					hash = window.location.hash;

			$(this).find('.tabs a:first').addClass('active');
			$(this).find('.panel:first').show();

			tabs.click(function () {
				var active = $(this).attr("href");

				// Toggle active class
				tabs.parent().removeClass('active');
				$(this).parent().addClass('active');

				// Toggle selected panel
				panels.hide();
				$(active).show();

				// Prevent default behavior
				return false;
			});
		});
	});

})(jQuery);

/*-------------------------------------------
	Fin
---------------------------------------------*/