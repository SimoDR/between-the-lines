SET time_zone = "+00:00";

DROP TABLE IF EXISTS autori;
CREATE TABLE autori
(
    ID           INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome         VARCHAR(255) NOT NULL,
    cognome      VARCHAR(255) NOT NULL,
    data_nascita DATE         NOT NULL,
    data_morte   DATE,
    CONSTRAINT nominativo_univoco UNIQUE (nome, cognome)
) ENGINE = InnoDB;

DROP TABLE IF EXISTS generi;
CREATE TABLE generi
(
    ID   INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(127) NOT NULL
) ENGINE = InnoDB;


DROP TABLE IF EXISTS libri;
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

DROP TABLE IF EXISTS copertine;
CREATE TABLE copertine
(
    ID       INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_libro INTEGER UNSIGNED NOT NULL,
    path_img VARCHAR(255)     NOT NULL,
    alt_text VARCHAR(255)     NOT NULL,
    FOREIGN KEY (id_libro) REFERENCES libri (ID) ON DELETE CASCADE
) ENGINE = InnoDB;



DROP TABLE IF EXISTS foto_profilo;
CREATE TABLE foto_profilo
(
    ID        SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    path_foto VARCHAR(255),
    alt_text  VARCHAR(255)
) ENGINE = InnoDB;

DROP TABLE IF EXISTS utenti;
CREATE TABLE utenti
(
    ID        INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username  VARCHAR(31)  NOT NULL,
    password  VARCHAR(200)  NOT NULL, -- high limit because it's the hashed password
    id_propic SMALLINT UNSIGNED,
    mail      VARCHAR(127) NOT NULL,
    is_admin  TINYINT(1)   NOT NULL,
    FOREIGN KEY (id_propic) REFERENCES foto_profilo (ID) ON DELETE SET NULL,
    UNIQUE (username),
    UNIQUE (mail)
) ENGINE = InnoDB;

DROP TABLE IF EXISTS recensioni;
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


-- TO DO Elimina drop table classificazioni
DROP TABLE IF EXISTS classificazioni;