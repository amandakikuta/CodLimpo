<?php

require_once(dirname(__DIR__) . '/paths.php');

//Validação

$atual = basename(dirname($_SERVER['SCRIPT_FILENAME']));

if ($atual != CAMINHO) {
    header('Location:./../index.php');
}

class Database
{
    private const SERVERNAME = "localhost";
    private const USERNAME = "root";
    private const PASSWORD = "";
    private const PORT = '3307';
    private const DATABASE = 'rec2210';
    private static $conexao = null;

    public static function conectar()
    {
        if (self::$conexao === null) {
            try {
                self::$conexao = new PDO("mysql:host=" . Database::SERVERNAME . ";port=3307;dbname=rec2210", Database::USERNAME, Database::PASSWORD);
                // echo 'conectado';
                self::$conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (Exception $error) {
                error_log('Erro ao conectar com o BD! ' . $error->getMessage());
                throw $error;
            }
        }

        return self::$conexao;
    }

}
