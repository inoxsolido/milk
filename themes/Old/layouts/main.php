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

            <nav class="navbar navbar-inverse">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="#">ระบบส่งคำขอตั้งงบประมาณ</a>
                    </div>

                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
                        <?php if (!valid::is_not_login()): ?>
                            <ul class="nav navbar-nav">
                                <?php if (!valid::is_level(3)): ?>
                                    <li class='dropdown'><a href="#"  role='button' aria-expanded='false'>กรอกงบประมาณ</a></li>
                                <?php endif; ?>
                                <?php if (!valid::is_level(1)): ?>
                                    <li class='dropdown'><a href="#"  role='button'>ยืนยัน</a></li>
                                <?php endif; ?>
                                <li class='dropdown'><a href="#"  role='button'>ภาพรวม</a></li>
                                <?php if (valid::is_level(3)): ?>
                                    <li class='dropdown'><a href="#"  role='button'>จัดการบัญชี</a></li>
                                    <li class='dropdown'><a href="<?= Yii::app()->createAbsoluteUrl('./milk/usermanager') ?>"  role='button'>จัดการผู้ใช้</a></li>
                                <?php endif; ?>
                            </ul>
                        <?php endif; ?>


                        <ul class="nav navbar-nav navbar-right">
                            <li>
                                <a href="<?= Yii::app()->createAbsoluteUrl('./milk/faq') ?>"  role='button'>คู่มือ</a>
                            </li>
                            <?php if (!valid::is_not_login()): ?>
                                <li>
                                    <a href="#" class='dropdown-toggle' data-toggle='dropdown' role='button' aria-expanded='false'><?= valid::get_user(); ?> <span class="caret"></span> </a>
                                    <ul class="dropdown-menu" role='menu'>
                                        <li><a href="#">แก้ไขข้อมูลส่วนตัว</a></li>
                                        <li><a href="<?= Yii::app()->createAbsoluteUrl('./milk/logout')?>" >ออกจากระบบ</a></li>
                                    </ul>
                                </li>
                            <?php else: ?>
                                <li>
                                    <a href="<?= Yii::app()->createAbsoluteUrl('./milk/login') ?>" role='button' aria-expanded='false'>ลงชื่อเข้าใช้</a>
                                </li>
                            <?php endif; ?>

                        </ul>
                    </div>
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
