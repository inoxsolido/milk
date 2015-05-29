<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of milkController
 *
 * @author s5602041620019
 */
class MilkController extends Controller {

    public function actionIndex() {
        if (!Yii::app()->user->isGuest) {
            $this->redirect(Yii::app()->createAbsoluteUrl('./milk/main'));
        } else {
            $url = Yii::app()->createAbsoluteUrl('./milk/faq');
            $this->redirect($url);
        }
    }

    public function actionFaq() {
        $this->render('faq');
    }

    public function actionLogin() {
        if (!Yii::app()->user->isGuest) {
            //logged in
            $this->redirect(Yii::app()->createAbsoluteUrl('./milk/main'));
        } else
            $this->render('login');
    }

    public function actionMain() {
        if (Yii::app()->user->isGuest) {
            $this->redirect(Yii::app()->createAbsoluteUrl('./milk/login'));
        } else {
            $this->redirect('faq');
            
        }
    }
    public function actionLogout(){
        Yii::app()->user->logout();
        $this->redirect('../milk/index');
    }
    
    public function actionUserManager(){
        if(!Yii::app()->user->isGuest){
            if(Yii::app()->user->isAdmin){
                $dep = TbDepartment::model()->findall();
                $fac = TbFaction::model()->findall();
                $position = TbPosition::model()->findall();
                $this->render('usermanager',array(
                    'dep'=>$dep,
                    'fac'=>$fac,
                    'pos'=>$position,
                ));
            }
            else{
               echo 'You have not permission to access this pages';
               echo '<br>';
               ?><a href="<?=Yii::app()->createAbsoluteUrl('./milk/main')?>">Back to main</a><?php
            }
        }else{
            $this->redirect('../milk/index');
        }
    }

}
