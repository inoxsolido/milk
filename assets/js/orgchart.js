$(function(){
    
    checkParams();
    
    
    var _year;
    var _method;
    
    var _divs;
    function ReqData(){
        $(".loading").fadeIn();
        $.ajax({
            url: "../OrgChart/FillOrgYear",
            type: 'POST',
            async: false,
            success: function (data, textStatus, jqXHR) {
                $("#yearlist").children("tbody").html(data);
                $("#yearlist").show();
                $(".tree-org").hide();
                $("#saveopt").hide();
                $(".loading").fadeOut();
            }
            
        });
    }
    function ReqNewData(method, year){
        if(!method) method = _method;
        else _method = method;
        if(!year) year = _year;
        else _year = year;
        
        $(".loading").css('display','block');
        
            $.ajax({
                url: "../OrgChart/JSONDivision",
                type: 'POST',
                async: false,
                data:{
                    year: _year
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
                            ditem_2.append("<label><input type='checkbox' class='dcheck' lv='2' value='"+divs[2][i].id+"'/>"+divs[2][i].name+"</label>");
                        }
                        for (var i in divs[1]){
                            ditem_2.append("<label><input type='checkbox' class='dcheck' lv='3' value='"+divs[1][i].id+"'/>"+divs[1][i].name+"</label>");
                        }
                        for (var i in divs[3]) {// สร้างฝ่ายทั้งหมดก่อน
                            var li = $("<li/>", {did: divs[3][i].id, lv: 1});//parent of dnode
                            var dnode = $("<div/>", {class: "node dropdown-check-list",html: divs[3][i].name+" <br/>"});//parent
                            var span = $("<span/>", {class: "anchor glyphicon glyphicon-edit"});
                            //var ditem = $("<div/>", {class: "items"});
                            var ul = $("<ul/>", {class: "level-2"});//member container

                            //assemble 
                            $(dnode).append(span);
                            $(dnode).append(ditem_2.clone());
                            $(li).append(dnode);
                            //$(li).append(ul);
                            $(".level-1-container").append(li);

                        }
                        $(".dcheck").each(function(){
                            var parent = $(this).parent().parent().parent().parent();//li
                            var parent_id = $(parent).attr("did");
                            $(this).attr("par", parent_id);
                        });

                        

                        dropdown_checklist();
                        //$(".tree-org input[type=checkbox]").change(onCheckboxChange);
                        bindCheckbox();
                        
                        $("#yearlist").hide();
                        $(".tree-org").show();
                        $("#saveopt").show();
                        
                    }
                },dataType: 'JSON'
            });
        if(method == "edit"){
            $.ajax({
                url: "../OrgChart/JSONDivRelate",
                type: 'POST',
                async: false,
                data:{
                    year: _year
                },
                success: function (data, textStatus, jqXHR) {
                    if(data.error == "no year"){
                        alert("ไม่สามารถแก้ไขโครงสร้างองค์กรในปีที่ยังไม่เคยกำหนดโครงสร้างองค์กรได้");
                        
                        return false;
                    }else{
                        var div = data.old;
                        
                        for(var i in div){
                            //select parent lv3 first
                            ($("input[value="+div[i].child.id+"][par="+div[i].parent.id+"]").prop({checked: true}).change());
                        }
                        

                        
                    }
                },dataType: 'JSON'
            });
        }
        
        
        containerUpdate();
        
        
        $(".loading").slideUp();
    }
    
    function getDivLevel(id){
        var found = false;
        //lv2
        var obj = _divs[1].filter(function(e){return e.id === id});
        found = obj.length?1:0;
        if(!found){
            obj = _divs[2].filter(function(e){return e.id === id});
            found = obj.length?2:0;
        }
        return found;
    }
    
    function bindCheckbox(){
        $(".dcheck").unbind("change", onCheckboxChange);
        $(".dcheck").change(onCheckboxChange);
    }
    
    function containerUpdate() {
        //get level-1 item width
        var twidth = 200;
        $(".level-1-container").children("li").each(function () {
            twidth += $(this).width();
            //alert(twidth);
        });
        $(".level-1-container").width(twidth);
    }
    
    function onCheckboxChange(e){
        var id = $(this).attr("value");
        var status = $(this).is(":checked");
        var parent = $(this).parent().parent().parent().parent();//li
        var parent_id = $(parent).attr("did");
        var parent_lv = Number($(parent).attr("lv"));
        var ul_lv = Number(/[0-9]/.exec($(this).parent().parent().parent().next().attr("class")));
        if(status){//checked
            //get newNodeLevel
            var lv = getDivLevel(id);
            //disable all item except this
            $("input[value="+id+"]:not(checked)").parent().hide();
            //$(this).prop("disabled", false);
            $(this).parent().show();
            //create node
            var li = $("<li/>", {did: id, lv: parent_lv+1});//parent of dnode
            var dnode = $("<div/>", {class: "node dropdown-check-list",html: $(this).parent().text()+" <br/>"});//parent
            var span = $("<span/>", {class: "anchor glyphicon glyphicon-edit"});
            //var ditem = $("<div/>", {class: "items"});
            var ul = $("<ul/>", {class: "level-"+Number(ul_lv+1)});//member container
            
            //create items
            var ditems = $("<div/>", {class: "items"});
            if(lv == 2){//กอง
                for (var i in _divs[1]){
                    var checked = $("input[value="+_divs[1][i].id+"]:checked").length > 0?'style="display:none"':"";
                    ditems.append("<label "+checked+"><input type='checkbox' class='dcheck' par='"+id+"' lv='3' value='"+_divs[1][i].id+"'  />"+_divs[1][i].name+"</label>");
                    
                }
                $(dnode).append(span);
                $(dnode).append(ditems);
                $(li).append(dnode);
            }else if(lv == 1){
                $(li).append(dnode);
            }            
            //check exist siblings
            if($(parent).children("ul").length){//exist siblings
                
                $(parent).children("ul").append(li);
            }else{
                $(ul).append(li);
                $(parent).append(ul);
            }
            //$(".tree-org input[type=checkbox]").change(onCheckboxChange);
            bindCheckbox();
            dropdown_checklist();
        }else{
            
            //find node
            var node = $("li[did="+id+"]");
            //check siblings
            var sib = $(node).siblings().length;
            //check children of node
            var exist_children = $(node).children("ul").length > 0;
            //alert if exist children
            //*can uncheck only leaf node !
            if(exist_children){
                alert("ไม่สามารถลบได้ เนื่องจากยังมีหน่วยงาน สังกัดอยู่");
                $(this).prop({checked:true});
            }
            else{
                if(sib)
                    $(node).remove();
                else{
                    $(node).parent().remove();
                }
                //enable all item
                $("input[value="+id+"]:not(checked)").parent().show();
            }
            
        }
        containerUpdate();
        
    }
    
    function generateData(){
        //get checked input
        var fdata = [];
        $(".dcheck:checked").each(function(){
            var rec = {id: $(this).val(), par:$(this).attr("par")};
            fdata.push(rec);
        });
        return fdata;
    }
    
    function checkParams(){
        var params = $.getQueryParameters();
        if(!params.method || !params.year) 
            ReqData();
        else 
            ReqNewData(params.method, params.year);
    }
    
    
    
    
    
    
    $("#yearlist").on("click", ".assign, .edit, .cancel", function(){
        var method;
        if($(this).hasClass("assign")) method = "assign";
        else if($(this).hasClass("edit")) {
            method = "edit";
            if(!confirm("การแก้ไขโครงสร้างองค์กรที่ใช้ในปีงบประมาณจะทำให้ลำดับของการยืนยันข้อมูลและการออกรายงานมีการเปลี่ยนแปลง\r\nต้องการแก้ไข ?")) return false;
        }
        else if($(this).hasClass("cancel")) {
            if(confirm("การลบโครงสร้างองค์กรที่ใช้ในปีงบประมาณออกจะทำให้ไม่สามารถออกรายงานงบประมาณในปี "+(Number($(this).parent().attr("year"))+543)+" ได้\r\nคุณต้องการที่จะลบ ?")){
                $(".loading").show();
                $.ajax({
                    url: "../OrgChart/DeleteOrgChart",
                    type: 'POST',
                    async: false,
                    data:{
                        year: $(this).parent().attr("year")
                    },success: function (data, textStatus, jqXHR) {
                        if(data == 'ok'){
                            alert("การลบเสร็จสมบูรณ์");
                            ReqData();
                        }else{
                            alert("การลบโครงสร้างองค์กรล้มเหลว "+data);
                            $(".loading").fadeOut();
                        }
                    }
                });
            }
            return true;
        };
        if(method === undefined) return false;
        
        _year = $(this).parent().attr("year");
        _method = method;
        ReqNewData();
        
        
    });
    $("#btnback").click(function () {
        if (confirm("โครงส้รางองค์กรที่คุณกำหนดไว้จะหายไป\r\nคุณต้องการย้อนกลับ ?"))
        {
            $(".level-1-container").html("").parent().hide();
            $("#yearlist").show();
            $("#saveopt").hide();
            window.location.search = "";
        }
    });
    $("#btnsave").click(function(){
       if(confirm("ต้องการบันทึก")) {
           $(".loading").slideDown();
           
           var fdata = generateData();
           if(!fdata.length){
               alert("กรุณาเลือกโครงสร้างองค์กรก่อนบันทึก");
               return false;
           }
           
           $.ajax({
                url: "../OrgChart/SaveOrgChart",
                type: 'POST',
                async: false,
                data:{
                    year:_year,
                    fdata:fdata
                },success: function (data, textStatus, jqXHR) {
                    if(data == 'ok'){
                        alert("การบันทึกสำเร็จ");
                        window.location.search = "";
                        $(".level-1-container").html("").parent().hide();
                        $("#yearlist").show();
                        $("#saveopt").hide();
                    }else{
                        alert("การบันทึกล้มเหลว");
                    }
                }
           });
           $(".loading").slideUp();
           
           
            
       }
    });
});