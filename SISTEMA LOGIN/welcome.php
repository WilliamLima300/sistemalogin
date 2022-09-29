<?php
    //Iniciar sessão
    session_start();

    // Verifique se o usuário está logado, se não, redirecione-o para uma página de login
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        
        header("location: login.php");
        exit;
    }
?>

<!DOCTYPE html>

<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <title>Cadastro</title>

    <!-- inclusão da framework bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <style>
    body {
        font: 14px arial;
        display: flex;
        justify-content: center;
        flex-direction: column;
        align-items: center;
    }

    .wrapper {
        margin-top: 50px;
        width: 360px;
        padding: 20px;
        color: azure;
        background: rgb(2, 0, 36);
        background: linear-gradient(90deg, rgba(2, 0, 36, 1) 0%, rgba(91, 2, 2, 1) 100%, rgba(255, 0, 0, 1) 100%);
        border-radius: 20px 12px;
    }
    </style>

<body>
    <h1 class="my-5">Oi, <b><?php echo htmlspecialchars($_SESSION["nomeUsuario"]); ?></b>. Bem vindo ao nosso site.</h1>
    <p>
        <a href="Alterarsenha.php" class="btn btn-warning">Redefinir sua senha</a>

        <a href="logout.php" class="btn btn-danger ml-3">Sair da conta</a>

    </p>

</body>

</html>