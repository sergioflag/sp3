<?php

require "../../vendor/autoload.php";

use models\Usuarios as usuarios;

$headers = getallheaders();
$request = json_decode(file_get_contents("php://input"));


switch ($_SERVER['REQUEST_METHOD']) {

    case 'GET':
        if(isset($_GET['id'])){
            echo json_encode(usuarios::unUsuario($_GET['id']));
        }else{
            echo json_encode(usuarios::todos());
        }
        break;

    case 'POST':
        echo json_encode(usuarios::crear($request));
        break;

    case 'PUT':
        if(isset($_GET['id'])){
            echo json_encode(usuarios::actualizar($request,$_GET['id']));
        }else{
            echo json_encode(["error"=>true,"mensaje"=>"El id del usuario no está declarado"]);
        }
        break;

    case 'DELETE':
        if(isset($_GET['id'])){
            echo json_encode(usuarios::eliminar($_GET['id']));
        }else{
            echo json_encode(["error"=>true,"mensaje"=>"El id del usuario no está declarado"]);
        }
        break;
    
    default:
        echo "This API works";
        break;
}



?>