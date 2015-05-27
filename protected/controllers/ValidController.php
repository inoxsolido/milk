<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ValidController
 *
 * @author s5602041620019
 */
class ValidController extends Controller {

    public function actionChkLogin() {
        if (isset($_POST['user']) && isset($_POST['pass'])) {
            $user = $_POST['user'];
            $pass = $_POST['pass'];
            $result = TbUser::model()->findAll("username = '$user' AND password = '$pass'");

//check result must found only one row !
            if (count($result) == 1) {
//add value to session 
                Yii::app()->session['username'] = $user;
                Yii::app()->session['password'] = $pass;
                Yii::app()->session['user_id'] = $result[0]->user_id;
                Yii::app()->session['position'] = $result[0]->position_id;
                echo 1;
            } else {
                echo 0;
            }
        }
    }

    public function actionLogin() {
        if (isset($_POST['user']) && isset($_POST['pass'])) {
            $model = new TbUser;
            $model->username = $_POST['user'];
            $model->password = $_POST['pass'];
            $errorCode = 1;
            $identity = new UserIdentity($model->username, $model->password);
            $identity->authenticate();
            $errorCode = $identity->errorCode;
            if ($identity->errorCode === UserIdentity::ERROR_NONE) {
                Yii::app()->user->login($identity);
                echo 1;
            } else if ($errorCode === UserIdentity::ERROR_USER_NOT_ACTIVE) {
                echo 2;
            } else {
                echo 3;
            }
        }
    }

    public function actionChkUsrDup() {
        if (isset($_POST['user'])) {
            $user = $_POST['user'];
            $result = TbUser::model()->findAll("username = '$user'");
            if (count($result)) {
                echo 'dup';
            } else {
                echo 'no';
            }
        }
    }

    public function actionChkPerDup() {
        if (isset($_POST['personid'])) {
            $perid = $_POST['personid'];
            $result = TbUser::model()->findAll("person_id = '$perid'");
            if (count($result)) {
                echo 'dup';
            } else {
                echo 'no';
            }
        }
    }

}
