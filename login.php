<?php
include('conexao.php');

header("Access-Control-Allow-Origin: http://localhost:3000");
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header("Access-Control-Allow-Headers: Content-Type");

$postdata = json_decode(file_get_contents('php://input'), true);

if(isset($postdata) && !empty($postdata)){
     
    $email = $postdata["email"];
    $senha = $postdata["senha"];

    $sql = "SELECT id, nome, nivel FROM usuarios WHERE email = '{$email}' AND senha = SHA1('{$senha}') LIMIT 1";

    $query =  mysqli_query($conexao, $sql);

    if (mysqli_num_rows($query) != 1) {
    
        http_response_code(422); 
    
    } else {
        
        http_response_code(200);

        $resultado = mysqli_fetch_assoc($query);
        if (!isset($_SESSION)) session_start();
    
        // Salva os dados encontrados na sessão
        $_SESSION['UsuarioID'] = $resultado['id'];
        $_SESSION['UsuarioNome'] = $resultado['nome'];
        $_SESSION['UsuarioNivel'] = $resultado['nivel'];

    // Redireciona o visitante
    /* Niveis de acesso: 1 - Aluno
                         2 - Faculdade
                         3 - Professores
                         4 - Direção
                         5 - Coordenadores 
                         6 - Serviços gerais */
    if ($_SESSION['UsuarioNivel'] == '1'){
        $tipouser = 'aluno';
    } elseif ($_SESSION['UsuarioNivel'] == '2'){
        $tipouser = 'admin';
    } elseif ($_SESSION['UsuarioNivel'] == '3'){
        $tipouser = 'professores';
    } elseif ($_SESSION['UsuarioNivel'] == '4'){   
        $tipouser = 'direcao';
    } elseif ($_SESSION['UsuarioNivel'] == '5'){
        $tipouser = 'coordenadores';
    } else{
        $tipouser = 'servicosgerais';
    };
    
    $returnData = [
        'id' => $_SESSION['UsuarioID'],
        'nome' => $_SESSION['UsuarioNome'],
        'tipo1' => $_SESSION['UsuarioNivel'],
        'tipo2' => $tipouser
    ];

        echo json_encode($returnData);
    };
         
};


mysqli_close($conexao);