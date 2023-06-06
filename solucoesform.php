<?php
include('conexao.php');

header("Access-Control-Allow-Origin: http://localhost:3000");
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Content-Type");

$postdata = json_decode(file_get_contents('php://input'), true);

if(isset($postdata) && !empty($postdata)){

    // Definindo as variáveis
    $idsetor = $postdata['idsetor'];
    $usuario = $postdata['usuario'];
    $solucao = $postdata["solucao"];

    // Comando de busca no mysql
    $sql = "INSERT INTO solucoes (idsetor, usuario, solucoes, `data`) VALUES (?, ?, ?, Now())";

    // Iniciando conexão
    $stmt = mysqli_stmt_init($conexao);
    if ( ! mysqli_stmt_prepare($stmt, $sql)){
        die(mysqli_error($conexao));
    };

    // Organizando a entrada
    mysqli_stmt_bind_param($stmt, "iss", $idsetor, $usuario, $solucao);

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