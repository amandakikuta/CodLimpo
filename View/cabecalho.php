<?php
require_once(dirname(__DIR__) . '/paths.php');

//Validação

$atual = basename(dirname($_SERVER['SCRIPT_FILENAME']));

if ($atual != CAMINHO) {
    header('Location:./../index.php');
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Links -->
    <link href="./../View/css/style.css" rel="stylesheet">
    <link href="./../View/css/responsavidade.css" rel="stylesheet">
    <link href="./../View/css/reset.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Patrick+Hand&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alex+Brush&display=swap" rel="stylesheet">

</head>

<body>

    <header>

        <div class="nav azul">
            <div class="logo fonte-branco alexBrush titulo negrito">
                Destiny
            </div>
            <div class="nav-filha py-1 azulClaro patrickHand sub-titulo negrito">
                <a class="nav-elemento fonte-preto" href="./../Controller/Viagem.ctrl.php"
                    <?php echo $pgAtual == 'home' ? 'hidden' : '' ?>
                >HOME</a>

                <a class="nav-elemento fonte-preto" href="./../Controller/Viagem.ctrl.php?act=cad"
                    <?php echo $pgAtual == 'cadastrar' ? 'hidden' : '' ?>
                >CADASTRAR</a>

                <a class="nav-elemento fonte-preto" href="./../Controller/Viagem.ctrl.php?act=galeria"
                    <?php echo $pgAtual == 'galeria' ? 'hidden' : '' ?>
                >LISTAGEM</a>
            </div>
        </div>

    </header>