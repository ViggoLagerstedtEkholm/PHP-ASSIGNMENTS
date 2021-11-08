-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Värd: 127.0.0.1
-- Tid vid skapande: 30 aug 2021 kl 04:18
-- Serverversion: 10.4.20-MariaDB
-- PHP-version: 8.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databas: `7.3`
--

-- --------------------------------------------------------

--
-- Tabellstruktur `feed`
--

CREATE TABLE `feed` (
  `ID` int(11) NOT NULL,
  `URL` varchar(300) NOT NULL,
  `displayCount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumpning av Data i tabell `feed`
--

INSERT INTO `feed` (`ID`, `URL`, `displayCount`) VALUES
(20, 'https://rss.art19.com/apology-line', 10),
(28, 'http://rssfeeds.usatoday.com/UsatodaycomNation-TopStories', 10),
(44, 'https://rss.art19.com/apology-line', 20),
(46, 'https://feeds.expressen.se/nyheter/', 5);

--
-- Index för dumpade tabeller
--

--
-- Index för tabell `feed`
--
ALTER TABLE `feed`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT för dumpade tabeller
--

--
-- AUTO_INCREMENT för tabell `feed`
--
ALTER TABLE `feed`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
