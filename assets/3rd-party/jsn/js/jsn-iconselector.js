(
    function ($) {
        var JSNIconSelector = function (params) {

        }
        JSNIconSelector.prototype = {
            GenerateSelector:function (container, actionSelector, value) {
                var self = this;
                var resultsFilter = $("<ul/>", {"class":"jsn-items-list"});
                $("#jsn-quicksearch-icons").val("");
                $(container).find(".jsn-reset-search").hide();
                self.renderListIconSelector(resultsFilter, self.Icomoon(), actionSelector, value);
                $.fn.delayKeyup = function (callback, ms) {
                    var timer = 0;
                    var el = $(this);
                    $(this).keyup(function () {
                        clearTimeout(timer);
                        timer = setTimeout(function () {
                            callback(el)
                        }, ms);
                    });
                    return $(this);
                };
                var oldIconFilter = "";
                return $("<div/>", {"class":"jsn-iconselector"}).append(
                    $("<div/>", {"class":"jsn-fieldset-filter"}).append(
                        $("<fieldset/>").append(
                            $("<div/>", {"class":"jsn-quick-search"}).append(
                                $("<input/>", {"class":"input input-sm search-query form-control", "type":"text","id":"jsn-quicksearch-icons", "placeholder":"Search..."}).delayKeyup(function (el) {
                                    if ($(el).val() != oldIconFilter) {
                                        oldIconFilter = $(el).val();
                                        self.filterResults($(el).val(), resultsFilter);
                                    }
                                    if($(el).val() == ""){
                                        $(el).parents(".jsn-iconselector").find(".jsn-reset-search").hide();
                                    }else{
                                        $(el).parents(".jsn-iconselector").find(".jsn-reset-search").show();
                                    }
                                }, 500)
                            ).append(
                                $("<a/>",{"href":"javascript:void(0);","title":"Clear Search","class":"jsn-reset-search"}).append($("<i/>",{"class":"icon-remove"})).click(function(){
                                    $(this).parents(".jsn-iconselector").find("#jsn-quicksearch-icons").val("");
                                    oldIconFilter = "";
                                    self.filterResults("", resultsFilter);
                                    $(this).hide();
                                })
                            )
                        )
                    )
                ).append(resultsFilter);

            },
            filterResults:function (value, resultsFilter) {
                $(resultsFilter).find("li").hide();
                if (value != "") {
                    $(resultsFilter).find("li").each(function () {
                        var textField = $(this).find("a").attr("data-value").toLowerCase();
                        textField = textField.replace('icon-', '');
                        if (textField.search(value.toLowerCase()) == -1) {
                            $(this).hide();
                        } else {
                            $(this).fadeIn(1200);
                        }
                    });
                } else {
                    $(resultsFilter).find("li").each(function () {
                        $(this).fadeIn(1200);
                    });
                }
            },
            renderListIconSelector:function ( container, list, actionSelector, valueDefault) {
                $(container).find("li").removeClass("active");
                $(container).html("");

                var _nonIconClass	= 'jsn-item';
                if (!valueDefault) {
                	_nonIconClass	= 'jsn-item active';
                }
                $(container).append(
                        $("<li/>", {'class': _nonIconClass}).append(
                            $("<a/>", {"href":"javascript:void(0)", "class":"icons-item wr-tooltip-icon", "data-value":'', "data-original-title": ''}).append($("<i/>", {"class":'icon-'})).append('None').click(function () {
                                actionSelector(this);
                            })
                        )
                );

                $.each(list, function (value, title) {
                    var classActive = {"class":"jsn-item"};
                    if (value == valueDefault) {
                        classActive = {"class":"jsn-item active"};
                    }
                    $(container).append(
                        $("<li/>", classActive).append(
                            $("<a/>", {"href":"javascript:void(0)", "class":"icons-item wr-tooltip-icon", "data-value":value, "data-original-title": title}).append($("<i/>", {"class":value})).click(function () {
                                actionSelector(this);
								$('[data-title-prepend]').trigger('change');
                            })
                        )
                    );
                });
            },
            Icomoon:function () {
                return {
                    "icon-home":"home",
                    "icon-user":"user",
                    "icon-locked":"locked",
                    "icon-comments":"comments",
                    "icon-comments-2":"comments-2",
                    "icon-out":"out",
                    "icon-redo":"redo",
                    "icon-undo":"undo",
                    "icon-file-add":"file-add",
                    "icon-plus":"plus",
                    "icon-pencil":"pencil",
                    "icon-pencil-2":"pencil-2",
                    "icon-folder":"folder",
                    "icon-folder-2":"folder-2",
                    "icon-picture":"picture",
                    "icon-pictures":"pictures",
                    "icon-list-view":"list-view",
                    "icon-power-cord":"power-cord",
                    "icon-cube":"cube",
                    "icon-puzzle":"puzzle",
                    "icon-flag":"flag",
                    "icon-tools":"tools",
                    "icon-cogs":"cogs",
                    "icon-cog":"cog",
                    "icon-equalizer":"equalizer",
                    "icon-wrench":"wrench",
                    "icon-brush":"brush",
                    "icon-eye":"eye",
                    "icon-checkbox-unchecked":"checkbox-unchecked",
                    "icon-checkbox":"checkbox",
                    "icon-checkbox-partial":"checkbox-partial",
                    "icon-star":"star",
                    "icon-star-2":"star-2",
                    "icon-star-empty":"star-empty",
                    "icon-calendar":"calendar",
                    "icon-calendar-2":"calendar-2",
                    "icon-help":"help",
                    "icon-support":"support",
                    "icon-warning":"warning",
                    "icon-checkmark":"checkmark",
                    "icon-cancel":"cancel",
                    "icon-minus":"minus",
                    "icon-remove":"remove",
                    "icon-mail":"mail",
                    "icon-mail-2":"mail-2",
                    "icon-drawer":"drawer",
                    "icon-drawer-2":"drawer-2",
                    "icon-box-add":"box-add",
                    "icon-box-remove":"box-remove",
                    "icon-search":"search",
                    "icon-filter":"filter",
                    "icon-camera":"camera",
                    "icon-play":"play",
                    "icon-music":"music",
                    "icon-grid-view":"grid-view",
                    "icon-grid-view-2":"grid-view-2",
                    "icon-menu":"menu",
                    "icon-thumbs-up":"thumbs-up",
                    "icon-thumbs-down":"thumbs-down",
                    "icon-cancel-2":"cancel-2",
                    "icon-plus-2":"plus-2",
                    "icon-minus-2":"minus-2",
                    "icon-key":"key",
                    "icon-quote":"quote",
                    "icon-quote-2":"quote-2",
                    "icon-database":"database",
                    "icon-location":"location",
                    "icon-zoom-in":"zoom-in",
                    "icon-zoom-out":"zoom-out",
                    "icon-expand":"expand",
                    "icon-contract":"contract",
                    "icon-expand-2":"expand-2",
                    "icon-contract-2":"contract-2",
                    "icon-health":"health",
                    "icon-wand":"wand",
                    "icon-refresh":"refresh",
                    "icon-vcard":"vcard",
                    "icon-clock":"clock",
                    "icon-compass":"compass",
                    "icon-address":"address",
                    "icon-feed":"feed",
                    "icon-flag-2":"flag-2",
                    "icon-pin":"pin",
                    "icon-lamp":"lamp",
                    "icon-chart":"chart",
                    "icon-bars":"bars",
                    "icon-pie":"pie",
                    "icon-dashboard":"dashboard",
                    "icon-lightning":"lightning",
                    "icon-move":"move",
                    "icon-next":"next",
                    "icon-previous":"previous",
                    "icon-first":"first",
                    "icon-last":"last",
                    "icon-loop":"loop",
                    "icon-shuffle":"shuffle",
                    "icon-arrow-first":"arrow-first",
                    "icon-arrow-last":"arrow-last",
                    "icon-arrow-up":"arrow-up",
                    "icon-arrow-right":"arrow-right",
                    "icon-arrow-down":"arrow-down",
                    "icon-arrow-left":"arrow-left",
                    "icon-arrow-up-2":"arrow-up-2",
                    "icon-arrow-right-2":"arrow-right-2",
                    "icon-arrow-down-2":"arrow-down-2",
                    "icon-arrow-left-2":"arrow-left-2",
                    "icon-play-2":"play-2",
                    "icon-menu-2":"menu-2",
                    "icon-arrow-up-3":"arrow-up-3",
                    "icon-arrow-right-3":"arrow-right-3",
                    "icon-arrow-down-3":"arrow-down-3",
                    "icon-arrow-left-3":"arrow-left-3",
                    "icon-printer":"printer",
                    "icon-color-palette":"color-palette",
                    "icon-camera-2":"camera-2",
                    "icon-file":"file",
                    "icon-file-remove":"file-remove",
                    "icon-copy":"copy",
                    "icon-cart":"cart",
                    "icon-basket":"basket",
                    "icon-broadcast":"broadcast",
                    "icon-screen":"screen",
                    "icon-tablet":"tablet",
                    "icon-mobile":"mobile",
                    "icon-users":"users",
                    "icon-briefcase":"briefcase",
                    "icon-download":"download",
                    "icon-upload":"upload",
                    "icon-bookmark":"bookmark",
                    "icon-out-2":"out-2"
                }
            }
        }

        $(document).ready(function() {
			var bind_icon_picker = function( wrapper ){
				var selector = (wrapper != null) ? $(".icon_selector", wrapper) : $(".icon_selector");
				if(selector.first().children('.jsn-iconselector').length){
					return false;
				}
				selector.first().each(function(){
					var inputIcon  = $(this).find(":hidden").first()
					var iconSelector = new JSNIconSelector()
					var actionSelector = $.proxy(function (_this) {
						$(_this).parents(".jsn-items-list").find(".active").removeClass("active");
						$(_this).parent().addClass("active");
						inputIcon.val($(_this).attr("data-value"));
					}, this);
					$(this).append(iconSelector.GenerateSelector($(this), actionSelector, inputIcon.val()));
				});
			};

			// Add tooltip for icons
			var add_tooltip = function () {
                if ( typeof ( $.fn.tooltip ) == 'function' ) {
                	$('.wr-tooltip-icon').each(function () {
                    	$(this).tooltip({
                    		placement: 'top'
                    	});
                    });
                }
            };

            if($(".icon_selector").length){
				bind_icon_picker();
				add_tooltip();
            }

			// init Icon selector for Inline edit Sub item
			$('body').on( 'trigger_icon_selector', function(e, wrapper){
				bind_icon_picker( wrapper );
				add_tooltip();
			} );
        })
    })(jQuery)