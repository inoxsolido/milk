/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function ulcollapse(target){
    target.on("click",function(){
        var sib = $(this).siblings('ul');
        var icon = $(this).children('i');
        var state = icon.hasClass("glyphicon-plus");//if plus mean next to show (minus)
        if(state)
        {
            sib.slideDown();
            icon.removeClass("glyphicon-plus");
            icon.addClass("glyphicon-minus");
        }else{
            sib.slideUp();
            icon.removeClass("glyphicon-minus");
            icon.addClass("glyphicon-plus");
        }
    });
}
