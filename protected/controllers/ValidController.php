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

    public function actionCheckEncrypt() {
        echo Yii::app()->Encryption->EncryptPassword("1234");
    }

    public function actionLogin() {
        if (isset($_POST['user']) && isset($_POST['pass'])) {
            $model = new TbUser;
            $model->username = $_POST['user'];
            $pass = $_POST['pass'];
            $encryptpass = Yii::app()->Encryption->EncryptPassword($pass);
            $model->password = $encryptpass;
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

    public function actionChkDivNameDup() {
        if (isset($_POST['divname'])) {
            $divname = $_POST['divname'];
            $result = TbDivision::model()->findAll("division_name = '$divname'");
            if (count($result)) {
                echo 'dup';
            }
        }
    }

    public function actionChkDivErpDup() {
        if (isset($_POST['erpid'])) {
            $erp = $_POST['erpid'];
            $result = TbDivision::model()->findAll("erp_id = '$erp'");
            if (count($result)) {
                echo 'dup';
            }
        }
    }

    public function actionCheckAccNameDup() {
        if (isset($_POST['name'])) {
            $name = $_POST['name'];
            echo count(TbAccount::model()->findAll("acc_name LIKE '$name'")) ? "dup" : "ok";
        }
    }

    public function actionCheckAccNameDupEdit() {
        if (isset($_POST['name']) && isset($_POST['id'])) {
            $name = $_POST['name'];
            $id = $_POST['id'];

            echo count(TbAccount::model()->findAll("acc_id != $id AND acc_name LIKE '$name'")) ? "dup" : "ok";
        }
    }

    public function actionCheckAccErpDup() {
        if (isset($_POST['erp'])) {
            $erp = $_POST['erp'];
            echo count(TbAccount::model()->findAll("acc_erp LIKE '$erp'")) ? "dup" : "ok";
        }
    }

    public function actionCheckAccErpDupEdit() {
        if (isset($_POST['erp']) && isset($_POST['id'])) {
            $erp = $_POST['erp'];
            $id = $_POST['id'];

            echo count(TbAccount::model()->findAll("acc_id != $id AND acc_erp LIKE '$name'")) ? "dup" : "ok";
        }
    }

}
