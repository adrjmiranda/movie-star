DROP DATABASE IF EXISTS movie_star;
CREATE DATABASE movie_star CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE movie_star;

CREATE TABLE users (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(100) NOT NULL,
    lastName VARCHAR(150) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    image VARCHAR(255),
    bio TEXT,
    token VARCHAR(255),
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL,
    image VARCHAR(255),
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE movies (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    trailer TEXT,
    duration SMALLINT UNSIGNED,
    categoryId INT UNSIGNED NOT NULL,
    userId INT UNSIGNED NOT NULL,
    FOREIGN KEY (categoryId) REFERENCES categories(id),
    FOREIGN KEY (userId) REFERENCES users(id) ON DELETE CASCADE,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE reviews (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    comment TEXT NOT NULL,
    rating TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 10),
    movieId INT UNSIGNED NOT NULL,
    userId INT UNSIGNED NOT NULL,
    FOREIGN KEY (movieId) REFERENCES movies(id) ON DELETE CASCADE,
    FOREIGN KEY (userId) REFERENCES users(id) ON DELETE CASCADE,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    updatedAt DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO categories (name) VALUES ("Ação"),
                                    ("Animação"),
                                    ("Aventura"),
                                    ("Biografia"),
                                    ("Comédia"),
                                    ("Crime"),
                                    ("Documentário"),
                                    ("Drama"),
                                    ("Fantasia"),
                                    ("Ficção Científica"),
                                    ("Guerra"),
                                    ("Histórico"),
                                    ("Horror"),
                                    ("Músical"),
                                    ("Policial"),
                                    ("Romance"),
                                    ("Suspense"),
                                    ("Western"),
                                    ("Esporte"),
                                    ("Família"),
                                    ("Noir"),
                                    ("Experimental"),
                                    ("Arte"),
                                    ("Adolescente"),
                                    ("Sci-Fi"),
                                    ("Épico"),
                                    ("Paródia"),
                                    ("Espionagem"),
                                    ("Fantástico"),
                                    ("Super-herói"),
                                    ("Cult"),
                                    ("Drama Psicológico"),
                                    ("Pós-Apocalíptico"),
                                    ("Distopia"),
                                    ("Utopia"),
                                    ("Nerd / Geek"),
                                    ("Comédia Romântica"),
                                    ("Drama Social"),
                                    ("Terror Psicológico"),
                                    ("Sobrevivência"),
                                    ("Viagem no Tempo"),
                                    ("Vampiros"),
                                    ("Lobisomens"),
                                    ("Zumbis"),
                                    ("Infantojuvenil"),
                                    ("Religioso"),
                                    ("Mitologia"),
                                    ("Tecnologia"),
                                    ("Ecologia"),
                                    ("Other");