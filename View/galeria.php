<?php
require_once(dirname(__DIR__) . '/paths.php');

//Validação

$atual = basename(dirname($_SERVER['SCRIPT_FILENAME']));

if ($atual != CAMINHO) {
    header('Location:./../index.php');
}

?>

<title>Galeria de Viagens</title>

<body class="cinzaClaro">

    <div class="flex">
        <?php Viagem::listarHTML(); ?>
    </div>

</body>