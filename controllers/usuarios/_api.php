<?php

require "../../vendor/autoload.php";

use models\Usuarios as usuarios;

$headers = getallheaders();
$request = json_decode(file_get_contents("php://input"));


switch ($_SERVER['REQUEST_METHOD']) {

    case 'GET':
        echo json_encode(usuarios::lista());
        break;
    
    default:
        
        break;
}



?>