-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 04. Mai 2015 um 11:24
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
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `aufgaben`
--

INSERT INTO `aufgaben` (`AID`, `ANr`, `AMaxPunkte`, `SchemaID`) VALUES
(49, 1, 10, 15),
(50, 2, 20, 15),
(51, 3, 10, 15),
(52, 4, 15, 15),
(53, 5, 5, 15),
(54, 1, 10, 16),
(55, 2, 10, 16),
(56, 3, 10, 16),
(57, 4, 10, 16),
(58, 5, 10, 16);

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

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `kurse`
--

CREATE TABLE IF NOT EXISTS `kurse` (
`KID` int(11) NOT NULL,
  `KBez` varchar(50) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `kurse`
--

INSERT INTO `kurse` (`KID`, `KBez`) VALUES
(5, 'TINF13IN'),
(6, 'TINF12IN'),
(7, 'TINF11IN');

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
(3, 'Vossen', 'Paulus', 'paulusvossen', 'vossen@dhbw.de', 1),
(4, 'Externus', 'Pruefus', 'pruferus', 'pruefer@daimler.de', 2),
(5, 'Schneider', 'GÃ¼nther', 'passwort123', 'schneider@gmx.net', 3);

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
(32, 5),
(33, 1),
(32, 3),
(34, 5);

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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `pruefling`
--

INSERT INTO `pruefling` (`PrID`, `PrName`, `PrVName`, `PrPwd`, `PID`, `KID`, `PrEmail`) VALUES
(1, 'Schmidt', 'Mike', 'mike', 1, 5, 'mike@dhbw.de'),
(2, 'Steidl', 'Max', 'maximax', 1, 5, 'max@dhbw.de'),
(3, 'Dierolf', 'Nils', 'nilsinils', 1, 5, 'nils@dhbw.de');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pruefungsleistungen`
--

CREATE TABLE IF NOT EXISTS `pruefungsleistungen` (
`PruefID` int(11) NOT NULL,
  `PID` int(11) NOT NULL,
  `VID` int(11) NOT NULL,
  `PruefBez` varchar(50) DEFAULT NULL,
  `SchemaID` int(11) NOT NULL,
  `Toleranz` float DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `pruefungsleistungen`
--

INSERT INTO `pruefungsleistungen` (`PruefID`, `PID`, `VID`, `PruefBez`, `SchemaID`, `Toleranz`) VALUES
(32, 1, 11, 'Programmentwurf', 15, 0.9),
(33, 1, 12, 'Klausur', 16, 0.3),
(34, 1, 13, 'Klausur', 15, 0.1);

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
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `pruefungsleistungsobjekt`
--

INSERT INTO `pruefungsleistungsobjekt` (`PruefObjID`, `PrID`, `PruefID`, `PruefObjKommentar`, `PruefStatus`) VALUES
(53, 1, 32, NULL, 0),
(54, 2, 32, NULL, 0),
(55, 3, 32, NULL, 0),
(56, 1, 33, NULL, 0),
(57, 2, 33, NULL, 0),
(58, 3, 33, NULL, 0),
(59, 1, 34, NULL, 0),
(60, 2, 34, NULL, 0),
(61, 3, 34, NULL, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pruefungsschema`
--

CREATE TABLE IF NOT EXISTS `pruefungsschema` (
`SchemaID` int(11) NOT NULL,
  `SchemaBez` varchar(50) DEFAULT NULL,
  `PruefGenauigkeit` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `pruefungsschema`
--

INSERT INTO `pruefungsschema` (`SchemaID`, `SchemaBez`, `PruefGenauigkeit`) VALUES
(15, 'Schma 1', 5),
(16, 'Schema 2', 3);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `vorlesungen`
--

CREATE TABLE IF NOT EXISTS `vorlesungen` (
`VID` int(11) NOT NULL,
  `KID` int(11) NOT NULL,
  `PID` int(11) NOT NULL,
  `VBez` varchar(50) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `vorlesungen`
--

INSERT INTO `vorlesungen` (`VID`, `KID`, `PID`, `VBez`) VALUES
(11, 5, 3, 'Softwareengineering'),
(12, 5, 1, 'Elektronik'),
(13, 5, 4, 'Mathematik');

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
 ADD PRIMARY KEY (`PruefID`, `PID`), ADD KEY `PruefID` (`PruefID`), ADD KEY `PID` (`PID`);

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
MODIFY `AID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=59;
--
-- AUTO_INCREMENT für Tabelle `bewertungen`
--
ALTER TABLE `bewertungen`
MODIFY `BID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT für Tabelle `kurse`
--
ALTER TABLE `kurse`
MODIFY `KID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT für Tabelle `pruefer`
--
ALTER TABLE `pruefer`
MODIFY `PID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT für Tabelle `pruefling`
--
ALTER TABLE `pruefling`
MODIFY `PrID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT für Tabelle `pruefungsleistungen`
--
ALTER TABLE `pruefungsleistungen`
MODIFY `PruefID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=35;
--
-- AUTO_INCREMENT für Tabelle `pruefungsleistungsobjekt`
--
ALTER TABLE `pruefungsleistungsobjekt`
MODIFY `PruefObjID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=62;
--
-- AUTO_INCREMENT für Tabelle `pruefungsschema`
--
ALTER TABLE `pruefungsschema`
MODIFY `SchemaID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT für Tabelle `vorlesungen`
--
ALTER TABLE `vorlesungen`
MODIFY `VID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `aufgaben`
--
ALTER TABLE `aufgaben`
ADD CONSTRAINT `aufgaben_ibfk_1` FOREIGN KEY (`SchemaID`) REFERENCES `pruefungsschema` (`SchemaID`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `bewertungen`
--
ALTER TABLE `bewertungen`
ADD CONSTRAINT `bewertungen_ibfk_1` FOREIGN KEY (`AID`) REFERENCES `aufgaben` (`AID`) ON DELETE CASCADE,
ADD CONSTRAINT `bewertungen_ibfk_2` FOREIGN KEY (`PruefObjID`) REFERENCES `pruefungsleistungsobjekt` (`PruefObjID`) ON DELETE CASCADE,
ADD CONSTRAINT `bewertungen_ibfk_3` FOREIGN KEY (`PID`) REFERENCES `pruefer` (`PID`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `pruefer_pruefungsleistungen`
--
ALTER TABLE `pruefer_pruefungsleistungen`
ADD CONSTRAINT `pruefer_pruefungsleistungen_ibfk_1` FOREIGN KEY (`PruefID`) REFERENCES `pruefungsleistungen` (`PruefID`) ON DELETE CASCADE,
ADD CONSTRAINT `pruefer_pruefungsleistungen_ibfk_2` FOREIGN KEY (`PID`) REFERENCES `pruefer` (`PID`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `pruefling`
--
ALTER TABLE `pruefling`
ADD CONSTRAINT `pruefling_ibfk_1` FOREIGN KEY (`KID`) REFERENCES `kurse` (`KID`) ON DELETE CASCADE,
ADD CONSTRAINT `pruefling_ibfk_2` FOREIGN KEY (`PID`) REFERENCES `pruefer` (`PID`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `pruefungsleistungen`
--
ALTER TABLE `pruefungsleistungen`
ADD CONSTRAINT `pruefungsleistungen_ibfk_1` FOREIGN KEY (`PID`) REFERENCES `pruefer` (`PID`) ON DELETE CASCADE,
ADD CONSTRAINT `pruefungsleistungen_ibfk_2` FOREIGN KEY (`VID`) REFERENCES `vorlesungen` (`VID`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `pruefungsleistungsobjekt`
--
ALTER TABLE `pruefungsleistungsobjekt`
ADD CONSTRAINT `pruefungsleistungsobjekt_ibfk_1` FOREIGN KEY (`PrID`) REFERENCES `pruefling` (`PrID`) ON DELETE CASCADE,
ADD CONSTRAINT `pruefungsleistungsobjekt_ibfk_2` FOREIGN KEY (`PruefID`) REFERENCES `pruefungsleistungen` (`PruefID`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `vorlesungen`
--
ALTER TABLE `vorlesungen`
ADD CONSTRAINT `vorlesungen_ibfk_1` FOREIGN KEY (`KID`) REFERENCES `kurse` (`KID`) ON DELETE CASCADE,
ADD CONSTRAINT `vorlesungen_ibfk_2` FOREIGN KEY (`PID`) REFERENCES `pruefer` (`PID`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
