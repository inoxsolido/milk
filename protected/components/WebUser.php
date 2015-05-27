<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WebUser
 *
 * @author s5602041620019
 */
define('Dep',1);
define('Div',2);
define('Admin',3);
class WebUser extends CWebUser {

    private $_model;
    public function isLevel($level) {
        $user = $this->loadUser(Yii::app()->user->id);
        return intval($user->position_id) == $level;
    }
    /*
     * isAdmin is function check user's level from Dep to Admin
     * isDivision is function check user's level from Dep to Division
     * isDepartment is function check user's level Department
     * isDivisionOnly just only true Devision only
     * isDepartmentOnly just only true Department 
     */
    public function isAdmin() {
        return Valid::isLevel(3);
    }
    public function isDivision() {
        return Valid::isLevel(2);
    }
    public function isDepartment(){
        return Valid::isLevel(1);
    }
    protected function loadUser($id = null) {
        if ($this->_model === null) {
            if ($id !== null):
                $this->_model = TbUser::model()->findByPk($id);
            else:
                $this->_model->user_type_id = 0;
            endif;
        }
        return $this->_model;
    }

}
