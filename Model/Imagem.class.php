<?php

require_once(dirname(__DIR__) . '/paths.php');

//Validação

$atual = basename(dirname($_SERVER['SCRIPT_FILENAME']));

if ($atual != CAMINHO) {
    header('Location:./../index.php');
}

const MAX_FILE_SIZE_BYTES = 1500000;

class ImagemPHP
{
    public const DIRETORIO_IMG = './../img/';
    private static $ultimoUpload;

    /**
     * Faz upload da imagem de name $fileInputName para a pasta DIRETORIO_IMG.
     * Se já houver imagem com mesmo nome, sobreescreve.
     */

    public static function salvarImagem($fileInputName)
    {
        $erro = null;
        //Se não exsitir pasta, cria
        if (!file_exists(ImagemPHP::DIRETORIO_IMG)) {
            mkdir(ImagemPHP::DIRETORIO_IMG);
        }

        //caminho completo para salvar imagem
        $targetFile = ImagemPHP::DIRETORIO_IMG . basename($_FILES[$fileInputName]["name"]);

        //$imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        //verifica se é um arquivo
        if (isset($_POST["submit"])) {

            $check = getimagesize($_FILES[$fileInputName]["tmp_name"]);
            if ($check == false) {
                $erro = new Exception("ARQUIVO NAO E IMAGEM.");
            }
        }

        //verifica se não é muito grande
        if ($_FILES[$fileInputName]["size"] > MAX_FILE_SIZE_BYTES) {
            $erro = new Exception("ARQUIVO MUITO GRANDE!");
        }

        if ($erro != null) {
            throw $erro;
        } else {
            //move arquivo para pasta e retorna o caminho+nome
            if (move_uploaded_file($_FILES[$fileInputName]["tmp_name"], $targetFile)) {
                return ImagemPHP::$ultimoUpload = htmlspecialchars($targetFile);
            } else {
                return null;
            }
        }
    }
}
