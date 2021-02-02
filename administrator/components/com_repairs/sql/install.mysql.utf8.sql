--
-- Tabellenstruktur für Tabelle `uw3ro_repairs`
--

CREATE TABLE IF NOT EXISTS `#__repairs` (
    `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `asset_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'FK to the #__assets table.',
    `title` varchar(255) NOT NULL DEFAULT '',
    `alias` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
    `text` longtext NOT NULL,
    `level` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
    `done` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
    `state` tinyint(3) NOT NULL DEFAULT 0,
    `catid` int(10) UNSIGNED NOT NULL DEFAULT 0,
    `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_by` int(10) UNSIGNED NOT NULL DEFAULT 0,
    `created_by_alias` varchar(255) NOT NULL DEFAULT '',
    `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `modified_by` int(10) UNSIGNED NOT NULL DEFAULT 0,
    `checked_out` int(10) UNSIGNED NOT NULL DEFAULT 0,
    `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `params` mediumtext NOT NULL,
    `version` int(10) UNSIGNED NOT NULL DEFAULT 1,
    `ordering` int(11) NOT NULL DEFAULT 0,
    `metakey` mediumtext NOT NULL,
    `metadesc` mediumtext NOT NULL,
    `access` int(10) UNSIGNED NOT NULL DEFAULT 0,
    `metadata` mediumtext NOT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_access` (`access`),
    KEY `idx_checkout` (`checked_out`),
    KEY `idx_state` (`state`),
    KEY `idx_catid` (`catid`),
    KEY `idx_createdby` (`created_by`),
    KEY `idx_featured_catid` (`catid`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tabellenstruktur für Tabelle `uw3ro_repairs_parts`
--

CREATE TABLE IF NOT EXISTS `#__repairs_parts` (
    `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Die ID der Resource',
    `asset_id` int(10) UNSIGNED NOT NULL COMMENT 'FK to the #__assets table.',
    `rid` int(10) UNSIGNED NOT NULL COMMENT 'Die Reparatur ID',
    `resource` varchar(255) DEFAULT NULL,
    `title` varchar(255) NOT NULL COMMENT 'Der Name der Res',
    `alias` varchar(400) NOT NULL,
    `text` longtext NOT NULL,
    `worktime` int(10) UNSIGNED NOT NULL COMMENT 'Voraussichtliche Arbeitszeit',
    `dt_start` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Beginn der Res-Notwendigkeit',
    `dt_end` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Ende der Res-Notwendigkeit',
    `done` tinyint(3) NOT NULL DEFAULT 0 COMMENT 'Arbeitsschritt erledigt?',
    `state` tinyint(3) NOT NULL DEFAULT 0,
    `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_by` int(10) UNSIGNED NOT NULL DEFAULT 0,
    `created_by_alias` varchar(255) NOT NULL DEFAULT '',
    `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `modified_by` int(10) UNSIGNED NOT NULL DEFAULT 0,
    `checked_out` int(10) UNSIGNED NOT NULL DEFAULT 0,
    `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `params` mediumtext NOT NULL,
    `version` int(10) UNSIGNED NOT NULL DEFAULT 1,
    `ordering` int(11) UNSIGNED NOT NULL DEFAULT 0,
    `access` int(10) UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    KEY `ResId` (`rid`,`dt_start`) COMMENT='Sortierung nach ResId und Startzeit'
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Resourcenverbindung zu Reperaturen';

--
-- Tabellenstruktur für Tabelle `uw3ro_repairs_resources`
--

CREATE TABLE IF NOT EXISTS `#__repairs_resources` (
    `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Die ID der Resource',
    `asset_id` int(10) UNSIGNED NOT NULL COMMENT 'FK to the #__assets table.',
    `catid` int(10) UNSIGNED NOT NULL,
    `title` varchar(255) NOT NULL COMMENT 'Der Name der Res',
    `alias` varchar(400) NOT NULL,
    `text` longtext NOT NULL,
    `state` tinyint(3) NOT NULL DEFAULT 0,
    `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `created_by` int(10) UNSIGNED NOT NULL DEFAULT 0,
    `created_by_alias` varchar(255) NOT NULL,
    `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `modified_by` int(10) UNSIGNED NOT NULL DEFAULT 0,
    `checked_out` int(10) UNSIGNED NOT NULL DEFAULT 0,
    `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    `params` mediumtext NOT NULL,
    `version` int(10) UNSIGNED NOT NULL DEFAULT 1,
    `ordering` int(11) UNSIGNED NOT NULL DEFAULT 0,
    `access` int(10) UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Resourcenverbindung zu Reperaturen';