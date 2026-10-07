<?php

require_once(dirname(__DIR__) . '/paths.php');

//Validação

$atual = basename(dirname($_SERVER['SCRIPT_FILENAME']));

if ($atual != CAMINHO) {
    header('Location:./../index.php');
}

class Viagem
{
    private $idViagem;
    private $nomeViagem;
    private $descricaoViagem;
    private $pathImgViagem;
    private $favoritoViagem;

    //Getters
    public function __get($name)
    {
        return $this->$name;
    }

    //Setters
    public function __set($name, $value)
    {

        if (property_exists('Viagem', $name)) {
            $this->$name = $value;
        } else {
            throw new Exception("Atributo não existe");
        }
    }

    public function __construct($nomeViagem, $descricaoViagem, $pathImgViagem)
    {
        $this->nomeViagem = $nomeViagem;
        $this->descricaoViagem = $descricaoViagem;
        $this->pathImgViagem = $pathImgViagem;
    }

    public function salvar()
    {
        try {
            $con = Database::conectar();

            //Prepara sql
            $sql = $con->prepare('INSERT INTO viagem VALUES (default, :nome, :descricao, :img, false)');

            $sql->bindValue(':nome', $this->nomeViagem);
            $sql->bindValue(':descricao', $this->descricaoViagem);
            $sql->bindValue(':img', $this->pathImgViagem);

            return $sql->execute();
        } catch (PDOException $e) {
            echo 'Ocorreu um erro ao executar a instrução SQL: ' . $e->getMessage();
            return false;
        }
    }

    //Excluir
    public static function apagar($idViagem)
    {
        try {
            $conn = Database::conectar();

            $stmt = $conn->prepare("DELETE FROM viagem WHERE id=:id");
            $stmt->bindParam(':id', $idViagem);

            return $stmt->execute();
        } catch (PDOException $e) {
            die("Erro ao excluir! " . $e->getMessage());
        }
    }

    //Listar
    public static function listar()
    {
        $conn = Database::conectar();

        $stmt = $conn->prepare('SELECT * FROM viagem');

        $stmt->execute();

        while ($linha = $stmt->fetch()) {
            $viagem = new Viagem(
                $linha['nome'],
                $linha['descricao'],
                $linha['path_imagem'],
            );

            $viagem->idViagem = $linha['id'];
            $viagem->favoritoViagem = $linha['favorito'];

            $viagens[] = $viagem;
        }
        if (isset($viagens)) {
            return $viagens;
        }
    }

    public static function listarHTML()
    {

        if (!empty(Viagem::listar())) {
            $i = 0;

            foreach (Viagem::listar() as $viagem) {

                if ($i % 2 == 0) {
                    echo "<div class=\"itens\">";
                }

                echo "
                <div class=\"flex my-4 mx-2\">
                    <div class=\"nav-filha elemento-titulo border-radius azul\">
                        <div id=\"$viagem->idViagem\" class=\"fonte-amarelo m-rigth\"";
                if (!$viagem->favoritoViagem) {
                    echo"style=\"display: none;\"";
                } echo">
                            <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" fill=\"currentColor\" class=\"bi bi-star\" viewBox=\"0 0 16 16\">
                            <path d=\"M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z\"/>
                            </svg>
                        </div>

                        <a onclick=\"favoritar($viagem->idViagem)\" class=\"fonte-branco patrickHand\">$viagem->nomeViagem</a>
                        <script type=\"text/javascript\" src=\"./../View/js/ajax.js\"></script>

                        <a class=\"fonte-azul m-left\" href=\"./../Controller/Viagem.ctrl.php?act=del&id={$viagem->idViagem}\">
                            <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"25\" height=\"25\" fill=\"currentColor\" class=\"bi bi-x\" viewBox=\"0 0 16 16\">
                            <path d=\"M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z\"/>
                            </svg>
                        </a>
                    </div>

                    <div class=\"flex bloco p-bloco azulClaro mt-15\">
                        <figure class=\"flex\">
                            <img class=\"border-img\" width=\"300px\" src=\"$viagem->pathImgViagem\" alt=\"foto da viagem\">
                            <div class=\"desc mt-1\">$viagem->descricaoViagem</div>
                        </figure>
                    </div>
                </div>
                ";

                if (($i + 1) % 2 == 0) {
                    echo "</div>";
                }

                $i++;

            }

            echo "</div>";
        }

    }

    //Favoritar
    public static function favoritar($idViagem)
    {
        try {
            $conn = Database::conectar();

            $stmt = $conn->prepare("UPDATE viagem SET favorito = true WHERE id=:id");
            $stmt->bindParam(':id', $idViagem);

            return $stmt->execute();
        } catch (PDOException $e) {
            die("Erro ao favoritar! " . $e->getMessage());
        }
    }

    //Desfavoritar
    public static function desfavoritar($idViagem)
    {
        try {
            $conn = Database::conectar();

            $stmt = $conn->prepare("UPDATE viagem SET favorito = false WHERE id=:id");
            $stmt->bindParam(':id', $idViagem);

            return $stmt->execute();
        } catch (PDOException $e) {
            die("Erro ao desfavoritar! " . $e->getMessage());
        }
    }

    //Buscar por id
    public static function buscarPorId($idViagem)
    {
        try {
            $conn = Database::conectar();

            $stmt = $conn->prepare("SELECT * FROM viagem WHERE id=:id");
            $stmt->bindParam(':id', $idViagem);

            $stmt->execute();
            $linha = $stmt->fetch();
            return $linha['favorito'];
        } catch (PDOException $e) {
            die("Erro ao procurar ID! " . $e->getMessage());
        }
    }
}
