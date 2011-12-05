    /*	CarouFredSel: an infinite, circular jQuery carousel.
	Configuration created by the "Configuration Robot"
	at caroufredsel.frebsite.nl
*/
var $gigx_shows_slides = jQuery.noConflict();

$gigx_shows_slides(document).ready(function() {

$gigx_shows_slides("#shows_container").carouFredSel({
	height: "variable",
	padding: 10,
	items: 1,
	scroll: {
		fx: "crossfade",
		//easing: "easeInQuad",
		duration: 500
	},
	auto: false,
	pagination: "#shows_pager"
});

});