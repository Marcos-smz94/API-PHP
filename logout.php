<?php

header("Access-Control-Allow-Origin: http://localhost:3000");
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Content-Type");

$postdata = json_decode(file_get_contents('php://input'), true);

if(isset($postdata) && !empty($postdata)){

    session_start();
    if(session_destroy()){
        http_response_code(200);
    }
    else{
         http_response_code(422); 
    };
};