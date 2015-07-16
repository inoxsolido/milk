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
                var stickyNavTop = $('.navbar').offset().top;
                var stickyMenuTop = $('.menu').offset().top;
                var stickyNav = function () {
                    var scrollTop = $(window).scrollTop();

                    if (scrollTop > stickyNavTop) {
                        $('.navbar').addClass('sticky');
                    } else {
                        $('.navbar').removeClass('sticky');
                    }
                };
                var stickyMenu = function () {
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
                    stickyNav();
                    stickyMenu();
                });
            });
        </script>
        <style type="text/css">
            #menu{
                margin-bottom: -21px;
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
                width: 150px;
                height: 25px;
                position: absolute;
                left: 50%;
                top: 50%; 
                margin-left: -75px;
                margin-top: -12.5px;
                background-color:white;
                text-align: center;
                border: 2px solid black;
                box-shadow: 2px 2px 2px 10px black;
                z-index: 5000;
            }
            .sticky {
                position: fixed;
                width: 100%;
                left: 0;
                top: 0;
                z-index: 100;
                border-top: 0;
            }
            .stickmenu{
                position: fixed;
                width: 100%;
                left: 0;
                top: 50px;
                z-index: 100;
                border-top: 0;
            }
        </style>


        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    </head>

    <body>
        <div id="menu">
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
                                ['label' => 'กรอกงบประมาณ', 'url' => '#', 'visible' => Yii::app()->user->isDepartment],
                                ['label' => 'ยืนยันคำขอ', 'url' => '#', 'visible' => Yii::app()->user->isDivision || Yii::app()->user->isAdmin],
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
                                        ['label' => 'แก้ไขข้อมูลส่วนตัว', 'url' => '#', 'class' => 'dropdown-toggle', 'role' => 'menu'],
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
        <div class="loading">loading ...</div>
    </body>
</html>
