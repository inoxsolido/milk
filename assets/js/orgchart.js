$(function(){
    
    var struct = {
        id:1,
        name:"",
        lv:1,
        par:0
    }
    var _divs;
    
    function ReqNewData(){
        $(".loader").slideDown();
        $.ajax({
            url: "../Data/JSONDivision",
            type: 'POST',
            async: false,
            data:{
                year: $("#mayear").val()
            },
            success: function (data, textStatus, jqXHR) {
                if(data.error == "dup"){
                    alert("มีการกำหนดโครงสร้างองค์กรสำหรับปีที่เลือกแล้ว\r\nกรุณาเลือกปีใหม่อีกครั้ง");
                }else{
                    var divs = data.divs;
                    _divs = divs;
                    var twidth = 200;
                    var ditem_2 = $("<div/>", {class: "items"});
                    for (var i in divs[2]){
                        ditem_2.append("<label><input type='checkbox' lv='2' value='"+divs[2][i].id+"'/>"+divs[2][i].name+"</label>");
                    }
                    for (var i in divs[1]){
                        ditem_2.append("<label><input type='checkbox' lv='3' value='"+divs[1][i].id+"'/>"+divs[1][i].name+"</label>");
                    }
                    for (var i in divs[3]) {// สร้างฝ่ายทั้งหมดก่อน
                        var li = $("<li/>", {did: divs[3][i].id, lv: 1});//parent of dnode
                        var dnode = $("<div/>", {class: "node dropdown-check-list",html: divs[3][i].name+" <br/>"});//parent
                        var span = $("<span/>", {class: "anchor glyphicon glyphicon-plus"});
                        //var ditem = $("<div/>", {class: "items"});
                        var ul = $("<ul/>", {class: "level-2"});//member container
                        
                        //assemble 
                        $(dnode).append(span);
                        $(dnode).append(ditem_2.clone());
                        $(li).append(dnode);
                        //$(li).append(ul);
                        twidth += $(li).width();
                        $(".level-1-container").append(li);
                        
                    }
                    //get level-1 item width
                    $(".level-1-container").children("li").each(function(){
                        twidth += $(this).width();
                        //alert(twidth);
                    });
                    $(".level-1-container").width(twidth);
                    
                    dropdown_checklist();
                    //$(".tree-org input[type=checkbox]").change(onCheckboxChange);
                }
            },dataType: 'JSON'
        });
        $(".loader").slideUp();
    }
    
    function onCheckboxChange(){
        var id = $(this).attr("value");
        var status = $(this).is(":checked");
        var parent = $(this).parent().parent().parent().parent();
        var parent_id = $(parent).attr("did");
        var parent_lv = $(parent).attr("lv");
        if(status){//checked
            //disable all item except this
            $("input[value="+id+"]:not(checked)").parent().hide();
            //$(this).prop("disabled", false);
            $(this).parent().show();
            //create node
            var li = $("<li/>", {did: id, lv: parent_lv+1});//parent of dnode
            var dnode = $("<div/>", {class: "node dropdown-check-list",html: $(this).parent().text()+" <br/>"});//parent
            var span = $("<span/>", {class: "anchor glyphicon glyphicon-plus"});
            //var ditem = $("<div/>", {class: "items"});
            var ul = $("<ul/>", {class: "level-"+parent_lv+1});//member container
            
            //create items
            var ditems = $("<div/>", {class: "items"});
            if(parent_lv+1 == 2){//กอง
                for(var i in _divs[1]){//ใส่ข้อมูลของแผนก/ตำแหน่ง
                    var label = $("<label/>", {text:_divs[1][i].name});
                    var input = $("<input/>", {value: _divs[1][i].id, lv:3});
                    $(label).prepend(input);
                    $(ditems).append(label);
                }
            }
            
            //assemble
            $(dnode).append(span);
            $(dnode).append(ditems);
            $(li).append(dnode);
            
            //check exist siblings
            if($(parent).children("ul").length){//exist siblings
                $(parent).children("ul").append(li);
            }else{
                $(parent).append($(ul).append(li));
            }
            $(".tree-org input[type=checkbox]").change(onCheckboxChange);
            dropdown_checklist();
        }else{
            //enable all item
            $("input[value="+id+"]:not(checked)").parent().show();
            //find node
            var node = $("li[did="+id+"]");
            //check children of node
            var exist_children = $(node).children("ul").length > 0;
            //alert if exist children
            //*can uncheck only leaf node !
            if(exist_children)
                alert("ไม่สามารถลบได้ เนื่องจากยังมีสังกัด สังกัดอยู่");
            else{
                $(node).remove();
            }
            
        }
              
        
    }
    
    
    
    
    
    $("#mbtnselyear").click(function(){
        var year = $("#mayear").val();
        if(year){
            ReqNewData();
        }
    });
});