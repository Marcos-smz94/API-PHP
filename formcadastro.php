<?php
include('conexao.php');

header("Access-Control-Allow-Origin: http://localhost:3000");
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Content-Type");

$postdata = json_decode(file_get_contents('php://input'), true);

if(isset($postdata) && !empty($postdata)){

    // Definindo as variáveis
    $nome = trim($postdata["nome"]);
    $email = trim($postdata["email"]);
    $senha = trim($postdata["senha"]);
    $tipousuario = trim($postdata["tipousuario"]);
    
    // Encriptando a senha
    $hash = password_hash($senha, PASSWORD_DEFAULT);

    // Comando de busca no mysql
    $sql = "INSERT INTO usuarios (nome, senha, email, nivel) VALUES (?, ?, ?, ?)";

    // Iniciando conexão
    $stmt = mysqli_stmt_init($conexao);
    if ( ! mysqli_stmt_prepare($stmt, $sql)){
        die(mysqli_error($conexao));
    };

    // Organizando a entrada
    mysqli_stmt_bind_param($stmt, "ssss", $nome, $hash, $email, $tipousuario);

    // Enviando para o banco de dados
    if( mysqli_stmt_execute($stmt)){
        http_response_code(200);
    }
    else{
        http_response_code(422); 
    }; 
};

// Fechando a conexão
mysqli_close($conexao);