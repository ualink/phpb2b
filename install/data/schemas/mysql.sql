--
-- Database: `phpb2b`
--

-- --------------------------------------------------------

--
-- Table `pb_adminfields`
--

DROP TABLE IF EXISTS `pb_adminfields`;
CREATE TABLE `pb_adminfields` (
  `member_id` int(10) NOT NULL DEFAULT '-1',
  `depart_id` tinyint(1) NOT NULL DEFAULT '0',
  `first_name` varchar(25) NOT NULL DEFAULT '',
  `last_name` varchar(25) NOT NULL DEFAULT '',
  `level` tinyint(1) NOT NULL DEFAULT '0',
  `last_login` int(10) NOT NULL DEFAULT '0',
  `last_ip` varchar(25) NOT NULL DEFAULT '',
  `expired` int(10) NOT NULL DEFAULT '0',
  `permissions` text NOT NULL,
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`member_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table `pb_adminmodules`
--

DROP TABLE IF EXISTS `pb_adminmodules`;
CREATE TABLE `pb_adminmodules` (
  `id` smallint(3) NOT NULL AUTO_INCREMENT,
  `parent_id` smallint(3) NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table `pb_adminnotes`
--

DROP TABLE IF EXISTS `pb_adminnotes`;
CREATE TABLE `pb_adminnotes` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `member_id` int(10) NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `content` text,
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table `pb_adminprivileges`
--

DROP TABLE IF EXISTS `pb_adminprivileges`;
CREATE TABLE `pb_adminprivileges` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `adminmodule_id` int(5) NOT NULL DEFAULT '0',
  `name` varchar(25) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table `pb_adminroles`
--

DROP TABLE IF EXISTS `pb_adminroles`;
CREATE TABLE `pb_adminroles` (
  `id` tinyint(2) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table `pb_adses`
--

DROP TABLE IF EXISTS `pb_adses`;
CREATE TABLE `pb_adses` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `adzone_id` smallint(3) NOT NULL DEFAULT '0',
  `title` varchar(50) NOT NULL DEFAULT '',
  `description` text,
  `is_image` tinyint(1) NOT NULL DEFAULT '1',
  `source_name` varchar(100) NOT NULL DEFAULT '',
  `source_type` varchar(100) NOT NULL DEFAULT '',
  `source_url` varchar(255) NOT NULL DEFAULT '',
  `target_url` varchar(255) NOT NULL DEFAULT '',
  `width` smallint(6) NOT NULL DEFAULT '0',
  `height` smallint(6) NOT NULL DEFAULT '0',
  `alt_words` varchar(25) NOT NULL DEFAULT '',
  `start_date` int(10) NOT NULL DEFAULT '0',
  `end_date` int(10) NOT NULL DEFAULT '0',
  `priority` tinyint(1) NOT NULL DEFAULT '0',
  `clicked` smallint(6) NOT NULL DEFAULT '1',
  `target` enum('_parent','_self','_blank') NOT NULL DEFAULT '_blank',
  `seq` tinyint(1) NOT NULL DEFAULT '0',
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `picture_replace` varchar(255) NOT NULL DEFAULT '',
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_adzones`
--

DROP TABLE IF EXISTS `pb_adzones`;
CREATE TABLE `pb_adzones` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `membergroup_ids` varchar(50) NOT NULL DEFAULT '',
  `what` varchar(10) NOT NULL DEFAULT '',
  `style` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL DEFAULT '',
  `description` text,
  `additional_adwords` text,
  `price` float(9,2) NOT NULL DEFAULT '0.00',
  `file_name` varchar(100) NOT NULL DEFAULT '',
  `width` smallint(6) NOT NULL DEFAULT '0',
  `height` smallint(6) NOT NULL DEFAULT '0',
  `wrap` smallint(6) NOT NULL DEFAULT '0',
  `max_ad` smallint(6) NOT NULL DEFAULT '0',
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_albums`
--

DROP TABLE IF EXISTS `pb_albums`;
CREATE TABLE `pb_albums` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `member_id` int(10) NOT NULL DEFAULT '0',
  `attachment_id` int(10) NOT NULL DEFAULT '0',
  `type_id` smallint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_albumtypes`
--

DROP TABLE IF EXISTS `pb_albumtypes`;
CREATE TABLE `pb_albumtypes` (
  `id` smallint(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `display_order` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_announcements`
--

DROP TABLE IF EXISTS `pb_announcements`;
CREATE TABLE `pb_announcements` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `announcetype_id` smallint(3) NOT NULL DEFAULT '0',
  `subject` varchar(255) NOT NULL DEFAULT '',
  `message` text,
  `display_order` tinyint(1) NOT NULL DEFAULT '0',
  `display_expiration` int(10) unsigned NOT NULL DEFAULT '0',
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_announcementtypes`
--

DROP TABLE IF EXISTS `pb_announcementtypes`;
CREATE TABLE `pb_announcementtypes` (
  `id` smallint(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_areas`
--

DROP TABLE IF EXISTS `pb_areas`;
CREATE TABLE `pb_areas` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `attachment_id` int(10) NOT NULL DEFAULT '0',
  `areatype_id` smallint(3) NOT NULL DEFAULT '0',
  `child_ids` text,
  `path` varchar(100) NOT NULL,
  `top_parentid` smallint(6) NOT NULL DEFAULT '0',
  `level` tinyint(1) NOT NULL DEFAULT '1',
  `name` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `alias_name` varchar(255) NOT NULL DEFAULT '',
  `highlight` tinyint(1) NOT NULL DEFAULT '0',
  `parent_id` smallint(6) NOT NULL DEFAULT '0',
  `display_order` tinyint(1) NOT NULL DEFAULT '0',
  `description` text,
  `available` tinyint(1) NOT NULL DEFAULT '1',
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_areatypes`
--

DROP TABLE IF EXISTS `pb_areatypes`;
CREATE TABLE `pb_areatypes` (
  `id` smallint(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_attachments`
--

DROP TABLE IF EXISTS `pb_attachments`;
CREATE TABLE `pb_attachments` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `attachmenttype_id` smallint(3) NOT NULL DEFAULT '0',
  `member_id` int(10) NOT NULL DEFAULT '-1',
  `file_name` char(100) NOT NULL DEFAULT '',
  `attachment` char(255) NOT NULL DEFAULT '',
  `title` char(100) NOT NULL DEFAULT '',
  `description` text,
  `file_type` char(50) NOT NULL DEFAULT '0',
  `file_size` mediumint(8) NOT NULL DEFAULT '0',
  `thumb` varchar(100) NOT NULL DEFAULT '',
  `remote` varchar(100) NOT NULL DEFAULT '',
  `is_image` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_attachmenttypes`
--

DROP TABLE IF EXISTS `pb_attachmenttypes`;
CREATE TABLE `pb_attachmenttypes` (
  `id` tinyint(1) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table `pb_banned`
--

DROP TABLE IF EXISTS `pb_banned`;
CREATE TABLE `pb_banned` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `ip1` char(3) NOT NULL DEFAULT '',
  `ip2` char(3) NOT NULL DEFAULT '',
  `ip3` char(3) NOT NULL DEFAULT '',
  `ip4` char(3) NOT NULL DEFAULT '',
  `expiration` int(10) NOT NULL DEFAULT '0',
  `created` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ip1` (`ip1`,`ip2`,`ip3`,`ip4`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table `pb_brands`
--

DROP TABLE IF EXISTS `pb_brands`;
CREATE TABLE `pb_brands` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `member_id` int(10) NOT NULL DEFAULT '-1',
  `company_id` int(10) NOT NULL DEFAULT '-1',
  `type_id` smallint(3) NOT NULL DEFAULT '0',
  `if_commend` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL DEFAULT '',
  `alias_name` varchar(100) NOT NULL DEFAULT '',
  `picture` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `hits` smallint(6) NOT NULL DEFAULT '0',
  `ranks` smallint(3) NOT NULL DEFAULT '0',
  `letter` varchar(2) NOT NULL DEFAULT '',
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_brandtypes`
--

DROP TABLE IF EXISTS `pb_brandtypes`;
CREATE TABLE `pb_brandtypes` (
  `id` smallint(3) NOT NULL AUTO_INCREMENT,
  `parent_id` smallint(3) NOT NULL DEFAULT '0',
  `level` tinyint(1) NOT NULL DEFAULT '1',
  `name` varchar(100) NOT NULL DEFAULT '',
  `display_order` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_companies`
--

DROP TABLE IF EXISTS `pb_companies`;
CREATE TABLE `pb_companies` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `member_id` int(10) NOT NULL DEFAULT '0',
  `cache_spacename` varchar(255) NOT NULL DEFAULT '',
  `cache_membergroupid` smallint(3) NOT NULL DEFAULT '0',
  `cache_credits` smallint(6) NOT NULL DEFAULT '0',
  `topleveldomain` varchar(255) NOT NULL DEFAULT '',
  `industry_id` smallint(6) NOT NULL DEFAULT '0',
  `area_id` char(6) NOT NULL DEFAULT '0',
  `type_id` tinyint(2) NOT NULL DEFAULT '0',
  `name` char(255) NOT NULL DEFAULT '',
  `description` text,
  `english_name` char(100) NOT NULL DEFAULT '',
  `adwords` char(25) NOT NULL DEFAULT '',
  `keywords` varchar(50) NOT NULL DEFAULT '',
  `boss_name` varchar(25) NOT NULL DEFAULT '',
  `manage_type` varchar(25) NOT NULL DEFAULT '',
  `year_annual` tinyint(2) NOT NULL DEFAULT '0',
  `property` tinyint(1) NOT NULL DEFAULT '0',
  `configs` text,
  `bank_from` varchar(50) NOT NULL DEFAULT '',
  `bank_account` varchar(50) NOT NULL DEFAULT '',
  `main_prod` varchar(100) NOT NULL DEFAULT '',
  `employee_amount` varchar(25) NOT NULL DEFAULT '',
  `found_date` char(10) NOT NULL DEFAULT '0',
  `reg_fund` tinyint(2) NOT NULL DEFAULT '0',
  `reg_address` varchar(200) NOT NULL DEFAULT '',
  `address` varchar(200) NOT NULL DEFAULT '',
  `zipcode` varchar(15) NOT NULL DEFAULT '',
  `main_brand` varchar(100) NOT NULL DEFAULT '',
  `main_market` varchar(200) NOT NULL DEFAULT '',
  `main_biz_place` varchar(50) NOT NULL DEFAULT '',
  `main_customer` varchar(200) NOT NULL DEFAULT '',
  `link_man` varchar(25) NOT NULL DEFAULT '',
  `link_man_gender` tinyint(1) NOT NULL DEFAULT '0',
  `position` tinyint(1) NOT NULL DEFAULT '0',
  `tel` varchar(25) NOT NULL DEFAULT '',
  `fax` varchar(25) NOT NULL DEFAULT '',
  `mobile` varchar(25) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `site_url` varchar(100) NOT NULL DEFAULT '',
  `picture` varchar(50) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `first_letter` char(2) NOT NULL DEFAULT '',
  `if_commend` tinyint(1) NOT NULL DEFAULT '0',
  `clicked` int(5) NOT NULL DEFAULT '1',
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`),
  KEY `name` (`name`),
  KEY `status` (`status`),
  KEY `picture` (`picture`),
  KEY `industry_id1` (`industry_id`,`area_id`),
  KEY `status_2` (`status`),
  KEY `picture_2` (`picture`,`status`),
  KEY `name_2` (`name`),
  KEY `name_3` (`name`),
  KEY `status_3` (`status`),
  KEY `picture_3` (`picture`),
  KEY `industry_id1_2` (`industry_id`,`area_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_companyfields`
--

DROP TABLE IF EXISTS `pb_companyfields`;
CREATE TABLE `pb_companyfields` (
  `company_id` int(10) NOT NULL DEFAULT '0',
  `map_longitude` varchar(25) NOT NULL DEFAULT '',
  `map_latitude` varchar(25) NOT NULL DEFAULT '',
  PRIMARY KEY (`company_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table `pb_companynewses`
--

DROP TABLE IF EXISTS `pb_companynewses`;
CREATE TABLE `pb_companynewses` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `member_id` int(10) NOT NULL DEFAULT '-1',
  `company_id` int(10) NOT NULL DEFAULT '-1',
  `type_id` smallint(3) NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `content` text,
  `picture` varchar(100) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `clicked` int(5) NOT NULL DEFAULT '1',
  `created` int(9) NOT NULL DEFAULT '0',
  `modified` int(9) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_companynewstypes`
--

DROP TABLE IF EXISTS `pb_companynewstypes`;
CREATE TABLE `pb_companynewstypes` (
  `id` smallint(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `display_order` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_companytypes`
--

DROP TABLE IF EXISTS `pb_companytypes`;
CREATE TABLE `pb_companytypes` (
  `id` smallint(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `display_order` tinyint(1) NOT NULL DEFAULT '0',
  `url` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table `pb_countries`
--

DROP TABLE IF EXISTS `pb_countries`;
CREATE TABLE `pb_countries` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `picture` varchar(100) NOT NULL DEFAULT '0',
  `display_order` smallint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_dicts`
--

DROP TABLE IF EXISTS `pb_dicts`;
CREATE TABLE `pb_dicts` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `dicttype_id` smallint(6) NOT NULL DEFAULT '0',
  `extend_dicttypeid` varchar(25) NOT NULL DEFAULT '',
  `word` varchar(255) NOT NULL DEFAULT '',
  `word_name` varchar(255) NOT NULL DEFAULT '',
  `digest` varchar(255) NOT NULL DEFAULT '',
  `content` text,
  `picture` varchar(255) NOT NULL DEFAULT '',
  `refer` tinytext,
  `hits` int(10) NOT NULL DEFAULT '1',
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  `if_commend` tinyint(1) NOT NULL DEFAULT '0',
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_dicttypes`
--

DROP TABLE IF EXISTS `pb_dicttypes`;
CREATE TABLE `pb_dicttypes` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `parent_id` smallint(6) NOT NULL DEFAULT '0',
  `display_order` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_expomembers`
--

DROP TABLE IF EXISTS `pb_expomembers`;
CREATE TABLE `pb_expomembers` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `expo_id` smallint(6) NOT NULL DEFAULT '0',
  `member_id` int(10) NOT NULL DEFAULT '-1',
  `company_id` int(10) NOT NULL DEFAULT '-1',
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `expo_id` (`expo_id`,`member_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table `pb_expos`
--

DROP TABLE IF EXISTS `pb_expos`;
CREATE TABLE `pb_expos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `expotype_id` smallint(3) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL DEFAULT '',
  `description` text,
  `begin_time` int(10) NOT NULL DEFAULT '0',
  `end_time` int(10) NOT NULL DEFAULT '0',
  `industry_ids` varchar(100) NOT NULL DEFAULT '0',
  `industry_id` smallint(6) NOT NULL DEFAULT '0',
  `area_id` smallint(6) NOT NULL DEFAULT '0',
  `address` varchar(100) NOT NULL DEFAULT '',
  `stadium_name` varchar(100) NOT NULL DEFAULT '',
  `refresh_method` varchar(100) NOT NULL DEFAULT '',
  `scope` varchar(100) NOT NULL DEFAULT '',
  `hosts` varchar(255) NOT NULL DEFAULT '',
  `organisers` varchar(255) NOT NULL DEFAULT '',
  `co_organisers` varchar(255) NOT NULL DEFAULT '',
  `sponsors` varchar(255) NOT NULL DEFAULT '',
  `contacts` text,
  `important_notice` text,
  `picture` varchar(100) NOT NULL DEFAULT '',
  `if_commend` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `hits` smallint(6) NOT NULL DEFAULT '1',
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `status_2` (`status`),
  KEY `status_3` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_expostadiums`
--

DROP TABLE IF EXISTS `pb_expostadiums`;
CREATE TABLE `pb_expostadiums` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `sa` varchar(100) DEFAULT '',
  `country_id` smallint(6) DEFAULT '0',
  `province_id` smallint(6) DEFAULT '0',
  `city_id` smallint(6) DEFAULT '0',
  `sb` varchar(200) DEFAULT '',
  `sc` varchar(150) DEFAULT '',
  `sd` varchar(150) DEFAULT '',
  `se` varchar(150) DEFAULT '',
  `sf` varchar(150) DEFAULT '',
  `sg` text,
  `sh` smallint(6) DEFAULT '0',
  `created` int(10) DEFAULT NULL,
  `modified` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table `pb_expotypes`
--

DROP TABLE IF EXISTS `pb_expotypes`;
CREATE TABLE `pb_expotypes` (
  `id` smallint(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_favorites`
--

DROP TABLE IF EXISTS `pb_favorites`;
CREATE TABLE `pb_favorites` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `member_id` int(10) NOT NULL DEFAULT '-1',
  `target_id` int(10) NOT NULL DEFAULT '-1',
  `type_id` tinyint(1) NOT NULL DEFAULT '0',
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `member_id` (`member_id`,`target_id`,`type_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table `pb_feeds`
--

DROP TABLE IF EXISTS `pb_feeds`;
CREATE TABLE `pb_feeds` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type_id` tinyint(1) NOT NULL DEFAULT '0',
  `type` varchar(100) NOT NULL DEFAULT '',
  `member_id` int(10) NOT NULL DEFAULT '0',
  `username` varchar(100) NOT NULL DEFAULT '',
  `data` text NOT NULL,
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table `pb_formattributes`
--

DROP TABLE IF EXISTS `pb_formattributes`;
CREATE TABLE `pb_formattributes` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type_id` tinyint(1) NOT NULL DEFAULT '0',
  `form_id` smallint(3) NOT NULL DEFAULT '0',
  `formitem_id` smallint(3) NOT NULL DEFAULT '0',
  `primary_id` int(10) NOT NULL DEFAULT '-1',
  `attribute` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_formitems`
--

DROP TABLE IF EXISTS `pb_formitems`;
CREATE TABLE `pb_formitems` (
  `id` smallint(3) NOT NULL AUTO_INCREMENT,
  `form_id` smallint(3) NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `description` text,
  `identifier` varchar(50) NOT NULL DEFAULT '',
  `type` enum('checkbox','select','radio','calendar','url','image','textarea','email','number','text') NOT NULL DEFAULT 'text',
  `rules` text,
  `display_order` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_forms`
--

DROP TABLE IF EXISTS `pb_forms`;
CREATE TABLE `pb_forms` (
  `id` smallint(3) NOT NULL AUTO_INCREMENT,
  `type_id` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL DEFAULT '',
  `items` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_friendlinks`
--

DROP TABLE IF EXISTS `pb_friendlinks`;
CREATE TABLE `pb_friendlinks` (
  `id` smallint(3) NOT NULL AUTO_INCREMENT,
  `type_id` tinyint(1) NOT NULL DEFAULT '0',
  `industry_id` smallint(6) NOT NULL DEFAULT '0',
  `area_id` smallint(6) NOT NULL DEFAULT '0',
  `title` varchar(50) NOT NULL DEFAULT '',
  `logo` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(50) NOT NULL DEFAULT '',
  `priority` smallint(3) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `description` text,
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_friendlinktypes`
--

DROP TABLE IF EXISTS `pb_friendlinktypes`;
CREATE TABLE `pb_friendlinktypes` (
  `id` tinyint(1) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_goods`
--

DROP TABLE IF EXISTS `pb_goods`;
CREATE TABLE `pb_goods` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `type_id` smallint(3) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` text,
  `price` float(9,2) NOT NULL DEFAULT '0.00',
  `closed` tinyint(1) NOT NULL DEFAULT '1',
  `picture` varchar(100) NOT NULL DEFAULT '',
  `if_commend` tinyint(1) NOT NULL DEFAULT '0',
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_goodtypes`
--

DROP TABLE IF EXISTS `pb_goodtypes`;
CREATE TABLE `pb_goodtypes` (
  `id` smallint(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `display_order` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_helps`
--

DROP TABLE IF EXISTS `pb_helps`;
CREATE TABLE `pb_helps` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `helptype_id` smallint(3) NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `content` text,
  `highlight` tinyint(1) NOT NULL DEFAULT '0',
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_helptypes`
--

DROP TABLE IF EXISTS `pb_helptypes`;
CREATE TABLE `pb_helptypes` (
  `id` smallint(3) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(100) NOT NULL DEFAULT '',
  `parent_id` smallint(3) NOT NULL DEFAULT '0',
  `level` tinyint(1) NOT NULL DEFAULT '0',
  `display_order` tinyint(1) NOT NULL DEFAULT '0',
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_industries`
--

DROP TABLE IF EXISTS `pb_industries`;
CREATE TABLE `pb_industries` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `attachment_id` int(9) NOT NULL DEFAULT '0',
  `industrytype_id` smallint(3) NOT NULL DEFAULT '0',
  `child_ids` text,
  `path` varchar(100) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `alias_name` varchar(255) NOT NULL DEFAULT '',
  `highlight` tinyint(1) NOT NULL DEFAULT '0',
  `parent_id` smallint(6) NOT NULL DEFAULT '0',
  `top_parentid` smallint(6) NOT NULL DEFAULT '0',
  `level` tinyint(1) NOT NULL DEFAULT '1',
  `display_order` tinyint(1) NOT NULL DEFAULT '0',
  `description` text,
  `available` tinyint(1) NOT NULL DEFAULT '1',
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_industrytypes`
--

DROP TABLE IF EXISTS `pb_industrytypes`;
CREATE TABLE `pb_industrytypes` (
  `id` smallint(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_inqueries`
--

DROP TABLE IF EXISTS `pb_inqueries`;
CREATE TABLE `pb_inqueries` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `to_member_id` int(10) DEFAULT NULL,
  `to_company_id` int(10) DEFAULT NULL,
  `title` varchar(50) NOT NULL DEFAULT '',
  `content` text,
  `send_achive` tinyint(1) DEFAULT NULL,
  `know_more` varchar(50) NOT NULL DEFAULT '',
  `exp_quantity` varchar(15) NOT NULL DEFAULT '',
  `exp_price` float(9,2) NOT NULL DEFAULT '0.00',
  `contacts` text,
  `user_ip` varchar(11) DEFAULT '',
  `created` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table `pb_jobs`
--

DROP TABLE IF EXISTS `pb_jobs`;
CREATE TABLE `pb_jobs` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `member_id` int(10) NOT NULL DEFAULT '-1',
  `company_id` int(10) NOT NULL DEFAULT '-1',
  `cache_spacename` varchar(25) NOT NULL DEFAULT '',
  `industry_id` smallint(6) NOT NULL DEFAULT '0',
  `area_id` smallint(6) NOT NULL DEFAULT '0',
  `name` varchar(150) NOT NULL DEFAULT '',
  `work_station` varchar(50) NOT NULL DEFAULT '',
  `content` text,
  `require_gender_id` tinyint(1) NOT NULL DEFAULT '0',
  `peoples` varchar(5) NOT NULL DEFAULT '',
  `require_education_id` tinyint(1) NOT NULL DEFAULT '0',
  `require_age` varchar(10) NOT NULL DEFAULT '',
  `salary_id` tinyint(1) NOT NULL DEFAULT '0',
  `worktype_id` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `clicked` int(5) NOT NULL DEFAULT '1',
  `jobtype_id` smallint(6) NOT NULL DEFAULT '0',
  `expire_time` int(10) NOT NULL DEFAULT '0',
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_jobtypes`
--

DROP TABLE IF EXISTS `pb_jobtypes`;
CREATE TABLE `pb_jobtypes` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `parent_id` smallint(6) NOT NULL DEFAULT '0',
  `level` tinyint(1) NOT NULL DEFAULT '1',
  `name` varchar(255) NOT NULL DEFAULT '',
  `display_order` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_keywords`
--

DROP TABLE IF EXISTS `pb_keywords`;
CREATE TABLE `pb_keywords` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `title` varchar(25) NOT NULL DEFAULT '',
  `target_id` int(10) NOT NULL DEFAULT '0',
  `target_position` tinyint(1) NOT NULL DEFAULT '0',
  `type_name` enum('trades','companies','newses','products') NOT NULL DEFAULT 'trades',
  `hits` smallint(6) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `title` (`title`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table `pb_logs`
--

DROP TABLE IF EXISTS `pb_logs`;
CREATE TABLE `pb_logs` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `handle_type` enum('error','info','warning') NOT NULL DEFAULT 'info',
  `source_module` varchar(50) NOT NULL DEFAULT '',
  `description` text,
  `ip_address` varchar(15) NOT NULL DEFAULT '',
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_markets`
--

DROP TABLE IF EXISTS `pb_markets`;
CREATE TABLE `pb_markets` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `main_business` varchar(255) NOT NULL DEFAULT '',
  `content` text,
  `markettype_id` smallint(3) NOT NULL DEFAULT '0',
  `area_id` smallint(6) NOT NULL DEFAULT '0',
  `industry_id` smallint(6) NOT NULL DEFAULT '0',
  `picture` varchar(50) NOT NULL DEFAULT '',
  `ip_address` varchar(15) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `clicked` smallint(6) NOT NULL DEFAULT '1',
  `if_commend` tinyint(1) NOT NULL DEFAULT '0',
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_markettypes`
--

DROP TABLE IF EXISTS `pb_markettypes`;
CREATE TABLE `pb_markettypes` (
  `id` smallint(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `display_order` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_membercaches`
--

DROP TABLE IF EXISTS `pb_membercaches`;
CREATE TABLE `pb_membercaches` (
  `member_id` int(10) NOT NULL DEFAULT '-1',
  `data1` text NOT NULL DEFAULT '',
  `data2` text NOT NULL DEFAULT '',
  `expiration` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`member_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table `pb_memberfields`
--

DROP TABLE IF EXISTS `pb_memberfields`;
CREATE TABLE `pb_memberfields` (
  `member_id` int(10) NOT NULL DEFAULT '0',
  `today_logins` smallint(6) NOT NULL DEFAULT '0',
  `total_logins` smallint(6) NOT NULL DEFAULT '0',
  `area_id` smallint(6) NOT NULL DEFAULT '0',
  `first_name` varchar(25) NOT NULL DEFAULT '',
  `last_name` varchar(25) NOT NULL DEFAULT '',
  `gender` tinyint(1) NOT NULL DEFAULT '0',
  `tel` varchar(25) NOT NULL DEFAULT '',
  `fax` varchar(25) NOT NULL DEFAULT '',
  `mobile` varchar(25) NOT NULL DEFAULT '',
  `qq` varchar(12) NOT NULL DEFAULT '',
  `msn` varchar(50) NOT NULL DEFAULT '',
  `icq` varchar(12) NOT NULL DEFAULT '',
  `yahoo` varchar(50) NOT NULL DEFAULT '',
  `skype` varchar(50) NOT NULL DEFAULT '',
  `address` varchar(50) NOT NULL DEFAULT '',
  `zipcode` varchar(16) NOT NULL DEFAULT '',
  `site_url` varchar(100) NOT NULL DEFAULT '',
  `question` varchar(50) NOT NULL DEFAULT '',
  `answer` varchar(50) NOT NULL DEFAULT '',
  `reg_ip` varchar(25) NOT NULL DEFAULT '0',
  PRIMARY KEY (`member_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table `pb_membergroups`
--

DROP TABLE IF EXISTS `pb_membergroups`;
CREATE TABLE `pb_membergroups` (
  `id` smallint(3) NOT NULL AUTO_INCREMENT,
  `membertype_id` tinyint(1) NOT NULL DEFAULT '-1',
  `name` varchar(50) NOT NULL DEFAULT '',
  `description` text,
  `type` enum('define','special','system') NOT NULL DEFAULT 'define',
  `system` enum('private','public') NOT NULL DEFAULT 'private',
  `picture` varchar(50) NOT NULL DEFAULT 'default.gif',
  `point_max` smallint(6) NOT NULL DEFAULT '0',
  `point_min` smallint(6) NOT NULL DEFAULT '0',
  `max_offer` smallint(3) NOT NULL DEFAULT '0',
  `max_product` smallint(3) NOT NULL DEFAULT '0',
  `max_job` smallint(3) NOT NULL DEFAULT '0',
  `max_companynews` smallint(3) NOT NULL DEFAULT '0',
  `max_producttype` smallint(3) NOT NULL DEFAULT '3',
  `max_album` smallint(3) NOT NULL DEFAULT '0',
  `max_attach_size` smallint(6) NOT NULL DEFAULT '0',
  `max_size_perday` smallint(6) NOT NULL DEFAULT '0',
  `max_favorite` smallint(3) NOT NULL DEFAULT '0',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `allow_offer` tinyint(1) NOT NULL DEFAULT '0',
  `allow_market` tinyint(1) NOT NULL DEFAULT '0',
  `allow_company` tinyint(1) NOT NULL DEFAULT '0',
  `allow_product` tinyint(1) NOT NULL DEFAULT '0',
  `allow_job` tinyint(1) NOT NULL DEFAULT '0',
  `allow_companynews` tinyint(1) NOT NULL DEFAULT '1',
  `allow_album` tinyint(1) NOT NULL DEFAULT '0',
  `allow_space` tinyint(1) NOT NULL DEFAULT '1',
  `default_live_time` tinyint(1) NOT NULL DEFAULT '1',
  `after_live_time` tinyint(1) NOT NULL DEFAULT '1',
  `exempt` tinyint(1) unsigned zerofill NOT NULL DEFAULT '0',
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_members`
--

DROP TABLE IF EXISTS `pb_members`;
CREATE TABLE `pb_members` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `space_name` varchar(255) NOT NULL DEFAULT '',
  `templet_id` smallint(3) NOT NULL DEFAULT '0',
  `username` varchar(25) NOT NULL DEFAULT '',
  `userpass` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `points` smallint(6) NOT NULL DEFAULT '0',
  `credits` smallint(6) NOT NULL DEFAULT '0',
  `balance_amount` float(7,2) NOT NULL DEFAULT '0.00',
  `trusttype_ids` varchar(25) NOT NULL DEFAULT '',
  `status` enum('3','2','1','0') NOT NULL DEFAULT '0',
  `photo` varchar(100) NOT NULL DEFAULT '',
  `membertype_id` smallint(3) NOT NULL DEFAULT '0',
  `membergroup_id` smallint(3) NOT NULL DEFAULT '0',
  `last_login` varchar(11) NOT NULL DEFAULT '0',
  `last_ip` varchar(25) NOT NULL DEFAULT '0',
  `service_start_date` varchar(11) NOT NULL DEFAULT '0',
  `service_end_date` varchar(11) NOT NULL DEFAULT '0',
  `office_redirect` smallint(6) NOT NULL DEFAULT '0',
  `created` varchar(10) NOT NULL DEFAULT '0',
  `modified` varchar(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_membertypes`
--

DROP TABLE IF EXISTS `pb_membertypes`;
CREATE TABLE `pb_membertypes` (
  `id` smallint(3) NOT NULL AUTO_INCREMENT,
  `default_membergroup_id` smallint(3) NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '',
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_messages`
--

DROP TABLE IF EXISTS `pb_messages`;
CREATE TABLE `pb_messages` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` enum('system','user','inquery') NOT NULL DEFAULT 'user',
  `from_member_id` int(10) NOT NULL DEFAULT '-1',
  `cache_from_username` varchar(25) NOT NULL DEFAULT '',
  `to_member_id` int(10) NOT NULL DEFAULT '-1',
  `cache_to_username` varchar(25) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `content` text,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table `pb_navs`
--

DROP TABLE IF EXISTS `pb_navs`;
CREATE TABLE `pb_navs` (
  `id` smallint(3) NOT NULL AUTO_INCREMENT,
  `parent_id` smallint(3) NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `target` enum('_blank','_self') NOT NULL DEFAULT '_self',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `display_order` smallint(3) NOT NULL DEFAULT '0',
  `highlight` tinyint(1) NOT NULL DEFAULT '0',
  `level` tinyint(1) NOT NULL DEFAULT '0',
  `class_name` varchar(25) NOT NULL DEFAULT '',
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_newscomments`
--

DROP TABLE IF EXISTS `pb_newscomments`;
CREATE TABLE `pb_newscomments` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `news_id` int(10) NOT NULL DEFAULT '0',
  `member_id` int(10) NOT NULL DEFAULT '-1',
  `cache_username` varchar(25) NOT NULL DEFAULT '',
  `message` text,
  `ip_address` varchar(15) NOT NULL DEFAULT '',
  `invisible` tinyint(1) NOT NULL DEFAULT '1',
  `created` int(10) NOT NULL DEFAULT '0',
  `date_line` datetime NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table `pb_newses`
--

DROP TABLE IF EXISTS `pb_newses`;
CREATE TABLE `pb_newses` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type_id` smallint(3) NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `industry_id` smallint(3) NOT NULL DEFAULT '0',
  `area_id` smallint(3) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `content` text,
  `source` varchar(25) NOT NULL DEFAULT '',
  `picture` varchar(50) NOT NULL DEFAULT '',
  `if_focus` tinyint(1) NOT NULL DEFAULT '0',
  `if_commend` tinyint(1) NOT NULL DEFAULT '0',
  `highlight` tinyint(1) NOT NULL DEFAULT '0',
  `clicked` int(10) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `flag` tinyint(1) NOT NULL DEFAULT '0',
  `require_membertype` varchar(15) NOT NULL DEFAULT '0',
  `tag_ids` varchar(255) DEFAULT '',
  `created` int(10) NOT NULL DEFAULT '0',
  `create_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `start_time` DATE NOT NULL DEFAULT '0000-00-00',
  `end_time` DATE NOT NULL DEFAULT '0000-00-00',
  `modified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `type_id` (`type_id`,`status`),
  KEY `type_id_2` (`type_id`,`status`),
  KEY `status` (`status`),
  KEY `picture` (`picture`,`status`),
  KEY `type_id_3` (`type_id`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_newstypes`
--

DROP TABLE IF EXISTS `pb_newstypes`;
CREATE TABLE `pb_newstypes` (
  `id` smallint(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL DEFAULT '',
  `level_id` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `parent_id` smallint(3) NOT NULL DEFAULT '0',
  `created` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_ordergoods`
--

DROP TABLE IF EXISTS `pb_ordergoods`;
CREATE TABLE `pb_ordergoods` (
  `goods_id` smallint(6) NOT NULL DEFAULT '0',
  `order_id` smallint(6) unsigned zerofill NOT NULL DEFAULT '000000',
  `trade_no` char(16) NOT NULL DEFAULT '',
  `amount` smallint(3) NOT NULL DEFAULT '1',
  PRIMARY KEY (`goods_id`,`order_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table `pb_orders`
--

DROP TABLE IF EXISTS `pb_orders`;
CREATE TABLE `pb_orders` (
  `id` smallint(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `trade_no` char(16) NOT NULL DEFAULT '',
  `member_id` int(10) NOT NULL DEFAULT '-1',
  `anonymous` tinyint(1) NOT NULL DEFAULT '0',
  `cache_username` varchar(25) NOT NULL DEFAULT '',
  `total_price` float(9,2) NOT NULL DEFAULT '0.00',
  `subject` varchar(100) NOT NULL DEFAULT '',
  `content` text,
  `pay_status` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `pay_id` smallint(3) NOT NULL DEFAULT '0',
  `pay_name` varchar(25) NOT NULL DEFAULT '',
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_passports`
--

DROP TABLE IF EXISTS `pb_passports`;
CREATE TABLE `pb_passports` (
  `id` smallint(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL DEFAULT '',
  `title` varchar(25) NOT NULL DEFAULT '',
  `description` text,
  `url` varchar(25) NOT NULL DEFAULT '',
  `config` text,
  `available` tinyint(1) NOT NULL DEFAULT '1',
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_payhistories`
--

DROP TABLE IF EXISTS `pb_payhistories`;
CREATE TABLE `pb_payhistories` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `member_id` int(9) NOT NULL DEFAULT '-1',
  `trade_no` char(25) NOT NULL DEFAULT '-1',
  `amount` float(7,2) NOT NULL DEFAULT '0.00',
  `remain` float(7,2) NOT NULL DEFAULT '0.00',
  `ip_address` varchar(15) NOT NULL DEFAULT '',
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `one_trade_no` (`trade_no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_payments`
--

DROP TABLE IF EXISTS `pb_payments`;
CREATE TABLE `pb_payments` (
  `id` smallint(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL DEFAULT '',
  `title` varchar(25) NOT NULL DEFAULT '',
  `description` text,
  `config` text,
  `available` tinyint(1) NOT NULL DEFAULT '1',
  `if_online_support` tinyint(1) NOT NULL DEFAULT '0',
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_personals`
--

DROP TABLE IF EXISTS `pb_personals`;
CREATE TABLE `pb_personals` (
  `member_id` int(10) NOT NULL DEFAULT '-1',
  `resume_status` tinyint(1) NOT NULL DEFAULT '0',
  `max_education` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`member_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table `pb_plugins`
--

DROP TABLE IF EXISTS `pb_plugins`;
CREATE TABLE `pb_plugins` (
  `id` smallint(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL DEFAULT '',
  `title` varchar(25) NOT NULL DEFAULT '',
  `description` text,
  `copyright` varchar(25) NOT NULL DEFAULT '',
  `version` varchar(15) NOT NULL DEFAULT '',
  `pluginvar` text,
  `available` tinyint(1) NOT NULL DEFAULT '1',
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table `pb_pointlogs`
--

DROP TABLE IF EXISTS `pb_pointlogs`;
CREATE TABLE `pb_pointlogs` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `member_id` int(10) NOT NULL DEFAULT '-1',
  `action_name` varchar(25) NOT NULL DEFAULT '',
  `points` smallint(3) NOT NULL DEFAULT '0',
  `description` text,
  `ip_address` varchar(15) NOT NULL DEFAULT '',
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_productcategories`
--

DROP TABLE IF EXISTS `pb_productcategories`;
CREATE TABLE `pb_productcategories` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `parent_id` smallint(6) NOT NULL DEFAULT '0',
  `level` tinyint(1) NOT NULL DEFAULT '1',
  `name` varchar(255) NOT NULL DEFAULT '',
  `display_order` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_productprices`
--

DROP TABLE IF EXISTS `pb_productprices`;
CREATE TABLE `pb_productprices` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type_id` tinyint(1) NOT NULL DEFAULT '1',
  `product_id` int(10) NOT NULL DEFAULT '-1',
  `brand_id` smallint(6) NOT NULL DEFAULT '-1',
  `member_id` int(10) NOT NULL DEFAULT '-1',
  `company_id` int(10) NOT NULL DEFAULT '-1',
  `area_id` smallint(6) NOT NULL DEFAULT '0',
  `price_trends` tinyint(1) NOT NULL DEFAULT '0',
  `category_id` smallint(6) NOT NULL DEFAULT '0',
  `source` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `units` tinyint(1) NOT NULL DEFAULT '1',
  `currency` tinyint(1) NOT NULL DEFAULT '1',
  `price` float(9,2) NOT NULL DEFAULT '0.00',
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_products`
--

DROP TABLE IF EXISTS `pb_products`;
CREATE TABLE `pb_products` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `member_id` int(10) NOT NULL DEFAULT '-1',
  `company_id` int(10) NOT NULL DEFAULT '0',
  `cache_companyname` varchar(100) NOT NULL DEFAULT '',
  `sort_id` tinyint(1) NOT NULL DEFAULT '1',
  `brand_id` smallint(6) NOT NULL DEFAULT '0',
  `category_id` smallint(6) NOT NULL DEFAULT '0',
  `industry_id` smallint(6) NOT NULL DEFAULT '0',
  `country_id` smallint(6) NOT NULL DEFAULT '0',
  `area_id` smallint(6) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `price` float(9,2) NOT NULL DEFAULT '0.00',
  `sn` varchar(20) NOT NULL DEFAULT '',
  `spec` varchar(20) NOT NULL DEFAULT '',
  `produce_area` varchar(50) NOT NULL DEFAULT '',
  `packing_content` varchar(100) NOT NULL DEFAULT '',
  `picture` varchar(50) NOT NULL DEFAULT '',
  `content` text,
  `type_id` smallint(6) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `ifnew` tinyint(1) NOT NULL DEFAULT '0',
  `if_commend` tinyint(1) NOT NULL DEFAULT '0',
  `priority` tinyint(1) NOT NULL DEFAULT '0',
  `tag_ids` varchar(255) DEFAULT '',
  `clicked` smallint(6) NOT NULL DEFAULT '1',
  `formattribute_ids` text,
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `picture` (`picture`,`status`,`state`),
  KEY `picture_2` (`picture`,`status`,`state`),
  KEY `picture_3` (`picture`,`status`,`state`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_productsorts`
--

DROP TABLE IF EXISTS `pb_productsorts`;
CREATE TABLE `pb_productsorts` (
  `id` smallint(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `display_order` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_producttypes`
--

DROP TABLE IF EXISTS `pb_producttypes`;
CREATE TABLE `pb_producttypes` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `member_id` int(10) NOT NULL DEFAULT '-1',
  `company_id` int(10) NOT NULL DEFAULT '-1',
  `name` varchar(25) NOT NULL DEFAULT '',
  `level` tinyint(1) NOT NULL DEFAULT '0',
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_quotes`
--

DROP TABLE IF EXISTS `pb_quotes`;
CREATE TABLE `pb_quotes` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `product_id` int(10) NOT NULL DEFAULT '-1',
  `market_id` smallint(6) NOT NULL DEFAULT '-1',
  `type_id` smallint(6) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `area_id` smallint(6) NOT NULL DEFAULT '0',
  `area_id1` smallint(6) NOT NULL DEFAULT '0',
  `area_id2` smallint(6) NOT NULL DEFAULT '0',
  `area_id3` smallint(6) NOT NULL DEFAULT '0',
  `max_price` float(9,2) NOT NULL DEFAULT '0.00',
  `min_price` float(9,2) NOT NULL DEFAULT '0.00',
  `units` tinyint(1) NOT NULL DEFAULT '1',
  `currency` tinyint(1) NOT NULL DEFAULT '1',
  `trend_data` text NOT NULL,
  `hits` int(10) NOT NULL DEFAULT '1',
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_quotetypes`
--

DROP TABLE IF EXISTS `pb_quotetypes`;
CREATE TABLE `pb_quotetypes` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `parent_id` smallint(6) NOT NULL DEFAULT '0',
  `level` tinyint(1) NOT NULL DEFAULT '1',
  `name` varchar(255) NOT NULL DEFAULT '',
  `display_order` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_roleadminers`
--

DROP TABLE IF EXISTS `pb_roleadminers`;
CREATE TABLE `pb_roleadminers` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `adminrole_id` int(2) DEFAULT NULL,
  `adminer_id` int(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table `pb_roleprivileges`
--

DROP TABLE IF EXISTS `pb_roleprivileges`;
CREATE TABLE `pb_roleprivileges` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `adminrole_id` int(2) DEFAULT NULL,
  `adminprivilege_id` int(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table `pb_services`
--

DROP TABLE IF EXISTS `pb_services`;
CREATE TABLE `pb_services` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `member_id` int(10) NOT NULL DEFAULT '-1',
  `title` varchar(25) NOT NULL DEFAULT '',
  `content` text,
  `nick_name` varchar(25) DEFAULT '',
  `email` varchar(25) NOT NULL DEFAULT '',
  `user_ip` varchar(11) NOT NULL DEFAULT '',
  `type_id` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  `revert_content` text,
  `revert_date` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table `pb_sessions`
--

DROP TABLE IF EXISTS `pb_sessions`;
CREATE TABLE `pb_sessions` (
  `sesskey` char(32) NOT NULL DEFAULT '',
  `expiry` int(10) NOT NULL DEFAULT '0',
  `expireref` char(64) NOT NULL DEFAULT '',
  `data` text,
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  KEY `sess2_expiry` (`expiry`),
  KEY `sess2_expireref` (`expireref`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table `pb_settings`
--

DROP TABLE IF EXISTS `pb_settings`;
CREATE TABLE `pb_settings` (
  `id` smallint(3) NOT NULL AUTO_INCREMENT,
  `type_id` tinyint(1) NOT NULL DEFAULT '0',
  `variable` varchar(150) NOT NULL DEFAULT '',
  `valued` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `variable` (`variable`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_spacecaches`
--

DROP TABLE IF EXISTS `pb_spacecaches`;
CREATE TABLE `pb_spacecaches` (
  `cache_spacename` varchar(255) NOT NULL DEFAULT '',
  `company_id` int(10) NOT NULL DEFAULT '-1',
  `data1` text NOT NULL DEFAULT '',
  `data2` text NOT NULL DEFAULT '',
  `expiration` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`company_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table `pb_spacelinks`
--

DROP TABLE IF EXISTS `pb_spacelinks`;
CREATE TABLE `pb_spacelinks` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `member_id` int(10) NOT NULL DEFAULT '0',
  `company_id` int(10) NOT NULL DEFAULT '0',
  `display_order` smallint(3) NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `is_outlink` tinyint(1) NOT NULL DEFAULT '0',
  `description` varchar(100) NOT NULL DEFAULT '',
  `logo` varchar(255) NOT NULL DEFAULT '',
  `highlight` tinyint(1) NOT NULL DEFAULT '0',
  `created` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_spreads`
--

DROP TABLE IF EXISTS `pb_spreads`;
CREATE TABLE `pb_spreads` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `member_id` int(10) NOT NULL DEFAULT '-1',
  `keyword_name` varchar(25) NOT NULL DEFAULT '',
  `title` varchar(50) NOT NULL DEFAULT '',
  `content` varchar(200) NOT NULL DEFAULT '',
  `target_url` varchar(100) NOT NULL DEFAULT '',
  `hits` int(10) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `expiration` int(10) NOT NULL DEFAULT '0',
  `show_times` int(10) NOT NULL DEFAULT '1',
  `cost_every_hit` FLOAT( 7, 2 ) NOT NULL DEFAULT '0',
  `display_order` tinyint(1) NOT NULL DEFAULT '0',
  `created` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `spread` (`id`,`keyword_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_standards`
--

DROP TABLE IF EXISTS `pb_standards`;
CREATE TABLE `pb_standards` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `attachment_id` smallint(6) NOT NULL DEFAULT '0',
  `type_id` smallint(6) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `source` varchar(255) NOT NULL DEFAULT '',
  `digest` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `publish_time` int(10) NOT NULL DEFAULT '0',
  `force_time` int(10) NOT NULL DEFAULT '0',
  `clicked` smallint(6) NOT NULL DEFAULT '1',
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_standardtypes`
--

DROP TABLE IF EXISTS `pb_standardtypes`;
CREATE TABLE `pb_standardtypes` (
  `id` smallint(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `display_order` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_tags`
--

DROP TABLE IF EXISTS `pb_tags`;
CREATE TABLE `pb_tags` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `member_id` int(10) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `numbers` smallint(6) NOT NULL DEFAULT '0',
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  `flag` tinyint(1) NOT NULL DEFAULT '0',
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `title` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_templets`
--

DROP TABLE IF EXISTS `pb_templets`;
CREATE TABLE `pb_templets` (
  `id` smallint(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL DEFAULT '',
  `title` varchar(25) NOT NULL DEFAULT '',
  `directory` varchar(100) NOT NULL DEFAULT '',
  `type` enum('system','user') NOT NULL DEFAULT 'system',
  `author` varchar(100) NOT NULL DEFAULT '',
  `style` varchar(255) NOT NULL DEFAULT '',
  `description` text,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `require_membertype` varchar(100) NOT NULL DEFAULT '0',
  `require_membergroups` varchar(100) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_topicnews`
--

DROP TABLE IF EXISTS `pb_topicnews`;
CREATE TABLE `pb_topicnews` (
  `topic_id` smallint(6) NOT NULL DEFAULT '0',
  `news_id` smallint(6) NOT NULL DEFAULT '0'
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table `pb_topics`
--

DROP TABLE IF EXISTS `pb_topics`;
CREATE TABLE `pb_topics` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `alias_name` varchar(100) NOT NULL DEFAULT '',
  `templet` varchar(100) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `picture` varchar(255) NOT NULL DEFAULT '',
  `description` text,
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_tradefields`
--

DROP TABLE IF EXISTS `pb_tradefields`;
CREATE TABLE `pb_tradefields` (
  `trade_id` int(10) NOT NULL DEFAULT '0',
  `member_id` int(10) NOT NULL DEFAULT '0',
  `link_man` varchar(100) NOT NULL DEFAULT '',
  `address` varchar(100) NOT NULL DEFAULT '',
  `company_name` varchar(100) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `prim_tel` tinyint(1) NOT NULL DEFAULT '0',
  `prim_telnumber` varchar(25) NOT NULL DEFAULT '',
  `prim_im` tinyint(1) NOT NULL DEFAULT '0',
  `prim_imaccount` varchar(100) NOT NULL DEFAULT '',
  `brand_name` char(50) NOT NULL DEFAULT '',
  `template` char(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`trade_id`),
  UNIQUE KEY `trade_id` (`trade_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table `pb_trades`
--

DROP TABLE IF EXISTS `pb_trades`;
CREATE TABLE `pb_trades` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type_id` tinyint(2) NOT NULL DEFAULT '0',
  `industry_id` smallint(6) NOT NULL DEFAULT '0',
  `country_id` smallint(6) NOT NULL DEFAULT '0',
  `area_id` smallint(6) NOT NULL DEFAULT '0',
  `member_id` int(10) NOT NULL DEFAULT '0',
  `company_id` int(5) NOT NULL DEFAULT '0',
  `cache_username` varchar(25) NOT NULL DEFAULT '',
  `cache_companyname` varchar(100) NOT NULL DEFAULT '',
  `cache_contacts` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(100) NOT NULL DEFAULT '',
  `adwords` varchar(125) NOT NULL DEFAULT '',
  `content` text,
  `price` float(9,2) NOT NULL DEFAULT '0.00',
  `measuring_unit` varchar(15) NOT NULL DEFAULT '0',
  `monetary_unit` varchar(15) NOT NULL DEFAULT '0',
  `packing` varchar(150) NOT NULL DEFAULT '',
  `quantity` varchar(25) NOT NULL DEFAULT '',
  `display_order` tinyint(1) NOT NULL DEFAULT '0',
  `display_expiration` int(10) NOT NULL DEFAULT '0',
  `spec` varchar(200) NOT NULL DEFAULT '',
  `sn` varchar(25) NOT NULL DEFAULT '',
  `picture` varchar(50) NOT NULL DEFAULT '',
  `picture_remote` varchar(50) NOT NULL DEFAULT '',
  `status` tinyint(2) NOT NULL DEFAULT '0',
  `submit_time` int(10) NOT NULL DEFAULT '0',
  `expire_time` int(10) NOT NULL DEFAULT '0',
  `expire_days` int(3) NOT NULL DEFAULT '10',
  `if_commend` tinyint(1) NOT NULL DEFAULT '0',
  `if_urgent` enum('0','1') NOT NULL DEFAULT '0',
  `if_locked` enum('0','1') NOT NULL DEFAULT '0',
  `require_point` smallint(6) NOT NULL DEFAULT '0',
  `require_membertype` smallint(6) NOT NULL DEFAULT '0',
  `require_freedate` int(10) NOT NULL DEFAULT '0',
  `ip_addr` varchar(15) NOT NULL DEFAULT '',
  `clicked` int(10) NOT NULL DEFAULT '1',
  `tag_ids` varchar(255) NOT NULL DEFAULT '',
  `formattribute_ids` text,
  `highlight` tinyint(2) NOT NULL DEFAULT '0',
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `type_id` (`type_id`,`picture`,`status`,`expire_time`),
  KEY `type_id_2` (`type_id`,`picture`,`status`,`expire_time`),
  KEY `type_id_3` (`type_id`,`status`),
  KEY `type_id_4` (`type_id`,`status`,`expire_time`),
  KEY `type_id_5` (`type_id`,`picture`,`status`,`expire_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_tradetypes`
--

DROP TABLE IF EXISTS `pb_tradetypes`;
CREATE TABLE `pb_tradetypes` (
  `id` smallint(3) NOT NULL AUTO_INCREMENT,
  `parent_id` smallint(3) NOT NULL DEFAULT '0',
  `name` varchar(25) NOT NULL DEFAULT '',
  `level` tinyint(1) NOT NULL DEFAULT '1',
  `display_order` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_trustlogs`
--

DROP TABLE IF EXISTS `pb_trustlogs`;
CREATE TABLE `pb_trustlogs` (
  `member_id` int(10) NOT NULL AUTO_INCREMENT,
  `trusttype_id` smallint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`member_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table `pb_trusttypes`
--

DROP TABLE IF EXISTS `pb_trusttypes`;
CREATE TABLE `pb_trusttypes` (
  `id` smallint(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `description` text,
  `image` varchar(255) NOT NULL DEFAULT '',
  `display_order` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_typemodels`
--

DROP TABLE IF EXISTS `pb_typemodels`;
CREATE TABLE `pb_typemodels` (
  `id` smallint(3) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL DEFAULT '',
  `type_name` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_typeoptions`
--

DROP TABLE IF EXISTS `pb_typeoptions`;
CREATE TABLE `pb_typeoptions` (
  `id` smallint(3) NOT NULL AUTO_INCREMENT,
  `typemodel_id` smallint(3) NOT NULL DEFAULT '0',
  `option_value` varchar(50) NOT NULL DEFAULT '',
  `option_label` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_userpages`
--

DROP TABLE IF EXISTS `pb_userpages`;
CREATE TABLE `pb_userpages` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `templet_name` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(50) NOT NULL DEFAULT '',
  `digest` varchar(50) NOT NULL DEFAULT '',
  `content` text,
  `url` varchar(100) NOT NULL DEFAULT '',
  `display_order` tinyint(1) NOT NULL DEFAULT '0',
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `pb_visitlogs`
--

DROP TABLE IF EXISTS `pb_visitlogs`;
CREATE TABLE `pb_visitlogs` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `salt` varchar(32) NOT NULL DEFAULT '',
  `date_line` varchar(15) NOT NULL DEFAULT '',
  `type_name` varchar(15) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `salt` (`salt`)
) TYPE=MyISAM;

-- --------------------------------------------------------

--
-- Table `pb_words`
--

DROP TABLE IF EXISTS `pb_words`;
CREATE TABLE `pb_words` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL DEFAULT '',
  `replace_to` varchar(50) NOT NULL DEFAULT '',
  `expiration` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `word` (`title`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- For 4.1

DROP TABLE IF EXISTS `pb_metas`;
CREATE TABLE IF NOT EXISTS `pb_metas` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `object_id` int(10) NOT NULL DEFAULT '0',
  `object_type` varchar(100) NOT NULL DEFAULT '',
  `content` tinytext NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM ;

DROP TABLE IF EXISTS `pb_spreadadses`;
CREATE TABLE `pb_spreadadses` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `member_id` int(10) NOT NULL DEFAULT '0',
  `title` varchar(250) NOT NULL DEFAULT '',
  `desc1` varchar(200) NOT NULL DEFAULT '',
  `desc2` varchar(200) NOT NULL DEFAULT '',
  `show_url` varchar(100) NOT NULL DEFAULT '',
  `target_url` varchar(100) NOT NULL DEFAULT '',
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;

DROP TABLE IF EXISTS `pb_spreadadstypes`;
CREATE TABLE `pb_spreadadstypes` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `member_id` int(10) NOT NULL DEFAULT '0',
  `name` varchar(250) NOT NULL DEFAULT '',
  `created` int(10) NOT NULL DEFAULT '0',
  `modified` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) TYPE=MyISAM;