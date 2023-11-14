# homerseklet_kamra
- HASH OK
- SALT (automatic with password_hash())
- config.php /autoload beiktatása, config áthelyezése (jobb?)
- SQL Injection /Javítva pár elem
    - CHECK IF EMAIL IS TAKEN!!!!!!
- Cross Siting /Jobb?
- Users tábla megjelenítése 'Felhasználók' oldalon

https://www.youtube.com/watch?v=pIO0pmMTJ6Y 1:30:45

SQL--------
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2023. Nov 14. 23:02
-- Kiszolgáló verziója: 10.4.28-MariaDB
-- PHP verzió: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `homerseklet_kamra`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(30) NOT NULL DEFAULT 'error@error.hu',
  `password` varchar(60) NOT NULL DEFAULT 'password',
  `chamber_id` varchar(30) NOT NULL DEFAULT 'chamber',
  `role` varchar(50) DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `chamber_id`, `role`) VALUES
(1, 'szadaegyetem@gmail.com', '$2y$10$drKdFSY4EcYLd/M6YPpIFusp.U0QmrNenEt3xckVi721UDlFC/Jk2', 'ChamberTest01', 'admin'),
(2, 'test1@test.com', '$2y$10$o8cQOaKqkCFJTtmdWzMcCe8Q.nHqZUtN2YZ0hSlCCphNOQJbOQKlK', 'ChamberTest02', 'user'),
(3, 'test2@test.com', '$2y$10$hcUS76wgBjDBKJnCZrF.nObo/A6QTXAAM7U1/cJchCbW9wspZc7Kq', 'Chambertest03', 'user'),
(4, 'test3@test.com', '$2y$10$PgBULCo7vUhuLVwKl1w3AO1yssI6YyomN24/pbo3G0MIYtxHMKr4m', 'Chambertest04', 'user'),
(5, 'test4@test.com', '$2y$10$O6.flbnYeiwbHmsaGzGoj.awTgT1cMmknRAiWorMgcOav/qgNMyeq', 'Chambertest05', 'user'),
(6, 'test5@test.com', '$2y$10$JG62kVV5kPjJIgikDYmWt.rpurZvz8ghamkbfkDfOWNJPk/EjQpJa', 'ChamberTest06', 'admin'),
(7, 'test6@test.com', '$2y$10$VJjfiZgy0GscoVV6OMFCze/g4n1skITnJOuBU1rG7NZSN3fIXWkpe', 'ChamberTest07', 'admin'),
(8, 'test7@test.com', '$2y$10$FxBgUQnw1arz2W2nYoP7DeA1ZVbgbw.z1ywAdcR.1qsZY2jpkdox6', 'ChamberTest07', 'user'),
(9, 'testX@test.com', '$2y$10$uDaDEJFrkARDLB24LRQHZuA91j2OPA.5tHaorP7LSQcf1PKX7Z5FG', 'X01', 'user'),
(10, 'testXY@test.com', '$2y$10$3QzJ6NaxWF0L9BGCcef9HO4WoiDyXrMTgWAHZKORIMsohEHen15R.', 'Chambertest0300', 'user');

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
