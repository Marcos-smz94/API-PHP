<?php
include('conexao.php');

header("Access-Control-Allow-Origin: http://localhost:3000");
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Content-Type");

// Recebendo o POST feito pelo front
$postdata = json_decode(file_get_contents('php://input'), true);


// Verificando se há algo no POST
if(isset($postdata) && !empty($postdata)){

    // Selecionando o conteúdo
    $jsonemail = trim($postdata["email"]);
    $jsonsenha = trim($postdata["senha"]);

    // Checando por sql injection
    $email = mysqli_real_escape_string($conexao, $jsonemail);
    $senha = mysqli_real_escape_string($conexao, $jsonsenha);
    
    // Comando de busca no mysql
    $sql = "SELECT id, nome, nivel, senha FROM usuarios WHERE email = '$email' LIMIT 1";
    
    // Query de busca no banco de dados
    $query =  mysqli_query($conexao, $sql);

    // Busca no banco de dados
    $row = mysqli_fetch_assoc($query);

    // Checando se as informações batem
    if(!password_verify($senha, $row['senha'])){

        http_response_code(422); 
    
    } else {
        
        http_response_code(200);

        // Selecionando informações que serão úteis para o frontend
        if (!isset($_SESSION)) session_start();
    
        // Salva os dados encontrados na sessão
        $_SESSION['UsuarioID'] = $row['id'];
        $_SESSION['UsuarioNome'] = $row['nome'];
        $_SESSION['UsuarioNivel'] = $row['nivel'];

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
        $tipouser = 'setor';
    } elseif ($_SESSION['UsuarioNivel'] == '4'){   
        $tipouser = 'setor';
    } elseif ($_SESSION['UsuarioNivel'] == '5'){
        $tipouser = 'setor';
    } else{
        $tipouser = 'setor';
    };
    
    // O que vamos retornar para o front
    $returnData = [
        'id' => $_SESSION['UsuarioID'],
        'nome' => $_SESSION['UsuarioNome'],
        'tipo1' => $_SESSION['UsuarioNivel'],
        'tipo2' => $tipouser
    ];
    
        // Enviando em formato json que suporta utf-8
        echo json_encode($returnData, JSON_UNESCAPED_UNICODE);
    };
};

// Fechando a conexão
mysqli_close($conexao);