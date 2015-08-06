$(function() {
  	  // Apparently click is better chan change? Cuz IE?
      $("#accyear").on("change", 'input[type="checkbox"]', function(e) {
      var checked = $(this).prop("checked"),
          container = $(this).parent();

      container.find('input[type="checkbox"]').prop({
          indeterminate: false,
          checked: checked
      });

      function checkSiblings(el) {
          var parent = el.parent().parent(),
              all = true;
          el.siblings().each(function(index) {
            console.log("all["+index+"] : "+($(this).children('input[type="checkbox"]').prop("checked") === checked));
              return all = ($(this).children('input[type="checkbox"]').prop("checked") === checked);
          });

          if (all && checked) {
              parent.children('input[type="checkbox"]').prop({
                  indeterminate: false,
                  checked: checked
              });
              checkSiblings(parent);
          } else if (all && !checked) {
              parent.children('input[type="checkbox"]').prop("checked", checked);
              parent.children('input[type="checkbox"]').prop("indeterminate", (parent.find('input[type="checkbox"]:checked').length > 0));
              checkSiblings(parent);
          } else {
              el.parents("li").children('input[type="checkbox"]').prop({
                  indeterminate: true,
                  checked: false
              });
          }
        }

        checkSiblings(container);
      });
    });