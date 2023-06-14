<?php
include('conexao.php');

header("Access-Control-Allow-Origin: http://localhost:3000");
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

$postdata = json_decode(file_get_contents('php://input'), true);

if(isset($postdata) && !empty($postdata)){

        // Comando de busca no mysql
        $sql = "SELECT * FROM respostas";

        // Query de busca no banco de dados
        $query =  mysqli_query($conexao, $sql);

        // Checar se há respostas
        if(mysqli_num_rows($query) == 0){
            http_response_code(201);
        } else {

        http_response_code(200);
        
        $createarray = array();

        // Pegando as informações e colocando em uma array em seguida em json
        while($row = mysqli_fetch_assoc($query)) {
            $createarray[] = $row;
            $json = json_encode($createarray, JSON_UNESCAPED_UNICODE);
        };
    };
};

echo $json;

// Fechando a conexão
mysqli_close($conexao);