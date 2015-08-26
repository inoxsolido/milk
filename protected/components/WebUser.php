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
    
    
    public function getIsAdmin(){
        return $this->getState("isAdmin")==3;
    }
    public function getIsDivision(){
        return $this->getState("isDiv")==2;
    }
    public function getIsDepartment(){
        return $this->getState("isDep")==1;
    }
    public function getUserId(){
        return $this->getState("user_id");
    }
    public function getUserDiv(){
        return $this->getState("user_division");
    }
    public function getUserPosition(){
        return $this->getState("user_position");
    }
    

}
