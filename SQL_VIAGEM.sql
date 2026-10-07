/* MYSQL */
CREATE TABLE viagem (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao VARCHAR(1024) NOT NULL,
    path_imagem VARCHAR(1024) NOT NULL,
    favorito boolean NOT NULL
);