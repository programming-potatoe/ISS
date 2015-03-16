-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 16. Mrz 2015 um 11:44
-- Server Version: 5.6.21
-- PHP-Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `scoresystem`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `aufgaben`
--

CREATE TABLE IF NOT EXISTS `aufgaben` (
`AID` int(11) NOT NULL,
  `ANr` int(11) DEFAULT NULL,
  `AMaxPunkte` int(11) DEFAULT NULL,
  `SchemaID` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `aufgaben`
--

INSERT INTO `aufgaben` (`AID`, `ANr`, `AMaxPunkte`, `SchemaID`) VALUES
(31, 1, 10, 11),
(32, 2, 10, 11),
(33, 3, 10, 11),
(34, 4, 10, 11),
(35, 5, 5, 11),
(36, 1, 5, 12),
(37, 2, 5, 12),
(38, 3, 5, 12),
(39, 4, 5, 12),
(40, 5, 5, 12);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bewertungen`
--

CREATE TABLE IF NOT EXISTS `bewertungen` (
`BID` int(11) NOT NULL,
  `AID` int(11) NOT NULL,
  `PruefObjID` int(11) NOT NULL,
  `PID` int(11) NOT NULL,
  `BPunkte` int(11) DEFAULT NULL,
  `BBewertungsstufe` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `bewertungen`
--

INSERT INTO `bewertungen` (`BID`, `AID`, `PruefObjID`, `PID`, `BPunkte`, `BBewertungsstufe`) VALUES
(26, 31, 15, 1, 5, 2),
(27, 31, 15, 1, 5, 4),
(28, 32, 15, 1, 5, 0),
(29, 32, 15, 1, 2, 3),
(30, 32, 15, 1, 3, 4),
(31, 33, 15, 1, 10, 4),
(32, 34, 15, 1, 10, 4),
(33, 35, 15, 1, 5, 2),
(34, 31, 22, 1, 10, 4),
(35, 32, 22, 1, 10, 4),
(36, 33, 22, 1, 10, 4),
(37, 34, 22, 1, 10, 4),
(38, 35, 22, 1, 5, 4);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kurse`
--

CREATE TABLE IF NOT EXISTS `kurse` (
`KID` int(11) NOT NULL,
  `KBez` varchar(50) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `kurse`
--

INSERT INTO `kurse` (`KID`, `KBez`) VALUES
(5, 'TINF13IN'),
(6, 'TINF12IN');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pruefer`
--

CREATE TABLE IF NOT EXISTS `pruefer` (
`PID` int(11) NOT NULL,
  `PName` varchar(20) DEFAULT NULL,
  `PVName` varchar(20) DEFAULT NULL,
  `PPwd` varchar(32) DEFAULT NULL,
  `PEmail` varchar(30) DEFAULT NULL,
  `PArt` int(1) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `pruefer`
--

INSERT INTO `pruefer` (`PID`, `PName`, `PVName`, `PPwd`, `PEmail`, `PArt`) VALUES
(1, 'Richter', 'Alexander', 'hallo123', 'leiter@dhbw.de', 0),
(2, 'Rumpelszielzchen', 'Adolf', 'Blausaure23', 'blondi@obersalzberg.de', 1),
(3, 'Musterdozent', 'Max', 'Musterpasswort', 'musteremail@muster.de', 1),
(4, 'Externus', 'Pruefus', 'pruferus', 'pruefer@daimler.de', 2),
(5, 'Schneider', 'Halsab', 'passwort123', 'schneider@7aufeinenstreich@gri', 2);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pruefer_pruefungsleistungen`
--

CREATE TABLE IF NOT EXISTS `pruefer_pruefungsleistungen` (
  `PruefID` int(11) NOT NULL,
  `PID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `pruefer_pruefungsleistungen`
--

INSERT INTO `pruefer_pruefungsleistungen` (`PruefID`, `PID`) VALUES
(16, 1),
(16, 1),
(18, 1),
(16, 1),
(19, 1),
(18, 1),
(18, 3);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pruefling`
--

CREATE TABLE IF NOT EXISTS `pruefling` (
`PrID` int(11) NOT NULL,
  `PrName` varchar(20) DEFAULT NULL,
  `PrVName` varchar(20) DEFAULT NULL,
  `PrPwd` varchar(32) DEFAULT NULL,
  `PID` int(11) NOT NULL,
  `KID` int(11) NOT NULL,
  `PrEmail` varchar(20) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `pruefling`
--

INSERT INTO `pruefling` (`PrID`, `PrName`, `PrVName`, `PrPwd`, `PID`, `KID`, `PrEmail`) VALUES
(5, 'Schmidt', 'Mike', 'mike', 1, 5, 'mike@dhbw.de'),
(6, 'Steidl', 'Max', 'maximax', 1, 6, 'max@dhbw.de'),
(7, 'Dierolf', 'Nils', 'nilsinils', 1, 6, 'nils@dhbw.de'),
(8, 'Pocher', 'Oliver', 'oliolioli', 1, 5, 'oli@dhbw.de'),
(9, 'Schmidt', 'Andre', 'hallo', 1, 5, 'email.andre@web.de');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pruefungsleistungen`
--

CREATE TABLE IF NOT EXISTS `pruefungsleistungen` (
`PruefID` int(11) NOT NULL,
  `PID` int(11) NOT NULL,
  `VID` int(11) NOT NULL,
  `PruefBez` varchar(50) DEFAULT NULL,
  `SchemaID` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `pruefungsleistungen`
--

INSERT INTO `pruefungsleistungen` (`PruefID`, `PID`, `VID`, `PruefBez`, `SchemaID`) VALUES
(16, 1, 7, 'Klausur', 11),
(17, 1, 7, 'Klausur', 11),
(18, 1, 8, 'Klausur', 11),
(19, 1, 10, 'Klausur', 11),
(20, 1, 8, 'Klausur', 11),
(21, 1, 8, '', 12);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pruefungsleistungsobjekt`
--

CREATE TABLE IF NOT EXISTS `pruefungsleistungsobjekt` (
`PruefObjID` int(11) NOT NULL,
  `PrID` int(11) NOT NULL,
  `PruefID` int(11) NOT NULL,
  `PruefObjKommentar` varchar(200) DEFAULT NULL,
  `PruefStatus` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `pruefungsleistungsobjekt`
--

INSERT INTO `pruefungsleistungsobjekt` (`PruefObjID`, `PrID`, `PruefID`, `PruefObjKommentar`, `PruefStatus`) VALUES
(14, 6, 16, NULL, 1),
(15, 7, 16, 'Du warst toll!', 1),
(16, 6, 17, NULL, 1),
(17, 7, 17, NULL, 1),
(18, 5, 18, NULL, 1),
(19, 8, 18, NULL, 1),
(20, 5, 19, NULL, 1),
(21, 8, 19, NULL, 1),
(22, 9, 19, 'Hey Alter, End krass!', 1),
(23, 5, 20, NULL, 0),
(24, 8, 20, NULL, 0),
(25, 9, 20, NULL, 0),
(26, 5, 21, NULL, 0),
(27, 8, 21, NULL, 0),
(28, 9, 21, NULL, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pruefungsschema`
--

CREATE TABLE IF NOT EXISTS `pruefungsschema` (
`SchemaID` int(11) NOT NULL,
  `SchemaBez` varchar(50) DEFAULT NULL,
  `PruefGenauigkeit` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `pruefungsschema`
--

INSERT INTO `pruefungsschema` (`SchemaID`, `SchemaBez`, `PruefGenauigkeit`) VALUES
(11, NULL, 5),
(12, NULL, 5);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `vorlesungen`
--

CREATE TABLE IF NOT EXISTS `vorlesungen` (
`VID` int(11) NOT NULL,
  `KID` int(11) NOT NULL,
  `PID` int(11) NOT NULL,
  `VBez` varchar(50) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `vorlesungen`
--

INSERT INTO `vorlesungen` (`VID`, `KID`, `PID`, `VBez`) VALUES
(7, 6, 1, 'Mathematik'),
(8, 5, 1, 'Physik'),
(9, 6, 3, 'Rechnerarchitekturen'),
(10, 5, 1, 'Physik');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `aufgaben`
--
ALTER TABLE `aufgaben`
 ADD PRIMARY KEY (`AID`), ADD KEY `SchemaID` (`SchemaID`);

--
-- Indizes für die Tabelle `bewertungen`
--
ALTER TABLE `bewertungen`
 ADD PRIMARY KEY (`BID`), ADD KEY `AID` (`AID`), ADD KEY `PruefObjID` (`PruefObjID`), ADD KEY `PID` (`PID`);

--
-- Indizes für die Tabelle `kurse`
--
ALTER TABLE `kurse`
 ADD PRIMARY KEY (`KID`);

--
-- Indizes für die Tabelle `pruefer`
--
ALTER TABLE `pruefer`
 ADD PRIMARY KEY (`PID`);

--
-- Indizes für die Tabelle `pruefer_pruefungsleistungen`
--
ALTER TABLE `pruefer_pruefungsleistungen`
 ADD KEY `PruefID` (`PruefID`), ADD KEY `PID` (`PID`);

--
-- Indizes für die Tabelle `pruefling`
--
ALTER TABLE `pruefling`
 ADD PRIMARY KEY (`PrID`), ADD KEY `KID` (`KID`), ADD KEY `PID` (`PID`);

--
-- Indizes für die Tabelle `pruefungsleistungen`
--
ALTER TABLE `pruefungsleistungen`
 ADD PRIMARY KEY (`PruefID`), ADD KEY `PID` (`PID`), ADD KEY `VID` (`VID`);

--
-- Indizes für die Tabelle `pruefungsleistungsobjekt`
--
ALTER TABLE `pruefungsleistungsobjekt`
 ADD PRIMARY KEY (`PruefObjID`), ADD KEY `PrID` (`PrID`), ADD KEY `PruefID` (`PruefID`);

--
-- Indizes für die Tabelle `pruefungsschema`
--
ALTER TABLE `pruefungsschema`
 ADD PRIMARY KEY (`SchemaID`);

--
-- Indizes für die Tabelle `vorlesungen`
--
ALTER TABLE `vorlesungen`
 ADD PRIMARY KEY (`VID`), ADD KEY `KID` (`KID`), ADD KEY `PID` (`PID`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `aufgaben`
--
ALTER TABLE `aufgaben`
MODIFY `AID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=41;
--
-- AUTO_INCREMENT für Tabelle `bewertungen`
--
ALTER TABLE `bewertungen`
MODIFY `BID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT für Tabelle `kurse`
--
ALTER TABLE `kurse`
MODIFY `KID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT für Tabelle `pruefer`
--
ALTER TABLE `pruefer`
MODIFY `PID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT für Tabelle `pruefling`
--
ALTER TABLE `pruefling`
MODIFY `PrID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT für Tabelle `pruefungsleistungen`
--
ALTER TABLE `pruefungsleistungen`
MODIFY `PruefID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT für Tabelle `pruefungsleistungsobjekt`
--
ALTER TABLE `pruefungsleistungsobjekt`
MODIFY `PruefObjID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT für Tabelle `pruefungsschema`
--
ALTER TABLE `pruefungsschema`
MODIFY `SchemaID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT für Tabelle `vorlesungen`
--
ALTER TABLE `vorlesungen`
MODIFY `VID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `aufgaben`
--
ALTER TABLE `aufgaben`
ADD CONSTRAINT `aufgaben_ibfk_1` FOREIGN KEY (`SchemaID`) REFERENCES `pruefungsschema` (`SchemaID`);

--
-- Constraints der Tabelle `bewertungen`
--
ALTER TABLE `bewertungen`
ADD CONSTRAINT `bewertungen_ibfk_1` FOREIGN KEY (`AID`) REFERENCES `aufgaben` (`AID`),
ADD CONSTRAINT `bewertungen_ibfk_2` FOREIGN KEY (`PruefObjID`) REFERENCES `pruefungsleistungsobjekt` (`PruefObjID`),
ADD CONSTRAINT `bewertungen_ibfk_3` FOREIGN KEY (`PID`) REFERENCES `pruefer` (`PID`);

--
-- Constraints der Tabelle `pruefer_pruefungsleistungen`
--
ALTER TABLE `pruefer_pruefungsleistungen`
ADD CONSTRAINT `pruefer_pruefungsleistungen_ibfk_1` FOREIGN KEY (`PruefID`) REFERENCES `pruefungsleistungen` (`PruefID`),
ADD CONSTRAINT `pruefer_pruefungsleistungen_ibfk_2` FOREIGN KEY (`PID`) REFERENCES `pruefer` (`PID`);

--
-- Constraints der Tabelle `pruefling`
--
ALTER TABLE `pruefling`
ADD CONSTRAINT `pruefling_ibfk_1` FOREIGN KEY (`KID`) REFERENCES `kurse` (`KID`),
ADD CONSTRAINT `pruefling_ibfk_2` FOREIGN KEY (`PID`) REFERENCES `pruefer` (`PID`);

--
-- Constraints der Tabelle `pruefungsleistungen`
--
ALTER TABLE `pruefungsleistungen`
ADD CONSTRAINT `pruefungsleistungen_ibfk_1` FOREIGN KEY (`PID`) REFERENCES `pruefer` (`PID`),
ADD CONSTRAINT `pruefungsleistungen_ibfk_2` FOREIGN KEY (`VID`) REFERENCES `vorlesungen` (`VID`);

--
-- Constraints der Tabelle `pruefungsleistungsobjekt`
--
ALTER TABLE `pruefungsleistungsobjekt`
ADD CONSTRAINT `pruefungsleistungsobjekt_ibfk_1` FOREIGN KEY (`PrID`) REFERENCES `pruefling` (`PrID`),
ADD CONSTRAINT `pruefungsleistungsobjekt_ibfk_2` FOREIGN KEY (`PruefID`) REFERENCES `pruefungsleistungen` (`PruefID`);

--
-- Constraints der Tabelle `vorlesungen`
--
ALTER TABLE `vorlesungen`
ADD CONSTRAINT `vorlesungen_ibfk_1` FOREIGN KEY (`KID`) REFERENCES `kurse` (`KID`),
ADD CONSTRAINT `vorlesungen_ibfk_2` FOREIGN KEY (`PID`) REFERENCES `pruefer` (`PID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
