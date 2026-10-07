<?php

require_once(dirname(__DIR__) . '/paths.php');
require_once(dirname(__DIR__) . '/Model/Imagem.class.php');
require_once(dirname(__DIR__) . '/Model/Viagem.class.php');
require_once(dirname(__DIR__) . '/Model/Database.class.php');

function carregaHome()
{
    //Define a página em que o usuário se encontra a partir da função
    $pgAtual = 'home';

    include_once(dirname(__DIR__) . '/View/cabecalho.php');
    include_once(dirname(__DIR__) . '/View/home.php');
    include_once(dirname(__DIR__) . '/View/rodape.php');
}

function carregaCadastro()
{
    //Define a página em que o usuário se encontra a partir da função
    $pgAtual = 'cadastrar';

    include_once(dirname(__DIR__) . '/View/cabecalho.php');
    include_once(dirname(__DIR__) . '/View/cadastrar.php');
    include_once(dirname(__DIR__) . '/View/rodape.php');
}

function carregaGaleria()
{
    //Define a página em que o usuário se encontra a partir da função
    $pgAtual = 'galeria';

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

function removeMsg()
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
    removeMsg();
}

//Chega por get
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    //Cadastrar
    if (isset($_GET['act']) && $_GET['act'] == 'cad') {
        carregaCadastro();
    }

    //Carrega Galeria
    elseif (isset($_GET['act']) && $_GET['act'] == 'galeria') {
        carregaGaleria();
    }

    //Favoritar
    elseif (isset($_GET['act']) && $_GET['act'] == 'favoritar') {
        if (!isset($_GET['id'])) {
            $msg = 'ID não encontrado';
            header("Location: ./Viagem.ctrl.php?msg=$msg");
        }

        //Faz a ação se tiver o id
        elseif (isset($_GET['id'])) {
            $id = $_GET['id'];

            if (Viagem::buscarId($id)) {
                Viagem::desfavoritar($id);
            } elseif (!Viagem::buscarId($id)) {
                Viagem::favoritar($id);
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
            $id = $_GET['id'];
            $deuCerto = Viagem::apagar($id);

            //* Carrega a página principal
            $msg = 'Viagem excluida com sucesso!';
            header("Location: ./Viagem.ctrl.php?act=galeria&msg=$msg");
        }

    } else {
        carregaHome();
    }

}

//Salvar
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['act']) && $_POST['act'] == 'save') {
        //Pega os valores que veio por post
        $nome = $_POST['nome'];
        $desc = $_POST['descricao'];

        //Validação do campo nome
        if (contarLetras($nome) > 20) {
            echo "<script>alert('Erro ao cadastrar, o nome não deve possuir mais de 20 caracteres')</script>";
            header("Refresh:0; url=./Viagem.ctrl.php?act=cad");
            exit();
        }

        //Salva imagem na pasta
        $path_img = ImagemPHP::salvaImagem('imagem');

        //Cria um objeto do tipo Viagem e preenche os valores dos atributos
        $viagem = new Viagem($nome, $desc, $path_img);

        if (isset($nome) && !empty($nome) && isset($desc) && !empty($desc) && isset($path_img)) {
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
