<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of budController
 *
 * @author s5602041620019
 */
class BudController extends Controller {

    public function actionIndex() {
        if (!Yii::app()->user->isGuest) {
            $this->redirect(Yii::app()->createUrl('./Bud/main'));
        } else {
            $url = Yii::app()->createUrl('./Bud/faq');
            $this->redirect($url);
        }
    }

    public function actionFaq() {
        $this->render('faq');
    }

    public function actionLogin() {
        if (!Yii::app()->user->isGuest) {
            //logged in
            $this->redirect(Yii::app()->createAbsoluteUrl('./Bud/main'));
        } else
            $this->render('login');
    }

    public function actionMain() {
        if (Yii::app()->user->isGuest) {
            $this->redirect(Yii::app()->createAbsoluteUrl('./Bud/login'));
        } else {
            $this->redirect('faq');
        }
    }

    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect('../Bud/index');
    }

    public function actionUserManager() {
        if (!Yii::app()->user->isGuest) {
            if (Yii::app()->user->isAdmin) {
                $dep = TbDepartment::model()->findall();
                $div = TbDivision::model()->findall();
                $position = TbPosition::model()->findall();
                $this->render('usermanager', array(
                    'dep' => $dep,
                    'div' => $div,
                    'pos' => $position,
                ));
            } else {
                echo 'You have not permission to access this pages';
                echo '<br>';
                ?><a href="<?= Yii::app()->createAbsoluteUrl('./Bud/main') ?>">Back to main</a><?php
            }
        } else {
            $this->redirect('../Bud/index');
        }
    }
 
    public function actionDivManager() {
        if (yii::app()->user->isAdmin) {
            
            $model = TbDivision::model()->findAll();
            
            $this->render('divManager',array('par_model' => $model));
        } else {
            echo 'You have not permission to access this pages';
            echo '<br>';
            ?><a href="<?= Yii::app()->createAbsoluteUrl('./Bud/main') ?>">Back to main</a><?php
        }
    }
}
