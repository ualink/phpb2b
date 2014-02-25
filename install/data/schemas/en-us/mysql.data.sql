--
-- Table Datas `pb_adzones`
--

INSERT INTO `pb_adzones` (`id`, `membergroup_ids`, `what`, `style`, `name`, `description`, `additional_adwords`, `price`, `file_name`, `width`, `height`, `wrap`, `max_ad`, `created`, `modified`) VALUES(1, '8,9', '1', 0, 'index page top banner', '', '', 1000.00, 'index.php', 760, 52, 6, 12, unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_adzones` (`id`, `membergroup_ids`, `what`, `style`, `name`, `description`, `additional_adwords`, `price`, `file_name`, `width`, `height`, `wrap`, `max_ad`, `created`, `modified`) VALUES(2, '0', '1', 0, 'index banner', '', '', 3000.00, 'index.php', 958, 62, 0, 0, unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_adzones` (`id`, `membergroup_ids`, `what`, `style`, `name`, `description`, `additional_adwords`, `price`, `file_name`, `width`, `height`, `wrap`, `max_ad`, `created`, `modified`) VALUES(3, '', '1', 1, 'offer page banner', '', '', 1000.00, '', 380, 270, 0, 0, unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_adzones` (`id`, `membergroup_ids`, `what`, `style`, `name`, `description`, `additional_adwords`, `price`, `file_name`, `width`, `height`, `wrap`, `max_ad`, `created`, `modified`) VALUES(4, '', '1', 1, 'product page banner', '', '', 0.01, '', 570, 170, 0, 0, unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_adzones` (`id`, `membergroup_ids`, `what`, `style`, `name`, `description`, `additional_adwords`, `price`, `file_name`, `width`, `height`, `wrap`, `max_ad`, `created`, `modified`) VALUES(5, '0', '1', 1, 'index page big banner', '', '', 0.01, '', 473, 170, 0, 0, unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_adzones` (`id`, `membergroup_ids`, `what`, `style`, `name`, `description`, `additional_adwords`, `price`, `file_name`, `width`, `height`, `wrap`, `max_ad`, `created`, `modified`) VALUES(6, '', '1', 0, 'special page left banner', '', '', 0.00, '', 0, 0, 0, 0, unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_adzones` (`id`, `membergroup_ids`, `what`, `style`, `name`, `description`, `additional_adwords`, `price`, `file_name`, `width`, `height`, `wrap`, `max_ad`, `created`, `modified`) VALUES(7, '', '1', 0, 'Community', 'PHPB2B community', '', 0.00, '', 0, 0, 0, 0, unix_timestamp(now()), unix_timestamp(now()));

--
-- Table Datas `pb_albumtypes`
--

INSERT INTO `pb_albumtypes` (`id`, `name`, `display_order`) VALUES(1, 'Albums', 0);
INSERT INTO `pb_albumtypes` (`id`, `name`, `display_order`) VALUES(2, 'Products', 0);
INSERT INTO `pb_albumtypes` (`id`, `name`, `display_order`) VALUES(3, 'Advertisement', 0);

--
-- Table Datas `pb_announcementtypes`
--

INSERT INTO `pb_announcementtypes` (`id`, `name`) VALUES(1, 'Site Announce');
INSERT INTO `pb_announcementtypes` (`id`, `name`) VALUES(2, 'Site Ads');

--
-- Table Datas `pb_companies`
--

INSERT INTO `pb_companies` (`id`, `member_id`, `cache_spacename`, `cache_membergroupid`, `cache_credits`, `topleveldomain`, `industry_id`, `area_id`, `type_id`, `name`, `description`, `english_name`, `adwords`, `keywords`, `boss_name`, `manage_type`, `year_annual`, `property`, `configs`, `bank_from`, `bank_account`, `main_prod`, `employee_amount`, `found_date`, `reg_fund`, `reg_address`, `address`, `zipcode`, `main_brand`, `main_market`, `main_biz_place`, `main_customer`, `link_man`, `link_man_gender`, `position`, `tel`, `fax`, `mobile`, `email`, `site_url`, `picture`, `status`, `first_letter`, `if_commend`, `clicked`, `created`, `modified`) VALUES(1, 1, 'admin', 9, 0, '', 1, '3', 1, 'Ualink E-Commerce', 'PHPB2B', 'UALINK E-Commerce', '', '', 'Stephen', '1', 3, 1, 'a:1:{s:12:"templet_name";b:0;}', 'Bank Of Beijing', '12342143', '', '4', '946684800', 5, 'Beijing', 'Beijing East District', '100010', 'Ualink', '1,2,3', 'Beijing City', 'Company Unit', 'Stephen', 1, 4, '(086)10-41235678', '(086)10-41235678', '130123456782', 'service@phpb2b.com', 'http://www.phpb2b.com/', 'sample/company/1.jpg', 1, 'A', 1, 1, unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_companies` (`id`, `member_id`, `cache_spacename`, `cache_membergroupid`, `cache_credits`, `topleveldomain`, `industry_id`, `area_id`, `type_id`, `name`, `description`, `english_name`, `adwords`, `keywords`, `boss_name`, `manage_type`, `year_annual`, `property`, `configs`, `bank_from`, `bank_account`, `main_prod`, `employee_amount`, `found_date`, `reg_fund`, `reg_address`, `address`, `zipcode`, `main_brand`, `main_market`, `main_biz_place`, `main_customer`, `link_man`, `link_man_gender`, `position`, `tel`, `fax`, `mobile`, `email`, `site_url`, `picture`, `status`, `first_letter`, `if_commend`, `clicked`, `created`, `modified`) VALUES(2, 1, 'admin2', 9, 0, '', 1, '3', 1, 'Beijing Ualink E-Commerce Inc.', 'Beijing Ualink E-Commerce Inc.', '', '', '', '', '1', 0, 1, NULL, '', '', '', '', '', 5, 'Beijing', 'Beijing East District', '100010', 'Ualink', '2,3,4', 'Beijing City', '', '', 1, 4, '(086)10-41235678', '(086)10-41235678', '', 'service@phpb2b.com', 'http://www.phpb2b.com/', 'sample/company/1.jpg', 1, 'A', 1, 1, unix_timestamp(now()), 0);

--
-- Table Datas `pb_countries`
--

INSERT INTO `pb_countries` (`id`, `name`, `picture`, `display_order`) VALUES(1, 'China', 'cn.gif', 0);
INSERT INTO `pb_countries` (`id`, `name`, `picture`, `display_order`) VALUES(3, 'Hongkong', 'hk.gif', 0);

--
-- Table Datas `pb_formitems`
--

INSERT INTO `pb_formitems` (`id`, `form_id`, `title`, `description`, `identifier`, `type`, `rules`, `display_order`) VALUES(1, 0, 'Quality', '', 'product_quantity', 'text', '', 0);
INSERT INTO `pb_formitems` (`id`, `form_id`, `title`, `description`, `identifier`, `type`, `rules`, `display_order`) VALUES(2, 0, 'Package', '', 'packing', 'text', '', 0);
INSERT INTO `pb_formitems` (`id`, `form_id`, `title`, `description`, `identifier`, `type`, `rules`, `display_order`) VALUES(3, 0, 'Price', '', 'product_price', 'text', '', 0);
INSERT INTO `pb_formitems` (`id`, `form_id`, `title`, `description`, `identifier`, `type`, `rules`, `display_order`) VALUES(4, 0, 'Scale', '', 'product_specification', 'text', '', 0);
INSERT INTO `pb_formitems` (`id`, `form_id`, `title`, `description`, `identifier`, `type`, `rules`, `display_order`) VALUES(5, 0, 'Serial', '', 'serial_number', 'text', '', 0);
INSERT INTO `pb_formitems` (`id`, `form_id`, `title`, `description`, `identifier`, `type`, `rules`, `display_order`) VALUES(6, 0, 'Produce', '', 'production_place', 'text', '', 0);
INSERT INTO `pb_formitems` (`id`, `form_id`, `title`, `description`, `identifier`, `type`, `rules`, `display_order`) VALUES(7, 0, 'Brand', NULL, 'brand_name', 'text', NULL, 0);

--
-- Table Datas `pb_forms`
--

INSERT INTO `pb_forms` (`id`, `type_id`, `name`, `items`) VALUES(1, 1, 'Trade Column', '1,2,3,4,5,6');
INSERT INTO `pb_forms` (`id`, `type_id`, `name`, `items`) VALUES(2, 2, 'Product Column', '1,2,3,4,5,6,7');

--
-- Table Datas `pb_friendlinks`
--

INSERT INTO `pb_friendlinks` (`id`, `type_id`, `industry_id`, `area_id`, `title`, `logo`, `url`, `priority`, `status`, `description`, `created`, `modified`) VALUES(1, 1, 0, 0, 'PHPB2B', '', 'http://www.phpb2b.com/', 0, 1, '', 1293936472, 0);
INSERT INTO `pb_friendlinks` (`id`, `type_id`, `industry_id`, `area_id`, `title`, `logo`, `url`, `priority`, `status`, `description`, `created`, `modified`) VALUES(2, 2, 0, 0, 'PHPB2B Demo', '', 'http://demo.phpb2b.com/', 0, 1, '', 1293936472, 0);

--
-- Table Datas `pb_friendlinktypes`
--

INSERT INTO `pb_friendlinktypes` (`id`, `name`) VALUES(1, 'Links');
INSERT INTO `pb_friendlinktypes` (`id`, `name`) VALUES(2, 'Partners');

--
-- Table Datas `pb_goods`
--

INSERT INTO `pb_goods` (`id`, `type_id`, `name`, `description`, `price`, `closed`, `picture`, `if_commend`, `created`, `modified`) VALUES(2, 1, 'VIP Upgrade', '', 0.02, 1, '', 0, 1293936472, 1300889949);
INSERT INTO `pb_goods` (`id`, `type_id`, `name`, `description`, `price`, `closed`, `picture`, `if_commend`, `created`, `modified`) VALUES(1, 1, 'Professional Upgrade', '', 0.01, 1, '', 0, 1293936472, 1300889956);

--
-- Table Datas `pb_goodtypes`
--

INSERT INTO `pb_goodtypes` (`id`, `name`, `display_order`) VALUES(1, 'Service', 0);
INSERT INTO `pb_goodtypes` (`id`, `name`, `display_order`) VALUES(2, 'Cache', 0);
INSERT INTO `pb_goodtypes` (`id`, `name`, `display_order`) VALUES(3, 'Ads', 0);

--
-- Table Datas `pb_markettypes`
--

INSERT INTO `pb_markettypes` (`id`, `name`, `display_order`) VALUES(1, 'Internal', 0);
INSERT INTO `pb_markettypes` (`id`, `name`, `display_order`) VALUES(2, 'External', 0);
INSERT INTO `pb_markettypes` (`id`, `name`, `display_order`) VALUES(3, 'Super', 0);

--
-- Table Datas `pb_memberfields`
--

INSERT INTO `pb_memberfields` (`member_id`, `today_logins`, `total_logins`, `area_id`, `first_name`, `last_name`, `gender`, `tel`, `fax`, `mobile`, `qq`, `msn`, `icq`, `yahoo`, `skype`, `address`, `zipcode`, `site_url`, `question`, `answer`, `reg_ip`) VALUES(1, 0, 0, 6, 'Zhang', 'San', 1, '', '', '', '', '', '', '', '', '', '', '', '', '', '');
INSERT INTO `pb_memberfields` (`member_id`, `today_logins`, `total_logins`, `area_id`, `first_name`, `last_name`, `gender`, `tel`, `fax`, `mobile`, `qq`, `msn`, `icq`, `yahoo`, `skype`, `address`, `zipcode`, `site_url`, `question`, `answer`, `reg_ip`) VALUES(2, 0, 0, 0, 'Li', 'Si', 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '');

--
-- Table Datas `pb_membergroups`
--

INSERT INTO `pb_membergroups` (`id`, `membertype_id`, `name`, `description`, `type`, `system`, `picture`, `point_max`, `point_min`, `max_offer`, `max_product`, `max_job`, `max_companynews`, `max_producttype`, `max_album`, `max_attach_size`, `max_size_perday`, `max_favorite`, `is_default`, `allow_offer`, `allow_market`, `allow_company`, `allow_product`, `allow_job`, `allow_companynews`, `allow_album`, `allow_space`, `default_live_time`, `after_live_time`, `exempt`, `created`, `modified`) VALUES(1, 1, 'Associate', '', 'system', 'private', 'informal.gif', 0, -32767, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 2, 0, 0, 1274002638);
INSERT INTO `pb_membergroups` (`id`, `membertype_id`, `name`, `description`, `type`, `system`, `picture`, `point_max`, `point_min`, `max_offer`, `max_product`, `max_job`, `max_companynews`, `max_producttype`, `max_album`, `max_attach_size`, `max_size_perday`, `max_favorite`, `is_default`, `allow_offer`, `allow_market`, `allow_company`, `allow_product`, `allow_job`, `allow_companynews`, `allow_album`, `allow_space`, `default_live_time`, `after_live_time`, `exempt`, `created`, `modified`) VALUES(2, 1, 'Formal', '', 'system', 'private', 'formal.gif', 32767, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 1, 0, 2, 25, 0, 1274002638);
INSERT INTO `pb_membergroups` (`id`, `membertype_id`, `name`, `description`, `type`, `system`, `picture`, `point_max`, `point_min`, `max_offer`, `max_product`, `max_job`, `max_companynews`, `max_producttype`, `max_album`, `max_attach_size`, `max_size_perday`, `max_favorite`, `is_default`, `allow_offer`, `allow_market`, `allow_company`, `allow_product`, `allow_job`, `allow_companynews`, `allow_album`, `allow_space`, `default_live_time`, `after_live_time`, `exempt`, `created`, `modified`) VALUES(3, 1, 'Pending', 'Awaiting verification', 'special', 'private', 'special_checking.gif', 0, 0, 0, 0, 0, 0, 3, 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 2, 0, 0, 1274002638);
INSERT INTO `pb_membergroups` (`id`, `membertype_id`, `name`, `description`, `type`, `system`, `picture`, `point_max`, `point_min`, `max_offer`, `max_product`, `max_job`, `max_companynews`, `max_producttype`, `max_album`, `max_attach_size`, `max_size_perday`, `max_favorite`, `is_default`, `allow_offer`, `allow_market`, `allow_company`, `allow_product`, `allow_job`, `allow_companynews`, `allow_album`, `allow_space`, `default_live_time`, `after_live_time`, `exempt`, `created`, `modified`) VALUES(4, 1, 'Forbidden', 'Block access to Web site', 'special', 'private', 'special_novisit.gif', 0, 0, 0, 0, 0, 0, 3, 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 2, 0, 0, 1274002638);
INSERT INTO `pb_membergroups` (`id`, `membertype_id`, `name`, `description`, `type`, `system`, `picture`, `point_max`, `point_min`, `max_offer`, `max_product`, `max_job`, `max_companynews`, `max_producttype`, `max_album`, `max_attach_size`, `max_size_perday`, `max_favorite`, `is_default`, `allow_offer`, `allow_market`, `allow_company`, `allow_product`, `allow_job`, `allow_companynews`, `allow_album`, `allow_space`, `default_live_time`, `after_live_time`, `exempt`, `created`, `modified`) VALUES(5, 1, 'Embargo', 'Prohibit any of the information published in the Business Room', 'special', 'private', 'special_noperm.gif', 0, 0, 0, 0, 0, 0, 3, 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 2, 0, 0, 1274002638);
INSERT INTO `pb_membergroups` (`id`, `membertype_id`, `name`, `description`, `type`, `system`, `picture`, `point_max`, `point_min`, `max_offer`, `max_product`, `max_job`, `max_companynews`, `max_producttype`, `max_album`, `max_attach_size`, `max_size_perday`, `max_favorite`, `is_default`, `allow_offer`, `allow_market`, `allow_company`, `allow_product`, `allow_job`, `allow_companynews`, `allow_album`, `allow_space`, `default_live_time`, `after_live_time`, `exempt`, `created`, `modified`) VALUES(6, 1, 'Prohibition of landing', 'Prohibition of Commercial Office Login', 'special', 'private', 'special_nologin.gif', 0, 0, 0, 0, 0, 0, 3, 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 2, 0, 0, 1274002638);
INSERT INTO `pb_membergroups` (`id`, `membertype_id`, `name`, `description`, `type`, `system`, `picture`, `point_max`, `point_min`, `max_offer`, `max_product`, `max_job`, `max_companynews`, `max_producttype`, `max_album`, `max_attach_size`, `max_size_perday`, `max_favorite`, `is_default`, `allow_offer`, `allow_market`, `allow_company`, `allow_product`, `allow_job`, `allow_companynews`, `allow_album`, `allow_space`, `default_live_time`, `after_live_time`, `exempt`, `created`, `modified`) VALUES(7, 1, 'Individual Members', 'General Level Member', 'define', 'public', 'copper.gif', 0, 0, 5, 0, 0, 0, 3, 0, 0, 0, 0, 1, 3, 1, 3, 3, 3, 3, 1, 1, 1, 9, 24, 0, 1274002638);
INSERT INTO `pb_membergroups` (`id`, `membertype_id`, `name`, `description`, `type`, `system`, `picture`, `point_max`, `point_min`, `max_offer`, `max_product`, `max_job`, `max_companynews`, `max_producttype`, `max_album`, `max_attach_size`, `max_size_perday`, `max_favorite`, `is_default`, `allow_offer`, `allow_market`, `allow_company`, `allow_product`, `allow_job`, `allow_companynews`, `allow_album`, `allow_space`, `default_live_time`, `after_live_time`, `exempt`, `created`, `modified`) VALUES(8, 1, 'Senior Individual Member', 'Senior Individual Member', 'define', 'public', 'silver.gif', 0, 0, 0, 0, 0, 0, 3, 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 2, 6, 25, 0, 1274002638);
INSERT INTO `pb_membergroups` (`id`, `membertype_id`, `name`, `description`, `type`, `system`, `picture`, `point_max`, `point_min`, `max_offer`, `max_product`, `max_job`, `max_companynews`, `max_producttype`, `max_album`, `max_attach_size`, `max_size_perday`, `max_favorite`, `is_default`, `allow_offer`, `allow_market`, `allow_company`, `allow_product`, `allow_job`, `allow_companynews`, `allow_album`, `allow_space`, `default_live_time`, `after_live_time`, `exempt`, `created`, `modified`) VALUES(9, 1, 'Ordinary Corporate Member', 'Member companies at this level generally', 'define', 'public', 'gold.gif', 0, 0, 2, 2, 0, 0, 3, 0, 0, 0, 0, 0, 2, 3, 3, 2, 2, 2, 2, 1, 1, 2, 31, 0, 1274002638);
INSERT INTO `pb_membergroups` (`id`, `membertype_id`, `name`, `description`, `type`, `system`, `picture`, `point_max`, `point_min`, `max_offer`, `max_product`, `max_job`, `max_companynews`, `max_producttype`, `max_album`, `max_attach_size`, `max_size_perday`, `max_favorite`, `is_default`, `allow_offer`, `allow_market`, `allow_company`, `allow_product`, `allow_job`, `allow_companynews`, `allow_album`, `allow_space`, `default_live_time`, `after_live_time`, `exempt`, `created`, `modified`) VALUES(10, 2, 'VIP Corporate Membership', 'Senior Corporate Member', 'define', 'public', 'vip.gif', 0, 0, 0, 0, 0, 0, 3, 0, 0, 0, 0, 0, 3, 3, 3, 3, 3, 3, 3, 1, 1, 2, 31, 0, 1274002638);

--
-- Table Datas `pb_members`
--

INSERT INTO `pb_members` (`id`, `space_name`, `templet_id`, `username`, `userpass`, `email`, `points`, `credits`, `balance_amount`, `trusttype_ids`, `status`, `photo`, `membertype_id`, `membergroup_id`, `last_login`, `last_ip`, `service_start_date`, `service_end_date`, `office_redirect`, `created`, `modified`) VALUES(1, 'zxcvzxcv', 5, 'admin', '980ac217c6b51e7dc41040bec1edfec8', 'administrator@yourdomain.com', 38, 55, 500.00, '2,1', '1', '', 2, 9, '1303431038', '2130706433', '1301414400', '1304092800', 0, '1293936462', '1301585957');
INSERT INTO `pb_members` (`id`, `space_name`, `templet_id`, `username`, `userpass`, `email`, `points`, `credits`, `balance_amount`, `trusttype_ids`, `status`, `photo`, `membertype_id`, `membergroup_id`, `last_login`, `last_ip`, `service_start_date`, `service_end_date`, `office_redirect`, `created`, `modified`) VALUES(2, 'athena', 1, 'athena', 'e10adc3949ba59abbe56e057f20f883e', 'administrator@host.com', 81, 80, 0.00, '1,2', '1', '', 2, 9, '1293936472', '2130706433', '1293936472', '1294022872', 0, '1293936472', '0');

--
-- Table Datas `pb_membertypes`
--

INSERT INTO `pb_membertypes` (`id`, `default_membergroup_id`, `name`, `description`) VALUES(1, 7, 'Personal', 'Personal Member');
INSERT INTO `pb_membertypes` (`id`, `default_membergroup_id`, `name`, `description`) VALUES(2, 9, 'Company', 'Company Member');
INSERT INTO `pb_membertypes` (`id`, `default_membergroup_id`, `name`, `description`) VALUES(3, 10, 'Shop', 'Shoper');

--
-- Table Datas `pb_navs`
--

INSERT INTO `pb_navs` (`id`, `parent_id`, `name`, `description`, `url`, `target`, `status`, `display_order`, `highlight`, `level`, `class_name`, `created`, `modified`) VALUES(1, 0, 'Home', '', 'index.php', '_self', 1, 1, 0, 0, '', unix_timestamp(now()), 0);
INSERT INTO `pb_navs` (`id`, `parent_id`, `name`, `description`, `url`, `target`, `status`, `display_order`, `highlight`, `level`, `class_name`, `created`, `modified`) VALUES(2, 0, 'Buy', '', 'index.php?do=offer&action=lists&typeid=1&navid=2', '_self', 1, 2, 0, 0, '', unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_navs` (`id`, `parent_id`, `name`, `description`, `url`, `target`, `status`, `display_order`, `highlight`, `level`, `class_name`, `created`, `modified`) VALUES(3, 0, 'Sell', '', 'index.php?do=offer&action=lists&typeid=2&navid=3', '_self', 1, 3, 0, 0, '', unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_navs` (`id`, `parent_id`, `name`, `description`, `url`, `target`, `status`, `display_order`, `highlight`, `level`, `class_name`, `created`, `modified`) VALUES(4, 0, 'Invest', '', 'index.php?do=offer&action=invest', '_self', 1, 5, 0, 0, '', unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_navs` (`id`, `parent_id`, `name`, `description`, `url`, `target`, `status`, `display_order`, `highlight`, `level`, `class_name`, `created`, `modified`) VALUES(5, 0, 'Fair', '', 'index.php?do=fair', '_self', 1, 6, 0, 0, '', unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_navs` (`id`, `parent_id`, `name`, `description`, `url`, `target`, `status`, `display_order`, `highlight`, `level`, `class_name`, `created`, `modified`) VALUES(6, 0, 'Quote', '', 'index.php?do=market&action=quote', '_self', 1, 8, 0, 0, '', unix_timestamp(now()), 0);
INSERT INTO `pb_navs` (`id`, `parent_id`, `name`, `description`, `url`, `target`, `status`, `display_order`, `highlight`, `level`, `class_name`, `created`, `modified`) VALUES(7, 0, 'Market', '', 'index.php?do=market', '_self', 1, 9, 0, 0, '', unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_navs` (`id`, `parent_id`, `name`, `description`, `url`, `target`, `status`, `display_order`, `highlight`, `level`, `class_name`, `created`, `modified`) VALUES(9, 0, 'Job', '', 'index.php?do=job', '_self', 1, 11, 0, 0, '', unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_navs` (`id`, `parent_id`, `name`, `description`, `url`, `target`, `status`, `display_order`, `highlight`, `level`, `class_name`, `created`, `modified`) VALUES(10, 0, 'Brand', '', 'index.php?do=brand', '_self', 1, 7, 0, 0, '', unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_navs` (`id`, `parent_id`, `name`, `description`, `url`, `target`, `status`, `display_order`, `highlight`, `level`, `class_name`, `created`, `modified`) VALUES(11, 0, 'Wholesale', '', 'index.php?do=offer&action=wholesale', '_self', 1, 4, 0, 0, '', unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_navs` (`id`, `parent_id`, `name`, `description`, `url`, `target`, `status`, `display_order`, `highlight`, `level`, `class_name`, `created`, `modified`) VALUES(12, 0, 'Dict', 'Industry Dictionary', 'index.php?do=dict', '_self', 1, 12, 0, 0, '', unix_timestamp(now()), unix_timestamp(now()));

--
-- Table Datas `pb_productsorts`
--

INSERT INTO `pb_productsorts` (`id`, `name`, `display_order`) VALUES(1, 'Newest Product', 0);
INSERT INTO `pb_productsorts` (`id`, `name`, `display_order`) VALUES(2, 'Stored Product', 0);
INSERT INTO `pb_productsorts` (`id`, `name`, `display_order`) VALUES(3, 'Common Product', 0);

--
-- Table Datas `pb_settings`
--

INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(348, 0, 'site_name', 'PHPB2B');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(349, 0, 'site_title', 'A New PHPB2B Site - Powered By PHPB2B');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(350, 0, 'site_banner_word', 'The Professional B2B Online');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(4, 0, 'company_name', 'Copyright');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(352, 0, 'site_url', 'http://www.host.com/');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(353, 0, 'icp_number', 'ICP Num.');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(7, 0, 'service_tel', '(86)10-41235678');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(8, 0, 'sale_tel', '(86)10-41235678');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(9, 0, 'service_qq', '1319250566');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(10, 0, 'service_msn', 'service@host.com');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(11, 0, 'service_email', 'service@host.com');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(324, 0, 'site_description', '<p>phpb2b description</p>');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(13, 0, 'cp_picture', '0');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(14, 0, 'register_picture', '0');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(15, 0, 'login_picture', '0');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(16, 0, 'vispost_auth', '1');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(17, 0, 'watermark', '1');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(62, 0, 'watertext', 'PHPB2B');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(19, 0, 'watercolor', '#990000');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(20, 0, 'add_market_check', '1');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(21, 0, 'regcheck', '0');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(268, 0, 'vis_post', '1');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(270, 0, 'vis_post_check', '0');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(271, 0, 'sell_logincheck', '1');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(272, 0, 'buy_logincheck', '0');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(57, 0, 'install_dateline', unix_timestamp(now()));
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(27, 0, 'last_backup', unix_timestamp(now()));
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(28, 0, 'smtp_server', 'smtp.yourdomain.com');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(29, 0, 'smtp_port', '25');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(30, 0, 'smtp_auth', '1');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(31, 0, 'mail_from', 'administrator@host.com');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(32, 0, 'mail_fromwho', 'Administrator');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(33, 0, 'auth_username', 'administrator@host.com');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(34, 0, 'auth_password', 'password');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(35, 0, 'send_mail', '2');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(36, 0, 'sendmail_silent', '1');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(37, 0, 'mail_delimiter', '0');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(360, 0, 'reg_filename', 'register.php');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(402, 0, 'new_userauth', '0');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(361, 0, 'post_filename', 'post.php');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(41, 0, 'forbid_ip', '');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(403, 0, 'ip_reg_sep', '0');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(60, 0, 'backup_dir', 'EdSJUs');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(258, 0, 'capt_logging', '0');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(259, 0, 'capt_register', '1');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(260, 0, 'capt_post_free', '0');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(261, 0, 'capt_add_market', '0');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(262, 0, 'capt_login_admin', '1');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(263, 0, 'capt_apply_friendlink', '0');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(264, 0, 'capt_service', '0');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(51, 0, 'backup_type', '1');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(400, 0, 'register_type', 'open_common_reg');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(267, 0, 'auth_key', 'MPQJq&D6');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(54, 0, 'keyword_bidding', '0');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(201, 0, 'passport_support', '0');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(351, 0, 'site_logo', 'static/images/logo.jpg');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(362, 0, 'main_cache', '1');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(363, 0, 'member_cache', '0');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(364, 0, 'space_cache', '0');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(365, 0, 'label_cache', '0');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(366, 0, 'main_cache_lifetime', '3600');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(367, 0, 'main_cache_check', '0');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(344, 1, 'update_alert_type', '1');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(345, 1, 'update_alert_lasttime', '1301549982');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(369, 0, 'theme', 'red');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(269, 0, 'tag_check', '0');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(273, 0, 'session_savepath', '0');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(274, 1, 'offer_expire_method', '1');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(275, 1, 'offer_moderate_point', '0');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(276, 1, 'offer_refresh_lower_day', '3');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(277, 1, 'offer_update_lower_hour', '24');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(278, 1, 'offer_filter', '0');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(358, 0, 'redirect_url', '');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(399, 0, 'languages', '');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(346, 0, 'time_offset', '0');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(347, 0, 'date_format', 'Y-m-d');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(401, 0, 'agreement', 'Demo');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(359, 0, 'space_name', 'Space');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(404, 0, 'welcome_msg', '0');

--
-- Table Datas `pb_tags`
--

INSERT INTO `pb_tags` (`id`, `member_id`, `name`, `numbers`, `closed`, `flag`, `created`, `modified`) VALUES(1, 0, 'Hots', 1, 0, 0, unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_tags` (`id`, `member_id`, `name`, `numbers`, `closed`, `flag`, `created`, `modified`) VALUES(2, 0, 'Search', 2, 0, 1, unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_tags` (`id`, `member_id`, `name`, `numbers`, `closed`, `flag`, `created`, `modified`) VALUES(3, 0, 'Label', 1, 0, 2, unix_timestamp(now()), unix_timestamp(now()));

--
-- Table Datas `pb_templets`
--

INSERT INTO `pb_templets` (`id`, `name`, `title`, `directory`, `type`, `author`, `style`, `description`, `is_default`, `require_membertype`, `require_membergroups`, `status`) VALUES(3, 'brown', 'Brown Space Skin', 'skins/brown/', 'user', 'PB TEAM', '', 'A PHPB2B Corperate Templet. Enjoy!', 0, '0', '0', 1);
INSERT INTO `pb_templets` (`id`, `name`, `title`, `directory`, `type`, `author`, `style`, `description`, `is_default`, `require_membertype`, `require_membergroups`, `status`) VALUES(4, 'red', 'Red Space Skin', 'skins/red/', 'user', 'PB TEAM', '', 'A PHPB2B Corperate Templet. Enjoy!', 0, '0', '0', 1);
INSERT INTO `pb_templets` (`id`, `name`, `title`, `directory`, `type`, `author`, `style`, `description`, `is_default`, `require_membertype`, `require_membergroups`, `status`) VALUES(5, 'default', 'Default Space Skin', 'skins/default/', 'user', 'PB TEAM', '', 'A PHPB2B Corperate Templet. Enjoy!', 0, '0', '0', 1);
INSERT INTO `pb_templets` (`id`, `name`, `title`, `directory`, `type`, `author`, `style`, `description`, `is_default`, `require_membertype`, `require_membergroups`, `status`) VALUES(7, 'green', 'Green Space Skin', 'skins/green/', 'user', 'PB TEAM', '', 'A PHPB2B Corperate Templet. Enjoy!', 0, '0', '0', 1);
INSERT INTO `pb_templets` (`id`, `name`, `title`, `directory`, `type`, `author`, `style`, `description`, `is_default`, `require_membertype`, `require_membergroups`, `status`) VALUES(8, 'orange', 'Orange Space Skin', 'skins/orange/', 'user', 'PB TEAM', '', 'A PHPB2B Corperate Templet. Enjoy!', 0, '0', '0', 1);
INSERT INTO `pb_templets` (`id`, `name`, `title`, `directory`, `type`, `author`, `style`, `description`, `is_default`, `require_membertype`, `require_membergroups`, `status`) VALUES(12, 'default', 'Default Site Templet', 'templates/default/', 'system', 'PB TEAM', '#4DB5F8', 'A Ualink Default Template. Enjoy!', 0, '0', '0', 1);

--
-- Table Datas `pb_topics`
--

INSERT INTO `pb_topics` (`id`, `alias_name`, `templet`, `title`, `picture`, `description`, `created`, `modified`) VALUES(1, 'test1', 'test1', 'London 2012', 'sample/topic/1.jpg', '', unix_timestamp(now()), 0);
INSERT INTO `pb_topics` (`id`, `alias_name`, `templet`, `title`, `picture`, `description`, `created`, `modified`) VALUES(2, 'test2', '', 'Ipad3 Released', 'sample/topic/2.jpg', '', unix_timestamp(now()), 0);
INSERT INTO `pb_topics` (`id`, `alias_name`, `templet`, `title`, `picture`, `description`, `created`, `modified`) VALUES(3, 'test3', '', 'China import and export fair', 'sample/topic/3.jpg', '', unix_timestamp(now()), 0);

--
-- Table Datas `pb_tradetypes`
--

INSERT INTO `pb_tradetypes` (`id`, `parent_id`, `name`, `level`, `display_order`) VALUES(1, 0, 'Buy', 1, 0);
INSERT INTO `pb_tradetypes` (`id`, `parent_id`, `name`, `level`, `display_order`) VALUES(2, 0, 'Sell', 1, 0);
INSERT INTO `pb_tradetypes` (`id`, `parent_id`, `name`, `level`, `display_order`) VALUES(3, 0, 'Agent', 1, 0);
INSERT INTO `pb_tradetypes` (`id`, `parent_id`, `name`, `level`, `display_order`) VALUES(4, 3, 'Ship', 1, 0);
INSERT INTO `pb_tradetypes` (`id`, `parent_id`, `name`, `level`, `display_order`) VALUES(5, 0, 'Commercial', 1, 0);
INSERT INTO `pb_tradetypes` (`id`, `parent_id`, `name`, `level`, `display_order`) VALUES(6, 5, 'Invest', 1, 0);
INSERT INTO `pb_tradetypes` (`id`, `parent_id`, `name`, `level`, `display_order`) VALUES(7, 0, 'Store', 1, 0);
INSERT INTO `pb_tradetypes` (`id`, `parent_id`, `name`, `level`, `display_order`) VALUES(8, 7, 'Stock', 1, 0);
INSERT INTO `pb_tradetypes` (`id`, `parent_id`, `name`, `level`, `display_order`) VALUES(9, 1, 'Internal', 2, 0);
INSERT INTO `pb_tradetypes` (`id`, `parent_id`, `name`, `level`, `display_order`) VALUES(10, 1, 'External', 2, 0);
INSERT INTO `pb_tradetypes` (`id`, `parent_id`, `name`, `level`, `display_order`) VALUES(11, 7, 'Wholesale', 2, 0);
INSERT INTO `pb_tradetypes` (`id`, `parent_id`, `name`, `level`, `display_order`) VALUES(12, 3, 'Agent', 2, 0);
INSERT INTO `pb_tradetypes` (`id`, `parent_id`, `name`, `level`, `display_order`) VALUES(13, 5, 'Investment ', 2, 0);

--
-- Table Datas `pb_trusttypes`
--

INSERT INTO `pb_trusttypes` (`id`, `name`, `description`, `image`, `display_order`, `status`) VALUES(2, 'Company', NULL, 'company.gif', 0, 1);
INSERT INTO `pb_trusttypes` (`id`, `name`, `description`, `image`, `display_order`, `status`) VALUES(1, 'ID', NULL, 'truename.gif', 0, 1);

-- 
-- Export data in the table `pb_typemodels`
-- 

INSERT INTO `pb_typemodels` (`id`, `title`, `type_name`) VALUES 
(1, 'expiration time', 'offer_expire'),
(2, 'Type', 'manage_type'),
(3, 'major markets', 'main_market'),
(4, 'registered capital', 'reg_fund'),
(5, 'turnover', 'year_annual'),
(6, 'economy type', 'economic_type'),
(7, 'moderation status', 'check_status'),
(8, 'employees', 'employee_amount'),
(9, 'status', 'common_status'),
(10, 'the proposed type', 'service_type'),
(11, 'educational experience', 'education'),
(12, 'wages', 'salary'),
(13, 'the nature', 'work_type'),
(14, 'Job Title', 'position'),
(15, 'gender', 'gender'),
(16, 'Phone Type', 'phone_type'),
(17, 'instant messaging category', 'im_type'),
(18, 'option', 'common_option'),
(19, 'honorific', 'calls'),
(20, 'units', 'measuring'),
(21, 'currency', 'monetary'),
(22, 'quote type', 'price_type'),
(23, 'price trend', 'price_trends');

-- 
-- Export data in the table `pb_typeoptions`
-- 

INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES 
(1, 1,'10','10 days'), 
(2, 1,'30','month'),
(3, 1,'90','three'), 
(4, 1,'180','six'), 
(5, 2,'1','production'), 
(6, 2,'2','trade type'), 
(7, 2,'3','service'), 
(8, 2,'4','the Government or other agencies'), 
(9, 3,'1','China'), 
(10, 3,'2','Hong Kong, Macao and Taiwan'), 
(11, 3,'3','North America'), 
(12, 3,'4','South America'), 
(13, 3,'5','Europe'), 
(14, 3,'6','Asia'), 
(15, 3,'7','Africa'), 
(16, 3,'8','Oceania'), 
(17, 3,'9','other market'), 
(18, 4,'0','closed'), 
(19, 4,'1','one hundred thousand yuan less'), 
(20, 4,'2','RMB 10-30 million'), 
(21, 4,'3','RMB 30-50 million'), 
(22, 4,'4','RMB 50-100 million'), 
(23, 4,'5','RMB 100-300 million'), 
(24, 4,'6','RMB 300-500 million'), 
(25, 4,'7','RMB 500-1,000 million'), 
(26, 4,'8','million RMB 1000-5000'), 
(27, 4,'9','more than RMB 50 million'), 
(28, 4,'10','other'), 
(29, 5,'1','RMB 10 million or less/year'), 
(30, 5,'2','RMB 10-30 million/year'), 
(31, 5,'3','RMB 30-50 million/year'), 
(32, 5,'4','RMB 50-100 million/year'), 
(33, 5,'5','RMB 100-300 million/year'), 
(34, 5,'6','RMB 300-500 million/year'), 
(35, 5,'7','RMB 500-1,000 million/year'), 
(36, 5,'8','RMB 1000-5000 million/year'), 
(37, 5,'9', 'more than 50 million RMB/year'),
(38, 5,'10','other'), 
(39, 6,'1','state-owned enterprises'), 
(40, 6,'2','collective enterprises'), 
(41, 6,'3','Corporations'), 
(42, 6,'4','joint venture'), 
(43, 6,'5','limited liability company') ,
(44, 6,'6','Corporation'), 
(45, 6,'7','private'), 
(46, 6,'8','individual enterprise'), 
(47, 6,'9','non-profit organization'), 
(48, 6,'10','other'), 
(49, 7,'0','invalid'), 
(50, 7,'1','effective'), 
(51, 7,'2','awaiting approval'), 
(52, 7,'3','audit is not passed'), 
(53, 8,'1','5 less'), 
(54, 8,'2','5-10 people'), 
(55, 8,'3','11-50 people'), 
(56, 8,'4','51-100 people'), 
(57, 8,'5','101-500 persons'), 
(58, 8,'6','501-1000 person'), 
(59, 8,'7','1000 or more'), 
(60, 10,'1','consultation'), 
(61, 10,'2','proposal'), 
(62, 10,'3','complaints'), 
(63, 11,'0','other'), 
(64, 11,'-1','not required'), 
(65, 11,'-2','open'), 
(66, 11,'1','Doctor'), 
(67, 11,'2','Master'), 
(68, 11,'3','undergraduate'), 
(69, 11,'4','college'), 
(70, 11,'5','secondary'), 
(71, 11,'6','technical school'), 
(72, 11,'7','high'), 
(73, 11,'8','middle'), 
(74, 11,'9','primary'), 
(75, 12,'0','no choice'), 
(76, 12,'-1','Interview'), 
(77, 12,'1','1500 less'), 
(78, 12,'2','1500-1999 RMB/month'), 
(79, 12,'3','2000-2999 yuan/month'), 
(80, 12,'4','3000-4999 yuan/month'), 
(81, 12,'5','5000 above'), 
(82, 13,'0','no choice'), 
(83, 13,'1','full'), 
(84, 13,'2','part-time'), 
(85, 13,'3','provisional'), 
(86, 13,'4','practice'), 
(87, 13,'5','other'), 
(88, 14,'0','no choice'), 
(89, 14,'1','chairman, president and deputies'), 
(90, 14,'2','the executive branch managers/executives'), 
(91, 14,'3','technical manager/technical staff'), 
(92, 14,'4','production manager/production staff'), 
(93, 14,'5','marketing manager/marketing staff'), 
(94, 14,'6,','purchasing department manager/procurement officer') ,
(95, 14,'7','sales manager/sales'), 
(96, 14,'8','other'), 
(97, 15,'0','no choice'), 
(98, 15,'1','Male'), 
(99, 15,'2','Female'), 
(100, 15,'-1','open'), 
(101, 16,'1','mobile phone'), 
(102, 16,'2','residential'), 
(103, 16,'3','business phone'), 
(104, 16,'4','other'), 
(105, 17,'1','QQ'), 
(106, 17,'2','ICQ'), 
(107, 17,'3','MSN Messenger'), 
(108, 17,'4','Yahoo Messenger'), 
(109, 17,'5','Skype'), 
(110, 17,'6','other'), 
(111, 17,'0','no choice'), 
(112, 16,'0','no choice'), 
(113, 6,'0','no choice'), 
(114, 9,'0','invalid'), 
(115, 9,'1','effective'), 
(116, 18,'0','no'), 
(117, 18,'1','yes'), 
(118, 19,'1','Mr.'), 
(119, 19,'2','Ms.'), 
(120, 20,'1','single'), 
(121, 20,'2','pieces'), 
(122, 21,'1','element'), 
(123, 21,'3','USD'), 
(124, 22,'1','buy'), 
(125, 22,'2','sell'), 
(126, 23,'1','up'), 
(127, 23,'2','stable'), 
(128, 23,'3','down'), 
(129, 23,'4','uncertain'), 
(130, 21,'2','million');

--
-- Table Datas `pb_userpages`
--

INSERT INTO `pb_userpages` (`id`, `templet_name`, `name`, `title`, `digest`, `content`, `url`, `display_order`, `created`, `modified`) VALUES(1, '', 'aboutus', 'About Us', '', '', '', 0, unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_userpages` (`id`, `templet_name`, `name`, `title`, `digest`, `content`, `url`, `display_order`, `created`, `modified`) VALUES(2, '', 'contactus', 'Contacts', '', '', '', 0, unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_userpages` (`id`, `templet_name`, `name`, `title`, `digest`, `content`, `url`, `display_order`, `created`, `modified`) VALUES(4, '', 'sitemap', 'Sitemap', '', '', '', 0, unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_userpages` (`id`, `templet_name`, `name`, `title`, `digest`, `content`, `url`, `display_order`, `created`, `modified`) VALUES(5, '', 'legal', 'Agreement', '', '', '', 0, unix_timestamp(now()), 0);
INSERT INTO `pb_userpages` (`id`, `templet_name`, `name`, `title`, `digest`, `content`, `url`, `display_order`, `created`, `modified`) VALUES(6, '', 'friendlink', 'Links', '', '', 'index.php?do=friendlink', 0, unix_timestamp(now()), 0);
INSERT INTO `pb_userpages` (`id`, `templet_name`, `name`, `title`, `digest`, `content`, `url`, `display_order`, `created`, `modified`) VALUES(7, '', 'help', 'Helps', '', '', 'index.php?do=help', 0, unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_userpages` (`id`, `templet_name`, `name`, `title`, `digest`, `content`, `url`, `display_order`, `created`, `modified`) VALUES(8, '', 'service', 'Service', '', '', '', 0, unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_userpages` (`id`, `templet_name`, `name`, `title`, `digest`, `content`, `url`, `display_order`, `created`, `modified`) VALUES(9, '', 'special', 'Special', '', '', 'index.php?do=topic', 0, unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_userpages` (`templet_name`, `name`, `title`, `digest`, `content`, `url`, `display_order`, `created`, `modified`) VALUES('', 'wap', 'WAP', 'Wap', '', 'index.php?do=wap', 0, unix_timestamp(now()), unix_timestamp(now()));