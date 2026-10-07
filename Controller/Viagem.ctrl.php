<?php

require_once(dirname(__DIR__) . '/paths.php');
require_once(dirname(__DIR__) . '/Model/Imagem.class.php');
require_once(dirname(__DIR__) . '/Model/Viagem.class.php');
require_once(dirname(__DIR__) . '/Model/Database.class.php');

const MAX_CHAR_LENGTH = 20;

function carregarHome()
{
    //Define a página em que o usuário se encontra a partir da função
    $paginaAtual = 'home';

    include_once(dirname(__DIR__) . '/View/cabecalho.php');
    include_once(dirname(__DIR__) . '/View/home.php');
    include_once(dirname(__DIR__) . '/View/rodape.php');
}

function carregarCadastro()
{
    //Define a página em que o usuário se encontra a partir da função
    $paginaAtual = 'cadastrar';

    include_once(dirname(__DIR__) . '/View/cabecalho.php');
    include_once(dirname(__DIR__) . '/View/cadastrar.php');
    include_once(dirname(__DIR__) . '/View/rodape.php');
}

function carregarGaleria()
{
    //Define a página em que o usuário se encontra a partir da função
    $paginaAtual = 'galeria';

    include_once(dirname(__DIR__) . '/View/cabecalho.php');
    include_once(dirname(__DIR__) . '/View/galeria.php');
    include_once(dirname(__DIR__) . '/View/rodape.php');
}

//Conta as letras de um texto
function contarLetras($texto)
{
    //Conta a quantidade de letras
    $qtdeLetras = mb_strlen($texto, 'UTF-8');

    return $qtdeLetras;
}

function removerMsg()
{
    $url = $_SERVER['REQUEST_URI'];

    //Remove o parâmetro 'msg' da URL
    $url = preg_replace('/([&\?])msg=[^&]+(&|$)/', '', $url);
    header("Refresh:0; url=" . $url);
    exit;
}

function redirecionar($url)
{
    header("Location: $url");
    exit();
}

function validarId($idViagem)
{
    if (!is_numeric($idViagem) || $idViagem < 0 || $idViagem === null) {
        return false;
    }

    return true;
}

function obterId()
{
    if (!isset($_GET['id'])) {
        throw new Exception("ID não encontrado");
        redirecionar("./Viagem.ctrl.php?msg=ID <$ID> não encontrado");
    }

    validarId($_GET['id']);

    return (int) $_GET['id'];
}

function salvarViagem()
{
    try {
        //Pega os valores que veio por post
        $nomeViagem = $_POST['nome'] ?? '';
        $descricaoViagem = $_POST['descricao'] ?? '';

        if ($nomeViagem === '' || $descricaoViagem === '') {
            redirecionar("./Viagem.ctrl.php?act=cad&msg=Erro ao cadastrar, preencha todos os campos obrigatórios!");
        }

        //Validação do campo nome
        if (contarLetras($nomeViagem) > MAX_CHAR_LENGTH) {
            redirecionar("./Viagem.ctrl.php?act=cad&msg=Erro ao cadastrar, o nome não deve possuir mais de 20 caracteres");
            exit();
        }

        //Salva imagem na pasta
        $pathImgViagem = ImagemPHP::salvarImagem('imagem');

        //Cria um objeto do tipo Viagem e preenche os valores dos atributos
        $viagem = new Viagem($nomeViagem, $descricaoViagem, $pathImgViagem);

        if (!$viagem->salvar()) {
            throw new Exception('Erro ao salvar viagem.');
        }

        redirecionar("./Viagem.ctrl.php?act=galeria&msg=Viagem salva com sucesso!!");
    } catch (Exception $error) {
        redirecionar("./Viagem.ctrl.php?act=cad&msg=" . urlencode($e->getMessage()));
    }
}

function apagarViagem()
{
    try {
        $idViagem = obterId();

        if (!Viagem::idIsValid($idViagem)) {
            throw new InvalidArgumentException("ID inválido!");
        }

        if (!Viagem::apagar($idViagem)) {
            throw new Exception("Erro ao apagar viagem!");
        }

        redirecionar("./Viagem.ctrl.php?act=galeria&msg=Viagem excluida com sucesso!");
    } catch (InvalidArgumentExcpetion $error) {
        redirecionar("./Viagem.ctrl.php?act=galeria&msg=" . urlencode($error->getMessage()));
    } catch (Exception $error) {
        redirecionar("./Viagem.ctrl.php?act=galeria&msg=" . urlencode($error->getMessage()));
    }
}

function favoritarViagem()
{
    try {
        $idViagem = obterId();

        if (!Viagem::idIsValid($idViagem)) {
            throw new InvalidArgumentException("ID inválido!");
        }

        Viagem::alternarFavorito($idViagem);

        redirecionar('./Viagem.ctrl.php?act=galeria');
    } catch (InvalidArgumentException $error) {
        error_log($error->getMessage());
        redirecionar('./Viagem.ctrl.php?act=galeria&msg=' . urlencode($error->getMessage()));
    }
}

if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
    echo "<script>alert('$msg')</script>";
    removerMsg();
}

$metodoRequisicao = $_SERVER['REQUEST_METHOD'];
$acao = $_GET['act'] ?? null;

if ($metodoRequisicao === 'GET') {
    switch ($acao) {
        case 'cad':
            carregarCadastro();
            break;

        case 'galeria':
            carregarGaleria();
            break;

        case 'favoritar':
            favoritarViagem();
            break;

        case 'del':
            apagarViagem();
            break;

        default:
            carregarHome();
            break;
    }
}

if ($metodoRequisicao === 'POST') {
    switch ($acao) {
        case 'save':
            salvarViagem();
            break;

        default:
            redirecionar("./Viagem.ctrl.php");
    }
}
