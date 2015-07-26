/**
 * jQuery littleTree
 *
 * @version  0.1
 * @author   Mikahil Matyunin <free.all.bums@gmail.com>
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

(function($){
    $.fn.extend({

        checktree: function(){
            $(this)
                .addClass('checktree-root')
                .on('change', 'input[type="checkbox"]', function(e){
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
                    //alert(siblingsChecked+" "+siblingsCheckbox);
                    if (c.is(':checked') && siblingsChecked == siblingsCheckbox)
                        rootCheckbox.prop({'checked': true, 'indeterminate': false});
                    else if (siblingsChecked == 0)
                        rootCheckbox.prop({'checked': false, 'indeterminate': false});
                    else
                        rootCheckbox.prop({'checked': true, 'indeterminate': true});

                    checkParents(rootCheckbox);
                }
            }

            var checkChildren = function (c)
            {
                var childLi = $('ul li input[type="checkbox"]', c.parents('li:eq(0)'));

                if (childLi.length)
                    childLi.prop({'checked': c.is(':checked'), 'indeterminate': false});
            }
        }

    });
})(jQuery);
