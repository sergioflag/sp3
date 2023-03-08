<?php

namespace models;

use configuration\db as db;
use configuration\algorithms as algo;


class Usuarios{

    public static function todos(){
        #Generamos un query involucrando aquellas tablas relacionadas al usuario y se almacena en variable $query
        $query = "SELECT 
            personas.id_persona id_persona, personas.nombres nombres, personas.a_paterno a_paterno, personas.a_materno a_materno, personas.f_nacimiento f_nacimiento, personas.telefono telefono,
            usuarios.id_usuario id_usuario, usuarios.correo correo, usuarios.estado estado,
            perfiles.perfil perfil
        FROM usuarios
        INNER JOIN personas ON usuarios.id_persona = personas.id_persona
        INNER JOIN perfiles ON usuarios.id_perfil = perfiles.id_perfil";

        #En la variable consulta se almacenan los resultados al ejecutar el query generado, con la función query de la clase DB
        $consulta = db::query($query);

        #Se valida su la variable $consulta tiene registros o no
        if(empty($consulta)){
            #Si la variable no tiene registros, se devuelve un error
            $salida['error'] = true;
            $salida['mensaje'] = "No hay usuarios registrados";
        }else{
            #Si la variable tiene registros, se devuelven los registros
            $salida['error'] = false;
            $salida['usuarios'] = $consulta;
        }
        #Se devuelve una respuesta al API
        return $salida;

    }

    public static function unUsuario($_id){
        #Generamos un query involucrando aquellas tablas relacionadas al usuario y se almacena en variable $query, en base al ID de usuario
        $query = "SELECT 
            personas.id_persona id_persona, personas.nombres nombres, personas.a_paterno a_paterno, personas.a_materno a_materno, personas.f_nacimiento f_nacimiento, personas.telefono telefono,
            usuarios.id_usuario id_usuario, usuarios.correo correo, usuarios.estado estado,
            perfiles.perfil perfil
        FROM usuarios
        INNER JOIN personas ON usuarios.id_persona = personas.id_persona
        INNER JOIN perfiles ON usuarios.id_perfil = perfiles.id_perfil
        WHERE usuarios.id_usuario = '$_id'";

        #En la variable consulta se almacenan los resultados al ejecutar el query generado, con la función query de la clase DB
        $consulta = db::query($query);

        #Se valida su la variable $consulta en LA PRIMERA POSICIÓN tiene registros o no
        if(empty($consulta[0])){
            #Si el usuario no existe, se devuelve un error
            $salida['error'] = true;
            $salida['mensaje'] = "El usuario no existe";
        }else{
            #Si el usuario existe, se devuelve la data del usuario
            $salida['error'] = false;
            $salida['usuarios'] = $consulta[0];
        }
        #Se devuelve a respuesta al API
        return $salida;
    }

    public static function crear($data){
        $salida['error'] = true;
        if(!empty($data)){
            #Se crea la consulta de búsqueda del usuario en base a su correo y contraseña
            $query = "SELECT 1 existe
            FROM usuarios
            WHERE correo = 'sergio@mail.com'";
            #El resultado del METODO QUERY de la CLASE DB se almacena en la variable consulta
            $consulta = db::query($query);

            if(!isset($consulta[0]->existe)){
                #Si el primero registro de $consulta tiene el atributo "EXISTE"

                #Se crea un hash de la contraseña
                $data->contrasena = algo::hash($data->contrasena);

                #Se crea un arreglo $inserts y se almacena el query de personas
                $inserts[0] = "INSERT INTO personas(nombres,a_paterno,a_materno,f_nacimiento,telefono)
                VALUES('$data->nombres','$data->a_paterno','$data->a_materno','$data->f_nacimiento','$data->telefono')";

                #En eñ arreglo $query se almacena el query de usuarios
                $inserts[1] = "INSERT INTO usuarios(correo,contrasena,id_persona,id_perfil)
                VALUES('$data->correo','$data->contrasena',(SELECT MAX(id_persona) FROM personas),'$data->id_perfil')";

                #Se ejecutan los inserts con procedimientos almacenados
                if(db::stored_procedure($inserts)){
                    #Si el registro es exitoso, extraemos el ultimo usuarioregistrado
                    $query = "SELECT MAX(id_usuario) id_usuario FROM usuarios";
                    $id_usuario = db::query($query);
                    $id_usuario = $id_usuario[0]->id_usuario;

                    #Se asignan los valores de salida del API
                    $salida['error'] = false;
                    $salida['mensaje'] = 'El usuario se registró con éxito';
                    $salida['id_usuario'] = $id_usuario;

                }else{
                    $salida['mensaje'] = "Hubo un error con el registro de usuario";
                }

            }else{
                #Si el primero registro de $consulta NO tiene el atributo "EXISTE"
                $salida['mensaje'] = "El usuario ya existe";
            }
        }

        return $salida;
    }

    public static function actualizar($data,$_id){
        $salida['error'] = true;

        if(!empty($data)){
            #Buscamos si el usuario existe o no en base a su ID
            $query = "SELECT 1 existe FROM usuarios WHERE id_persona = '$_id'";
            $consulta = db::query($query);

            #Se evalua el contenido del ressultado consutado
            if(isset($consulta[0]->existe)){

                #Si el usuario existe
                #Se crea el UPDATE de la tabla PERSONAS
                $updates[0] = "UPDATE personas
                SET nombres = '$data->nombres', a_paterno = '$data->a_paterno', a_materno = '$data->a_materno', f_nacimiento = '$data->f_nacimiento', telefono = '$data->telefono'
                WHERE id_persona = '$_id'";

                #Se crea el UPDATE de la tabla USUARIOS
                $updates[1] = "UPDATE usuarios
                SET correo = '$data->correo', id_perfil = '$data->id_perfil'
                WHERE id_persona = '$_id'";

                if(db::stored_procedure($updates)){
                    $salida['error'] = false;
                    $salida['mensaje'] = "El usuario se actualizó con éxito";
                }else{
                    $salida['mensaje'] = "Hubo un error con el proceso de actualización del usuario";
                }

            }else{
                $salida['mensaje'] = "El usuario no existe";
            }
        }

        return $salida;
    }

    public static function eliminar($_id){
        $salida['error'] = true;
        #Buscamos el usuario en base a su id_usuario
        $query = "SELECT 1 existe FROM usuarios WHERE id_usuario = '$_id'";
        $consulta = db::query($query);

        #Se evalúa el contenido de la búsqueda
        if(isset($consulta[0]->existe)){
            #Si el usuario existe, se crea el query de eliminado
            $deletes[0] = "UPDATE personas SET estado = 0";
            $deletes[1] = "UPDATE usuarios SET estado = 0";

            if(db::stored_procedure($deletes)){
                $salida['error'] = false;
                $salida['mensaje'] = "El usuario se eliminó con éxito";
            }else{
                $salida['mensaje'] = "Hubo un error con la eliminación del registro ";
            }

        }else{
            $salida['mensaje'] = "El usuario no existe";
        }

        return $salida;
    }

}


?>
