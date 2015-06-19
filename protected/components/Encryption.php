<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Encryption
 *
 * @author Ball
 */
class Encryption extends CApplicationComponent {
    
    /*
     * Encrypt password
     * 
     * @param string $password your password to encrpyt
     * @return string encrypted password
     */
    public function EncryptPassword ($password)
    {
        $pass = $password;
        $set_encrypt_word = array("InoxSolido", "AbNKei", "BALLBANK");
        $i=0;
        foreach ($set_encrypt_word AS $ew)
        {
            $set_encrypt_word[$i++] = md5($ew);
        }
        $i=1;
        foreach ($set_encrypt_word AS $ew) {
            $pass = md5($pass.$ew);
        }
        return $pass;
    }
}
