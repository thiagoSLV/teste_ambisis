CREATE database teste_ambisis;
use teste_ambisis;
CREATE table empresa(
    id integer PRIMARY KEY NOT NULL AUTO_INCREMENT,
    razao_social(30),
    cnpj int(14),
    cep int(8),
    cidade varchar(30),
    estado varchar(2),
    bairro varchar(30),
    complemento varchar(30)
);

CREATE table licenca(
    id integer PRIMARY KEY NOT NULL AUTO_INCREMENT,
    empresa_id int,
    numero varchar(30),
    orgao_ambiental varchar(30),
    emissao date,
    validade date,
    CONSTRAINT tb_fk FOREIGN KEY (empresa_id) REFERENCES empresa(id)
);
