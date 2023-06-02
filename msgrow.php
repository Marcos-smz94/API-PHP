<?php
include('conexao.php');

header("Access-Control-Allow-Origin: http://localhost:3000");
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

$postdata = json_decode(file_get_contents('php://input'), true);

if(isset($postdata) && !empty($postdata)){
    
    $userid = $postdata["userid"];
    $filtro = $postdata["filter"];

    if($filtro == "filter"){
    $sql = "SELECT * FROM mensagens WHERE user_id = '{$userid}'";

    $query =  mysqli_query($conexao, $sql);

    $createarray = array();

    while($row = mysqli_fetch_assoc($query)) {
        $createarray[] = $row;
        $json = json_encode($createarray, JSON_UNESCAPED_UNICODE);
    };

    } elseif($filtro == "all"){
        $sql = "SELECT * FROM mensagens";
        $result = mysqli_query($conexao, $sql);

        $createarray = array();
        while($row = mysqli_fetch_assoc($result)) {
            $createarray[] = $row;
            $json = json_encode($createarray, JSON_UNESCAPED_UNICODE);
        };
    };
};

echo $json;

mysqli_close($conexao);

