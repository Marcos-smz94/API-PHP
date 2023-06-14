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
        $stmt = $conexao->prepare($sql); 
        $stmt->execute();
        $result = $stmt->get_result(); 
        $row = mysqli_num_rows($result);

        $sql1 = "SELECT * FROM mensagens WHERE msgdestino = '1'"; 
        $stmt = $conexao->prepare($sql1); 
        $stmt->execute();
        $result1 = $stmt->get_result(); 
        $row1 = mysqli_num_rows($result1);

        $sql2 = "SELECT * FROM mensagens WHERE msgdestino = '2'";
        $stmt = $conexao->prepare($sql2); 
        $stmt->execute();
        $result2 = $stmt->get_result(); 
        $row2 = mysqli_num_rows($result2);

        $sql3 = "SELECT * FROM mensagens WHERE msgdestino = '3'";
        $stmt = $conexao->prepare($sql3); 
        $stmt->execute();
        $result3 = $stmt->get_result(); 
        $row3 = mysqli_num_rows($result3);

        $sql4 = "SELECT * FROM mensagens WHERE msgdestino = '4'";
        $stmt = $conexao->prepare($sql4); 
        $stmt->execute();
        $result4 = $stmt->get_result(); 
        $row4 = mysqli_num_rows($result4);

        $sql5 = "SELECT * FROM mensagens WHERE msgdestino = '5'";
        $stmt = $conexao->prepare($sql5); 
        $stmt->execute();
        $result5 = $stmt->get_result(); 
        $row5 = mysqli_num_rows($result5);
            
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