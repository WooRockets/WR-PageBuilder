/**
 * @version    $Id$
 * @package    IGPGBLDR
 * @author     WooRockets Team <support@www.woorockets.com>
 * @copyright  Copyright (C) 2012 WooRockets.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.www.woorockets.com
 * Technical Support: Feedback - http://www.www.woorockets.com/contact-us/get-support.html
 */

(function ($) {
	"use strict";

	$.IGSelectFonts = function () {
		this.init();
	};

	$.IGSelectFonts.prototype = {
		init: function () {
			var self 		= this;
			$('.jsn-fontFaceType').each(function () {
				self.changeFontFaceType( $(this) );
			});
			$('.combo-item').delegate('.jsn-fontFaceType', 'change', function () {
				self.changeFontFaceType( $(this) );
			});
		},

		changeFontFaceType: function( _this ) {
			var self 		= this;
			var divParent	= $(_this).parents('.controls');
			var fontType	= $(_this).val();

			if ( ! fontType )
				return false;
			var dataOptions	= '';
			$(divParent).find("select.jsn-fontFace").html("");
			switch (fontType) {
				case 'standard fonts':
					dataOptions	= self.getStandardOptions();
					break;
				case 'google fonts':
					dataOptions	= self.getGoogleOptions();
					break;
			};

			$.each(dataOptions, function (i, val) {
				if (i == $(divParent).find("select.jsn-fontFace").attr("data-selected")) {
	                $(divParent).find("select.jsn-fontFace").append(
	                    $("<option/>", {"selected":"selected", "value":i, "text":val, "class":"jsn-fontFace-" + i.toLowerCase().replace(/\ /g, "-")})
	                )
	            } else {
	                $(divParent).find("select.jsn-fontFace").append(
	                    $("<option/>", {"value":i, "text":val, "class":"jsn-fontFace-" + i.toLowerCase().replace(/\ /g, "-")})
	                )
	            }
			});

			$(divParent).find("select.jsn-fontFace").select2({
	            dropdownCssClass:'jsn-list-fontFace'
	        });

			$(divParent).find("select.jsn-fontFaceType").select2({
				minimumResultsForSearch:99
			});
		},

		getStandardOptions: function () {
			var listFonts = {
				"Verdana":"Verdana",
	            "Georgia":"Georgia",
	            "Courier New":"Courier New",
	            "Arial":"Arial",
	            "Tahoma":"Tahoma",
	            "Trebuchet MS":"Trebuchet MS"
			};
			return listFonts;
		},

		getGoogleOptions: function () {
			var listFonts = {
				"Open Sans":"Open Sans", "Oswald":"Oswald", "Droid Sans":"Droid Sans", "Lato":"Lato", "Open Sans Condensed":"Open Sans Condensed", "PT Sans":"PT Sans", "Ubuntu":"Ubuntu", "PT Sans Narrow":"PT Sans Narrow",
	            "Yanone Kaffeesatz":"Yanone Kaffeesatz", "Roboto Condensed":"Roboto Condensed", "Source Sans Pro":"Source Sans Pro", "Nunito":"Nunito", "Francois One":"Francois One", "Roboto":"Roboto", "Raleway":"Raleway", "Arimo":"Arimo",
	            "Cuprum":"Cuprum", "Play":"Play", "Dosis":"Dosis", "Abel":"Abel", "Droid Serif":"Droid Serif", "Arvo":"Arvo", "Lora":"Lora", "Rokkitt":"Rokkitt", "PT Serif":"PT Serif", "Bitter":"Bitter", "Merriweather":"Merriweather", "Vollkorn":"Vollkorn",
	            "Cantata One":"Cantata One", "Kreon":"Kreon", "Josefin Slab":"Josefin Slab", "Playfair Display":"Playfair Display", "Bree Serif":"Bree Serif", "Crimson Text":"Crimson Text", "Old Standard TT":"Old Standard TT", "Sanchez":"Sanchez",
	            "Crete Round":"Crete Round", "Cardo":"Cardo", "Noticia Text":"Noticia Text", "Judson":"Judson", "Lobster":"Lobster", "Unkempt":"Unkempt", "Changa One":"Changa One", "Special Elite":"Special Elite",
	            "Chewy":"Chewy", "Comfortaa":"Comfortaa", "Boogaloo":"Boogaloo", "Fredoka One":"Fredoka One", "Luckiest Guy":"Luckiest Guy", "Cherry Cream Soda":"Cherry Cream Soda",
	            "Lobster Two":"Lobster Two", "Righteous":"Righteous", "Squada One":"Squada One", "Black Ops One":"Black Ops One", "Happy Monkey":"Happy Monkey", "Passion One":"Passion One", "Nova Square":"Nova Square", "Metamorphous":"Metamorphous", "Poiret One":"Poiret One", "Bevan":"Bevan", "Shadows Into Light":"Shadows Into Light", "The Girl Next Door":"The Girl Next Door", "Coming Soon":"Coming Soon",
	            "Dancing Script":"Dancing Script", "Pacifico":"Pacifico", "Crafty Girls":"Crafty Girls", "Calligraffitti":"Calligraffitti", "Rock Salt":"Rock Salt", "Amatic SC":"Amatic SC", "Leckerli One":"Leckerli One", "Tangerine":"Tangerine", "Reenie Beanie":"Reenie Beanie", "Satisfy":"Satisfy", "Gloria Hallelujah":"Gloria Hallelujah", "Permanent Marker":"Permanent Marker", "Covered By Your Grace":"Covered By Your Grace", "Walter Turncoat":"Walter Turncoat", "Patrick Hand":"Patrick Hand", "Schoolbell":"Schoolbell", "Indie Flower":"Indie Flower"
			};
			return listFonts;
		}

	}

})(jQuery);