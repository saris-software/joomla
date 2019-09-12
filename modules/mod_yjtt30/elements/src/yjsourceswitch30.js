/**
 * @package		YJ Module Engine
 * @author		Youjoomla LLC
 * @website     Youjoomla.com
 * @copyright	Copyright (c) 2007 - 2011 Youjoomla LLC.
 * @license   PHP files are GNU/GPL V2. CSS / JS / IMAGES are Copyrighted Commercial
 */
jQuery.noConflict();
(function ($) {
    $(function () {
        $(document).ready(function ($) {


            $('.control-group').each(function (el) {
                var get_my_label = $(this).find('label');

                if (get_my_label.length > 0) {

                    var AddNewClass = get_my_label.attr("for").replace("jform_params_", "");
                    $(this).addClass(AddNewClass + '_yjme');

                }

            });

            $('.yjcatfilter_yjme,.k2catfilter_yjme').hide();
            var gotjoomla = $('#joomla_items_holder');
            var gotk2 = $('#k2_items_holder');
            // collect all items for toggler
            var get_yj_items = $('.j_news_source_yjme,.yjcatfilter_yjme,.get_items_yjme,.item_yjme,.getspecific_yjme,.ordering_yjme,.show_frontpage_yjme');
            var get_k2_items = $('.k2_news_source_yjme,.k2catfilter_yjme,.category_id_yjme,.k2item_yjme,.k2items_yjme,.k2image_size_yjme,.k2ordering_yjme');
            // add items in toggler
            get_yj_items.appendTo(gotjoomla);
            get_k2_items.appendTo(gotk2);


            // move order
            var cssholder = $('#css_file');
            var cssselect = $('#jform_params_module_css-lbl').parent().parent();

            cssholder.insertAfter(cssselect);

            var tmplholder = $('#copy_template');
            var tmplselect = $('#jform_params_module_template-lbl').parent().parent();
            tmplholder.insertAfter(tmplselect);


            $('#jform_params_item_source').on('change', function (e) {

                var selectedsource = $('#jform_params_item_source').chosen().val();

                if (selectedsource == 1) { ///joomla selected
                    $('#k2_items_holder').fadeOut();
                    $('#joomla_items_holder').fadeIn();
                    $('#selectedresult').text('Your news source is Joomla Content!').css('color', '#769904');
                    $('#select_source_title').css('color', '#769904');
                    $('#k2not').css('display', 'none');
                }

                if (selectedsource == 2) { ///k2 selected
                    $('#k2_items_holder').fadeIn();
                    $('#joomla_items_holder').fadeOut();
                    $('#selectedresult').text('Your news source is K2 Content!').css('color', '#1A6EAE');
                    $('#select_source_title').css('color', '#1A6EAE');
                    $('#k2not').css('display', 'block');
                }
            }).change();
        });
    });
})(jQuery);