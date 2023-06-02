<?php
include('conexao.php');

header("Access-Control-Allow-Origin: http://localhost:3000");
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header("Access-Control-Allow-Headers: Content-Type");

$postdata = json_decode(file_get_contents('php://input'), true);

if(isset($postdata) && !empty($postdata)){
     
    $titulo = $postdata["titulo"];
    $msg = $postdata["msg"];
    $destino = $postdata["destino"];
    $userid = $postdata["userid"];
    $status = 0;

    $sql = "INSERT INTO mensagens (msgtitulo, msgtxt, msgdestino, msgstatus, user_id, msgdata) VALUES (?, ?, ?, ?, ?, Now())";

    $stmt = mysqli_stmt_init($conexao);

    if ( ! mysqli_stmt_prepare($stmt, $sql)){
        die(mysqli_error($conexao));
    };

    mysqli_stmt_bind_param($stmt, "ssiii", $titulo, $msg, $destino, $status, $userid);

    if( mysqli_stmt_execute($stmt)){
        http_response_code(200);
    }
    else{
         http_response_code(422); 
    };

    
};
         