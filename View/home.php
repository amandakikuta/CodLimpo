<?php
    require_once(dirname(__DIR__).'/paths.php');

    //Validação

    $atual = basename(dirname($_SERVER['SCRIPT_FILENAME']));

    if($atual != CAMINHO){
        header('Location:./../index.php');
    }

?>

<title>Home</title>

<body class="cinzaClaro">
    
    <div class="flex mt-2">

        <div class="azulEscuro flex border-radius p-titulo mt-2">
            <h2 class="titulo patrickHand fonte-branco">SEJA BEM-VINDO</h2>
        </div>
        <div class="bloco flex azulClaro mt-2 p-bloco patrickHand">
            <h2 class="sub-titulo">INFORMAÇÕES DO TRABALHO</h2>
            <p class="label-size mt-1">Aluna: Amanda Ayumi Koga Kikuta</p>
            <p class="label-size">Turma: 2210</p>
            <p class="label-size">Banco de dados: MySQL</p>
            <p class="label-size">Nome do banco de dados: rec2210</p>
        </div>

    </div>
    
</body>