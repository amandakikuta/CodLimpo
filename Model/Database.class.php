<?php

require_once(dirname(__DIR__) . '/paths.php');

//Validação

$atual = basename(dirname($_SERVER['SCRIPT_FILENAME']));

if ($atual != CAMINHO) {
    header('Location:./../index.php');
}

class Database
{
    private const servername = "localhost";
    private const username = "root";
    private const password = "";

    public static function conecta()
    {
        $conexao = null;
        try {
            $conexao = new PDO("mysql:host=" . Database::servername . ";port=3307;dbname=rec2210", Database::username, Database::password);
            // echo 'conectado';
            $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            echo 'Não foi possível conectar ao BD :(<br>';
            echo $e->getMessage();
        }

        return $conexao;
    }

}
