<?php
    require_once(dirname(__DIR__).'/paths.php');

    //Validação

    $atual = basename(dirname($_SERVER['SCRIPT_FILENAME']));

    if($atual != CAMINHO){
        header('Location:./../index.php');
    }

?>

<footer class="footer">

    <div class="azulEscuro py-1">
        <p class="text-center small fonte-branco"> ©Amanda Ayumi Koga Kikuta </p>
    </div>
    
</footer>

</html>