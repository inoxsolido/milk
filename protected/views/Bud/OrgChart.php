<link href="<?= Yii::app()->request->baseUrl ?>/assets/css/dropdown-check-list.css" rel="stylesheet" type="text/css"/>
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
        overflow-x:visible;
        min-height:400px;
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
    .table td, .table th{
        vertical-align: middle !important;
    }
</style>
<nav id="saveopt" class="menu" style="display:none">
    <button id="btnback" class="btn btn-info" style="float:left"><i class="glyphicon glyphicon-backward"></i> ย้อนกลับ </button>
    <button id="btnsave" class="btn btn-success" style="float:right"><i class="glyphicon glyphicon-book" ></i> บันทึก </button>
</nav>
<div class="container">
    <table id="yearlist" class="table table-bordered">
        <caption>ส่วนจัดการโครงสร้างที่ใช้ในแต่ละปีงบประมาณ</caption>
        <thead>
        <th>ปีงบประมาณ</th>
        <th>สถานะงบประมาณ</th>
        <th width="200">จัดการ</th>
        </thead>
        <tbody id="yearlistbody">
            <!--<tr><td>Hello</td><td><button class="btn btn-success glyphicon glyphicon-plus">เพิ่ม</button></td></tr>-->
        </tbody>
    </table>
</div>
<div class="tree-org">
    <ul class="level-1-container">
        <!--
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
        -->
    </ul>
</div>
<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/getQueryParameters.js" type="text/javascript"></script>
<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/dropdown-check-list.js" type="text/javascript"></script>
<script src="<?= Yii::app()->request->baseUrl ?>/assets/js/orgchart.js" type="text/javascript"></script>