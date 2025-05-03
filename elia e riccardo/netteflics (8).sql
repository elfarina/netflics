-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Feb 03, 2025 alle 20:51
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `netteflics`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `cast`
--

CREATE TABLE `cast` (
  `id` int(11) NOT NULL,
  `name` varchar(190) NOT NULL,
  `role` enum('actor','director','writer') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `cast`
--

INSERT INTO `cast` (`id`, `name`, `role`) VALUES
(43, 'Christopher Nolan', 'director'),
(44, 'Matthew McConaughey', 'actor'),
(45, 'Anne Hathaway', 'actor'),
(46, 'Jessica Chastain', 'actor'),
(47, 'Michael Caine', 'actor'),
(48, 'Mackenzie Foy', 'actor'),
(49, 'Topher Grace', 'actor'),
(50, 'Casey Affleck', 'actor'),
(51, 'John Lithgow', 'actor'),
(52, 'David Fincher', 'director'),
(53, 'Edward Norton', 'actor'),
(54, 'Brad Pitt', 'actor'),
(55, 'Helena Bonham Carter', 'actor'),
(56, 'Meat Loaf', 'actor'),
(57, 'Jared Leto', 'actor'),
(58, 'Zach Grenier', 'actor'),
(59, 'Holt McCallany', 'actor'),
(60, 'Eion Bailey', 'actor'),
(61, 'John David Washington', 'actor'),
(62, 'Robert Pattinson', 'actor'),
(63, 'Elizabeth Debicki', 'actor'),
(64, 'Kenneth Branagh', 'actor'),
(65, 'Dimple Kapadia', 'actor'),
(66, 'Himesh Patel', 'actor'),
(67, 'Clémence Poésy', 'actor'),
(68, 'Rami Malek', 'actor'),
(69, 'Christian Slater', 'actor'),
(70, 'Carly Chaikin', 'actor'),
(71, 'Portia Doubleday', 'actor'),
(72, 'Sam Esmail', 'director'),
(74, 'Martin Scorsese', 'director'),
(75, 'Leonardo DiCaprio', 'actor'),
(76, 'Margot Robbie', 'actor');

-- --------------------------------------------------------

--
-- Struttura della tabella `content`
--

CREATE TABLE `content` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `release_year` year(4) DEFAULT NULL,
  `type` enum('movie','series') NOT NULL,
  `poster_url` varchar(255) DEFAULT NULL,
  `video_path` varchar(100) DEFAULT NULL,
  `rating` float DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `content`
--

INSERT INTO `content` (`id`, `title`, `description`, `release_year`, `type`, `poster_url`, `video_path`, `rating`) VALUES
(1, 'Interstellar', 'In un futuro non precisato, un drastico cambiamento climatico colpisce duramente l\'agricoltura. Il granturco è l\'unica coltivazione ancora in grado di crescere ed un gruppo di scienziati è intenzionato ad attraversare lo spazio per trovare nuovi luoghi adatti a coltivarlo.', '2014', 'movie', 'images/posters/', '../movies/interstellar.mp4', 4.7),
(2, 'Fight Club', 'Tyler Durden ed un nuovo amico sfogano la loro aggressività creando un club di combattimento, che assume rapidamente connotati rivoluzionari, fino a esporre la vera identità di Tyler Durden.', '1999', 'movie', 'images/posters/', '../movies/fight club.mp4', 4.4),
(3, 'The Wolf of Wall Street', 'New York, anni 80. Eccessi e corruzione segnano la curva discendente della brillante carriera di Jordan Belfort, un ambizioso broker in grado di guadagnare migliaia di dollari al minuto e di spenderne altrettanti in droga e futilità.', '2014', 'movie', 'images/posters/', '../movies/The Wolf Of Wall Street.mp4', 4),
(4, 'Tenet', 'Un agente segreto riceve una sola parola come arma e viene inviato per prevenire l\'inizio della Terza Guerra Mondiale. Deve viaggiare nel tempo e piegare le leggi della natura per avere successo nella sua missione.', '2020', 'movie', 'images/posters/', '../movies/tenet.mp4', 3.7),
(5, 'Mr Robot', 'Un giovane ingegnere informatico lavora come esperto di sicurezza di giorno e come hacker vigilante di notte. Viene reclutato per distruggere una delle più grandi corporazioni al mondo.', '2015', 'series', 'images/posters/', '../episodes/mr robot/Hello Friend[s1e1].mp4', 4.8);

-- --------------------------------------------------------

--
-- Struttura della tabella `content_cast`
--

CREATE TABLE `content_cast` (
  `content_id` int(11) NOT NULL,
  `cast_id` int(11) NOT NULL,
  `role` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `content_cast`
--

INSERT INTO `content_cast` (`content_id`, `cast_id`, `role`) VALUES
(1, 43, 'director'),
(1, 44, 'Cooper'),
(1, 45, 'Amelia Brand'),
(1, 46, 'Murph'),
(1, 47, 'Professor Brand'),
(1, 48, 'Young Murph'),
(1, 49, 'Getty'),
(1, 50, 'Tom Cooper'),
(1, 51, 'Donald Cooper'),
(2, 52, 'director'),
(2, 53, 'Narrator'),
(2, 54, 'Tyler Durden'),
(2, 55, 'Marla Singer'),
(2, 56, 'Robert Paulson'),
(2, 57, 'Angel Face'),
(2, 58, 'Richard Chesler'),
(2, 59, 'The Mechanic'),
(2, 60, 'Ricky'),
(3, 74, 'Director'),
(3, 75, 'Jordan Belfort'),
(3, 76, 'Naomi Lapaglia'),
(4, 43, 'director'),
(4, 61, 'The Protagonist'),
(4, 62, 'Neil'),
(4, 63, 'Kat'),
(4, 64, 'Andrei Sator'),
(4, 65, 'Priya Singh'),
(4, 66, 'Mahir'),
(4, 67, 'Barbara'),
(5, 68, 'Elliot Alderson'),
(5, 69, 'Mr. Robot'),
(5, 70, 'Darlene Alderson'),
(5, 71, 'Angela Moss'),
(5, 72, 'director');

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `content_cast_series_view`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `content_cast_series_view` (
`content_id` int(11)
,`content_title` varchar(255)
,`content_description` text
,`content_url` varchar(100)
,`content_release_year` year(4)
,`content_type` enum('movie','series')
,`content_poster_url` varchar(255)
,`cast_id` int(11)
,`cast_name` varchar(190)
,`cast_role` enum('actor','director','writer')
,`series_seasons_count` int(11)
);

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `content_cast_view`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `content_cast_view` (
`content_id` int(11)
,`content_title` varchar(255)
,`content_description` text
,`content_release_year` year(4)
,`content_type` enum('movie','series')
,`content_poster_url` varchar(255)
,`cast_id` int(11)
,`cast_name` varchar(190)
,`cast_role` enum('actor','director','writer')
);

-- --------------------------------------------------------

--
-- Struttura della tabella `content_genres`
--

CREATE TABLE `content_genres` (
  `content_id` int(11) NOT NULL,
  `genre_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `content_genres`
--

INSERT INTO `content_genres` (`content_id`, `genre_id`) VALUES
(1, 1),
(1, 4),
(1, 6),
(1, 8),
(1, 10),
(1, 66),
(1, 68),
(2, 4),
(2, 11),
(2, 21),
(2, 26),
(2, 27),
(2, 30),
(3, 3),
(3, 4),
(3, 11),
(3, 13),
(3, 64),
(4, 6),
(4, 8),
(4, 10),
(4, 41),
(4, 60),
(4, 66),
(4, 241),
(5, 4),
(5, 6),
(5, 21);

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `content_series_view`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `content_series_view` (
`content_id` int(11)
,`content_title` varchar(255)
,`content_description` text
,`content_release_year` year(4)
,`content_type` enum('movie','series')
,`content_poster_url` varchar(255)
,`content_url` varchar(100)
,`content_rating` float
,`series_seasons_count` int(11)
);

-- --------------------------------------------------------

--
-- Struttura della tabella `episodes`
--

CREATE TABLE `episodes` (
  `id` int(11) NOT NULL,
  `series_id` int(11) NOT NULL,
  `season_number` int(11) NOT NULL,
  `episode_number` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `duration` int(11) NOT NULL,
  `release_date` date DEFAULT NULL,
  `video_url` varchar(255) NOT NULL,
  `next_episode_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `episodes`
--

INSERT INTO `episodes` (`id`, `series_id`, `season_number`, `episode_number`, `title`, `description`, `duration`, `release_date`, `video_url`, `next_episode_id`) VALUES
(31, 5, 1, 1, 'Hello Friend', 'Elliot, un giovane ingegnere informatico, viene contattato da un misterioso gruppo di hacker.', 60, '2015-06-24', '../episodes/mr robot/Hello Friend[s1e1].mp4', 32),
(32, 5, 1, 2, 'Ones and Zeroes', 'Elliot affronta le conseguenze della sua decisione e fa un passo importante.', 60, '2015-07-01', '../episodes/mr robot/Ones and Zeroes[s1e2].mp4', 33),
(33, 5, 1, 3, 'Debug', 'Elliot inizia a lavorare con la Fsociety e scopre la portata del loro piano.', 60, '2015-07-08', '../episodes/mr robot/Debug[s1e3].mp4', 34),
(34, 5, 1, 4, 'Daemons', 'Elliot affronta i suoi demoni interiori durante un attacco informatico.', 60, '2015-07-15', '', 35),
(35, 5, 1, 5, 'Exploits', 'Il team tenta di infiltrarsi nella Steel Mountain.', 60, '2015-07-22', '', 36),
(36, 5, 1, 6, 'Brave Traveler', 'Elliot affronta un dilemma morale riguardante Vera.', 60, '2015-07-29', '', 37),
(37, 5, 1, 7, 'View Source', 'Elliot scopre una verità sconvolgente su Mr. Robot.', 60, '2015-08-05', '', 38),
(38, 5, 1, 8, 'White Rose', 'Elliot incontra White Rose e capisce l\'entità del piano.', 60, '2015-08-12', '', 39),
(39, 5, 1, 9, 'Mirroring', 'Le tensioni tra i membri della Fsociety raggiungono il culmine.', 60, '2015-08-19', '', 40),
(40, 5, 1, 10, 'Zero Day', 'Elliot porta a termine il piano della Fsociety.', 60, '2015-08-26', '../episodes/mr robot/Zero Day[s1e10].mp4', 41),
(41, 5, 2, 1, 'Unmasking', 'Unmasking the truth about Elliot’s reality.', 60, '2016-07-13', '../episodes/mr robot/Unmasking[s2e1].mp4', 42),
(42, 5, 2, 2, 'The Price of Freedom', 'Elliot struggles with his sense of freedom and control.', 60, '2016-07-20', '', 43),
(43, 5, 2, 3, 'The Fight for Power', 'Difficult choices are made as power dynamics shift.', 60, '2016-07-27', '', 44),
(44, 5, 2, 4, 'Shadows of Betrayal', 'Betrayal emerges from unexpected sources.', 60, '2016-08-03', '', 45),
(45, 5, 2, 5, 'A World in Chaos', 'The world spirals out of control, leaving Elliot in turmoil.', 60, '2016-08-10', '', 46),
(46, 5, 2, 6, 'Revelations', 'Revelations that change everything for Elliot and his allies.', 60, '2016-08-17', '', 47),
(47, 5, 2, 7, 'Crisis and Control', 'Elliot confronts a critical moment of crisis.', 60, '2016-08-24', '', 48),
(48, 5, 2, 8, 'Escape and Confrontation', 'A moment of escape turns into a confrontation with enemies.', 60, '2016-08-31', '', 49),
(49, 5, 2, 9, 'The Battle Within', 'The battle for control of Elliot’s mind intensifies.', 60, '2016-09-07', '', 50),
(50, 5, 2, 10, 'Freedom or Submission', 'The final decision between freedom and submission.', 60, '2016-09-14', '', 51),
(51, 5, 3, 1, 'New Beginnings', 'Una nuova stagione inizia con Elliot che deve affrontare le sue nuove sfide.', 50, '2017-10-11', '', 52),
(52, 5, 3, 2, 'Mind Games', 'Elliot entra in una guerra mentale con nuove forze oscure.', 50, '2017-10-18', '', 53),
(53, 5, 3, 3, 'Revelation and Deceit', 'Una grande rivelazione scuote il mondo di Elliot e dei suoi alleati.', 50, '2017-10-25', '', 54),
(54, 5, 3, 4, 'Behind Closed Doors', 'Le intenzioni di alcuni protagonisti vengono finalmente svelate.', 50, '2017-11-01', '', 55),
(55, 5, 3, 5, 'Terror and Action', 'Un atto di violenza cambia il corso degli eventi in modo irreversibile.', 50, '2017-11-08', '', 56),
(56, 5, 3, 6, 'Unseen Forces', 'Una mano invisibile sembra orchestrare eventi drammatici.', 50, '2017-11-15', '', 57),
(57, 5, 3, 7, 'Hidden Secrets', 'Elliot scopre segreti sconvolgenti che lo costringono a scegliere da che parte stare.', 50, '2017-11-22', '', 58),
(58, 5, 3, 8, 'Destruction and Rebirth', 'Mentre tutto crolla, un possibile nuovo inizio si fa strada tra le rovine.', 50, '2017-11-29', '', 59),
(59, 5, 3, 9, 'Dark Revelations', 'Un colpo di scena cambia il destino di tutti i protagonisti.', 50, '2017-12-06', '', 60),
(60, 5, 3, 10, 'The Final Choice', 'Una decisione finale porta alla conclusione di molte trame.', 50, '2017-12-13', '', 61),
(61, 5, 4, 1, 'Endings Begin', 'La stagione finale inizia con Elliot che affronta le sue scelte cruciali.', 50, '2019-10-06', '', 62),
(62, 5, 4, 2, 'The Path Forward', 'Il cammino verso la verità diventa più difficile, con Elliot in lotta con se stesso.', 50, '2019-10-13', '', 63),
(63, 5, 4, 3, 'Alliances Form', 'Vecchie alleanze vengono messe alla prova mentre nuovi legami si formano.', 50, '2019-10-20', '', 64),
(64, 5, 4, 4, 'Deceptions Unfold', 'Gli inganni vengono rivelati e le bugie del passato cominciano a venire alla luce.', 50, '2019-10-27', '', 65),
(65, 5, 4, 5, 'The Tipping Point', 'Un punto di non ritorno si raggiunge, mentre il mondo intorno a Elliot crolla.', 50, '2019-11-03', '', 66),
(66, 5, 4, 6, 'A Broken System', 'Le fondamenta del sistema crollano, lasciando Elliot a fare scelte difficili.', 50, '2019-11-10', '', 67),
(67, 5, 4, 7, 'The Escape', 'Elliot cerca di fuggire dalla sua stessa realtà mentre la pressione aumenta.', 50, '2019-11-17', '', 68),
(68, 5, 4, 8, 'Fate Decides', 'Fate e destini si intrecciano mentre Elliot affronta il suo destino finale.', 50, '2019-11-24', '', 69),
(69, 5, 4, 9, 'The Last Stand', 'Un ultimo scontro decisivo si avvicina, con tutti pronti a dare il massimo.', 50, '2019-12-01', '', 70),
(70, 5, 4, 10, 'A New Dawn', 'La fine della saga di Elliot e l’inizio di una nuova era.', 50, '2019-12-08', '', NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `genres`
--

CREATE TABLE `genres` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `genres`
--

INSERT INTO `genres` (`id`, `name`) VALUES
(241, 'Action'),
(1, 'Adventure'),
(69, 'Adventure comedy'),
(36, 'Aliens'),
(2, 'Animation'),
(55, 'Anime'),
(33, 'Apocalyptic'),
(29, 'Art'),
(13, 'Biographical'),
(3, 'Comedy'),
(67, 'Comedy horror'),
(45, 'Comic'),
(58, 'Coming-of-age'),
(62, 'Conspiracy'),
(11, 'Crime'),
(59, 'Crime-drama'),
(27, 'Cult'),
(38, 'Cyberpunk'),
(64, 'Dark comedy'),
(15, 'Documentary'),
(4, 'Drama'),
(48, 'Dystopia'),
(31, 'Erotic'),
(23, 'Esoteric'),
(60, 'Espionage'),
(24, 'Experimental'),
(16, 'Family'),
(47, 'Fantastic'),
(9, 'Fantasy'),
(44, 'Found footage'),
(71, 'Gothic'),
(49, 'High fantasy'),
(12, 'Historical'),
(41, 'Historical fiction'),
(5, 'Horror'),
(26, 'Independent'),
(72, 'LGBTQ+'),
(50, 'Low fantasy'),
(42, 'Medieval'),
(54, 'Mockumentary'),
(46, 'Mocumentary'),
(70, 'Mondo'),
(14, 'Musical'),
(53, 'Musical-drama'),
(10, 'Mystery'),
(40, 'Mystery-thriller'),
(18, 'Noir'),
(56, 'Paranormal'),
(22, 'Parody'),
(65, 'Period drama'),
(51, 'Post-apocalyptic'),
(61, 'Psychedelic'),
(21, 'Psychological'),
(30, 'Pulp'),
(7, 'Romance'),
(57, 'Romantic comedy'),
(32, 'Romantic-drama'),
(28, 'Satire'),
(8, 'Sci-Fi'),
(52, 'Slasher'),
(68, 'Space opera'),
(20, 'Sports'),
(39, 'Steampunk'),
(37, 'Superheroes'),
(25, 'Surrealist'),
(63, 'Survival'),
(43, 'Theatrical'),
(6, 'Thriller'),
(66, 'Time travel'),
(35, 'Vampires'),
(19, 'War'),
(17, 'Western'),
(34, 'Zombie');

-- --------------------------------------------------------

--
-- Struttura della tabella `series`
--

CREATE TABLE `series` (
  `id` int(11) NOT NULL,
  `seasons_count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `series`
--

INSERT INTO `series` (`id`, `seasons_count`) VALUES
(5, 4);

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

CREATE TABLE `users` (
  `id` int(4) NOT NULL,
  `nome` varchar(60) NOT NULL,
  `cognome` varchar(60) NOT NULL,
  `em` varchar(60) NOT NULL,
  `username` varchar(60) DEFAULT NULL,
  `pw` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `users`
--

INSERT INTO `users` (`id`, `nome`, `cognome`, `em`, `username`, `pw`) VALUES
(7, 'Elia', 'Farina', 'eliafarina@gmail.com', 'elia.farina', '$2y$10$xAxAv8inKWcIazxSfkTcuu8YB4FL/xw/Zt08HiYilPVW6ttu5qaVe'),
(8, 'Mario', 'Rossi', 'mariorossi@gmail.com', 'mario.rossi', '$2y$10$YotVUOysEGtTLkzyiGrEWuliLOqiKvabRFnA4DuEZ8fRIEEW9tKeS');

--
-- Trigger `users`
--
DELIMITER $$
CREATE TRIGGER `generate_username` BEFORE INSERT ON `users` FOR EACH ROW BEGIN
    DECLARE base_username VARCHAR(255);
    DECLARE count INT;
    DECLARE new_username VARCHAR(255);

    -- Genera il nome base in formato lowercase
    SET base_username = LOWER(CONCAT(NEW.nome, '.', NEW.cognome));

    -- Controlla se esistono già utenti con lo stesso base_username
    SET count = (SELECT COUNT(*) FROM users WHERE username LIKE CONCAT(base_username, '%'));

    -- Genera l'username finale
    IF count = 0 THEN
        -- Nessun conflitto, utilizza il base_username
        SET NEW.username = base_username;
    ELSE
        -- Conflitto: aggiungi il numero incrementale
        SET NEW.username = CONCAT(base_username, count + 1);
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura per vista `content_cast_series_view`
--
DROP TABLE IF EXISTS `content_cast_series_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `content_cast_series_view`  AS SELECT `ccv`.`content_id` AS `content_id`, `ccv`.`content_title` AS `content_title`, `ccv`.`content_description` AS `content_description`, `csv`.`content_url` AS `content_url`, `ccv`.`content_release_year` AS `content_release_year`, `ccv`.`content_type` AS `content_type`, `ccv`.`content_poster_url` AS `content_poster_url`, `ccv`.`cast_id` AS `cast_id`, `ccv`.`cast_name` AS `cast_name`, `ccv`.`cast_role` AS `cast_role`, `csv`.`series_seasons_count` AS `series_seasons_count` FROM (`content_cast_view` `ccv` left join `content_series_view` `csv` on(`ccv`.`content_id` = `csv`.`content_id`)) ;

-- --------------------------------------------------------

--
-- Struttura per vista `content_cast_view`
--
DROP TABLE IF EXISTS `content_cast_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `content_cast_view`  AS SELECT `c`.`id` AS `content_id`, `c`.`title` AS `content_title`, `c`.`description` AS `content_description`, `c`.`release_year` AS `content_release_year`, `c`.`type` AS `content_type`, `c`.`poster_url` AS `content_poster_url`, `ca`.`id` AS `cast_id`, `ca`.`name` AS `cast_name`, `ca`.`role` AS `cast_role` FROM ((`content` `c` join `content_cast` `cc` on(`c`.`id` = `cc`.`content_id`)) join `cast` `ca` on(`cc`.`cast_id` = `ca`.`id`)) ;

-- --------------------------------------------------------

--
-- Struttura per vista `content_series_view`
--
DROP TABLE IF EXISTS `content_series_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `content_series_view`  AS SELECT `c`.`id` AS `content_id`, `c`.`title` AS `content_title`, `c`.`description` AS `content_description`, `c`.`release_year` AS `content_release_year`, `c`.`type` AS `content_type`, `c`.`poster_url` AS `content_poster_url`, `c`.`video_path` AS `content_url`, `c`.`rating` AS `content_rating`, `s`.`seasons_count` AS `series_seasons_count` FROM (`content` `c` left join `series` `s` on(`c`.`id` = `s`.`id` and `c`.`type` = 'series')) ;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `cast`
--
ALTER TABLE `cast`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indici per le tabelle `content`
--
ALTER TABLE `content`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `content_cast`
--
ALTER TABLE `content_cast`
  ADD PRIMARY KEY (`content_id`,`cast_id`),
  ADD KEY `fk_content_cast_cast` (`cast_id`);

--
-- Indici per le tabelle `content_genres`
--
ALTER TABLE `content_genres`
  ADD PRIMARY KEY (`content_id`,`genre_id`),
  ADD KEY `fk_content_genres_genres` (`genre_id`);

--
-- Indici per le tabelle `episodes`
--
ALTER TABLE `episodes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `series_id` (`series_id`,`season_number`,`episode_number`),
  ADD KEY `fk_episodes_next_episode` (`next_episode_id`);

--
-- Indici per le tabelle `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indici per le tabelle `series`
--
ALTER TABLE `series`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `cast`
--
ALTER TABLE `cast`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT per la tabella `content`
--
ALTER TABLE `content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT per la tabella `episodes`
--
ALTER TABLE `episodes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT per la tabella `genres`
--
ALTER TABLE `genres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=242;

--
-- AUTO_INCREMENT per la tabella `users`
--
ALTER TABLE `users`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `content_cast`
--
ALTER TABLE `content_cast`
  ADD CONSTRAINT `fk_content_cast_cast` FOREIGN KEY (`cast_id`) REFERENCES `cast` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_content_cast_content` FOREIGN KEY (`content_id`) REFERENCES `content` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `content_genres`
--
ALTER TABLE `content_genres`
  ADD CONSTRAINT `fk_content_genres_content` FOREIGN KEY (`content_id`) REFERENCES `content` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_content_genres_genres` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `episodes`
--
ALTER TABLE `episodes`
  ADD CONSTRAINT `fk_episodes_next_episode` FOREIGN KEY (`next_episode_id`) REFERENCES `episodes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_episodes_series` FOREIGN KEY (`series_id`) REFERENCES `series` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `series`
--
ALTER TABLE `series`
  ADD CONSTRAINT `fk_series_content` FOREIGN KEY (`id`) REFERENCES `content` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
