<?php
include('conexao.php');

header("Access-Control-Allow-Origin: http://localhost:3000");
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

$postdata = json_decode(file_get_contents('php://input'), true);

if(isset($postdata) && !empty($postdata)){

            // Definindo as variáveis
            $filtro = $postdata["filter"];
            $idsetor = isset($postdata["idsetor"]) ? $postdata["idsetor"] : null;

            if($filtro == "all"){
                
            // Comando de busca no mysql
            $sql = "SELECT * FROM solucoes";

            // Iniciando conexão
            $query = mysqli_query($conexao, $sql);

            if(mysqli_num_rows($query) == 0){

            $json = http_response_code(201);

            } else {

            $createarray = array();

            // Pegando as informações e colocando em uma array em seguida em json
            while($row = mysqli_fetch_assoc($query)) {
                $createarray[] = $row;
                $json = json_encode($createarray, JSON_UNESCAPED_UNICODE);

            };
        };
    }elseif($filtro == "filtrar"){

        // Comando de busca no mysql
            $sql = "SELECT * FROM solucoes WHERE idsetor = '$idsetor'";

            // Iniciando conexão
            $query = mysqli_query($conexao, $sql);

            if(mysqli_num_rows($query) == 0){

            $json = http_response_code(201);

            } else {

            $createarray = array();

            // Pegando as informações e colocando em uma array em seguida em json
            while($row = mysqli_fetch_assoc($query)) {
                $createarray[] = $row;
                $json = json_encode($createarray, JSON_UNESCAPED_UNICODE);

            };
        };
    };
};

echo $json;

// Fechando a conexão
mysqli_close($conexao);

