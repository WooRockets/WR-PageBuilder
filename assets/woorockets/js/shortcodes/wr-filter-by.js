/**
 * @version    $Id$
 * @package    WR PageBuilder
 * @author     WooRockets Team <support@woorockets.com>
 * @copyright  Copyright (C) 2012 woorockets.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.woorockets.com
 * Technical Support:  Feedback - http://www.woorockets.com
 */

/**
 * Custom script for Filter By in ContentList, ContentClips, ...
 */
(function($) {
    "use strict";

    $.WR_FilterBy_Element = $.WR_FilterBy_Element || {};

    $.WR_FilterBy_Element = function() {
        $('#param-item_filter').on('change', function(e) {
            $.WR_FilterBy_Element_select();
            $('#param-cl_start_from_menu option').removeAttr('selected');
            $('#param-cl_start_from_menu option[value="0"]').attr('selected', '');
        });
        $.WR_FilterBy_Element_select();
    }

    $.WR_FilterBy_Element_select = function() {
        var selectValue = $('#param-item_filter').val();
        if (selectValue) {
            $('#param-cl_start_from_menu option').hide();
            $('#param-cl_start_from_menu option[value="0"]').show();
        }
    }

    $.WR_FilterBy_MenuChange = function() {
        $('#param-item_filter').on('change', function() {
            var selectVal = $('#param-item_filter').val();
            $('#param-wr_cl_menu_start_from option').hide();
            if (selectVal) {
                $('#param-wr_cl_menu_start_from option[value="0"]').attr('selected', '');
            }
        });
    }

    $(document).ready(function() {
        $.WR_FilterBy_Element();
        $.WR_FilterBy_MenuChange();

        $('select[name="param-wr_cl_orderby"]').on('change', function() {
            var option_val = $("#param-wr_cl_source option:selected").val();
            var allow_depth = $('#param-wr_cl_depth_level_allow').val();
            var arr_allow_depth = allow_depth.split(',');
            if ($(this).val() != 'no_order') {
                $('#param-wr_cl_order').show();
                $('#parent-param-cl_depth_level').hide();
                if (arr_allow_depth.indexOf(option_val) >= 0) {
                    $('#parent-param-item_filter').hide();
                }
            } else {
                $('#param-wr_cl_order').hide();
                $('#parent-param-item_filter').show();
                if (arr_allow_depth.indexOf(option_val) >= 0) {
                    $('#parent-param-cl_depth_level').show();
                }
            }
            // process show/hidden taxonomy without parent-child type
            var wr_cl_tax_no_parent = $('#param-wr_cl_tax_no_parent').val();
            var arr_wr_cl_tax_no_parent = wr_cl_tax_no_parent.split(',');

            if (arr_wr_cl_tax_no_parent.indexOf(option_val) >= 0) {
                $('#parent-param-item_filter').hide();
            }

            // process category from parent type to single type
            if ($('#parent-param-item_filter div[data-depend-value="' + option_val + '"] #param-item_filter').attr('multiple') && $('#parent-param-item_filter').children('.control-label').html() == Wr_Translate.startFrom) {
                $('#parent-param-item_filter.control-group').hide();
                $('#parent-param-cl_depth_level').hide();
            }
        });

        $('#param-wr_cl_source').on('change', function() {
            $("#parent-param-item_filter").show();
            $('#parent-param-cl_depth_level').show();
            var option_text = $("#param-wr_cl_source option:selected").text();
            var option_val = $("#param-wr_cl_source option:selected").val();
            var label = $("#parent-param-item_filter").children('.control-label');

            // hide Category in Content Elements box if option_val = page
            if(option_val == 'page'){
                $("#parent-param-elements .jsn-items-list .jsn-item").filter(function(){
                    return $(this).find('#param-elements').val() == "category"
                }).addClass('hidden');
            }else{
                $("#parent-param-elements .jsn-items-list .jsn-item").filter(function(){
                    return $(this).find('#param-elements').val() == "category"
                }).removeClass('hidden');
            }

            var allow_depth = $('#param-wr_cl_depth_level_allow').val();
            var arr_allow_depth = allow_depth.split(',');
            var allow_filter = $('#param-wr_cl_filter_allow').val();
            var arr_compare = [];
            for (var i = 0; i < arr_allow_depth.length; i++) {
                if (allow_filter.search(arr_allow_depth[i]) >= 0) {
                    allow_filter = allow_filter.replace(arr_allow_depth[i], '');
                }
            }
            if (allow_filter) {
                var arr_allow_filter = allow_filter.split(',');
            }

            var controls = $("#parent-param-item_filter").children('.controls');
            var visibleChild = controls.children("[data-depend-value='" + option_val + "']");
            var dropbox = visibleChild.children('select[name="param-item_filter"]');

            var allow_filter_by = false;
            if (typeof (dropbox.html()) != 'undefined') {
                var dropbox_html = dropbox.html();
                if (dropbox_html.search('optgroup') > 0) {
                    allow_filter_by = true;
                }
            }

            if (option_val == 'nav_menu_item') {
                label.html(Wr_Translate.menu);
            } else if (allow_filter_by == true) {
                label.html(Wr_Translate.filterBy);
            } else if (arr_allow_depth.indexOf(option_val) >= 0) {
                label.html(Wr_Translate.startFrom);
            } else {
                label.html(Wr_Translate.itemFilter.replace('%s', option_text));
            }

            // show depth level options
            if (arr_allow_depth.indexOf(option_val) >= 0 && $.trim(visibleChild.html()) != '' && $.trim(visibleChild.html()) != '<label style="margin-top: 6px;">' + Wr_Translate.noItem.replace('%s', option_text.toLowerCase()) + '</label>') {
                $('#parent-param-cl_depth_level').show();
            } else {
                $('#parent-param-cl_depth_level').hide();
            }

            if ($.trim(visibleChild.html()) == '') {
                if (arr_allow_filter.indexOf(option_val) >= 0) {
                    visibleChild.html('<label style="margin-top: 6px;">' + Wr_Translate.noItem.replace('%s', 'taxonomy') + '</label>');
                } else {
                    visibleChild.html('<label style="margin-top: 6px;">' + Wr_Translate.noItem.replace('%s', option_text.toLowerCase()) + '</label>');
                }

                $('#parent-param-cl_depth_level').hide();
            }

            // show order options
            $('#parent-param-wr_cl_order_group').hide();
            var arr_orderby = $('#parent-param-wr_cl_order_group .wr_depend_other');
            var arr_allow_order = [];
            arr_orderby.each(function() {
                arr_allow_order.push($(this).attr('data-depend-value'));
            });
            if (arr_allow_order.indexOf(option_val) >= 0) {
                $('#parent-param-wr_cl_order_group').show();
            }
            $('#parent-param-wr_cl_order_group div[data-depend-value="' + option_val + '"] select').trigger('change');

            // process show/hidden taxonomy without parent-child type
            var wr_cl_tax_no_parent = $('#param-wr_cl_tax_no_parent').val();
            var arr_wr_cl_tax_no_parent = wr_cl_tax_no_parent.split(',');
            if (arr_wr_cl_tax_no_parent.indexOf(option_val) >= 0) {
                $('#parent-param-item_filter').hide();
            }

            if ($.trim(visibleChild.html()) == '<label style="margin-top: 6px;">' + Wr_Translate.noItem.replace('%s', option_text.toLowerCase()) + '</label>') {
                $("#parent-param-wr_cl_order_group").hide();
            }

            // only show options for paging if item amount is shown (source element doesn't have hierarchy )
            if (option_val == 'nav_menu_item' || option_val == 'page') {
                $('#parent-param-total_items').addClass('wr_hidden_depend');
                $('#parent-param-items_per_page').addClass('wr_hidden_depend');
                $('#parent-param-paging').addClass('wr_hidden_depend');
            }else{
                $('#parent-param-total_items').removeClass('wr_hidden_depend');
                $('#parent-param-items_per_page').removeClass('wr_hidden_depend');
                $('#parent-param-paging').removeClass('wr_hidden_depend');
            }

            // process when select menu
            if (!$('#parent-param-wr_cl_menu_start_from').hasClass('hidden')) {
                $('#parent-param-wr_cl_menu_start_from').addClass('hidden');
            }
            if (option_val == 'nav_menu_item') {
                if ($.trim(visibleChild.html()) != '<label style="margin-top: 6px;">' + Wr_Translate.noItem.replace('%s', option_text.toLowerCase()) + '</label>') {
                    if ($('#parent-param-wr_cl_menu_start_from').hasClass('hidden')) {
                        $('#parent-param-wr_cl_menu_start_from').removeClass('hidden');
                    }
                }
                $('#param-item_filter').trigger('change');
            }

            // reset options
            $('#parent-param-wr_cl_order_group div.wr_depend_other[data-depend-element="param-wr_cl_source"]').each(function() {
                if ($(this).attr('data-depend-value') != option_val) {
                    $(this).find('select').val($(this).find('select option:first').val());
                }
            });
            $('#parent-param-item_filter div.wr_depend_other[data-depend-element="param-wr_cl_source"]').each(function() {
                if ($(this).attr('data-depend-value') != option_val) {
                    if ($(this).find('select').attr('multiple')) {
                        $(this).find('select').select2('data', null);
                    } else {
                        $(this).find('select').val('root').trigger('change');
                    }
                }
            });

            $('#parent-param-wr_cl_orderby[data-depend-value=' + option_val + '] select[name="param-wr_cl_orderby"]').trigger('change');
        });
        $('#param-wr_cl_source').trigger('change');
    });

})(jQuery)