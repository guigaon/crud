<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <title>Alunos</title>
</head>

<body>
    <h1>Alunos</h1>
    <?php
    session_start();
    require "autoload.php";

    #processar informações recebidas
    if (isset($_GET['acao'])) {
        if ($_GET['acao'] == "salvar") {
            if ($_POST['enviar-turma']) {
                $turma = new Turma();
                $turma->setTurma($_POST['codigo-turma-aluno'], null, null, null);
                $aluno = new Aluno();

                $aluno->setAluno(
                    $_POST['codigo_aluno'],
                    $_POST['nome-aluno'],
                    $_POST['matricula-aluno'],
                    $turma
                );

                if ($aluno->salvar()) {
                    $msg['msg'] = "Registro Salvo com sucesso!";
                    $msg['class'] = "success";
                } else {
                    $msg['msg'] = "Falha ao salvar Registro!";
                    $msg['class'] = "success";
                }
                $_SESSION['msgs'][] = $msg;
                unset($aluno);
            }
        } else if ($_GET['acao'] == "excluir") {
            if (isset($_GET['codigo'])) {
                if (Aluno::excluir($_GET['codigo'])) {
                    $msg['msg'] = "Registro excluido com sucesso!";
                    $msg['class'] = "success";
                } else {
                    $msg['msg'] = "Falha ao excluir Registro!";
                    $msg['class'] = "danger";
                }
                $_SESSION['msgs'][] = $msg;
            }
            header("location: alunos.php");
        } else if ($_GET['acao'] == "editar") {
            if (isset($_GET['codigo'])) {
                $aluno = Aluno::getAluno($_GET['codigo']);
            }
        }
    }

	
    # Exibe Mensagens
    if (isset($_SESSION['msgs'])) {

        foreach ($_SESSION['msgs'] as $msg)
            echo "<div id='msg' class='alert alert-{$msg['class']}'>{$msg['msg']}</div>";

        echo
       "<script> 
    setTimeout(
        function(){
            document.querySelector('#msg').style='display:none';
        }
        ,
        5000
    );
</script>";

        unset($_SESSION['msgs']);
    }


    #exibir o formulário de cadastro
    if (!isset($aluno)) {
        $aluno = new Aluno();
        $aluno->setAluno(null, null, null, new Turma());
    }
    ?>
    <!-- Formulário -->
    <div class="container-fluid">
        <h2> Cadastro de Alunos</h2>
        <form name="form-aluno" method="POST" action="?acao=salvar">
            <input type="hidden" name="codigo_aluno" value="<?php echo $aluno->getCodigo() ?>" />
            <div class="input-group mb-2">
                <span class="input-group-text">Nome do Aluno:</span>
                <input type="text" class="form-control" id="nome-aluno" name="nome-aluno" value="<?php echo $aluno->getNome() ?>">
            </div>
            <div class="input-group mb-2">
                <span class="input-group-text">Matrícula:</span>
                <input type="text" class="form-control" id="matricula-aluno" name="matricula-aluno" value="<?php echo $aluno->getMatricula() ?>">
            </div>
            <div class="input-group mb-2 mb-2">
                <label class="input-group-text" for="inputGroupTurma">Turma</label>
                <select class="form-select" name="codigo-turma-aluno">
                    <option value="<?php echo $aluno->getTurma()->getCodigo()  ?>"><?php echo $aluno->getTurma()->getNome() ?></option>

                    <?php

                    $turma = new Turma();
                    $turmas = $turma->listar();
                    if ($turmas) {
                        foreach ($turmas as $item) {
                            echo "<option value='{$item->getCodigo()}'>{$item->getNome()}</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <input type="submit" class="btn btn-primary" name="enviar-turma" value="Enviar" />

        </form>
        <hr />
    </div>

    <!-- Tabela -->
    <div class="container-fluid">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Aluno</th>
                    <th scope="col">Matricula</th>
                    <th scope="col">Turma</th>
                    <th scope="col">Professor</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                #Busca lista de turmas
                $alunos = Aluno::listar();
                foreach ($alunos as $aluno) {
                    echo "<tr>
                    <td>{$aluno->getCodigo()}</td>
                    <td>{$aluno->getNome()}</td>
                    <td>{$aluno->getMatricula()}</td>
                    <td>{$aluno->getTurma()->getNome()}</td>
                    <td>{$aluno->getTurma()->getProfessor()->getNome()}</td>
                    <td>
                        <span class='badge rounded-pill bg-primary'>
                            <a href='?acao=editar&codigo={$aluno->getCodigo()}' style='color:#fff'><i class='bi bi-pencil-square'></i></a>
                        </span>
                        <span class='badge rounded-pill bg-danger'>
                            <a href='?acao=excluir&codigo={$aluno->getCodigo()}'style='color:#fff'><i class='bi bi-trash'></i></a>
                        </span>
                    </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>