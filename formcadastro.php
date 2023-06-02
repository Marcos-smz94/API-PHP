<?php
include('conexao.php');

header("Access-Control-Allow-Origin: http://localhost:3000");
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header("Access-Control-Allow-Headers: Content-Type");

$postdata = file_get_contents("php://input");

if(isset($postdata) && !empty($postdata)){
    $request = json_decode($postdata);
     
    $nome = $request->nome;
    $email = $request->email;
    $senha = SHA1($request->senha);
    $tipousuario = $request->tipousuario;

    $sql = "INSERT INTO usuarios (nome, senha, email, nivel) VALUES (?, ?, ?, ?)";

    $stmt = mysqli_stmt_init($conexao);

    if ( ! mysqli_stmt_prepare($stmt, $sql)){
        die(mysqli_error($conexao));
    };

    mysqli_stmt_bind_param($stmt, "ssss", $nome, $senha, $email, $tipousuario);

    if( mysqli_stmt_execute($stmt)){
        http_response_code(200);
    }
    else{
        http_response_code(422); 
    };
         
};

mysqli_close($conexao);