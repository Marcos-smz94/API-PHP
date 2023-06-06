<?php
include('conexao.php');

header("Access-Control-Allow-Origin: http://localhost:3000");
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

$postdata = json_decode(file_get_contents('php://input'), true);

if(isset($postdata) && !empty($postdata)){

    // Definindo as variáveis
    $userid = isset($postdata["userid"]) ? $postdata["userid"] : null;
    $filtro = $postdata["filter"];

    // Filtro de ação
    if($filtro == "filter"){

    // Comando de busca no mysql
    $sql = "SELECT * FROM mensagens WHERE user_id = '{$userid}'";

    // Iniciando conexão
    $query =  mysqli_query($conexao, $sql);

    // Checar se há respostas
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

    } elseif($filtro == "all"){

        // Comando de busca no mysql
        $sql = "SELECT * FROM mensagens";

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
}
    } elseif($filtro == "grafico"){
        $createarray = array();

        // Comando de busca no mysql
        // EU SEI QUE ISSO TA HORROROSO EU TENTEI FAZER DE OUTRO JEITO, MAS NÃO DEU :(
        $sql = "SELECT * FROM mensagens";
        $query =  mysqli_query($conexao, $sql);
        $row = mysqli_num_rows($query);
        
        $sql1 = "SELECT * FROM mensagens WHERE msgdestino = '1'";
        $query =  mysqli_query($conexao, $sql1);
        $row1 = mysqli_num_rows($query);

        $sql2 = "SELECT * FROM mensagens WHERE msgdestino = '2'";
        $query =  mysqli_query($conexao, $sql2);
        $row2 = mysqli_num_rows($query);

        $sql3 = "SELECT * FROM mensagens WHERE msgdestino = '3'";
        $query =  mysqli_query($conexao, $sql3);
        $row3 = mysqli_num_rows($query);

        $sql4 = "SELECT * FROM mensagens WHERE msgdestino = '4'";
        $query =  mysqli_query($conexao, $sql4);
        $row4 = mysqli_num_rows($query);

        $sql5 = "SELECT * FROM mensagens WHERE msgdestino = '5'";
        $query =  mysqli_query($conexao, $sql5);
        $row5 = mysqli_num_rows($query);

        $returnData = [
            'tudo' => $row,
            'faculdade' => $row1,
            'professor' => $row2,
            'direcao' => $row3,
            'servicosgerais' => $row4,
            'coordenacao' => $row5,
            'pfaculdade' => ($row1/$row)/100,
            'pprofessor' => ($row2/$row)/100,
            'pdirecao' => ($row3/$row)/100,
            'pservicosgerais' => ($row4/$row)/100,
            'pcoordenacao' => ($row5/$row)/100
        ];
        
        $json = json_encode($returnData, JSON_UNESCAPED_UNICODE);
    };
};

echo $json;

// Fechando a conexão
mysqli_close($conexao);