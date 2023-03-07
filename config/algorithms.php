<?php

namespace configuration;

//require "../../vendor/autoload.php";


class algorithms{
    
    //GENARADOR DE IDS PARA CAMPOS CHAR(32)
    public static function idGenerator(){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 18; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return strval(date("YmdHis", mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y")))) . $randomString;
    }

    //ENCRIPTAR LAS CONTRASEÑAS
    public static function hash($password){

        return password_hash($password, PASSWORD_DEFAULT, ['cost' => 8]);
    }

    //VERIFICA LAS CONTRASEÑAS ENCRIPTADAS
    public static function verify($password, $hash){

        return password_verify($password, $hash);
    }

    //GENARADOR DE FOLIOS PARA CREDITOS CHAR(21)

    public static function folioGenerator(){
        $characters = '0123456789QWERTYUIOPASDFGHJKLZXCVBNM';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 5; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return strval(date("Ymd-His")) . "-" . $randomString;
    }
}

?>