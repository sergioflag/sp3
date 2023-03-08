<?php

require "../../vendor/autoload.php";

use models\Auth as auth;

$headers = getallheaders();
$request = json_decode(file_get_contents("php://input"));


switch ($_SERVER['REQUEST_METHOD']) {

    case 'GET':
        if(isset($headers['token'])){
            echo json_encode(auth::menu($headers['token']));
        }else{
            echo json_encode(["error"=>true,"message"=>"No hay token en el encabezado"]);
        }
        break;

    case 'POST':

        echo json_encode(auth::login($request));
        break;

    case 'PUT':
        echo json_encode(auth::actualizar_contrasena($request));
        break;
    
    default:
        echo "This API works";
        break;
}



?>