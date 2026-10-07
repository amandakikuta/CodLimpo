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

if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
    echo "<script>alert('$msg')</script>";
    removerMsg();
}

//Chega por get
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    //Cadastrar
    if (isset($_GET['act']) && $_GET['act'] == 'cad') {
        carregarCadastro();
    }

    //Carrega Galeria
    elseif (isset($_GET['act']) && $_GET['act'] == 'galeria') {
        carregarGaleria();
    }

    //Favoritar
    elseif (isset($_GET['act']) && $_GET['act'] == 'favoritar') {
        if (!isset($_GET['id'])) {
            $msg = 'ID não encontrado';
            header("Location: ./Viagem.ctrl.php?msg=$msg");
        }

        //Faz a ação se tiver o id
        elseif (isset($_GET['id'])) {
            $idViagem = $_GET['id'];

            if (Viagem::buscarPorId($idViagem)) {
                Viagem::desfavoritar($idViagem);
            } elseif (!Viagem::buscarPorId($idViagem)) {
                Viagem::favoritar($idViagem);
            }
        }
    }

    //Deletar
    elseif (isset($_GET['act']) && $_GET['act'] == 'del') {
        //Interrompe a ação se não vier o id
        if (!isset($_GET['id'])) {
            $msg = 'ID não encontrado';
            header("Location: ./Viagem.ctrl.php?msg=$msg");
        }

        //Faz a ação se tiver o id
        elseif (isset($_GET['id'])) {
            $idViagem = $_GET['id'];
            $deuCerto = Viagem::apagar($idViagem);

            //* Carrega a página principal
            $msg = 'Viagem excluida com sucesso!';
            header("Location: ./Viagem.ctrl.php?act=galeria&msg=$msg");
        }

    } else {
        carregarHome();
    }

}

//Salvar
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['act']) && $_POST['act'] == 'save') {
        //Pega os valores que veio por post
        $nomeViagem = $_POST['nome'];
        $descricaoViagem = $_POST['descricao'];

        //Validação do campo nome
        if (contarLetras($nomeViagem) > MAX_CHAR_LENGTH) {
            echo "<script>alert('Erro ao cadastrar, o nome não deve possuir mais de 20 caracteres')</script>";
            header("Refresh:0; url=./Viagem.ctrl.php?act=cad");
            exit();
        }

        //Salva imagem na pasta
        $pathImgViagem = ImagemPHP::salvarImagem('imagem');

        //Cria um objeto do tipo Viagem e preenche os valores dos atributos
        $viagem = new Viagem($nomeViagem, $descricaoViagem, $pathImgViagem);

        if (isset($nomeViagem) && !empty($nomeViagem) && isset($descricaoViagem) && !empty($descricaoViagem) && isset($pathImgViagem)) {
            //Salva objeto no BD
            $deuCerto = $viagem->salvar();

            if ($deuCerto) {
                $msg = 'Viagem salva com sucesso!!';

                header("Location: ./Viagem.ctrl.php?act=galeria&msg=$msg");
            }
        } else {
            echo "<script>alert('Erro ao cadastrar, preencha todos os campos obrigatórios!')</script>";
            header("Refresh:0; url=./Viagem.ctrl.php?act=cad");
        }
    }

}
