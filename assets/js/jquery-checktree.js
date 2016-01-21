/**
 * jQuery littleTree
 * 
 * version 0.2 added indeterminate state
 *
 * @version  0.2
 * @author   Mikahil Matyunin <free.all.bums@gmail.com>
 * @editor Ritthichai Sakulthong
 */

/**
 * <ul id="tree">
 *   <li><label><input type="checkbox" />Item1</label></li>
 *   <li>
 *     <label><input type="checkbox" />ItemWithSubitems</label>
 *     <ul>
 *       <li><label><input type="checkbox" />Subitem1</label></li>
 *     </ul>
 *   </li>
 * </ul>
 *
 * Usage:
 *
 * $('ul#tree').checktree();
 *
 */

(function ($) {
    $.fn.extend({
        checktree: function () {
            $(this)
                    .addClass('checktree-root')
                    .on('change', 'input[type="checkbox"]', function (e) {
                        e.stopPropagation();
                        e.preventDefault();

                        checkChildren($(this));
                        checkParents($(this));
                    })
                    ;

            var checkParents = function (c)
            {
                var parentLi = c.parents('ul:eq(0)').parents('li:eq(0)');

                if (parentLi.length)
                {
                    var siblingsChecked = c.parents('ul:eq(0)').find('input[type="checkbox"]:checked').length,
                            siblingsCheckbox = c.parents('ul:eq(0)').find('input[type="checkbox"]').length,
                            rootCheckbox = parentLi.find('input[type="checkbox"]:eq(0)')
                            ;

                    if (c.is(':checked') && siblingsChecked == siblingsCheckbox)
                        rootCheckbox.prop({'checked': true, 'indeterminate': false});
                    else if (siblingsChecked == 0)
                        rootCheckbox.prop({'checked': false, 'indeterminate': false});
                    else
                        rootCheckbox.prop({'checked': true, 'indeterminate': true});
                    //console.log(rootCheckbox);
                    checkParents(rootCheckbox);
                }
            }

            var checkChildren = function (c)
            {
                var childLi = $('ul li input[type="checkbox"]', c.parents('li:eq(0)'));
                
                if (childLi.length) {
                    childLi.prop({'checked': c.is(':checked'), 'indeterminate': false});
                    childLi.each(function (index, entry) {
                        var div = $(entry).parent().next("div");
                        if ($(entry).is(":checked")) {
                            div.show();
                            div.addClass("txtshow");
                        } else {
                            div.hide();
                            div.removeClass("txtshow");
                        }
                    });
                    childLi.parent().next("div").find("input[type=text]")[0];
                    if(x)x.focus();
                            
                }
                var div = (c).parent().next("div");
                if ((c).is(":checked")) {
                    div.show();
                    div.addClass("txtshow");
                    var x=(c).parent().next("div").find("input[type=text]").focus();
                    if(x) $(x).focus();
                } else {
                    div.hide();
                    div.removeClass("txtshow");
                }
            }
        }

    });
})(jQuery);
