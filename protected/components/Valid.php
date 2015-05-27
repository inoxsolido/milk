<?php

class Valid extends CApplicationComponent {

    static public function get_position_id() {
        $user = Yii::app()->session['username'];
        $pass = Yii::app()->session['password'];
        $result = TbUser::model()->find("username = '$user' AND password = '$pass'");
        if (count($result) == 1)
            return $result->position_id;
        else
            return NULL;
    }

    static public function get_user() {
        return Yii::app()->session['username'];
    }

    static public function is_not_login() {
        if (!isset(Yii::app()->session['username']))
            return true;
        else
            return false;
    }

    /* static public function is_level($level) {
      if ($level >= 1 && $level <= 3) {
      $user = Yii::app()->session['username'];
      $pass = Yii::app()->session['password'];
      $result = TbUser::model()->find("username = '$user' AND password = '$pass'");
      @$id = $result->position_id;
      if ($id == $level)
      return 1;
      else
      return 0;
      }
      } */

    public static function isLevel($level) {

        $model = TbUser::model()->find('username = '."'".Yii::app()->user->id."'");
        if($model == NULL){
            return false;
        }
        return intval($model->position_id) == $level;
    }

    static public function is_login() {
        if (isset(Yii::app()->session['username']))
            return true;
        else
            return false;
    }

}
