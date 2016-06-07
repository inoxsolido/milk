<link href="<?= Yii::app()->request->baseUrl ?>/assets/css/datepicker.css" rel="stylesheet" type="text/css"/>
<style>
    /*Now the CSS*/
    * {margin: 0; padding: 0;}
       
    .tree-org ul {
        padding-top: 20px; position: relative;
        transition: all 0.5s;
        -webkit-transition: all 0.5s;
        -moz-transition: all 0.5s;
    }
    
    .tree-org .level-1-container{
        overflow-x:auto;
    }
    
    .tree-org li {
        float: left; text-align: center;
        list-style-type: none;
        position: relative;
        padding: 20px 5px 0 5px;

        transition: all 0.5s;
        -webkit-transition: all 0.5s;
        -moz-transition: all 0.5s;
        
    }

    /*We will use ::before and ::after to draw the connectors*/

    .tree-org li::before, .tree-org li::after{
        content: '';
        position: absolute; top: 0; right: 50%;
        border-top: 1px solid #ccc;
        width: 50%; height: 20px;
    }
    .tree-org li::after{
        right: auto; left: 50%;
        border-left: 1px solid #ccc;
    }

    /*We need to remove left-right connectors from elements without 
    any siblings*/
    .tree-org li:only-child::after, .tree-org li:only-child::before {
        display: none;
    }

    /*Remove space from the top of single children*/
    .tree-org li:only-child{ padding-top: 0;}

    /*Remove left connector from first child and 
    right connector from last child*/
    .tree-org li:first-child::before, .tree-org li:last-child::after{
        border: 0 none;
    }
    /*Adding back the vertical connector to the last nodes*/
    .tree-org li:last-child::before{
        border-right: 1px solid #ccc;
        border-radius: 0 5px 0 0;
        -webkit-border-radius: 0 5px 0 0;
        -moz-border-radius: 0 5px 0 0;
    }
    .tree-org li:first-child::after{
        border-radius: 5px 0 0 0;
        -webkit-border-radius: 5px 0 0 0;
        -moz-border-radius: 5px 0 0 0;
    }

    /*Time to add downward connectors from parents*/
    .tree-org ul ul::before{
        content: '';
        position: absolute; top: 0; left: 50%;
        border-left: 1px solid #ccc;
        width: 0; height: 20px;
    }

    .tree-org li div.node{
        cursor:pointer;
        border: 1px solid #ccc;
        padding: 5px 10px;
        text-decoration: none;
        color: #666;
        font-family: arial, verdana, tahoma;
        font-size: 14px;
        display: inline-block;
        border-radius: 5px;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;

        transition: all 0.5s;
        -webkit-transition: all 0.5s;
        -moz-transition: all 0.5s;
    }

    /*Time for some hover effects*/
    /*We will apply the hover effect the the lineage of the element also*/
    .tree-org li div.node:hover, .tree-org li div.node:hover+ul li div.node {
        background: #c8e4f8; color: #000; border: 1px solid #94a0b4;
    }
    /*Connector styles on hover*/
    .tree-org li div.node:hover+ul li::after, 
    .tree-org li div.node:hover+ul li::before, 
    .tree-org li div.node:hover+ul::before, 
    .tree-org li div.node:hover+ul ul::before{
        border-color:  #94a0b4;
    }
    .tree-org > ul > li::before,
    .tree-org > ul > li:last-child::before,
    .tree-org > ul > li::after{
        border: none;
    }
    .center{
        margin: auto 5px 0px auto;
        width: 100%;
        border-bottom-width: 3px;
        padding: 10px;
        text-align: center;
    }
    .text-warning{
        color: #FF7518 !important;
    }
    .text-danger{
        color: #a80026 !important;
    }
    #madd{
        border:2px solid #8AC007;
    }
    #medit{
        border:2px solid #c00;
    }
</style>
<link href="<?= Yii::app()->request->baseUrl ?>/assets/css/dropdown-check-list.css" rel="stylesheet" type="text/css"/>
<nav class="menu btn-group btn-group-justified">
    <a class="btn btn-sm btn-success" id="addm" onclick="showmenuadd();">เพิ่ม <i class="glyphicon glyphicon-plus"></i></a>
    <a class="btn btn-sm btn-warning" id="editm" onclick="showmenuedit();">แก้ไข <i class="glyphicon glyphicon-edit"></i></a>
</nav>
<div id="madd"  class="form-horizontal center" style="display: none">
    <div class="from-group" >
        <label>เพิ่มโครงสร้างองค์กรสำหรับปี </label>
        <input id="mayear" class="datepicker"  type="text" data-provide="datepicker" data-date-language="th-th">
        <a id="mbtnselyear" class="btn btn-sm btn-success">ตกลง <i class="glyphicon glyphicon-ok"></i></a>
    </div>
</div>
<div id="medit"  class="form-horizontal center" style="display: none">
    <div class="from-group" >
        <label>แก้ไขโครงสร้างองค์กรสำหรับปี: </label>
        <select id="meyear">
            <option>2557</option>
            <option>2558</option>
            <option>2559</option>
        </select>
        <a class="btn btn-sm btn-success">ตกลง <i class="glyphicon glyphicon-ok"></i></a><br/>
        <span class="text-warning" style="display: inline!important;">การแก้ไขจะส่งผลกระทบต่อการเรียกดูสรุปผล การยืนยันข้อมูล และการกำหนดเป้าหมายรายเดือน</span>
        <span class="text-danger" style="display:block" >*สามารถลบข้อมูลได้โดยการบันทึกค่าว่าง*</span>
    </div>
</div>

<div class="tree-org">
    <ul class="level-1-container">
        <li><div class="node dropdown-check-list">ฝ่ายบลา ๆ <br/>
                <span class="anchor glyphicon " data-id=""></span>
                <div class="items">
                    <label><input type="checkbox" />แผนกทรัพยากรบุคคล </label>
                    <label><input type="checkbox" />Orange</label>
                    <label><input type="checkbox" />Grapes </label>
                    <label><input type="checkbox" />Berry </label>
                    <label><input type="checkbox" />Mango </label>
                    <label><input type="checkbox" />Banana </label>
                    <label><input type="checkbox" />Tomato</label>
                </div>

            </div>
            <ul>
                <li><div class="node">lv2</div></li>
                <li><div class="node">lv2</div></li>
                <li><div class="node">lv2</div></li>
            </ul>
        </li>
    </ul>
</div>

<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/bootstrap-datepicker-thai.js" type="text/javascript"></script>
<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/locales/bootstrap-datepicker.th.js" type="text/javascript"></script>
<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/dropdown-check-list.js" type="text/javascript"></script>
<script type="text/javascript">
        $(function () {
            $('.datepicker').datepicker({
                format: "yyyy",
                viewMode: "years",
                minViewMode: "years",
                autoclose: true,
                clearBtn: true,
            });
        });
        var ua = false;
        function showmenuadd()
        {
            ua = ua != true;
            //clear old menu
            $("#medit").slideUp("fast");
            //add new menu
            $("#madd").slideToggle("fast");
            if (ua)
                $(window).scrollTop(0);

        }
        var ue = false;
        function showmenuedit()
        {
            ue = ue != true;
            //clear old menu
            $("#madd").slideUp("fast");
            //add new menu
            $("#medit").slideToggle("fast");
            if (ue)
                $(window).scrollTop();
        }

</script>
<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/orgchart.js" type="text/javascript"></script>