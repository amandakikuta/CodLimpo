<?php
require_once(dirname(__DIR__) . '/paths.php');

//Validação

$atual = basename(dirname($_SERVER['SCRIPT_FILENAME']));

if ($atual != CAMINHO) {
    header('Location:./../index.php');
}

?>

<title>Cadastrar nova viagem</title>

<body class="cinzaClaro">

    <div class="flex">

        <div class="flex">
            <div class="azul border-radius sub-titulo patrickHand fonte-branco p-titulo my-4">CADASTRAR NOVA VIAGEM</div>

            <form class="bloco azulClaro p-bloco mb-6 flex" action="./../Controller/Viagem.ctrl.php?act=save" method="post" enctype="multipart/form-data">

                <div class="flex mb-2">
                    <label class="mb-1 label-size patrickHand" for="nome">Nome:</label>
                    <input class="campo-texto" type="text" maxlength="20" name="nome" placeholder="Nome" required>
                </div>

                <div class="flex mb-2">
                <label class="mb-1 label-size patrickHand" for="descricao">Descrição:</label>
                    <textarea class="campo-texto" name="descricao" cols="30" rows="5" required></textarea>
                </div>

                <div class="flex mb-4">
                    <label class="mb-1 label-size patrickHand" for="imagem">Imagem:</label>
                    <input type="file" name="imagem" accept="image/*" required>
                </div>

                <button class="btn azul" type="submit" name="act" value="save">Cadastrar</button>
            </form>
        </div>

    </div>

</body>