<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="language" content="en">
        <meta name="viewport" content="width=device-width,height=device-height, initial-scale=1">

        <!-- jquery script -->
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/jquery-1.11.3.min.js"></script>
        <!-- bootstrap -->
        <link rel="stylesheet" href="<?= Yii::app()->request->baseUrl; ?>/assets/css/bootstrap-cosmo.css"/>
        <script src="<?= Yii::app()->request->baseUrl; ?>/assets/js/bootstrap.min.js"></script>
        <!--submenu -->
        <link rel="stylesheet" href="<?= Yii::app()->request->baseUrl ?>/assets/css/submenu.css">
        <script src="<?= Yii::app()->request->baseUrl; ?>/assets/js/submenu.js"></script>
        <script type="text/javascript">
            $(function () {
                $("div.loading").hide();
                /*var stickyNavTop;
                 if($('.navbar').length)
                 stickyNavTop = $('.navbar').offset().top;
                 
                 
                 var stickyMenuTop;
                 if($('.menu').length)
                 stickyMenuTop = $('.menu').offset().top;
                 
                 var stickyNav = function () {
                 if(!$('.navbar').length)return;
                 var scrollTop = $(window).scrollTop();
                 if (scrollTop > stickyNavTop) {
                 $('.navbar').addClass('sticky');
                 } else {
                 $('.navbar').removeClass('sticky');
                 }
                 };
                 var stickyMenu = function () {
                 if(!$('.menu').length)return;
                 var scrollTop = $(window).scrollTop();
                 if (scrollTop > stickyMenuTop-50) {
                 $('.menu').addClass('stickmenu');
                 } else {
                 $('.menu').removeClass('stickmenu');
                 }
                 };
                 stickyNav();
                 stickyMenu();
                 
                 $(window).scroll(function () {
                 if($('.navbar').length)
                 stickyNav();
                 if($('.menu').length)
                 stickyMenu();
                 });
                 */
            });
        </script>
        <style type="text/css">
            #menu > nav{
                margin-bottom: 0px;
            }
            div#content{
                position: relative;
                min-height: 100%;
                height: auto !important;
                height: 100%;
                margin-bottom: -30px;
            }
            .row{
                margin-right:0px;
            }
            div#footer{
                height:40px;
                width:100%;

            }
            div.loading{
                position: fixed;    
                top: 0px;     
                left: 0px;    
                background: #ccc;     
                width: 100%;     
                height: 100%;     
                opacity: .75;     
                filter: alpha(opacity=75);     
                -moz-opacity: .75;    
                z-index: 2000;    
                background: #fff url("<?= Yii::app()->request->baseUrl ?>/images/loading.gif" ) 50% 50% no-repeat;  
            }
            .sticky {
                position: fixed;
                width: 100%;
                left: 0;
                top: 0;
                z-index: 800;
                border-top: 0;
            }
            .menu, .stickmenu{
                position: fixed;
                width: 100%;
                left: 0;
                top: 52px;
                z-index: 790;
                border-top: 0;
            }
            #dummy{
                height: 83.5px;
            }
        </style>


        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    </head>

    <body>
        <div id="dummy"></div>
        <div id="menu" class="sticky">
            <nav class="navbar navbar-inverse top-navbar" role="navigation">
                <div class="container-fluid">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-data-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="<?= Yii::app()->createUrl('./') ?>"><?php echo Yii::app()->name ?></a>
                    </div>
                    <div class="collapse navbar-collapse" id="navbar-data-collapse">
                        <?php
                        $this->widget('zii.widgets.CMenu', array(
                            'items' => array(
                                ['label' => 'กรอกงบประมาณ', 'url' => 'MonthGoal', 'visible' => (Yii::app()->user->isDepartment || Yii::app()->user->isDivision)],
                                ['label' => 'ยืนยันคำขอ', 'url' => '#', 'visible' => (Yii::app()->user->isDivision || Yii::app()->user->isAdmin)],
                                ['label' => 'สรุปผล', 'url' => '#', 'visible' => !Yii::app()->user->isGuest],
                                ['label' => "จัดการบัญชี <i class='caret'></i>", 'url' => '#', 'visible' => Yii::app()->user->isAdmin,
                                    'linkOptions' => [
                                        'class' => 'dropdown-toggle',
                                        'data-toggle' => 'dropdown',
                                        'role' => 'button',
                                    ],
                                    'items' => [
                                        ['label' => 'จัดการรายละเอียดบัญชี', 'url' => 'AccountManager', 'class' => 'dropdown-toggle', 'role' => 'menu'],
                                        ['label' => 'กำหนดบัญชีที่ใช้แต่ละปี', 'url' => 'AccountYearAssign'],
                                    ]
                                ],
                                ['label' => "จัดการสังกัด <i class='caret'></i>", 'url' => '#', 'visible' => Yii::app()->user->isAdmin,
                                    'linkOptions' => [
                                        'class' => 'dropdown-toggle',
                                        'data-toggle' => 'dropdown',
                                        'role' => 'button',
                                    ],
                                    'items' => [
                                        ['label' => 'จัดการรายละเอียดสังกัด', 'url' => 'divManager', 'class' => 'dropdown-toggle', 'role' => 'menu'],
                                        ['label' => 'จัดการการกรอกของสังกัด', 'url' => 'fillingManager'],
                                    ]
                                ],
                                ['label' => 'จัดการผู้ใช้', 'url' => 'usermanager', 'visible' => Yii::app()->user->isAdmin],
                            ),
                            'activeCssClass' => 'active',
                            'htmlOptions' => ['class' => 'nav navbar-nav'],
                            'submenuHtmlOptions' => ['class' => 'dropdown-menu', 'role' => 'menu'],
                            'encodeLabel' => false
                        ));
                        ?>
                        <?php
                        $this->widget('zii.widgets.Cmenu', array(
                            'items' => array(
                                ['label' => 'คู่มือ', 'url' => './faq'],
                                ['label' => 'ลงชื่อเข้าใช้', 'url' => 'login', 'visible' => Yii::app()->user->isGuest],
                                ['label' => Yii::app()->user->name . "<i class='caret'></i>", 'url' => '#',
                                    'linkOptions' => [
                                        'class' => 'dropdown-toggle',
                                        'data-toggle' => 'dropdown',
                                        'role' => 'button',
                                    ],
                                    'items' => [
                                        ['label' => 'แก้ไขข้อมูลส่วนตัว', 'url' => '#',
                                            'linkOptions' => [
                                                'id' => 'chinfo',
                                            ]
                                        ],
                                        ['label' => 'ออกจากระบบ', 'url' => 'logout'],
                                    ],
                                    'visible' => !Yii::app()->user->isGuest
                                ]
                            ),
                            'activeCssClass' => 'active',
                            'htmlOptions' => ['class' => 'nav navbar-nav navbar-right'],
                            'submenuHtmlOptions' => ['class' => 'dropdown-menu', 'role' => 'menu'],
                            'encodeLabel' => false
                        ));
                        ?>
                    </div>
                </div>
            </nav>

        </div><!-- mainmenu -->
        <?php echo $content; ?>
        <div class="clear"></div>

        <div id="footer" style="background-color:#afd9ee; opacity: 50%;clear: both; z-index:-1;" class="col-lg-12">
            <div   style="text-align: center;">
                Copyright &copy; Mr.Ritthichai Skulthong and Mr.Thanakhan Pariput 
                <br>All Rights Reserved.<br/>

            </div><!-- footer -->
        </div>
        <div class="loading"></div>
        <div id='mchinfo' class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">เปลี่ยนแปลงข้อมูลส่วนตัว</h4>
                    </div>
                    <div class="modal-body">
                        <form id='chinfo' class='form-horizontal'>
                            <div class='form-group-sm'>
                                <label for='chname' class="control-label">ชื่อจริง</label>
                                <input type='text' class='form-control' id='chname' name='chname'/>
                                <span style='color: red;font-weight: bold' class='err'></span>
                            </div>
                            <div class='form-group-sm'>
                                <label for='chlname' class="control-label">นามสกุล</label>
                                <input type='text' class='form-control' id='chlname' name='chlname'/>
                                <span style='color: red;font-weight: bold' class='err'></span>
                            </div>
                            <div class='form-group-sm'>
                                <label for='chpwd1' class="control-label">รหัสผ่านใหม่</label>
                                <input type='password' class='form-control' id='chpwd1' name='chpwd1'/>
                                <span style='color: red;font-weight: bold' class='err'></span>
                            </div>
                            <div class='form-group-sm'>
                                <label for='chpwd2' class="control-label">รหัสผ่านอีกครั้ง</label>
                                <input type='password' class='form-control' id='chpwd2' name='chpwd2'/>
                                <span style='color: red;font-weight: bold' class='err'></span>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id='chinfosubmit' uid="<?= Yii::app()->user->getId(); ?>">บันทึกการเปลี่ยนแปลง</button>
                    </div>
                </div>

            </div>
        </div>
        <script></script>
        <script src="<?= Yii::app()->request->baseUrl ?>/assets/js/chinfo.js"></script>
    </body>
</html>
