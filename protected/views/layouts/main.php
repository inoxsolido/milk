<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="language" content="en">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- jquery script -->
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/jquery-1.11.3.min.js"></script>
        <!-- bootstrap -->
        <link rel="stylesheet" href="<?= Yii::app()->request->baseUrl; ?>/assets/css/bootstrap-cosmo.css"/>
        <script src="<?= Yii::app()->request->baseUrl; ?>/assets/js/bootstrap.min.js"></script>
        <!--submenu -->
        <link rel="stylesheet" href="<?= Yii::app()->request->baseUrl ?>/assets/css/submenu.css">
        <script src="<?= Yii::app()->request->baseUrl; ?>/assets/js/submenu.js"></script>



        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    </head>

    <body>
        <div id="menu">
            <nav class="navbar navbar-inverse top-navbar" role="navigation">
                <div class="container-fluid">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="<?=Yii::app()->createUrl('./')?>"><?php echo Yii::app()->name?></a>
                    </div>
                    <div class="collapse navbar-collapse">
                        <?php
                        $this->widget('zii.widgets.CMenu', array(
                            'items' => array(
                                ['label' => 'กรอกงบประมาณ', 'url' => '#','visible'=>Yii::app()->user->isDepartment()],
                                ['label' => 'ยืนยันคำขอ', 'url' => '#', 'visible'=>Yii::app()->user->isDivision()||Yii::app()->user->isAdmin()],
                                ['label' => 'สรุปผล', 'url' => '#','visible'=>!Yii::app()->user->isGuest],
                                ['label' => 'จัดการบัญชี', 'url' => '#','visible'=>Yii::app()->user->isAdmin()],
                                ['label' => 'จัดการผู้ใช้', 'url' => 'usermanager','visible'=>Yii::app()->user->isAdmin()],
                            ),
                            'activeCssClass' => 'active',
                            'htmlOptions' => ['class' => 'nav navbar-nav']
                        ));
                        ?>
                        <?php
                        $this->widget('zii.widgets.Cmenu', array(
                            'items' => array(
                                ['label' => 'คู่มือ', 'url' => './faq'],
                                ['label' => 'ลงชื่อเข้าใช้', 'url' => 'login','visible'=>Yii::app()->user->isGuest],
                                ['label' => Yii::app()->user->name."<i class='caret'></i>", 'url' => '#',
                                    'linkOptions' => [
                                        'class' => 'dropdown-toggle',
                                        'data-toggle' => 'dropdown',
                                        'role' => 'button',
                                    ],
                                    'items' => [
                                        ['label' => 'แก้ไขข้อมูลส่วนตัว', 'url' => '#', 'class' => 'dropdown-toggle', 'role' => 'menu'],
                                        ['label' => 'ออกจากระบบ', 'url' => 'logout'],
                                    ],
                                    'visible'=>!Yii::app()->user->isGuest
                                ]
                            ),
                            'activeCssClass' => 'active',
                            'htmlOptions' => ['class' => 'nav navbar-nav navbar-right'],
                            'submenuHtmlOptions' => ['class' => 'dropdown-menu', 'role' => 'menu'],
                            'encodeLabel' => false
                        ));
                        ?>
                    </div>
            </nav>

        </div><!-- mainmenu -->
        <?php /* if(isset($this->breadcrumbs)):?>
          <?php $this->widget('zii.widgets.CBreadcrumbs', array(
          'links'=>$this->breadcrumbs,
          )); ?><!-- breadcrumbs -->
          <?php endif */ ?>

        <?php echo $content; ?>

        <div class="clear"></div>

        <div id="footer">
            Copyright &copy; <?php echo date('Y'); ?> by My Company.<br/>
            All Rights Reserved.<br/>
        </div><!-- footer -->

    </body>
</html>
