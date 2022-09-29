<?php
    //Iniciar sessão.
    session_start();

    // Verificar se já está logado caso sim redirecionar.
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){

        header("location:welcome.php");

        exit;
    }

    // Incluir arquivo de conexão.
    require_once "Conexao.php";

    //Iniciar variaveis com valores vazios.

    $novaSenha = $confirmarSenha = "";
    $novaSenha_erro = $confirmarSenha_erro = "";

    // processar dados de formulario quando for enviado.

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        //validar nova senha
        if(empty(trim($_POST["novaSenha"]))){

            $novaSenha_erro = "Por favor insira a nova senha.";     
       
        } elseif(strlen(trim($_POST["novaSenha"])) < 6){
            
            $novaSenha_erro = "A senha deve ter pelo menos 6 caracteres.";
        
        } else{

            $novaSenha = trim($_POST["novaSenha"]);
        
        }

        //Validar e confirmar senha

        if(empty(trim($_POST["confirmarSenha"]))){

            $confirmarSenha_erro = "Por favor, confirme a senha.";
        
        } else{
            
            $confirmarSenha = trim($_POST["confirmarSenha"]);
            
            if(empty($novaSenha_erro) && ($novaSenha != $confirmarSenha)){
                $confirmarSenha_erro = "A senha não confere.";
            }
        }

        // Verifique os erros de entrada antes de atualizar o banco de dados
        if(empty($novaSenha_erro) && empty($confirmarSenha_erro)){
        
            // Prepare uma declaração de atualização
            $sql = "UPDATE usuarios SET senha = :senha WHERE id = :id";
        
            if($stmt = $pdo->prepare($sql)){
                
                // Vincule as variáveis à instrução preparada como parâmetros
                
                $stmt->bindParam(":senha", $param_senha, PDO::PARAM_STR);
                
                $stmt->bindParam(":id", $param_id, PDO::PARAM_INT);
            
                // Definir parâmetros
                $param_senha = password_hash($novaSenha, PASSWORD_DEFAULT);
                $param_id = $_SESSION["id"];
            
                // Tente executar a declaração preparada

                if($stmt->execute()){
                    
                // Senha atualizada com sucesso. Destrua a sessão e redirecione para a página de login
                session_destroy();

                header("location:login.php");

                exit();
            } else{

                echo "Ops! Algo deu errado. Por favor, tente novamente mais tarde.";
            }

            // Fechar declaração
            unset($stmt);
        }
    }
    
    // Fechar conexão
    unset($pdo);

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
    <div class="wrapper">

        <h2>Redefinir senha</h2>

        <p>Por favor, preencha este formulário para redefinir sua senha.</p>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">

            <div class="form-group">
                <label>Nova senha</label>
                <input type="password" name="novaSenha"
                    class="form-control <?php echo (!empty($novaSenha_erro)) ? 'is-invalid' : ''; ?>"
                    value="<?php echo $novaSenha; ?>">
                <span class="invalid-feedback"><?php echo $novaSenha_erro; ?></span>
            </div>

            <div class="form-group">
                <label>Confirme a senha</label>
                <input type="password" name="confirmarSenha"
                    class="form-control <?php echo (!empty($confirmarSenha_erro)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $confirmarSenha_erro; ?></span>
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Redefinir">
                <a class="btn btn-danger ml-2" href="welcome.php">Cancelar</a>
            </div>
        </form>
    </div>
</body>
<!-- DEV - WILLIAM LIMA ALVES-->

</html>