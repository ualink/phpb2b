--
-- Table Datas `pb_adzones`
--

INSERT INTO `pb_adzones` (`id`, `membergroup_ids`, `what`, `style`, `name`, `description`, `additional_adwords`, `price`, `file_name`, `width`, `height`, `wrap`, `max_ad`, `created`, `modified`) VALUES(1, '8,9', '1', 0, '首页顶部小图片广告', '6个图片一行，首页显示', '', 1000.00, 'index.php', 760, 52, 6, 12, unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_adzones` (`id`, `membergroup_ids`, `what`, `style`, `name`, `description`, `additional_adwords`, `price`, `file_name`, `width`, `height`, `wrap`, `max_ad`, `created`, `modified`) VALUES(2, '0', '1', 0, '首页横幅广告', '免费开源，支持多国语言，友邻B2B', '', 3000.00, 'index.php', 958, 62, 0, 0, unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_adzones` (`id`, `membergroup_ids`, `what`, `style`, `name`, `description`, `additional_adwords`, `price`, `file_name`, `width`, `height`, `wrap`, `max_ad`, `created`, `modified`) VALUES(3, '', '1', 1, '商机首页广告', '找商机首页', '', 1000.00, '', 380, 270, 0, 0, unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_adzones` (`id`, `membergroup_ids`, `what`, `style`, `name`, `description`, `additional_adwords`, `price`, `file_name`, `width`, `height`, `wrap`, `max_ad`, `created`, `modified`) VALUES(4, '', '1', 1, '产品首页广告', '6个图片一行，首页显示', '', 0.01, '', 570, 170, 0, 0, unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_adzones` (`id`, `membergroup_ids`, `what`, `style`, `name`, `description`, `additional_adwords`, `price`, `file_name`, `width`, `height`, `wrap`, `max_ad`, `created`, `modified`) VALUES(5, '0', '1', 1, '首页大图广告', '首页宣传1', '', 0.01, '', 473, 170, 0, 0, unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_adzones` (`id`, `membergroup_ids`, `what`, `style`, `name`, `description`, `additional_adwords`, `price`, `file_name`, `width`, `height`, `wrap`, `max_ad`, `created`, `modified`) VALUES(6, '', '1', 0, '专题页面左侧广告', '左侧位置广告', '', 0.00, '', 0, 0, 0, 0, unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_adzones` (`id`, `membergroup_ids`, `what`, `style`, `name`, `description`, `additional_adwords`, `price`, `file_name`, `width`, `height`, `wrap`, `max_ad`, `created`, `modified`) VALUES(7, '', '1', 0, 'Community', 'PHPB2B community', '', 0.00, '', 0, 0, 0, 0, unix_timestamp(now()), unix_timestamp(now()));

--
-- Table Datas `pb_albumtypes`
--

INSERT INTO `pb_albumtypes` (`id`, `name`, `display_order`) VALUES(1, '企业相册', 0);
INSERT INTO `pb_albumtypes` (`id`, `name`, `display_order`) VALUES(2, '产品图片', 0);
INSERT INTO `pb_albumtypes` (`id`, `name`, `display_order`) VALUES(3, '广告宣传', 0);

--
-- Table Datas `pb_announcementtypes`
--

INSERT INTO `pb_announcementtypes` (`id`, `name`) VALUES(1, '网站公告');
INSERT INTO `pb_announcementtypes` (`id`, `name`) VALUES(2, '广告时间');

--
-- Table Datas `pb_companies`
--

INSERT INTO `pb_companies` (`id`, `member_id`, `cache_spacename`, `cache_membergroupid`, `cache_credits`, `topleveldomain`, `industry_id`, `area_id`, `type_id`, `name`, `description`, `english_name`, `adwords`, `keywords`, `boss_name`, `manage_type`, `year_annual`, `property`, `configs`, `bank_from`, `bank_account`, `main_prod`, `employee_amount`, `found_date`, `reg_fund`, `reg_address`, `address`, `zipcode`, `main_brand`, `main_market`, `main_biz_place`, `main_customer`, `link_man`, `link_man_gender`, `position`, `tel`, `fax`, `mobile`, `email`, `site_url`, `picture`, `status`, `first_letter`, `if_commend`, `clicked`, `created`, `modified`) VALUES(1, 1, 'admin', 9, 0, '', 1, '3', 1, '友邻电子商务', '友邻B2B', 'UALINK E-Commerce', '', '', '张三', '1', 3, 1, 'a:1:{s:12:"templet_name";b:0;}', '北京银行', '12342143', '', '4', '946684800', 5, '北京', '北京市东城区', '100010', '友邻', '1,2,3', '北京市', '各企事业单位', '张三', 1, 4, '(086)10-41235678', '(086)10-41235678', '130123456782', 'service@phpb2b.org', 'http://www.phpb2b.com/', 'sample/company/1.jpg', 1, 'A', 1, 1, unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_companies` (`id`, `member_id`, `cache_spacename`, `cache_membergroupid`, `cache_credits`, `topleveldomain`, `industry_id`, `area_id`, `type_id`, `name`, `description`, `english_name`, `adwords`, `keywords`, `boss_name`, `manage_type`, `year_annual`, `property`, `configs`, `bank_from`, `bank_account`, `main_prod`, `employee_amount`, `found_date`, `reg_fund`, `reg_address`, `address`, `zipcode`, `main_brand`, `main_market`, `main_biz_place`, `main_customer`, `link_man`, `link_man_gender`, `position`, `tel`, `fax`, `mobile`, `email`, `site_url`, `picture`, `status`, `first_letter`, `if_commend`, `clicked`, `created`, `modified`) VALUES(2, 1, 'admin2', 9, 0, '', 1, '3', 1, '北京友邻电子商务科技有限公司', '北京友邻电子商务科技有限公司', '', '', '', '', '1', 0, 1, NULL, '', '', '', '', '', 5, '北京', '北京市东城区', '100010', '友邻', '2,3,4', '北京市', '', '', 1, 4, '(086)10-41235678', '(086)10-41235678', '', 'service@phpb2b.org', 'http://www.phpb2b.com/', 'sample/company/1.jpg', 1, 'A', 1, 1, unix_timestamp(now()), 0);

--
-- Table Datas `pb_countries`
--

INSERT INTO `pb_countries` (`id`, `name`, `picture`, `display_order`) VALUES(1, '中国', 'cn.gif', 0);
INSERT INTO `pb_countries` (`id`, `name`, `picture`, `display_order`) VALUES(3, '香港', 'hk.gif', 0);

--
-- Table Datas `pb_formitems`
--

INSERT INTO `pb_formitems` (`id`, `form_id`, `title`, `description`, `identifier`, `type`, `rules`, `display_order`) VALUES(1, 0, '产品数量', '', 'product_quantity', 'text', '', 0);
INSERT INTO `pb_formitems` (`id`, `form_id`, `title`, `description`, `identifier`, `type`, `rules`, `display_order`) VALUES(2, 0, '包装说明', '', 'packing', 'text', '', 0);
INSERT INTO `pb_formitems` (`id`, `form_id`, `title`, `description`, `identifier`, `type`, `rules`, `display_order`) VALUES(3, 0, '价格说明', '', 'product_price', 'text', '', 0);
INSERT INTO `pb_formitems` (`id`, `form_id`, `title`, `description`, `identifier`, `type`, `rules`, `display_order`) VALUES(4, 0, '产品规格', '', 'product_specification', 'text', '', 0);
INSERT INTO `pb_formitems` (`id`, `form_id`, `title`, `description`, `identifier`, `type`, `rules`, `display_order`) VALUES(5, 0, '产品编号', '', 'serial_number', 'text', '', 0);
INSERT INTO `pb_formitems` (`id`, `form_id`, `title`, `description`, `identifier`, `type`, `rules`, `display_order`) VALUES(6, 0, '产地', '', 'production_place', 'text', '', 0);
INSERT INTO `pb_formitems` (`id`, `form_id`, `title`, `description`, `identifier`, `type`, `rules`, `display_order`) VALUES(7, 0, '品牌', NULL, 'brand_name', 'text', NULL, 0);

--
-- Table Datas `pb_forms`
--

INSERT INTO `pb_forms` (`id`, `type_id`, `name`, `items`) VALUES(1, 1, '供求自定义字段', '1,2,3,4,5,6');
INSERT INTO `pb_forms` (`id`, `type_id`, `name`, `items`) VALUES(2, 2, '产品自定义字段', '1,2,3,4,5,6,7');

--
-- Table Datas `pb_friendlinks`
--

INSERT INTO `pb_friendlinks` (`id`, `type_id`, `industry_id`, `area_id`, `title`, `logo`, `url`, `priority`, `status`, `description`, `created`, `modified`) VALUES(1, 1, 0, 0, 'PHPB2B', '', 'http://www.phpb2b.com/', 0, 1, '', 1293936472, 0);
INSERT INTO `pb_friendlinks` (`id`, `type_id`, `industry_id`, `area_id`, `title`, `logo`, `url`, `priority`, `status`, `description`, `created`, `modified`) VALUES(2, 2, 0, 0, 'PHPB2B 演示', '', 'http://demo.phpb2b.com/', 0, 1, '', 1293936472, 0);

--
-- Table Datas `pb_friendlinktypes`
--

INSERT INTO `pb_friendlinktypes` (`id`, `name`) VALUES(1, '友情链接');
INSERT INTO `pb_friendlinktypes` (`id`, `name`) VALUES(2, '合作伙伴');

--
-- Table Datas `pb_goods`
--

INSERT INTO `pb_goods` (`id`, `type_id`, `name`, `description`, `price`, `closed`, `picture`, `if_commend`, `created`, `modified`) VALUES(2, 1, '高级会员升级', '', 0.02, 1, '', 0, 1293936472, 1300889949);
INSERT INTO `pb_goods` (`id`, `type_id`, `name`, `description`, `price`, `closed`, `picture`, `if_commend`, `created`, `modified`) VALUES(1, 1, '普通会员升级', '', 0.01, 1, '', 0, 1293936472, 1300889956);

--
-- Table Datas `pb_goodtypes`
--

INSERT INTO `pb_goodtypes` (`id`, `name`, `display_order`) VALUES(1, '商业服务', 0);
INSERT INTO `pb_goodtypes` (`id`, `name`, `display_order`) VALUES(2, '充值', 0);
INSERT INTO `pb_goodtypes` (`id`, `name`, `display_order`) VALUES(3, '广告位', 0);

--
-- Table Datas `pb_markettypes`
--

INSERT INTO `pb_markettypes` (`id`, `name`, `display_order`) VALUES(1, '国内市场', 0);
INSERT INTO `pb_markettypes` (`id`, `name`, `display_order`) VALUES(2, '国外市场', 0);
INSERT INTO `pb_markettypes` (`id`, `name`, `display_order`) VALUES(3, '超级市场', 0);

--
-- Table Datas `pb_memberfields`
--

INSERT INTO `pb_memberfields` (`member_id`, `today_logins`, `total_logins`, `area_id`, `first_name`, `last_name`, `gender`, `tel`, `fax`, `mobile`, `qq`, `msn`, `icq`, `yahoo`, `skype`, `address`, `zipcode`, `site_url`, `question`, `answer`, `reg_ip`) VALUES(1, 0, 0, 6, '张', '三', 1, '', '', '', '', '', '', '', '', '', '', '', '', '', '127.0.0.1');
INSERT INTO `pb_memberfields` (`member_id`, `today_logins`, `total_logins`, `area_id`, `first_name`, `last_name`, `gender`, `tel`, `fax`, `mobile`, `qq`, `msn`, `icq`, `yahoo`, `skype`, `address`, `zipcode`, `site_url`, `question`, `answer`, `reg_ip`) VALUES(2, 0, 0, 0, '李', '四', 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '127.0.0.1');

--
-- Table Datas `pb_membergroups`
--

INSERT INTO `pb_membergroups` (`id`, `membertype_id`, `name`, `description`, `type`, `system`, `picture`, `point_max`, `point_min`, `max_offer`, `max_product`, `max_job`, `max_companynews`, `max_producttype`, `max_album`, `max_attach_size`, `max_size_perday`, `max_favorite`, `is_default`, `allow_offer`, `allow_market`, `allow_company`, `allow_product`, `allow_job`, `allow_companynews`, `allow_album`, `allow_space`, `default_live_time`, `after_live_time`, `exempt`, `created`, `modified`) VALUES(1, 1, '非正式会员', '', 'system', 'private', 'informal.gif', 0, -32767, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 2, 0, 0, 1274002638);
INSERT INTO `pb_membergroups` (`id`, `membertype_id`, `name`, `description`, `type`, `system`, `picture`, `point_max`, `point_min`, `max_offer`, `max_product`, `max_job`, `max_companynews`, `max_producttype`, `max_album`, `max_attach_size`, `max_size_perday`, `max_favorite`, `is_default`, `allow_offer`, `allow_market`, `allow_company`, `allow_product`, `allow_job`, `allow_companynews`, `allow_album`, `allow_space`, `default_live_time`, `after_live_time`, `exempt`, `created`, `modified`) VALUES(2, 1, '正式会员', '', 'system', 'private', 'formal.gif', 32767, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 1, 0, 2, 25, 0, 1274002638);
INSERT INTO `pb_membergroups` (`id`, `membertype_id`, `name`, `description`, `type`, `system`, `picture`, `point_max`, `point_min`, `max_offer`, `max_product`, `max_job`, `max_companynews`, `max_producttype`, `max_album`, `max_attach_size`, `max_size_perday`, `max_favorite`, `is_default`, `allow_offer`, `allow_market`, `allow_company`, `allow_product`, `allow_job`, `allow_companynews`, `allow_album`, `allow_space`, `default_live_time`, `after_live_time`, `exempt`, `created`, `modified`) VALUES(3, 1, '待审核会员', '等待验证', 'special', 'private', 'special_checking.gif', 0, 0, 0, 0, 0, 0, 3, 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 2, 0, 0, 1274002638);
INSERT INTO `pb_membergroups` (`id`, `membertype_id`, `name`, `description`, `type`, `system`, `picture`, `point_max`, `point_min`, `max_offer`, `max_product`, `max_job`, `max_companynews`, `max_producttype`, `max_album`, `max_attach_size`, `max_size_perday`, `max_favorite`, `is_default`, `allow_offer`, `allow_market`, `allow_company`, `allow_product`, `allow_job`, `allow_companynews`, `allow_album`, `allow_space`, `default_live_time`, `after_live_time`, `exempt`, `created`, `modified`) VALUES(4, 1, '禁止访问', '禁止访问网站', 'special', 'private', 'special_novisit.gif', 0, 0, 0, 0, 0, 0, 3, 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 2, 0, 0, 1274002638);
INSERT INTO `pb_membergroups` (`id`, `membertype_id`, `name`, `description`, `type`, `system`, `picture`, `point_max`, `point_min`, `max_offer`, `max_product`, `max_job`, `max_companynews`, `max_producttype`, `max_album`, `max_attach_size`, `max_size_perday`, `max_favorite`, `is_default`, `allow_offer`, `allow_market`, `allow_company`, `allow_product`, `allow_job`, `allow_companynews`, `allow_album`, `allow_space`, `default_live_time`, `after_live_time`, `exempt`, `created`, `modified`) VALUES(5, 1, '禁止发布', '禁止在商务室发表任何信息', 'special', 'private', 'special_noperm.gif', 0, 0, 0, 0, 0, 0, 3, 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 2, 0, 0, 1274002638);
INSERT INTO `pb_membergroups` (`id`, `membertype_id`, `name`, `description`, `type`, `system`, `picture`, `point_max`, `point_min`, `max_offer`, `max_product`, `max_job`, `max_companynews`, `max_producttype`, `max_album`, `max_attach_size`, `max_size_perday`, `max_favorite`, `is_default`, `allow_offer`, `allow_market`, `allow_company`, `allow_product`, `allow_job`, `allow_companynews`, `allow_album`, `allow_space`, `default_live_time`, `after_live_time`, `exempt`, `created`, `modified`) VALUES(6, 1, '禁止登陆', '禁止登陆商务室', 'special', 'private', 'special_nologin.gif', 0, 0, 0, 0, 0, 0, 3, 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 2, 0, 0, 1274002638);
INSERT INTO `pb_membergroups` (`id`, `membertype_id`, `name`, `description`, `type`, `system`, `picture`, `point_max`, `point_min`, `max_offer`, `max_product`, `max_job`, `max_companynews`, `max_producttype`, `max_album`, `max_attach_size`, `max_size_perday`, `max_favorite`, `is_default`, `allow_offer`, `allow_market`, `allow_company`, `allow_product`, `allow_job`, `allow_companynews`, `allow_album`, `allow_space`, `default_live_time`, `after_live_time`, `exempt`, `created`, `modified`) VALUES(7, 1, '个人会员', '普通级别会员', 'define', 'public', 'copper.gif', 0, 0, 5, 0, 0, 0, 3, 0, 0, 0, 0, 1, 3, 1, 3, 3, 3, 3, 1, 1, 1, 9, 24, 0, 1274002638);
INSERT INTO `pb_membergroups` (`id`, `membertype_id`, `name`, `description`, `type`, `system`, `picture`, `point_max`, `point_min`, `max_offer`, `max_product`, `max_job`, `max_companynews`, `max_producttype`, `max_album`, `max_attach_size`, `max_size_perday`, `max_favorite`, `is_default`, `allow_offer`, `allow_market`, `allow_company`, `allow_product`, `allow_job`, `allow_companynews`, `allow_album`, `allow_space`, `default_live_time`, `after_live_time`, `exempt`, `created`, `modified`) VALUES(8, 1, '高级个人会员', '高级个人会员', 'define', 'public', 'silver.gif', 0, 0, 0, 0, 0, 0, 3, 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 2, 6, 25, 0, 1274002638);
INSERT INTO `pb_membergroups` (`id`, `membertype_id`, `name`, `description`, `type`, `system`, `picture`, `point_max`, `point_min`, `max_offer`, `max_product`, `max_job`, `max_companynews`, `max_producttype`, `max_album`, `max_attach_size`, `max_size_perday`, `max_favorite`, `is_default`, `allow_offer`, `allow_market`, `allow_company`, `allow_product`, `allow_job`, `allow_companynews`, `allow_album`, `allow_space`, `default_live_time`, `after_live_time`, `exempt`, `created`, `modified`) VALUES(9, 1, '普通企业会员', '企业会员一般此级别', 'define', 'public', 'gold.gif', 0, 0, 2, 2, 0, 0, 3, 0, 0, 0, 0, 0, 2, 3, 3, 2, 2, 2, 2, 1, 1, 2, 31, 0, 1274002638);
INSERT INTO `pb_membergroups` (`id`, `membertype_id`, `name`, `description`, `type`, `system`, `picture`, `point_max`, `point_min`, `max_offer`, `max_product`, `max_job`, `max_companynews`, `max_producttype`, `max_album`, `max_attach_size`, `max_size_perday`, `max_favorite`, `is_default`, `allow_offer`, `allow_market`, `allow_company`, `allow_product`, `allow_job`, `allow_companynews`, `allow_album`, `allow_space`, `default_live_time`, `after_live_time`, `exempt`, `created`, `modified`) VALUES(10, 2, 'VIP企业会员', '高级企业会员', 'define', 'public', 'vip.gif', 0, 0, 0, 0, 0, 0, 3, 0, 0, 0, 0, 0, 3, 3, 3, 3, 3, 3, 3, 1, 1, 2, 31, 0, 1274002638);

--
-- Table Datas `pb_members`
--

INSERT INTO `pb_members` (`id`, `space_name`, `templet_id`, `username`, `userpass`, `email`, `points`, `credits`, `balance_amount`, `trusttype_ids`, `status`, `photo`, `membertype_id`, `membergroup_id`, `last_login`, `last_ip`, `service_start_date`, `service_end_date`, `office_redirect`, `created`, `modified`) VALUES(1, 'zxcvzxcv', 5, 'admin', '980ac217c6b51e7dc41040bec1edfec8', 'administrator@yourdomain.com', 38, 55, 500.00, '2,1', '1', '', 2, 9, '1303431038', '2130706433', '1301414400', '1304092800', 0, '1293936462', '1301585957');
INSERT INTO `pb_members` (`id`, `space_name`, `templet_id`, `username`, `userpass`, `email`, `points`, `credits`, `balance_amount`, `trusttype_ids`, `status`, `photo`, `membertype_id`, `membergroup_id`, `last_login`, `last_ip`, `service_start_date`, `service_end_date`, `office_redirect`, `created`, `modified`) VALUES(2, 'athena', 1, 'athena', 'e10adc3949ba59abbe56e057f20f883e', 'administrator@host.com', 81, 80, 0.00, '1,2', '1', '', 2, 9, '1293936472', '2130706433', '1293936472', '1294022872', 0, '1293936472', '0');

--
-- Table Datas `pb_membertypes`
--

INSERT INTO `pb_membertypes` (`id`, `default_membergroup_id`, `name`, `description`) VALUES(1, 7, '个人会员', '您可以在本网免费发布供应信息、求购信息。');
INSERT INTO `pb_membertypes` (`id`, `default_membergroup_id`, `name`, `description`) VALUES(2, 9, '企业会员', '您可以拥有自己的企业网站；您可以建立自己的产品库、企业相册；您可以自由发布招聘信息。');
INSERT INTO `pb_membertypes` (`id`, `default_membergroup_id`, `name`, `description`) VALUES(3, 10, '商铺会员', '可以建立自己的商铺');

--
-- Table Datas `pb_navs`
--

INSERT INTO `pb_navs` (`id`, `parent_id`, `name`, `description`, `url`, `target`, `status`, `display_order`, `highlight`, `level`, `class_name`, `created`, `modified`) VALUES(1, 0, '[:en-us]Home[:zh-cn]首页', '', 'index.php', '_self', 1, 1, 0, 0, '', unix_timestamp(now()), 0);
INSERT INTO `pb_navs` (`id`, `parent_id`, `name`, `description`, `url`, `target`, `status`, `display_order`, `highlight`, `level`, `class_name`, `created`, `modified`) VALUES(2, 0, '[:en-us]Buy[:zh-cn]求购', '', 'index.php?do=offer&action=lists&typeid=1&navid=2', '_self', 1, 2, 0, 0, '', unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_navs` (`id`, `parent_id`, `name`, `description`, `url`, `target`, `status`, `display_order`, `highlight`, `level`, `class_name`, `created`, `modified`) VALUES(3, 0, '[:en-us]Sell[:zh-cn]供应', '', 'index.php?do=offer&action=lists&typeid=2&navid=3', '_self', 1, 3, 0, 0, '', unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_navs` (`id`, `parent_id`, `name`, `description`, `url`, `target`, `status`, `display_order`, `highlight`, `level`, `class_name`, `created`, `modified`) VALUES(4, 0, '[:en-us]Invest[:zh-cn]招商加盟', '', 'index.php?do=offer&action=invest', '_self', 1, 5, 0, 0, '', unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_navs` (`id`, `parent_id`, `name`, `description`, `url`, `target`, `status`, `display_order`, `highlight`, `level`, `class_name`, `created`, `modified`) VALUES(5, 0, '[:en-us]Expo[:zh-cn]展会', '', 'index.php?do=fair', '_self', 1, 6, 0, 0, '', unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_navs` (`id`, `parent_id`, `name`, `description`, `url`, `target`, `status`, `display_order`, `highlight`, `level`, `class_name`, `created`, `modified`) VALUES(6, 0, '[:en-us]Quote[:zh-cn]价格行情', '', 'index.php?do=market&action=quote', '_self', 1, 8, 0, 0, '', unix_timestamp(now()), 0);
INSERT INTO `pb_navs` (`id`, `parent_id`, `name`, `description`, `url`, `target`, `status`, `display_order`, `highlight`, `level`, `class_name`, `created`, `modified`) VALUES(7, 0, '[:en-us]Market[:zh-cn]专业市场', '', 'index.php?do=market', '_self', 1, 9, 0, 0, '', unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_navs` (`id`, `parent_id`, `name`, `description`, `url`, `target`, `status`, `display_order`, `highlight`, `level`, `class_name`, `created`, `modified`) VALUES(9, 0, '[:en-us]Job[:zh-cn]人才招聘', '', 'index.php?do=job', '_self', 1, 11, 0, 0, '', unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_navs` (`id`, `parent_id`, `name`, `description`, `url`, `target`, `status`, `display_order`, `highlight`, `level`, `class_name`, `created`, `modified`) VALUES(10, 0, '[:en-us]Brand[:zh-cn]品牌', '', 'index.php?do=brand', '_self', 1, 7, 0, 0, '', unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_navs` (`id`, `parent_id`, `name`, `description`, `url`, `target`, `status`, `display_order`, `highlight`, `level`, `class_name`, `created`, `modified`) VALUES(11, 0, '[:en-us]Wholesale[:zh-cn]批发', '', 'index.php?do=offer&action=wholesale', '_self', 1, 4, 0, 0, '', unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_navs` (`id`, `parent_id`, `name`, `description`, `url`, `target`, `status`, `display_order`, `highlight`, `level`, `class_name`, `created`, `modified`) VALUES(12, 0, '[:en-us]Dict[:zh-cn]百科', '[:en-us]Industry Dict[:zh-cn]行业百科', 'index.php?do=dict', '_self', 1, 12, 0, 0, '', unix_timestamp(now()), unix_timestamp(now()));

--
-- Table Datas `pb_productsorts`
--

INSERT INTO `pb_productsorts` (`id`, `name`, `display_order`) VALUES(1, '最新产品', 0);
INSERT INTO `pb_productsorts` (`id`, `name`, `display_order`) VALUES(2, '库存产品', 0);
INSERT INTO `pb_productsorts` (`id`, `name`, `display_order`) VALUES(3, '普通产品', 0);

--
-- Table Datas `pb_settings`
--

INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(348, 0, 'site_name', '友邻B2B行业电子商务网站管理系统');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(349, 0, 'site_title', '友邻B2B行业电子商务网4.0 - Powered By PHPB2B');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(350, 0, 'site_banner_word', '最专业的行业电子商务网站');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(4, 0, 'company_name', '网站的版权者');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(352, 0, 'site_url', 'http://www.host.com/');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(353, 0, 'icp_number', 'ICP备案号码');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(7, 0, 'service_tel', '(86)10-41235678');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(8, 0, 'sale_tel', '(86)10-41235678');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(9, 0, 'service_qq', '1319250566');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(10, 0, 'service_msn', 'service@host.com');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(11, 0, 'service_email', 'service@host.com');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(324, 0, 'site_description', '<p>phpb2b是一款开源免费的b2b建站程序。</p>');
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
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(32, 0, 'mail_fromwho', '网站管理员');
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
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(401, 0, 'agreement', '<p>Demo</p>');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(359, 0, 'space_name', '商铺');
INSERT INTO `pb_settings` (`id`, `type_id`, `variable`, `valued`) VALUES(404, 0, 'welcome_msg', '0');

--
-- Table Datas `pb_tags`
--

INSERT INTO `pb_tags` (`id`, `member_id`, `name`, `numbers`, `closed`, `flag`, `created`, `modified`) VALUES(1, 0, '热门', 1, 0, 0, unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_tags` (`id`, `member_id`, `name`, `numbers`, `closed`, `flag`, `created`, `modified`) VALUES(2, 0, '搜索', 2, 0, 1, unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_tags` (`id`, `member_id`, `name`, `numbers`, `closed`, `flag`, `created`, `modified`) VALUES(3, 0, '标签', 1, 0, 2, unix_timestamp(now()), unix_timestamp(now()));

--
-- Table Datas `pb_templets`
--

INSERT INTO `pb_templets` (`id`, `name`, `title`, `directory`, `type`, `author`, `style`, `description`, `is_default`, `require_membertype`, `require_membergroups`, `status`) VALUES(3, 'brown', '棕色企业模板', 'skins/brown/', 'user', 'PB TEAM', '', 'A PHPB2B Corperate Templet. Enjoy!', 0, '0', '0', 1);
INSERT INTO `pb_templets` (`id`, `name`, `title`, `directory`, `type`, `author`, `style`, `description`, `is_default`, `require_membertype`, `require_membergroups`, `status`) VALUES(4, 'red', '红色企业模板', 'skins/red/', 'user', 'PB TEAM', '', 'A PHPB2B Corperate Templet. Enjoy!', 0, '0', '0', 1);
INSERT INTO `pb_templets` (`id`, `name`, `title`, `directory`, `type`, `author`, `style`, `description`, `is_default`, `require_membertype`, `require_membergroups`, `status`) VALUES(5, 'default', '默认企业模板', 'skins/default/', 'user', 'PB TEAM', '', 'A PHPB2B Corperate Templet. Enjoy!', 0, '0', '0', 1);
INSERT INTO `pb_templets` (`id`, `name`, `title`, `directory`, `type`, `author`, `style`, `description`, `is_default`, `require_membertype`, `require_membergroups`, `status`) VALUES(7, 'green', '绿色企业模板', 'skins/green/', 'user', 'PB TEAM', '', 'A PHPB2B Corperate Templet. Enjoy!', 0, '0', '0', 1);
INSERT INTO `pb_templets` (`id`, `name`, `title`, `directory`, `type`, `author`, `style`, `description`, `is_default`, `require_membertype`, `require_membergroups`, `status`) VALUES(8, 'orange', '橙色企业模板', 'skins/orange/', 'user', 'PB TEAM', '', 'A PHPB2B Corperate Templet. Enjoy!', 0, '0', '0', 1);
INSERT INTO `pb_templets` (`id`, `name`, `title`, `directory`, `type`, `author`, `style`, `description`, `is_default`, `require_membertype`, `require_membergroups`, `status`) VALUES(12, 'default', '默认模板', 'templates/default/', 'system', 'PB TEAM', '#4DB5F8', 'A Ualink Default Template. Enjoy!', 0, '0', '0', 1);

--
-- Table Datas `pb_tradetypes`
--

INSERT INTO `pb_tradetypes` (`id`, `parent_id`, `name`, `level`, `display_order`) VALUES(1, 0, '求购', 1, 0);
INSERT INTO `pb_tradetypes` (`id`, `parent_id`, `name`, `level`, `display_order`) VALUES(2, 0, '供应', 1, 0);
INSERT INTO `pb_tradetypes` (`id`, `parent_id`, `name`, `level`, `display_order`) VALUES(3, 0, '代理合作', 1, 0);
INSERT INTO `pb_tradetypes` (`id`, `parent_id`, `name`, `level`, `display_order`) VALUES(4, 3, '合作', 1, 0);
INSERT INTO `pb_tradetypes` (`id`, `parent_id`, `name`, `level`, `display_order`) VALUES(5, 0, '招商加盟', 1, 0);
INSERT INTO `pb_tradetypes` (`id`, `parent_id`, `name`, `level`, `display_order`) VALUES(6, 5, '加盟', 1, 0);
INSERT INTO `pb_tradetypes` (`id`, `parent_id`, `name`, `level`, `display_order`) VALUES(7, 0, '库存批发', 1, 0);
INSERT INTO `pb_tradetypes` (`id`, `parent_id`, `name`, `level`, `display_order`) VALUES(8, 7, '库存', 1, 0);
INSERT INTO `pb_tradetypes` (`id`, `parent_id`, `name`, `level`, `display_order`) VALUES(9, 1, '国内求购', 2, 0);
INSERT INTO `pb_tradetypes` (`id`, `parent_id`, `name`, `level`, `display_order`) VALUES(10, 1, '国外求购', 2, 0);
INSERT INTO `pb_tradetypes` (`id`, `parent_id`, `name`, `level`, `display_order`) VALUES(11, 7, '批发', 2, 0);
INSERT INTO `pb_tradetypes` (`id`, `parent_id`, `name`, `level`, `display_order`) VALUES(12, 3, '代理', 2, 0);
INSERT INTO `pb_tradetypes` (`id`, `parent_id`, `name`, `level`, `display_order`) VALUES(13, 5, '招商', 2, 0);

--
-- Table Datas `pb_trusttypes`
--

INSERT INTO `pb_trusttypes` (`id`, `name`, `description`, `image`, `display_order`, `status`) VALUES(2, '企业资质认证', NULL, 'company.gif', 0, 1);
INSERT INTO `pb_trusttypes` (`id`, `name`, `description`, `image`, `display_order`, `status`) VALUES(1, '实名认证', NULL, 'truename.gif', 0, 1);

--
-- Table Datas `pb_typemodels`
--

INSERT INTO `pb_typemodels` (`id`, `title`, `type_name`) VALUES(1, '过期时间', 'offer_expire');
INSERT INTO `pb_typemodels` (`id`, `title`, `type_name`) VALUES(2, '公司类型', 'manage_type');
INSERT INTO `pb_typemodels` (`id`, `title`, `type_name`) VALUES(3, '主要市场', 'main_market');
INSERT INTO `pb_typemodels` (`id`, `title`, `type_name`) VALUES(4, '注册资金', 'reg_fund');
INSERT INTO `pb_typemodels` (`id`, `title`, `type_name`) VALUES(5, '年营业额', 'year_annual');
INSERT INTO `pb_typemodels` (`id`, `title`, `type_name`) VALUES(6, '经济类型', 'economic_type');
INSERT INTO `pb_typemodels` (`id`, `title`, `type_name`) VALUES(7, '审核状态', 'check_status');
INSERT INTO `pb_typemodels` (`id`, `title`, `type_name`) VALUES(8, '员工人数', 'employee_amount');
INSERT INTO `pb_typemodels` (`id`, `title`, `type_name`) VALUES(9, '状态', 'common_status');
INSERT INTO `pb_typemodels` (`id`, `title`, `type_name`) VALUES(10, '建议类型', 'service_type');
INSERT INTO `pb_typemodels` (`id`, `title`, `type_name`) VALUES(11, '教育经历', 'education');
INSERT INTO `pb_typemodels` (`id`, `title`, `type_name`) VALUES(12, '薪资水平', 'salary');
INSERT INTO `pb_typemodels` (`id`, `title`, `type_name`) VALUES(13, '工作性质', 'work_type');
INSERT INTO `pb_typemodels` (`id`, `title`, `type_name`) VALUES(14, '职位名称', 'position');
INSERT INTO `pb_typemodels` (`id`, `title`, `type_name`) VALUES(15, '性别', 'gender');
INSERT INTO `pb_typemodels` (`id`, `title`, `type_name`) VALUES(16, '电话类别', 'phone_type');
INSERT INTO `pb_typemodels` (`id`, `title`, `type_name`) VALUES(17, '即时通讯类别', 'im_type');
INSERT INTO `pb_typemodels` (`id`, `title`, `type_name`) VALUES(18, '选项', 'common_option');
INSERT INTO `pb_typemodels` (`id`, `title`, `type_name`) VALUES(19, '尊称', 'calls');
INSERT INTO `pb_typemodels` (`id`, `title`, `type_name`) VALUES(20, '计量单位', 'measuring');
INSERT INTO `pb_typemodels` (`id`, `title`, `type_name`) VALUES(21, '货币单位', 'monetary');
INSERT INTO `pb_typemodels` (`id`, `title`, `type_name`) VALUES(22, '报价类型', 'price_type');
INSERT INTO `pb_typemodels` (`id`, `title`, `type_name`) VALUES(23, '价格趋势', 'price_trends');

--
-- Table Datas `pb_typeoptions`
--

INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(1, 1, '10', '10天');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(2, 1, '30', '一个月');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(3, 1, '90', '三个月');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(4, 1, '180', '六个月');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(5, 2, '1', '生产型');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(6, 2, '2', '贸易型');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(7, 2, '3', '服务型');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(8, 2, '4', '政府或其他机构');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(9, 3, '1', '大陆');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(10, 3, '2', '港澳台');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(11, 3, '3', '北美');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(12, 3, '4', '南美');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(13, 3, '5', '欧洲');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(14, 3, '6', '亚洲');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(15, 3, '7', '非洲');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(16, 3, '8', '大洋洲');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(17, 3, '9', '其他市场');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(18, 4, '0', '不公开');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(19, 4, '1', '人民币10万元以下');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(20, 4, '2', '人民币10-30万');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(21, 4, '3', '人民币30-50万');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(22, 4, '4', '人民币50-100万');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(23, 4, '5', '人民币100-300万');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(24, 4, '6', '人民币300-500万');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(25, 4, '7', '人民币500-1000万');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(26, 4, '8', '人民币1000-5000万');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(27, 4, '9', '人民币5000万以上');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(28, 4, '10', '其他');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(29, 5, '1', '人民币10万以下/年');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(30, 5, '2', '人民币10-30万/年');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(31, 5, '3', '人民币30-50万/年');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(32, 5, '4', '人民币50-100万/年');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(33, 5, '5', '人民币100-300万/年');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(34, 5, '6', '人民币300-500万/年');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(35, 5, '7', '人民币500-1000万/年');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(36, 5, '8', '人民币1000-5000万/年');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(37, 5, '9', '人民币5000万以上/年');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(38, 5, '10', '其他');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(39, 6, '1', '国有企业');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(40, 6, '2', '集体企业');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(41, 6, '3', '股份合作企业');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(42, 6, '4', '联营企业');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(43, 6, '5', '有限责任公司');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(44, 6, '6', '股份有限公司');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(45, 6, '7', '私营企业');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(46, 6, '8', '个人独资企业');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(47, 6, '9', '非盈利组织');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(48, 6, '10', '其他');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(49, 7, '0', '无效');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(50, 7, '1', '有效');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(51, 7, '2', '等待审核');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(52, 7, '3', '审核不通过');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(53, 8, '1', '5人以下');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(54, 8, '2', '5-10人');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(55, 8, '3', '11-50人');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(56, 8, '4', '51-100人');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(57, 8, '5', '101-500人');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(58, 8, '6', '501-1000人');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(59, 8, '7', '1000人以上');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(60, 10, '1', '咨询');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(61, 10, '2', '建议');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(62, 10, '3', '投诉');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(63, 11, '0', '其他');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(64, 11, '-1', '不要求');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(65, 11, '-2', '不限');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(66, 11, '1', '博士');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(67, 11, '2', '硕士');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(68, 11, '3', '本科');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(69, 11, '4', '大专');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(70, 11, '5', '中专');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(71, 11, '6', '技校');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(72, 11, '7', '高中');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(73, 11, '8', '初中');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(74, 11, '9', '小学');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(75, 12, '0', '不选择');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(76, 12, '-1', '面议');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(77, 12, '1', '1500以下');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(78, 12, '2', '1500-1999元/月');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(79, 12, '3', '2000-2999元/月');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(80, 12, '4', '3000-4999元/月');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(81, 12, '5', '5000以上');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(82, 13, '0', '不选择');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(83, 13, '1', '全职');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(84, 13, '2', '兼职');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(85, 13, '3', '临时');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(86, 13, '4', '实习');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(87, 13, '5', '其他');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(88, 14, '0', '不选择');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(89, 14, '1', '董事长、总裁及副职，企业主、企业合伙人，总经理/副总经理');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(90, 14, '2', '行政部门经理/行政人员');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(91, 14, '3', '技术部门经理/技术人员');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(92, 14, '4', '生产部门经理/生产人员');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(93, 14, '5', '市场部门经理/市场人员');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(94, 14, '6', '采购部门经理/采购人员');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(95, 14, '7', '销售部门经理/销售人员');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(96, 14, '8', '其他');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(97, 15, '0', '不选择');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(98, 15, '1', '男');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(99, 15, '2', '女');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(100, 15, '-1', '不限');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(101, 16, '1', '移动电话');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(102, 16, '2', '住宅电话');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(103, 16, '3', '商务电话');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(104, 16, '4', '其他');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(105, 17, '1', 'QQ');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(106, 17, '2', 'ICQ');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(107, 17, '3', 'MSN Messenger');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(108, 17, '4', 'Yahoo Messenger');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(109, 17, '5', 'Skype');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(110, 17, '6', '其他');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(111, 17, '0', '不选择');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(112, 16, '0', '不选择');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(113, 6, '0', '不选择');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(114, 9, '0', '无效');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(115, 9, '1', '有效');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(116, 18, '0', '否');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(117, 18, '1', '是');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(118, 19, '1', '先生');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(119, 19, '2', '女士');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(120, 20, '1', '个');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(121, 20, '2', '件');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(122, 21, '1', '元');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(123, 21, '3', '美元');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(124, 22, '1', '买');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(125, 22, '2', '卖');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(126, 23, '1', '升');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(127, 23, '2', '稳');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(128, 23, '3', '降');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(129, 23, '4', '不确定');
INSERT INTO `pb_typeoptions` (`id`, `typemodel_id`, `option_value`, `option_label`) VALUES(130, 21, '2', '万元');

--
-- Table Datas `pb_userpages`
--

INSERT INTO `pb_userpages` (`id`, `templet_name`, `name`, `title`, `digest`, `content`, `url`, `display_order`, `created`, `modified`) VALUES(1, '', 'aboutus', '[:en-us]About us[:zh-cn]关于我们', '', '关于网站的说明', '', 0, unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_userpages` (`id`, `templet_name`, `name`, `title`, `digest`, `content`, `url`, `display_order`, `created`, `modified`) VALUES(2, '', 'contactus', '[:en-us]Contacts[:zh-cn]联系我们', '', '联系方式', '', 0, unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_userpages` (`id`, `templet_name`, `name`, `title`, `digest`, `content`, `url`, `display_order`, `created`, `modified`) VALUES(4, '', 'sitemap', '[:en-us]Sitemap[:zh-cn]网站地图', '', '网站站内地图', '', 0, unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_userpages` (`id`, `templet_name`, `name`, `title`, `digest`, `content`, `url`, `display_order`, `created`, `modified`) VALUES(5, '', 'legal', '[:en-us]Legal[:zh-cn]法律声明', '', '法律声明', '', 0, unix_timestamp(now()), 0);
INSERT INTO `pb_userpages` (`id`, `templet_name`, `name`, `title`, `digest`, `content`, `url`, `display_order`, `created`, `modified`) VALUES(6, '', 'friendlink', '[:en-us]Links[:zh-cn]友情链接', '', '申请友情链接', 'index.php?do=friendlink', 0, unix_timestamp(now()), 0);
INSERT INTO `pb_userpages` (`id`, `templet_name`, `name`, `title`, `digest`, `content`, `url`, `display_order`, `created`, `modified`) VALUES(7, '', 'help', '[:en-us]Helps[:zh-cn]帮助中心', '', '帮助中心', 'index.php?do=help', 0, unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_userpages` (`id`, `templet_name`, `name`, `title`, `digest`, `content`, `url`, `display_order`, `created`, `modified`) VALUES(8, '', 'service', '[:en-us]Service[:zh-cn]意见投诉', '', '意见与建议、投诉', '', 0, unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_userpages` (`id`, `templet_name`, `name`, `title`, `digest`, `content`, `url`, `display_order`, `created`, `modified`) VALUES(9, '', 'special', '[:en-us]Topic[:zh-cn]分站专题', '', '行业或者地区分站', 'index.php?do=topic', 0, unix_timestamp(now()), unix_timestamp(now()));
INSERT INTO `pb_userpages` (`templet_name`, `name`, `title`, `digest`, `content`, `url`, `display_order`, `created`, `modified`) VALUES('', 'wap', 'WAP', 'Wap', '', 'index.php?do=wap', 0, unix_timestamp(now()), unix_timestamp(now()));

--
-- Table Datas `pb_newstypes`
--

INSERT INTO `pb_newstypes` (`id`, `name`, `level_id`, `status`, `parent_id`, `created`) VALUES(1, '行业聚焦', 1, 1, 0, unix_timestamp(now()));
INSERT INTO `pb_newstypes` (`id`, `name`, `level_id`, `status`, `parent_id`, `created`) VALUES(2, '头条要闻', 1, 1, 0, unix_timestamp(now()));
INSERT INTO `pb_newstypes` (`id`, `name`, `level_id`, `status`, `parent_id`, `created`) VALUES(3, '本网动态', 1, 1, 0, unix_timestamp(now()));
INSERT INTO `pb_newstypes` (`id`, `name`, `level_id`, `status`, `parent_id`, `created`) VALUES(4, '企业报道', 1, 1, 0, unix_timestamp(now()));
INSERT INTO `pb_newstypes` (`id`, `name`, `level_id`, `status`, `parent_id`, `created`) VALUES(5, '媒体精粹', 1, 1, 0, unix_timestamp(now()));
INSERT INTO `pb_newstypes` (`id`, `name`, `level_id`, `status`, `parent_id`, `created`) VALUES(6, '热点专题', 1, 1, 0, unix_timestamp(now()));
INSERT INTO `pb_newstypes` (`id`, `name`, `level_id`, `status`, `parent_id`, `created`) VALUES(7, '高端访谈', 1, 1, 0, unix_timestamp(now()));
INSERT INTO `pb_newstypes` (`id`, `name`, `level_id`, `status`, `parent_id`, `created`) VALUES(8, '新闻速递', 1, 1, 0, unix_timestamp(now()));