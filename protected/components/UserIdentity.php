<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    const ERROR_USER_NOT_ACTIVE = 3;
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */

        private $user_id;
        public function authenticate()
        {
            $user = TbUser::model()->find('username=? AND password=?', array($this->username,$this->password));
            if($user==null){//fail
                $this->errorCode=self::ERROR_USERNAME_INVALID;
            }else if($user->enable == 0){//Deactive
                $this->errorCode=self::ERROR_USER_NOT_ACTIVE;
            }
            else{//Ok
                $this->user_id=$user->user_id;
                $this->username=$user->username;
                //set state here
                $pos = $user->position_id;
                $this->setState('isAdmin', $pos==3?1:0);
                $this->setState('isDiv', $pos==2?1:0);
                $this->setState('isDep', $pos==1?1:0);
                
                //no error
                $this->errorCode=self::ERROR_NONE;
            }
            return !$this->errorCode;
        }
}