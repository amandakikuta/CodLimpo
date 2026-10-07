<?php

require_once(dirname(__DIR__) . '/paths.php');

//Validação

$atual = basename(dirname($_SERVER['SCRIPT_FILENAME']));

if ($atual != CAMINHO) {
    header('Location:./../index.php');
}

class ImagemPHP
{
    public const DIRETORIO_IMG = './../img/';
    private static $ultimoUpload;

    /**
     * Faz upload da imagem de name $file_input_name para a pasta DIRETORIO_IMG.
     * Se já houver imagem com mesmo nome, sobreescreve.
     */

    public static function salvarImagem($file_input_name)
    {
        $erro = null;
        //Se não exsitir pasta, cria
        if (!file_exists(ImagemPHP::DIRETORIO_IMG)) {
            mkdir(ImagemPHP::DIRETORIO_IMG);
        }

        //caminho completo para salvar imagem
        $target_file = ImagemPHP::DIRETORIO_IMG . basename($_FILES[$file_input_name]["name"]);
        $ok = 1;

        //$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        //verifica se é um arquivo
        if (isset($_POST["submit"])) {

            $check = getimagesize($_FILES[$file_input_name]["tmp_name"]);
            if ($check == false) {
                $erro = new Exception("ARQUIVO NAO E IMAGEM.");
            }
        }

        //verifica se não é muito grande
        if ($_FILES[$file_input_name]["size"] > 1500000) {
            $erro = new Exception("ARQUIVO MUITO GRANDE!");
        }

        if ($erro != null) {
            throw $erro;
        } else {
            //move arquivo para pasta e retorna o caminho+nome
            $ok = move_uploaded_file($_FILES[$file_input_name]["tmp_name"], $target_file);
            if ($ok) {
                return ImagemPHP::$ultimoUpload = htmlspecialchars($target_file);
            } else {
                return null;
            }
        }
    }
}
