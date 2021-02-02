--
-- Tabellenstruktur für Tabelle `uw3ro_wartung`
--

CREATE TABLE IF NOT EXISTS `#__wartung` (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Wartungs ID',
    `m_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Maschinenzuordnung',
    `catid` int(11) NOT NULL DEFAULT 0 COMMENT 'Kategoriezuordnung',
    `title` varchar(250) NOT NULL DEFAULT '',
    `alias` varchar(255) NOT NULL,
    `description` text NOT NULL,
    `state` tinyint(1) NOT NULL DEFAULT 0,
    `checked_out` int(11) UNSIGNED NOT NULL DEFAULT 0,
    `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `ordering` int(11) NOT NULL DEFAULT 0,
    `access` int(11) NOT NULL DEFAULT 1,
    `params` text DEFAULT NULL,
    `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_by` int(10) UNSIGNED NOT NULL DEFAULT 0,
    `created_by_alias` varchar(255) NOT NULL,
    `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `modified_by` int(10) UNSIGNED NOT NULL DEFAULT 0,
    `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `version` int(10) UNSIGNED NOT NULL DEFAULT 1,
    `intervallart` int(11) NOT NULL DEFAULT -1 COMMENT 'Intervallzuordnung',
    `intervall` varchar(10) NOT NULL DEFAULT '-1',
    `warnung_bei` varchar(10) NOT NULL DEFAULT '-1',
    `alarm_bei` varchar(10) NOT NULL DEFAULT '-1',
    `grundlage` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Grundlagenzuordnung',
    `verantwortlich_1` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Zuordnung zu einem User',
    `verantwortlich_2` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Zuordnung zu einem User',
    `qualifikation` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Qualifikationszuordnung',
    `pruefung_durch_1` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Zuordnung zu einem Kontakt',
    `pruefung_durch_2` int(11) UNSIGNED NOT NULL COMMENT 'Zuordnung zu einem Kontakt',
    `aktennr` varchar(255) NOT NULL DEFAULT '-1' COMMENT 'Die Aktennummer der originalen Unterlagen',
    `terminiert` int(2) NOT NULL DEFAULT 0,
    `terminiert_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `terminiert_von` int(11) DEFAULT NULL,
    `terminiert_detail` text DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `cid` (`catid`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci COMMENT='Wartungstabelle';

--
-- Tabellenstruktur für Tabelle `uw3ro_wartung_done`
--

CREATE TABLE IF NOT EXISTS `#__wartung_done` (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `wid` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Zuordnung zu Wartungseintrag',
    `title` varchar(250) DEFAULT NULL,
    `alias` varchar(255) DEFAULT NULL,
    `state` tinyint(1) NOT NULL DEFAULT 1,
    `letzte_wartung_0` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Letzte Wartun Betriebsstunden',
    `letzte_wartung_1` datetime DEFAULT NULL COMMENT 'Letzte Wartung Datum',
    `next_wartung_0` int(11) DEFAULT NULL,
    `next_wartung_1` datetime DEFAULT NULL,
    `warnung_bei_0` int(11) NOT NULL,
    `warnung_bei_1` datetime NOT NULL,
    `alarm_bei_0` int(11) NOT NULL,
    `alarm_bei_1` datetime NOT NULL,
    `description` text NOT NULL,
    `created_by` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Zuordnung zu User',
    `created` datetime DEFAULT NULL,
    `checked_out` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Zuordnugn zu User',
    `checked_out_time` datetime DEFAULT NULL,
    `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `modified_by` int(10) UNSIGNED NOT NULL DEFAULT 0,
    `ordering` int(11) UNSIGNED NOT NULL DEFAULT 0,
    `access` int(11) UNSIGNED NOT NULL DEFAULT 1,
    `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `params` text DEFAULT NULL,
    `version` int(10) UNSIGNED NOT NULL DEFAULT 1,
    PRIMARY KEY (`id`),
    KEY `mid` (`wid`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

--
-- Tabellenstruktur für Tabelle `uw3ro_wartung_grundlage`
--

CREATE TABLE IF NOT EXISTS `#__wartung_grundlage` (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` tinytext DEFAULT NULL,
    `bezeichnung` mediumtext DEFAULT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

--
-- Tabellenstruktur für Tabelle `uw3ro_wartung_mangel`
--

CREATE TABLE IF NOT EXISTS `#__wartung_mangel` (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `masch_id` int(11) UNSIGNED NOT NULL COMMENT 'Beziehung zu wartung_maschine',
    `prio` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
    `bezeichnung` varchar(50) DEFAULT NULL,
    `beschreibung` mediumtext DEFAULT NULL,
    `erstellt` int(11) UNSIGNED DEFAULT NULL COMMENT 'Beziehung zu Users',
    `erstellt_time` datetime DEFAULT '0000-00-00 00:00:00',
    `gemeldet_von` int(11) UNSIGNED DEFAULT NULL COMMENT 'Beziehung zu Users',
    `checked_out` int(11) UNSIGNED DEFAULT NULL COMMENT 'Beziehung zu Users',
    `checked_out_time` datetime DEFAULT '0000-00-00 00:00:00',
    `published` tinyint(1) UNSIGNED DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `masch_id` (`masch_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

--
-- Tabellenstruktur für Tabelle `uw3ro_wartung_maschbs`
--

CREATE TABLE IF NOT EXISTS `#__wartung_maschbs` (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `wid` int(11) UNSIGNED NOT NULL,
    `datum` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `bs_aktuell` int(10) UNSIGNED NOT NULL,
    `title` varchar(250) DEFAULT NULL,
    `alias` varchar(255) DEFAULT NULL,
    `state` tinyint(1) NOT NULL DEFAULT 1,
    `created_by` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Zuordnung zu User',
    `created` datetime DEFAULT NULL,
    `checked_out` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Zuordnugn zu User',
    `checked_out_time` datetime DEFAULT NULL,
    `access` int(11) UNSIGNED NOT NULL DEFAULT 1,
    `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `params` text DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `wartung_id` (`wid`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

--
-- Tabellenstruktur für Tabelle `uw3ro_wartung_maschine`
--

CREATE TABLE IF NOT EXISTS `#__wartung_maschine` (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `catid` int(11) UNSIGNED DEFAULT NULL COMMENT 'Kategorie',
    `title` varchar(250) DEFAULT NULL,
    `alias` varchar(255) DEFAULT NULL,
    `name` varchar(255) DEFAULT NULL,
    `kommentar` mediumtext DEFAULT NULL,
    `state` tinyint(1) NOT NULL DEFAULT 0,
    `ordering` int(11) NOT NULL DEFAULT 0,
    `checked_out` int(11) UNSIGNED NOT NULL DEFAULT 0,
    `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `access` int(11) NOT NULL DEFAULT 1,
    `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `version` int(10) UNSIGNED NOT NULL,
    PRIMARY KEY (`id`),
    KEY `cat_id` (`catid`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

--
-- Tabellenstruktur für Tabelle `uw3ro_wartung_qualifikation`
--

CREATE TABLE IF NOT EXISTS `#__wartung_qualifikation` (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` tinytext DEFAULT NULL,
    `bezeichnung` mediumtext DEFAULT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

--
-- Tabellenstruktur für Tabelle `uw3ro_wartung_termin`
--

CREATE TABLE IF NOT EXISTS `#__wartung_termin` (
    `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `wid` int(11) UNSIGNED NOT NULL DEFAULT 0,
    `lft` int(11) NOT NULL DEFAULT 0,
    `rgt` int(11) NOT NULL DEFAULT 0,
    `level` int(10) UNSIGNED NOT NULL DEFAULT 0,
    `title` varchar(255) NOT NULL,
    `alias` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
    `description` longtext NOT NULL,
    `termin` datetime NOT NULL DEFAULT current_timestamp(),
    `state` tinyint(1) NOT NULL DEFAULT 0,
    `ordering` int(11) NOT NULL,
    `checked_out` int(11) UNSIGNED NOT NULL DEFAULT 0,
    `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `access` int(10) UNSIGNED NOT NULL DEFAULT 0,
    `params` mediumtext NOT NULL,
    `metadesc` varchar(1024) NOT NULL COMMENT 'The meta description for the page.',
    `metakey` varchar(1024) NOT NULL COMMENT 'The meta keywords for the page.',
    `metadata` varchar(2048) NOT NULL COMMENT 'JSON encoded metadata properties.',
    `created_by` int(10) UNSIGNED NOT NULL DEFAULT 0,
    `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_by_alias` varchar(255) NOT NULL DEFAULT '',
    `modified_by` int(10) UNSIGNED NOT NULL DEFAULT 0,
    `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `version` int(10) UNSIGNED NOT NULL DEFAULT 1,
    `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    PRIMARY KEY (`id`),
    KEY `tag_idx` (`state`,`access`),
    KEY `idx_access` (`access`),
    KEY `idx_checkout` (`checked_out`),
    KEY `idx_left_right` (`lft`,`rgt`),
    KEY `idx_alias` (`alias`(100))
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;