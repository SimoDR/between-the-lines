SET time_zone = "+00:00";

DROP TABLE IF EXISTS copertine;
DROP TABLE IF EXISTS recensioni;
DROP TABLE IF EXISTS libri;
DROP TABLE IF EXISTS autori;
DROP TABLE IF EXISTS generi;
DROP TABLE IF EXISTS utenti;
DROP TABLE IF EXISTS foto_profilo;


CREATE TABLE autori
(
    ID           INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome         VARCHAR(100)        NOT NULL,
    cognome      VARCHAR(100)        NOT NULL,
    anno_nascita INTEGER(4) UNSIGNED NOT NULL,
    anno_morte   INTEGER(4) UNSIGNED,
    CONSTRAINT nominativo_univoco UNIQUE (nome, cognome)
) ENGINE = InnoDB;


CREATE TABLE generi
(
    ID   INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(127) NOT NULL
) ENGINE = InnoDB;


CREATE TABLE libri
(
    ID        INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    titolo    TEXT             NOT NULL,
    id_autore INTEGER UNSIGNED NOT NULL,
    id_genere INTEGER UNSIGNED NOT NULL,
    trama     TEXT             NOT NULL,
    FOREIGN KEY (id_autore) REFERENCES autori (ID) ON DELETE CASCADE,
    FOREIGN KEY (id_genere) REFERENCES generi (ID) ON DELETE CASCADE
) ENGINE = InnoDB;


CREATE TABLE copertine
(
    ID       INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_libro INTEGER UNSIGNED NOT NULL,
    path_img VARCHAR(255)     NOT NULL,
    alt_text VARCHAR(255)     NOT NULL,
    FOREIGN KEY (id_libro) REFERENCES libri (ID) ON DELETE CASCADE
) ENGINE = InnoDB;


CREATE TABLE foto_profilo
(
    ID        SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    path_foto VARCHAR(255),
    alt_text  VARCHAR(255)
) ENGINE = InnoDB;


CREATE TABLE utenti
(
    ID        INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username  VARCHAR(31)  NOT NULL,
    password  VARCHAR(200) NOT NULL, -- high limit because it's the hashed password
    id_propic SMALLINT UNSIGNED,
    mail      VARCHAR(127) NOT NULL,
    is_admin  TINYINT(1)   NOT NULL,
    FOREIGN KEY (id_propic) REFERENCES foto_profilo (ID) ON DELETE SET NULL,
    UNIQUE (username),
    UNIQUE (mail)
) ENGINE = InnoDB;


CREATE TABLE recensioni
(
    ID          INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    dataora     DATETIME         NOT NULL,
    valutazione TINYINT(1)       NOT NULL,
    id_libro    INTEGER UNSIGNED NOT NULL,
    id_utente   INTEGER UNSIGNED NOT NULL,
    testo       TEXT             NOT NULL,
    FOREIGN KEY (id_libro) REFERENCES libri (ID) ON DELETE CASCADE,
    FOREIGN KEY (id_utente) REFERENCES utenti (ID) ON DELETE CASCADE
) ENGINE = InnoDB;

