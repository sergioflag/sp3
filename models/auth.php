<?php

namespace models;

use configuration\db as db;
use configuration\algorithms as algo;
use configuration\authToken as token;
use configuration\Mailer as mailer;


class Auth{

    public static function login($data){
        $salida['error'] = true;

        
        if(!empty($data)){

            #Buscamos el usuario en base a su correo y extremos el menu al que tiene acceso
            $query = "SELECT 
                personas.id_persona id_persona, personas.nombres nombres, personas.a_paterno a_paterno, personas.a_materno a_materno,
                usuarios.contrasena contrasena,
                perfiles.perfil perfil,
                menu.recurso recurso
            FROM usuarios
            INNER JOIN personas ON usuarios.id_persona = personas.id_persona
            INNER JOIN perfiles ON usuarios.id_perfil = perfiles.id_perfil
            INNER JOIN perfil_menu ON perfiles.id_perfil = perfil_menu.id_perfil
            INNER JOIN menu ON perfil_menu.id_menu = menu.id_menu
            WHERE usuarios.correo = '$data->correo'";

            $usuario = db::query($query);

            #Evaluamos los resultados en la variable $usuario
            if(isset($usuario[0]->contrasena)){
                #Si el usuario existe, validamos las contraseñas

                if(algo::verify($data->contrasena,$usuario[0]->contrasena)){
                    #Si la contraseña es correcta, se lee el arreglo
                    
                    foreach($usuario as $key=>$value){
                        $session['usuario']['nombres'] = $value->nombres;
                        $session['usuario']['a_paterno'] = $value->a_paterno;
                        $session['usuario']['a_materno'] = $value->a_materno;
                        $session['usuario']['perfil'] = $value->perfil;
                        $session['menu'][] = $value->recurso;
                    }

                    #Se define la salida al API
                    $salida['error'] = false;
                    $salida['token'] = token::SignIn($session);
                }else{
                    #Si la contraseña no es correcta
                    $salida['mensaje'] = "La contraseá es incorrecta";
                }


            }else{
            $salida['mensaje'] = "El usuario no existe";
            }

        }

        return $salida;

    }

    public static function menu($_token){
        $salida['error'] = true;

        if(token::Check($_token)){
            $salida['error'] = false;
            $salida['data'] = token::GetData($_token);
        }else {
            $salida['mensaje'] = "El token ha expirado";
        }
        return $salida;
    }

    public static function actualizar_contrasena($data){
        $salida['error'] = true;

        if(!empty($data)){
            #Buscamos si el usuario existe o no
            $query = "SELECT 
                personas.id_persona id_persona, personas.nombres nombres, personas.a_paterno a_paterno, personas.a_materno a_materno,
                usuarios.contrasena contrasena
            FROM usuarios
            INNER JOIN personas ON usuarios.id_persona = personas.id_persona
            WHERE usuarios.correo = '$data->correo'";

            $usuario = db::query($query);

            #Se evalúa el contenido de la consulta
            if(isset($usuario[0]->contrasena)){
                #Si el usuario existe, se evalua la contraseña
                if(algo::verify($data->contrasena,$usuario[0]->contrasena)){
                    #Si la contraseña es correcta, validamos la vieja contraseña y la nueva

                    if($data->contrasena === $data->nueva_contrasena){
                        #Si las contraseñas coinciden
                        
                        $data->nueva_contrasena = algo::hash($data->nueva_contrasena);

                        $query = "UPDATE usuarios
                        SET contrasena = '$data->nueva_contrasena'
                        WHERE correo = '$data->correo'";

                        if(db::query($query)){
                            $salida['error'] = false;
                            $salida['mensaje'] = "La contraseña se actualizó correctamente";
                            //$salida['mensaje'] = mailer::enviarCorreo($usuario[0]);

                            

                        }else{
                            $salida['mensaje'] = "Hubo un error con la actualización de la contraseña";
                        }

                    }else{
                        #Si las contraseñas no coinciden
                        $salida['mensaje'] = "Las contraseñas no coinciden";
                    }

                }else{
                    #Si la contraseña no es correcta
                    $salida['mensaje'] = "La contraseña no es correcta";
                }
            }else{
                #Si el usuario no existe
                $salida['mensaje'] = "El usuario no existe";
            }

        }

        return $salida;

    }

}


?>