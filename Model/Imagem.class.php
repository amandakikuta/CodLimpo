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

    public static function validarArquivo($fileInputName)
    {
        if (!isset($_FILES[$fileInputName])) {
            throw new Exception('Nenhuma imagem foi enviada.');
        }

        $arquivo = $_FILES[$fileInputName];

        if ($arquivo['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Erro ao enviar a imagem.');
        }

        if ($arquivo['size'] > MAX_FILE_SIZE_BYTES) {
            throw new Exception('Arquivo muito grande!');
        }

        if (getimagesize($arquivo['tmp_name']) === false) {
            throw new Exception('O arquivo enviado não é uma imagem.');
        }
    }

    private static function criarDiretorio(): void
    {
        if (!file_exists(self::DIRETORIO_IMG)) {
            mkdir(self::DIRETORIO_IMG);
        }
    }

    public static function salvarImagem($fileInputName)
    {
        self::validarArquivo($fileInputName);

        self::criarDiretorio();

        $arquivo = $_FILES[$fileInputName];

        $nomeArquivo = basename($arquivo['name']);
        $targetFile = self::DIRETORIO_IMG . $nomeArquivo;

        if (!move_uploaded_file($arquivo['tmp_name'], $targetFile)) {
            throw new Exception('Não foi possível salvar a imagem.');
        }

        return self::$ultimoUpload = htmlspecialchars($targetFile);
        ;
    }
}
