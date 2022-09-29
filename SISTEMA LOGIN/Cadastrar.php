<?php

    //requerir a conexão com banco de dados.
    require_once "Conexao.php";


    // variaveis iniciando vazias.
    $nomeUsuario = $senha = $confirmarSenha = "";

    $nomeUsuario_erro = $senha_erro = $confirmarSenha_erro = "";

    // Enviando dados quando o formulario estiver correto

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        //Validar nome do usuario
        if(empty(trim($_POST["nomeUsuario"]))){

            $nomeUsuario_erro = "Favor inserir nome do USUARIO.";

        }elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["nomeUsuario"]))){

            $nomeUsuario_erro = "O Nome do usuario não pode conter caracteres,numeros e sublinhados.Apenas letras.";
        }else{

            //preparar local do BD selecionado.

            $sql = "SELECT id FROM usuarios WHERE nomeUsuario = :nomeUsuario";


            if($stmt = $pdo -> prepare($sql)){

                //vincula as variaveis preparadas como parametros.
                $stmt -> bindParam(":nomeUsuario", $param_nomeUsuario, PDO::PARAM_STR);
                
                $param_nomeUsuario = trim($_POST["nomeUsuario"]);

                //executa declaração.

                if($stmt -> execute()){

                    if($stmt -> rowCount() == 1){
                        $nomeUsuario_erro = "Este nome de usuario já está em uso!";
                    }else{
                        $nomeUsuario = trim($_POST["nomeUsuario"]);
                    }
                }else{
                    echo "Ops! Algo deu errado. Tente novamente!";

                }

                //Fechar declaração.

                unset($stmt);
            }


        }

        //Validar Senha/Erros

        if(empty(trim($_POST["senha"]))){

            $senha_erro = "Insira uma senha.";

        }elseif(strlen(trim($_POST["senha"])) < 6){

            $senha_erro = "A senha deve conter pelo menos 6 caracteres.";

        }else{

            $senha = trim($_POST["senha"]);

        }

        //confirmação da senha

        if(empty(trim($_POST["confirmarSenha"]))){

            $confirmarSenha_erro = "Por favor, confirme a senha.";
        }else{
            $confirmarSenha = trim($_POST["confirmarSenha"]);
            if(empty($senha_erro) && ($senha != $confirmarSenha)){
                $confirmarSenha_erro = "A senha não está correta.";
            }
        }

        // Verificar erros de entrada antes de inserir no BANCO DE DADOS

        if(empty($nomeUsuario_erro) && empty($senha_erro) && empty($confirmarSenha_erro)){

            // Declaração de inserção de dados

            $sql = "INSERT INTO usuarios (nomeUsuario,senha) VALUES(:nomeUsuario,:senha)";
        
            if($stmt = $pdo ->prepare($sql)){
                
                // vincular as variaveis a instrução como parametro
                $stmt -> bindParam(":nomeUsuario", $param_nomeUsuario, PDO::PARAM_STR);
                $stmt -> bindParam(":senha", $param_senha, PDO::PARAM_STR);

                //Definir parametros

                $param_nomeUsuario = $nomeUsuario;
                $param_senha = password_hash($senha, PASSWORD_DEFAULT);
                
                //executar a declaração

                if($stmt->execute()){
                    //redirecionar para pagina de login

                    header("location:login.php");
                }else{
                    echo"Ops! Algo deu errado, tente novamente mais tarde!";
                }

                //fechar declaração

                unset($stmt);
            }
        
        }

        //fechar conexão com banco de DADOS

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

</head>

<body>
    <div class="wrapper">
        <h2> Cadastrar usuário</h2>

        <p>Preencha o formulario para criar uma conta:</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
            <div class="form-group">

                <label>Nome do usuário</label>
                <input type="text" name="nomeUsuario"
                    class="form-control <?php echo (!empty($nomeUsuario_erro)) ? 'is-invalid' : ''; ?> "
                    value="<?php echo $nomeUsuario?>">
                <span class="invalid-feedback"><?php echo $nomeUsuario_erro; ?></span>

            </div>

            <div class="form-group">

                <label>Senha</label>
                <input type="password" name="senha"
                    class="form-control <?php echo (!empty($senha_erro)) ? 'is-invalid' : ''; ?>"
                    value="<?php echo $senha; ?>">
                <span class="invalid-feedback"><?php echo $senha_erro; ?></span>

            </div>

            <div class="form-group">

                <label>Confirme a senha</label>
                <input type="password" name="confirmarSenha"
                    class="form-control <?php echo (!empty($confirmarSenha_erro)) ? 'is-invalid' : ''; ?>"
                    value="<?php echo $confirmarSenha; ?>">
                <span class="invalid-feedback"><?php echo $confirmarSenha_erro; ?></span>

            </div>

            <div class="form-group">

                <input type="submit" class="btn btn-primary" value="Criar Conta">

                <input type="reset" class="btn btn-danger ml-2" value="Apagar Dados">

            </div>

            <p>Já tem uma conta? <a href="login.php">Entre aqui</a>.</p>

        </form>
    </div>

</body>
<!-- DEV - WILLIAM LIMA ALVES-->

</html>