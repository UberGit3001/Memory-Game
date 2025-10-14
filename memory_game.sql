-- ============================================
--  BASE DE DONNÉES : Memory Game
-- ============================================

-- 1. Création de la base de données
CREATE DATABASE IF NOT EXISTS memory_game
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

-- 2. Sélection de la base de données
USE memory_game;


-- ============================================
--  TABLE DES JOUEURS
-- ============================================

-- Création de la table des joueurs pour le jeu de mémoire
CREATE TABLE players (
    id INT AUTO_INCREMENT PRIMARY KEY,          -- Identifiant unique du joueur
    name VARCHAR(50) NOT NULL UNIQUE,            -- Nom / pseudo du joueur
    best_score INT DEFAULT NULL,                 -- Meilleur score obtenu (moins = mieux)
    games_played INT DEFAULT 0,                  -- Nombre total de parties jouées
    date_joined DATETIME DEFAULT CURRENT_TIMESTAMP  -- Date d'inscription
);


-- ============================================
-- TABLE DES SCORES DES JOUEURS
-- ============================================
-- Création de la table des scores pour enregistrer les performances des joueurs

CREATE TABLE scores (
    id INT AUTO_INCREMENT PRIMARY KEY,         -- Identifiant unique du score
    player_id INT NOT NULL,                    -- Référence au joueur
    score INT NOT NULL,                        -- Résultat (ex: nombre de coups)
    pairs_count INT NOT NULL,                  -- Nombre de paires jouées (niveau)
    time_seconds INT DEFAULT NULL,             -- Temps écoulé si tu veux chronométrer
    date_played DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (player_id) REFERENCES players(id) ON DELETE CASCADE
);


-- Création de la table des parties pour suivre l'état des jeux en cours

CREATE TABLE games (
    id INT AUTO_INCREMENT PRIMARY KEY,
    player_id INT NOT NULL,
    pairs_count INT NOT NULL,
    state TEXT,                   -- JSON ou texte pour enregistrer les cartes retournées
    start_time DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (player_id) REFERENCES players(id)
);

-- ============================================
-- TABLE DU CLASSEMENT DES PARTIES
-- ============================================
-- Création de la table du classement pour afficher les meilleurs scores

CREATE TABLE scoreboard (
    id INT AUTO_INCREMENT PRIMARY KEY,
    player_id INT NOT NULL,
    score INT NOT NULL,
    rank_position INT DEFAULT NULL,
    pairs_count INT DEFAULT NULL,
    date_recorded DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (player_id) REFERENCES players(id) ON DELETE CASCADE
);

-- ============================================
-- Quelques exemples de données (optionnel)
-- ============================================

INSERT INTO players (name, best_score, games_played)
VALUES 
('SebNono', 28, 5),
('Alice', 34, 3),
('Lucas', 30, 2);

INSERT INTO scores (player_id, score, pairs_count, time_seconds)
VALUES
(1, 28, 6, 74),
(1, 34, 8, 95),
(2, 30, 6, 82),
(3, 33, 12, 110);

-- ============================================
-- Exemple de vue pour afficher le Top 10 dynamique
-- ============================================

CREATE OR REPLACE VIEW v_top10 AS
SELECT 
    p.name AS player_name,
    MIN(s.score) AS best_score,
    COUNT(s.id) AS games_played
FROM scores s
JOIN players p ON s.player_id = p.id
GROUP BY p.id
ORDER BY best_score ASC
LIMIT 10;

-- Insertion de quelques joueurs supplémentaires pour tester le Top 10

INSERT INTO players (name, best_score, games_played)
VALUES 
('Emma', 26, 9),
('Noah', 24, 5),
('Léa', 27, 4);

INSERT INTO scores (player_id, score, pairs_count, time_seconds)
VALUES 

(1, 22, 12, 95),
(1, 25, 10, 87),
(1, 24, 8, 72),
(1, 28, 6, 54),
(2, 35, 12, 140),
(2, 31, 8, 110),
(2, 28, 6, 80),
(3, 30, 8, 100),
(3, 33, 10, 120),
(4, 26, 6, 65),
(4, 29, 8, 82),
(4, 32, 12, 125),
(5, 24, 8, 90),
(5, 26, 10, 105),
(6, 27, 6, 75),
(6, 29, 8, 88),
(6, 34, 12, 130);

