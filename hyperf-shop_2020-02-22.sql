# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.22-0ubuntu0.16.04.1)
# Database: hyperf-shop
# Generation Time: 2020-02-22 14:34:45 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table category
# ------------------------------------------------------------

DROP TABLE IF EXISTS `category`;

CREATE TABLE `category` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `sort` int(10) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;

INSERT INTO `category` (`id`, `name`, `pid`, `sort`, `created_at`, `updated_at`)
VALUES
	(17,'家电/数码/手机',0,20,'2020-02-04 13:14:57','2020-02-04 13:14:57'),
	(18,'珠宝/眼镜/手表',0,99,'2020-02-04 13:15:27','2020-02-04 13:15:27'),
	(19,'游戏/动漫/影视',0,99,'2020-02-04 13:15:45','2020-02-04 13:15:45'),
	(20,'运动/户外',0,10,'2020-02-04 13:16:03','2020-02-04 13:16:03'),
	(21,'手机',17,99,'2020-02-04 13:16:26','2020-02-04 13:16:26'),
	(22,'电脑',17,99,'2020-02-04 13:16:37','2020-02-04 13:16:37'),
	(23,'卡西欧',18,99,'2020-02-04 13:17:06','2020-02-04 13:17:06'),
	(24,'钻戒',18,99,'2020-02-04 13:17:15','2020-02-04 13:17:15'),
	(25,'反辐射',18,99,'2020-02-04 13:17:31','2020-02-04 13:17:31'),
	(26,'老花眼',18,99,'2020-02-04 13:17:41','2020-02-04 13:17:41'),
	(27,'DNf',19,99,'2020-02-04 13:17:59','2020-02-04 13:17:59'),
	(28,'LOL',19,99,'2020-02-04 13:18:06','2020-02-04 13:18:06'),
	(29,'手办',19,99,'2020-02-04 13:18:42','2020-02-04 13:18:42'),
	(31,'AJ',20,99,'2020-02-04 13:19:19','2020-02-04 13:19:19');

/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table front_activities
# ------------------------------------------------------------

DROP TABLE IF EXISTS `front_activities`;

CREATE TABLE `front_activities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(10) unsigned NOT NULL COMMENT '活动跳转的商品id',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '活动名称',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '活动类型对应front_category 主键id',
  `start_at` timestamp NULL DEFAULT NULL COMMENT '开始时间',
  `end_at` timestamp NULL DEFAULT NULL COMMENT '结束时间',
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '活动链接',
  `image` text COLLATE utf8mb4_unicode_ci COMMENT '活动图片',
  `sort` tinyint(4) DEFAULT NULL COMMENT '活动排序',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态:0未开始1进行中',
  `click` tinyint(4) NOT NULL DEFAULT '0' COMMENT '点击数',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `front_activities` WRITE;
/*!40000 ALTER TABLE `front_activities` DISABLE KEYS */;

INSERT INTO `front_activities` (`id`, `product_id`, `name`, `type`, `start_at`, `end_at`, `url`, `image`, `sort`, `status`, `click`, `created_at`, `updated_at`)
VALUES
	(1,10,'iphone 折扣','3','2020-01-05 00:00:00','2020-01-10 00:00:00',NULL,'http://wuqinqiang.oss-cn-hangzhou.aliyuncs.com/%E5%B0%81%E9%9D%A2.jpeg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1609838193&Signature=bvO%2FxbL20jk3T8NQz2eWCz2WzGM%3D',100,0,0,'2020-01-04 10:01:05','2020-02-12 11:49:09'),
	(4,12,'机器人来了','3','2020-01-23 00:00:00','2020-02-26 00:00:00',NULL,'http://wuqinqiang.oss-cn-hangzhou.aliyuncs.com/jiqi2.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612335124&Signature=6iTcgQejDaBotg0U6r1lC7hHMWw%3D',2,0,0,'2020-01-06 18:14:25','2020-02-19 11:38:00'),
	(5,9,'激情活动','1','2020-02-05 00:00:00','2020-03-09 00:00:00',NULL,'http://wuqinqiang.oss-cn-hangzhou.aliyuncs.com/php3.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1613392094&Signature=tk%2FCNeN1QC2lIYtOfchvA8CKdFk%3D',55,0,0,'2020-02-04 14:40:59','2020-02-21 13:30:13');

/*!40000 ALTER TABLE `front_activities` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table front_category
# ------------------------------------------------------------

DROP TABLE IF EXISTS `front_category`;

CREATE TABLE `front_category` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '类目的名称',
  `detail` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '类目的描述',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `weight` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `front_category` WRITE;
/*!40000 ALTER TABLE `front_category` DISABLE KEYS */;

INSERT INTO `front_category` (`id`, `name`, `detail`, `created_at`, `updated_at`, `weight`)
VALUES
	(1,'经典套餐','经典套餐','2020-01-04 09:23:17','2020-01-06 10:43:32',210),
	(3,'激情抢购','2','2020-01-04 16:04:52','2020-02-04 14:50:44',0);

/*!40000 ALTER TABLE `front_category` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table front_image
# ------------------------------------------------------------

DROP TABLE IF EXISTS `front_image`;

CREATE TABLE `front_image` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `image` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '轮播图',
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#' COMMENT '跳转url',
  `status` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '1开启,2禁用',
  `weight` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '权重,越小越靠前',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `front_image` WRITE;
/*!40000 ALTER TABLE `front_image` DISABLE KEYS */;

INSERT INTO `front_image` (`id`, `image`, `url`, `status`, `weight`, `created_at`, `updated_at`)
VALUES
	(1,'http://wuqinqiang.oss-cn-hangzhou.aliyuncs.com/sh1.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612335214&Signature=goM4pHBBYqIAAB4eB%2FoCUT2kKLk%3D','',1,10,'2020-01-03 15:22:58','2020-02-04 14:53:37'),
	(2,'http://wuqinqiang.oss-cn-hangzhou.aliyuncs.com/sh2.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612335224&Signature=nfEEZseBqTJAmT9A4w2TWhDaxHk%3D','10',1,10,'2020-02-04 14:54:00','2020-02-04 14:54:00'),
	(3,'http://wuqinqiang.oss-cn-hangzhou.aliyuncs.com/sh3.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612335255&Signature=Lv1f7bsDa8I6AB9hasy7XkmBEdY%3D','',2,0,'2020-02-04 14:57:49','2020-02-21 17:04:19');

/*!40000 ALTER TABLE `front_image` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table migrations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;

INSERT INTO `migrations` (`id`, `migration`, `batch`)
VALUES
	(10,'2020_01_03_102033_create_users_table',1),
	(11,'2020_01_03_102721_create_products_table',1),
	(12,'2020_01_03_103314_create_category_table',1),
	(13,'2020_01_03_103413_create_product_skus_table',1),
	(14,'2020_01_03_103832_create_product_descriptions_table',1),
	(21,'2020_01_03_144013_create__rotation_charts_table',2),
	(22,'2020_01_03_144340_create_front_category_table',2),
	(23,'2020_01_03_144855_create_front_activities__table',2),
	(25,'2020_01_06_103638_add_weight_to_front_category',3),
	(26,'2020_01_07_144308_add_only_buy_to_product',4),
	(27,'2020_01_07_153246_add_category_name_to_product',5),
	(28,'2020_01_07_175713_create_permission_tables',6),
	(29,'2020_01_15_080207_create_orders_table',7),
	(30,'2020_01_15_080224_create_order_items_table',7),
	(31,'2020_01_17_105554_add_status_to_orders',7),
	(32,'2020_01_17_142921_add_pay_amount_to_order_items',7),
	(33,'2020_01_17_163645_create_order_refunds_table',7),
	(34,'2020_02_03_102228_update_product_image_to_product_sku',8),
	(35,'2020_02_03_235948_add_status_to_users',9);

/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table model_has_permissions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `model_has_permissions`;

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table model_has_roles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `model_has_roles`;

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`)
VALUES
	(1,'App\\Model\\User',2),
	(1,'App\\Model\\User',3),
	(1,'App\\Model\\User',4);

/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table order_items
# ------------------------------------------------------------

DROP TABLE IF EXISTS `order_items`;

CREATE TABLE `order_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL COMMENT '订单id',
  `product_id` int(10) unsigned NOT NULL COMMENT '商品id',
  `product_sku_id` int(10) unsigned NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` int(10) unsigned NOT NULL COMMENT '商品数量',
  `price` decimal(10,2) NOT NULL COMMENT '单价',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `pay_amount` decimal(10,2) NOT NULL COMMENT '订单金额',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_sku_id`, `product_name`, `sku_title`, `amount`, `price`, `created_at`, `updated_at`, `pay_amount`)
VALUES
	(1,27,6,34,'iphone x','s4t',2,23.00,'2020-02-04 13:04:35','2020-02-04 13:04:35',46.00),
	(2,28,6,34,'iphone x','s4t',2,23.00,'2020-02-04 13:05:06','2020-02-04 13:05:06',46.00),
	(3,29,6,34,'iphone x','s4t',2,23.00,'2020-02-04 13:05:08','2020-02-04 13:05:08',46.00),
	(4,30,6,34,'iphone x','s4t',2,23.00,'2020-02-04 13:05:08','2020-02-04 13:05:08',46.00),
	(5,31,6,34,'iphone x','s4t',2,23.00,'2020-02-04 13:05:10','2020-02-04 13:05:10',46.00),
	(6,32,9,35,'adidas Yeezy Boost 350','36码 黄',1,1599.00,'2020-02-04 14:08:35','2020-02-04 14:08:35',1599.00),
	(7,32,9,36,'adidas Yeezy Boost 350','40 白',1,1699.00,'2020-02-04 14:08:35','2020-02-04 14:08:35',1699.00),
	(8,33,9,36,'adidas Yeezy Boost 350','40 白',1,1699.00,'2020-02-04 14:09:43','2020-02-04 14:09:43',1699.00),
	(9,34,10,37,'Apple/苹果 iPhone 11 ','白色',1,4500.00,'2020-02-04 14:09:54','2020-02-04 14:09:54',4500.00),
	(10,34,10,38,'Apple/苹果 iPhone 11 ','黑色',1,5555.00,'2020-02-04 14:09:54','2020-02-04 14:09:54',5555.00),
	(11,35,12,41,'扫地机器人','白色',1,888.00,'2020-02-04 14:10:47','2020-02-04 14:10:47',888.00),
	(12,35,11,40,' Apple/苹果16英寸新款MacBook Pro 屏','银色',1,15999.00,'2020-02-04 14:10:47','2020-02-04 14:10:47',15999.00),
	(13,36,12,42,'扫地机器人','白色 全自动',1,1199.00,'2020-02-04 14:11:28','2020-02-04 14:11:28',1199.00),
	(14,37,13,43,'卡西欧','白色',1,17777.00,'2020-02-04 14:11:33','2020-02-04 14:11:33',17777.00),
	(15,38,14,46,'周大生钻戒女18K玫瑰金求婚','心形',1,6000.00,'2020-02-04 14:13:17','2020-02-04 14:13:17',6000.00),
	(16,39,14,47,'周大生钻戒女18K玫瑰金求婚','皇冠',1,16666.00,'2020-02-04 14:13:59','2020-02-04 14:13:59',16666.00),
	(17,40,10,38,'Apple/苹果 iPhone 11 ','黑色',1,5555.00,'2020-02-04 14:14:12','2020-02-04 14:14:12',5555.00),
	(18,41,12,42,'扫地机器人','白色 全自动',1,1199.00,'2020-02-04 14:14:58','2020-02-04 14:14:58',1199.00);

/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table order_refunds
# ------------------------------------------------------------

DROP TABLE IF EXISTS `order_refunds`;

CREATE TABLE `order_refunds` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL COMMENT '订单id',
  `refund_type` tinyint(3) unsigned NOT NULL COMMENT '1退款2退款退货3退货换货',
  `refund_status` tinyint(3) unsigned NOT NULL DEFAULT '2' COMMENT '1售后成功2正在售后',
  `refund_content` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '售后说明',
  `refund_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '售后金额',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table orders
# ------------------------------------------------------------

DROP TABLE IF EXISTS `orders`;

CREATE TABLE `orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '订单号',
  `user_id` int(10) unsigned NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '具体收货地址',
  `total_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单总额',
  `pay_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '实付金额',
  `user_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '收货人姓名',
  `user_phone` varchar(13) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '收货人手机号',
  `user_type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '用户类型1普通用户2代理商',
  `remark` text COLLATE utf8mb4_unicode_ci COMMENT '订单备注',
  `paid_at` datetime DEFAULT NULL COMMENT '支付时间 支付时间不为空说明已支付状态',
  `payment_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '支付订单号',
  `refund_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '售后状态0未申请售后1完成售后2正在申请售后3同意售后4拒绝售后',
  `closed` tinyint(1) NOT NULL DEFAULT '0' COMMENT '客户确认收货订单完成关闭',
  `reviewed` tinyint(1) NOT NULL DEFAULT '0' COMMENT '客户是否评价此订单',
  `ship_status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '物流状态0未发货1已收货2已发货',
  `ship_company` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '物流公司',
  `pay_ship` decimal(10,2) DEFAULT NULL COMMENT '物流费',
  `ship_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '快递号',
  `ship_image` varchar(199) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '快递单图片',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_no_unique` (`no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;

INSERT INTO `orders` (`id`, `no`, `user_id`, `address`, `total_amount`, `pay_amount`, `user_name`, `user_phone`, `user_type`, `remark`, `paid_at`, `payment_no`, `refund_status`, `closed`, `reviewed`, `ship_status`, `ship_company`, `pay_ship`, `ship_no`, `ship_image`, `created_at`, `updated_at`, `status`)
VALUES
	(25,'20200117143944978338test',1,'湖北省武汉市xxxx3幢1207',296.00,296.00,'库里','13106122572',1,NULL,NULL,NULL,0,0,0,0,NULL,NULL,NULL,NULL,'2020-01-17 14:39:44','2020-01-17 14:39:44',0),
	(26,'20200117143954739764test',1,'上海市xxxx3幢1207',46.00,46.00,'吴亲','13106122572',1,NULL,'2020-01-17 14:39:54',NULL,0,0,0,2,'申通',NULL,'463352234434',NULL,'2020-01-17 14:39:54','2020-01-17 15:37:05',0),
	(27,'20200204130435745102test',1,'浙江省衢州市xxxx3幢1207',46.00,46.00,'樊婷','13106122572',1,NULL,'2020-01-17 14:39:54',NULL,0,0,1,1,'申通',10.00,'432552242',NULL,'2020-02-04 13:04:35','2020-02-04 13:04:35',0),
	(28,'20200204130506395023test',1,'浙江省杭州市xxxx3幢1207',46.00,46.00,'李开阳','13106122572',1,NULL,NULL,NULL,0,0,0,0,NULL,NULL,NULL,NULL,'2020-02-04 13:05:06','2020-02-04 13:05:06',0),
	(29,'20200204130508738065test',1,'浙江省台州市xxxx街道',46.00,46.00,'谢人名','13106122572',1,NULL,NULL,NULL,1,0,0,0,NULL,NULL,NULL,NULL,'2020-02-04 13:05:08','2020-02-04 13:05:08',0),
	(30,'20200204130508341777test',1,'浙江省温州市苍南xx街道',46.00,46.00,'李冬冬','13106122572',1,NULL,NULL,NULL,0,0,0,0,NULL,NULL,NULL,NULL,'2020-02-04 13:05:08','2020-02-04 13:05:08',0),
	(31,'20200204130510560154test',1,'浙江省杭州市xxxx3幢1207',46.00,46.00,'张恒','13106122572',1,NULL,'2020-01-17 14:39:54',NULL,2,0,0,0,NULL,20.00,NULL,NULL,'2020-02-04 13:05:10','2020-02-04 13:05:10',0),
	(32,'20200204140835182880test',1,'浙江省杭州市xxxx3幢1207',3298.00,3298.00,'张子豪','15106122572',1,NULL,NULL,NULL,0,0,0,0,NULL,NULL,NULL,NULL,'2020-02-04 14:08:35','2020-02-04 14:08:35',0),
	(33,'20200204140943145949test',1,'江西省南昌市xxx街道',1699.00,1699.00,'滕子韬','1583425001',1,NULL,NULL,NULL,0,1,0,0,NULL,NULL,NULL,NULL,'2020-02-04 14:09:43','2020-02-04 14:09:43',0),
	(34,'20200204140954937812test',1,'江西省南昌市xxx街道',10055.00,10055.00,'滕子韬','1583425001',1,NULL,NULL,NULL,0,0,0,0,NULL,NULL,NULL,NULL,'2020-02-04 14:09:54','2020-02-04 14:09:54',0),
	(35,'20200204141047427554test',1,'浙江省衢州市开化县xxx街道',16887.00,16887.00,'李向','15869251545',1,NULL,'2020-01-17 14:39:54',NULL,0,0,0,2,NULL,NULL,NULL,NULL,'2020-02-04 14:10:47','2020-02-04 14:10:47',0),
	(36,'2020020414112869640test',1,'浙江省杭州市临安xxx街道',1199.00,1199.00,'王阳','13706708720',1,NULL,'2020-01-17 14:39:54',NULL,0,0,1,1,NULL,NULL,NULL,NULL,'2020-02-04 14:11:28','2020-02-04 14:11:28',0),
	(37,'2020020414113397234test',1,'浙江省杭州市临安xxx街道',17777.00,17777.00,'王阳','13706708720',1,NULL,'2020-01-17 14:39:54',NULL,0,0,1,1,NULL,NULL,NULL,NULL,'2020-02-04 14:11:33','2020-02-04 14:11:33',0),
	(38,'20200204141317571295test',1,'浙江省东阳市临安xxx街道',6000.00,6000.00,'吴优','13106122634',1,NULL,NULL,NULL,0,0,0,0,NULL,NULL,NULL,NULL,'2020-02-04 14:13:17','2020-02-04 14:13:17',0),
	(39,'20200204141359705098test',1,'上海市东山区北海街道xx号',16666.00,16666.00,'谢仁松','15166702232',1,NULL,'2020-01-17 14:39:54',NULL,0,0,0,0,NULL,NULL,NULL,NULL,'2020-02-04 14:13:59','2020-02-04 14:13:59',0),
	(40,'20200204141412668106test',1,'上海市东山区北海街道xx号',5555.00,5555.00,'谢仁松','15166702232',1,NULL,'2020-01-17 14:39:54',NULL,0,0,1,1,NULL,NULL,NULL,NULL,'2020-02-04 14:14:12','2020-02-04 14:14:12',1),
	(41,'20200204141458927335test',1,'山东省莆田区xx街道',1199.00,1199.00,'王娟','15166702232',1,NULL,'2020-01-17 14:39:54',NULL,0,0,0,1,NULL,NULL,NULL,NULL,'2020-02-04 14:14:58','2020-02-04 14:14:58',1);

/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table permissions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '名称',
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort` smallint(6) NOT NULL COMMENT '排序，数字越大越在前面',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;

INSERT INTO `permissions` (`id`, `parent_id`, `name`, `display_name`, `url`, `guard_name`, `sort`, `created_at`, `updated_at`)
VALUES
	(1,0,'/user/get','用户管理','/center-user/get','web',543,'2020-01-08 10:10:33','2020-02-09 01:49:53'),
	(3,0,'/product/get','商品管理','/product/get','web',0,'2020-01-08 11:02:00','2020-01-08 11:02:00'),
	(4,0,'/order/get','订单管理管理','/order/get','web',0,'2020-01-08 11:05:11','2020-01-08 11:05:11');

/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table product_descriptions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `product_descriptions`;

CREATE TABLE `product_descriptions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(10) unsigned NOT NULL COMMENT '对应商品id',
  `type` int(10) unsigned NOT NULL COMMENT '简介类型1图片2文本',
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '简介内容',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `product_descriptions` WRITE;
/*!40000 ALTER TABLE `product_descriptions` DISABLE KEYS */;

INSERT INTO `product_descriptions` (`id`, `product_id`, `type`, `content`, `created_at`, `updated_at`)
VALUES
	(1,1,1,'sdfdg','2020-01-03 11:00:06','2020-01-03 11:00:06'),
	(2,1,2,'2dfd','2020-01-03 11:00:06','2020-01-03 11:00:06'),
	(5,2,1,'sdfdg','2020-01-03 11:08:19','2020-01-03 11:08:19'),
	(6,2,2,'2dfd','2020-01-03 11:08:19','2020-01-03 11:08:19'),
	(7,3,1,'sdfdg','2020-01-03 11:31:46','2020-01-03 11:31:46'),
	(8,3,2,'2dfd','2020-01-03 11:31:46','2020-01-03 11:31:46'),
	(9,5,1,'sdfdg','2020-01-04 14:48:33','2020-01-04 14:48:33'),
	(10,5,2,'2dfd','2020-01-04 14:48:33','2020-01-04 14:48:33'),
	(25,7,2,'哈哈哈','2020-02-02 13:44:39','2020-02-02 13:44:39'),
	(33,10,1,'http://wuqinqiang.oss-cn-hangzhou.aliyuncs.com/iphone6.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612330703&Signature=l9xX0V1s5RfQVnpkJud3QlOhDJM%3D','2020-02-04 13:40:37','2020-02-04 13:40:37'),
	(35,11,1,'http://wuqinqiang.oss-cn-hangzhou.aliyuncs.com/6m.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612331382&Signature=e4mrQH%2ByykaU%2B28HKcZaooDW5qk%3D','2020-02-04 13:49:57','2020-02-04 13:49:57'),
	(36,12,1,'http://wuqinqiang.oss-cn-hangzhou.aliyuncs.com/jiqi1.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612331719&Signature=W77ilUv6QUkHqv92y2dKai1%2FnpU%3D','2020-02-04 13:56:08','2020-02-04 13:56:08'),
	(37,13,1,'http://wuqinqiang.oss-cn-hangzhou.aliyuncs.com/biao5.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612331963&Signature=R2c3KxgGkh39%2FI6WfZncOXPJILY%3D','2020-02-04 14:01:00','2020-02-04 14:01:00'),
	(38,14,1,'http://wuqinqiang.oss-cn-hangzhou.aliyuncs.com/z6.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612332285&Signature=lHWY1w463jf9Giiqunzl4ulWKNY%3D','2020-02-04 14:05:48','2020-02-04 14:05:48'),
	(39,9,1,'http://wuqinqiang.oss-cn-hangzhou.aliyuncs.com/6.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612329929&Signature=ka8W0lyxzXbxdlUDNW2T0tOpu%2Fc%3D','2020-02-09 10:19:18','2020-02-09 10:19:18');

/*!40000 ALTER TABLE `product_descriptions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table product_skus
# ------------------------------------------------------------

DROP TABLE IF EXISTS `product_skus`;

CREATE TABLE `product_skus` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'sku标题',
  `product_id` int(10) unsigned NOT NULL COMMENT '对应商品id',
  `before_price` decimal(10,2) DEFAULT NULL COMMENT '商品原价',
  `price` decimal(10,2) NOT NULL COMMENT '商品现价',
  `stock` int(10) unsigned NOT NULL COMMENT 'sku库存',
  `sku_image` text COLLATE utf8mb4_unicode_ci COMMENT 'sku库存',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `product_skus` WRITE;
/*!40000 ALTER TABLE `product_skus` DISABLE KEYS */;

INSERT INTO `product_skus` (`id`, `title`, `product_id`, `before_price`, `price`, `stock`, `sku_image`, `created_at`, `updated_at`)
VALUES
	(1,'Aj1(凯尔特人)',1,100.00,30.00,88,'http://rtrh.com','2020-01-03 11:00:06','2020-01-03 11:00:06'),
	(2,'Aj1(拆线)',1,100.00,50.00,66,'http://3534.com','2020-01-03 11:00:06','2020-01-03 11:00:06'),
	(5,'Aj1(凯尔特人)',2,100.00,30.00,88,'http://rtrh.com','2020-01-03 11:08:19','2020-01-03 11:08:19'),
	(6,'Aj1(拆线)',2,100.00,50.00,66,'http://3534.com','2020-01-03 11:08:19','2020-01-03 11:08:19'),
	(7,'Aj1(凯尔特人)',3,100.00,30.00,88,'http://rtrh.com','2020-01-03 11:31:46','2020-01-03 11:31:46'),
	(8,'Aj1(拆线)',3,100.00,50.00,66,'http://3534.com','2020-01-03 11:31:46','2020-01-03 11:31:46'),
	(9,'Aj1(凯尔特人)',5,100.00,30.00,88,'http://rtrh.com','2020-01-04 14:48:33','2020-01-04 14:48:33'),
	(10,'Aj1(拆线)',5,100.00,50.00,66,'http://3534.com','2020-01-04 14:48:34','2020-01-04 14:48:34'),
	(25,'256G',7,1000.00,500.00,300,'http://wuqinqiang.oss-cn-hangzhou.aliyuncs.com/8d006c56276a5005f23e9a562e0b0db6-256x128.png?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612158169&Signature=Dz7eEphpVEWV5KLY8e9R%2BRO5VcI%3D','2020-02-02 13:44:39','2020-02-02 13:44:39'),
	(32,'e',8,30.00,20.00,10,'http://wuqinqiang.oss-cn-hangzhou.aliyuncs.com/%E5%85%AC%E4%BC%97%E5%8F%B7.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612174349&Signature=WhO1aWwBsNImyFgRHkaNDANXI%2Fk%3D','2020-02-03 10:44:41','2020-02-03 10:44:41'),
	(34,'s4t',6,45.00,23.00,44,'http://wuqinqiang.oss-cn-hangzhou.aliyuncs.com/9121A5A7D623DBFF757A211182363D9E.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1609913632&Signature=j%2Bq0u080oSDp9%2FMSne3jwid8VPU%3D','2020-02-03 11:09:04','2020-02-03 11:09:04'),
	(37,'白色',10,5600.00,4500.00,22,'http://wuqinqiang.oss-cn-hangzhou.aliyuncs.com/iphone2.png?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612330752&Signature=R96Bt%2FT9fX44Q9wIuc51CgX78wo%3D','2020-02-04 13:40:37','2020-02-04 13:40:37'),
	(38,'黑色',10,6666.00,5555.00,20,'http://wuqinqiang.oss-cn-hangzhou.aliyuncs.com/iphone7.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612330825&Signature=mOf4hucu0C5Z6zfS3ssgxaUAL10%3D','2020-02-04 13:40:37','2020-02-04 13:40:37'),
	(40,'银色',11,16999.00,15999.00,40,'http://wuqinqiang.oss-cn-hangzhou.aliyuncs.com/4m.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612331390&Signature=SfublAucajgWN0VyG2Dxp7Yzg8g%3D','2020-02-04 13:49:57','2020-02-04 13:49:57'),
	(41,'白色',12,1000.00,888.00,400,'http://wuqinqiang.oss-cn-hangzhou.aliyuncs.com/jiqi2.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612331728&Signature=dJqojVMTHzcjvYx71GWpzvbthVo%3D','2020-02-04 13:56:08','2020-02-04 13:56:08'),
	(42,'白色 全自动',12,1299.00,1199.00,1000,'http://wuqinqiang.oss-cn-hangzhou.aliyuncs.com/jiqi3.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612331749&Signature=cAcXzG41esmA1AiJ1pxPKpvSIis%3D','2020-02-04 13:56:08','2020-02-04 13:56:08'),
	(43,'白色',13,19999.00,17777.00,10,'http://wuqinqiang.oss-cn-hangzhou.aliyuncs.com/biao4.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612331995&Signature=l1IDeaNlU2wszuICZ%2FeWbZhzZ6o%3D','2020-02-04 14:01:00','2020-02-04 14:01:00'),
	(44,'黑色',13,20000.00,19999.00,40,'http://wuqinqiang.oss-cn-hangzhou.aliyuncs.com/biao3.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612332014&Signature=I6t%2FU6Ukd06p%2FQCCX4CAZ%2BXZJZ8%3D','2020-02-04 14:01:00','2020-02-04 14:01:00'),
	(45,'金色',13,30000.00,26777.00,10,'http://wuqinqiang.oss-cn-hangzhou.aliyuncs.com/biao2.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612332050&Signature=LlPDj%2FufCiSLKAxswCtKfI8Cq5A%3D','2020-02-04 14:01:00','2020-02-04 14:01:00'),
	(46,'心形',14,6666.00,6000.00,10,'http://wuqinqiang.oss-cn-hangzhou.aliyuncs.com/z5.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612332310&Signature=y3ZIAhfrkwuTnuFqirc5o%2FMJAiQ%3D','2020-02-04 14:05:48','2020-02-04 14:05:48'),
	(47,'皇冠',14,19999.00,16666.00,20,'http://wuqinqiang.oss-cn-hangzhou.aliyuncs.com/z3.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612332331&Signature=iB68EjZBPQQl1DUGYht4%2BcZvLoU%3D','2020-02-04 14:05:48','2020-02-04 14:05:48'),
	(48,'36码 黄',9,1699.00,1599.00,200,'http://wuqinqiang.oss-cn-hangzhou.aliyuncs.com/3.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612329969&Signature=EjfaSCPZDtDQ16FpAnK2rGe87Nc%3D','2020-02-09 10:19:18','2020-02-09 10:19:18'),
	(49,'40 白',9,1799.00,1699.00,32,'http://wuqinqiang.oss-cn-hangzhou.aliyuncs.com/4.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612329999&Signature=QbCP6BLLtBVg8IdxyVYfJ5SPtYQ%3D','2020-02-09 10:19:18','2020-02-09 10:19:18');

/*!40000 ALTER TABLE `product_skus` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table products
# ------------------------------------------------------------

DROP TABLE IF EXISTS `products`;

CREATE TABLE `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(10) unsigned NOT NULL,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '商品详情',
  `on_sale` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '0下架1在售2定时上架',
  `sold_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '销量',
  `review_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评价数',
  `price` decimal(10,2) NOT NULL COMMENT 'sku最低价',
  `sort` int(10) unsigned NOT NULL COMMENT '商品排序',
  `time_on` timestamp NULL DEFAULT NULL COMMENT '定时上架时间',
  `time_off` timestamp NULL DEFAULT NULL COMMENT '定时下架时间',
  `deleted_at` timestamp NULL DEFAULT NULL COMMENT '定时下架时间',
  `fare` decimal(10,2) NOT NULL COMMENT '运费',
  `front_image` varchar(199) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '封面图片',
  `images` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '商品详情图片',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `only_buy` int(10) unsigned DEFAULT NULL COMMENT '用户最大购买次数',
  `only_count` int(10) unsigned DEFAULT NULL COMMENT '单次购买最大数量',
  `category_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '分类名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;

INSERT INTO `products` (`id`, `category_id`, `name`, `description`, `on_sale`, `sold_count`, `review_count`, `price`, `sort`, `time_on`, `time_off`, `deleted_at`, `fare`, `front_image`, `images`, `created_at`, `updated_at`, `only_buy`, `only_count`, `category_name`)
VALUES
	(6,14,'iphone x','sdfds',0,0,0,23.00,23,'2020-01-07 16:13:00','2020-01-10 00:00:00',NULL,2.00,'http://wuqinqiang.oss-cn-hangzhou.aliyuncs.com/9121A5A7D623DBFF757A211182363D9E.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1609913804&Signature=0H9AwgZdqvp0sv2alQE10gevd7A%3D','[\"http:\\/\\/wuqinqiang.oss-cn-hangzhou.aliyuncs.com\\/9121A5A7D623DBFF757A211182363D9E.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1609913807&Signature=EXoS1uKimRMYsjkqpVdRITjQtcg%3D\",\"http:\\/\\/wuqinqiang.oss-cn-hangzhou.aliyuncs.com\\/9121A5A7D623DBFF757A211182363D9E.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1609913830&Signature=IwIwn7w1xWHlLDPt%2FGxtFkysmmM%3D\",\"http:\\/\\/wuqinqiang.oss-cn-hangzhou.aliyuncs.com\\/8d006c56276a5005f23e9a562e0b0db6-256x128.png?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1609913841&Signature=2W8CA9h%2BMMEucZukwojDPjoBZAg%3D\"]','2020-01-07 14:17:22','2020-02-04 13:14:14',10,9999,'iphone 11'),
	(7,16,'定时上架','定时上架',0,0,0,500.00,23,'2020-02-02 13:45:00','2020-02-03 00:00:00',NULL,10.00,'http://wuqinqiang.oss-cn-hangzhou.aliyuncs.com/8d006c56276a5005f23e9a562e0b0db6-256x128.png?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612158093&Signature=MNiSvfAjfra2H%2BbcegVmeQl2egU%3D','[\"http:\\/\\/wuqinqiang.oss-cn-hangzhou.aliyuncs.com\\/9121A5A7D623DBFF757A211182363D9E.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612158132&Signature=77ztb4XDE%2F7BS5tzEbWGIzp3teY%3D\",\"http:\\/\\/wuqinqiang.oss-cn-hangzhou.aliyuncs.com\\/9121A5A7D623DBFF757A211182363D9E.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612158141&Signature=6q329l%2B1LqQc%2FfOCLu3afmG7owM%3D\",\"http:\\/\\/wuqinqiang.oss-cn-hangzhou.aliyuncs.com\\/9121A5A7D623DBFF757A211182363D9E.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612158145&Signature=0t%2BXRtg6i%2B02bZTJlNgbhmRB5M8%3D\"]','2020-02-02 13:44:39','2020-02-03 00:00:00',20,30,'苹果手机'),
	(8,16,'fvvf','333',0,0,0,20.00,23,NULL,NULL,NULL,23.00,'http://wuqinqiang.oss-cn-hangzhou.aliyuncs.com/QQ20191122-0.png?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612174224&Signature=ODLZyL8DDQRLYnf1VUCTfLZpFTc%3D','[\"http:\\/\\/wuqinqiang.oss-cn-hangzhou.aliyuncs.com\\/9121A5A7D623DBFF757A211182363D9E.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612174230&Signature=5hEfxAo4eJLu7cp7zVOk5ziEc9s%3D\",\"http:\\/\\/wuqinqiang.oss-cn-hangzhou.aliyuncs.com\\/1.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612174252&Signature=oF03ycBytgYTliMBpzLssT34RbY%3D\",\"http:\\/\\/wuqinqiang.oss-cn-hangzhou.aliyuncs.com\\/1.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612174280&Signature=b1iNL68Q7Y%2BJqMyO%2FMOGysgg8Kg%3D\"]','2020-02-02 18:12:44','2020-02-04 13:14:12',9999,9999,'苹果手机'),
	(9,32,'adidas Yeezy Boost 350','adidas Yeezy Boost 350 V2 侃爷椰子350',0,0,0,1599.00,20,NULL,NULL,NULL,10.00,'http://wuqinqiang.oss-cn-hangzhou.aliyuncs.com/3.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612329860&Signature=DzOSIU3XhyJK9l8dREqeWLxmGaY%3D','[\"http:\\/\\/wuqinqiang.oss-cn-hangzhou.aliyuncs.com\\/4.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612329874&Signature=0YYf8oC7CJC1dH8fVWKB9WR3IrQ%3D\",\"http:\\/\\/wuqinqiang.oss-cn-hangzhou.aliyuncs.com\\/5.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612329880&Signature=1ZKHl9pMhIRp5Xo%2Bvf%2B%2B%2FonzpFk%3D\",\"http:\\/\\/wuqinqiang.oss-cn-hangzhou.aliyuncs.com\\/2.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612330033&Signature=4tP8oPc2HjtlPoI0LEpqWHpZfdw%3D\"]','2020-02-04 13:33:45','2020-02-10 11:59:38',30,30,'Yeezy 350'),
	(10,21,'Apple/苹果 iPhone 11 ','Apple/苹果 iPhone 11 Pro',0,0,0,4500.00,10,NULL,NULL,NULL,10.00,'http://wuqinqiang.oss-cn-hangzhou.aliyuncs.com/iphone2.png?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612330649&Signature=8NvQx9U%2BA%2BTgq0VTDw2eApYtGrw%3D','[\"http:\\/\\/wuqinqiang.oss-cn-hangzhou.aliyuncs.com\\/iphone3.png?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612330653&Signature=mHJUW1kzt47iVG%2BYU1sz91Oh5b8%3D\",\"http:\\/\\/wuqinqiang.oss-cn-hangzhou.aliyuncs.com\\/iphone4.png?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612330657&Signature=O%2Fqy8Aok0H5q7DCAWyMTtMCOh3A%3D\",\"http:\\/\\/wuqinqiang.oss-cn-hangzhou.aliyuncs.com\\/iphone1.png?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612330661&Signature=RV5v3xS5%2F%2FwP%2B7NE5yQz48Ss8Ek%3D\"]','2020-02-04 13:40:37','2020-02-12 11:49:09',9999,9999,'手机'),
	(11,22,' Apple/苹果16英寸新款MacBook Pro 屏',' Apple/苹果16英寸新款MacBook Pro 屏',0,0,0,15999.00,30,NULL,NULL,NULL,10.00,'http://wuqinqiang.oss-cn-hangzhou.aliyuncs.com/1m.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612331339&Signature=YA7bUZjMD61CqtOY1slWFNRArTQ%3D','[\"http:\\/\\/wuqinqiang.oss-cn-hangzhou.aliyuncs.com\\/5.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612331145&Signature=aGmZL6qMUZTRlesfjw9vYnR%2Fuq8%3D\",\"http:\\/\\/wuqinqiang.oss-cn-hangzhou.aliyuncs.com\\/2.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612331149&Signature=fa6cIBX%2FtWRXkg0UbUCvvXFBZso%3D\",\"http:\\/\\/wuqinqiang.oss-cn-hangzhou.aliyuncs.com\\/4.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612331154&Signature=KtQljchoofRF9ZbJFugingA%2BCRQ%3D\"]','2020-02-04 13:47:10','2020-02-18 15:17:23',9999,9999,'电脑'),
	(12,33,'扫地机器人','扫地机器人',0,0,0,888.00,55,NULL,NULL,NULL,10.00,'http://wuqinqiang.oss-cn-hangzhou.aliyuncs.com/jiqi2.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612331700&Signature=VIZbj%2FSlVEJf9TWMaOwvg2J2frg%3D','[\"http:\\/\\/wuqinqiang.oss-cn-hangzhou.aliyuncs.com\\/jiqi2.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612331705&Signature=%2BJ1OhF%2B%2FZUplEhJeJAMFpKRsj48%3D\",\"http:\\/\\/wuqinqiang.oss-cn-hangzhou.aliyuncs.com\\/jiqi3.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612331709&Signature=0eMdo7A12VwP34ZQItZGjeyGjB0%3D\",\"http:\\/\\/wuqinqiang.oss-cn-hangzhou.aliyuncs.com\\/jiqi4.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612331712&Signature=6lAmsKrm55M4XuUpIBhhdVj5Y1s%3D\"]','2020-02-04 13:56:08','2020-02-19 11:38:00',9999,9999,'机器人'),
	(13,23,'卡西欧','卡西欧35周年限定小方块金属',0,0,0,17777.00,100,NULL,NULL,NULL,10.00,'http://wuqinqiang.oss-cn-hangzhou.aliyuncs.com/biao1.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612331941&Signature=IWkXsbU0sBG9GWefUzdD0hJ3K1Y%3D','[\"http:\\/\\/wuqinqiang.oss-cn-hangzhou.aliyuncs.com\\/biao2.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612331945&Signature=M2oTy5dvvgDM4O4FRV2fdYRhxms%3D\",\"http:\\/\\/wuqinqiang.oss-cn-hangzhou.aliyuncs.com\\/biao3.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612331950&Signature=1AkmNBxwHbVDgNfHoNuUeFJbXm8%3D\",\"http:\\/\\/wuqinqiang.oss-cn-hangzhou.aliyuncs.com\\/biao4.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612331954&Signature=wgDrkGYpufrEt4TXt9zzyG5%2BPZs%3D\"]','2020-02-04 14:01:00','2020-02-19 16:13:47',9999,9999,'卡西欧'),
	(14,24,'周大生钻戒女18K玫瑰金求婚','周大生钻戒女18K玫瑰金',0,0,0,6000.00,33,NULL,NULL,NULL,10.00,'http://wuqinqiang.oss-cn-hangzhou.aliyuncs.com/z1.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612332240&Signature=Nr%2BUChnfUKGniv8DTW%2Bv6h26kDw%3D','[\"http:\\/\\/wuqinqiang.oss-cn-hangzhou.aliyuncs.com\\/z2.jpeg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612332245&Signature=kHJUrIr9Q366PcEs%2FlXH2GrmbTA%3D\",\"http:\\/\\/wuqinqiang.oss-cn-hangzhou.aliyuncs.com\\/z3.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612332251&Signature=xwecxOHFdtvaoUE7AsO6o6QzslU%3D\",\"http:\\/\\/wuqinqiang.oss-cn-hangzhou.aliyuncs.com\\/z4.jpg?OSSAccessKeyId=LTAI4Fu9UYsNzjzeHCtmHpmX&Expires=1612332255&Signature=aQtjcnEH7CbBRGgtHajRFTjBix8%3D\"]','2020-02-04 14:05:48','2020-02-21 17:00:34',1,1,'钻戒');

/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table role_has_permissions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `role_has_permissions`;

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`)
VALUES
	(4,16);

/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table roles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;

INSERT INTO `roles` (`id`, `name`, `description`, `guard_name`, `created_at`, `updated_at`)
VALUES
	(1,'管理员','总权限','web','2020-01-08 09:42:16','2020-01-08 09:52:40'),
	(16,'管理员11','总权限111','web','2020-01-08 11:00:46','2020-01-08 11:00:46');

/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` tinyint(3) unsigned DEFAULT NULL COMMENT '1 男,2 女',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '2' COMMENT '1普通用户,2系统管理员',
  `password` varchar(199) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `monetary` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '总消费金额',
  `open_id` varchar(199) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '微信openid',
  `wechat_openid` varchar(199) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '微信openID',
  `union_id` varchar(199) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '微信union_id',
  `avatar_url` varchar(199) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '头像avatar_url',
  `wechat` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '用户微信号',
  `location` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '用户所在地区',
  `buy_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户购买次数',
  `last_buy` timestamp NULL DEFAULT NULL COMMENT '最近购买时间',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1正常用户2禁用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `gender`, `type`, `password`, `deleted_at`, `monetary`, `open_id`, `wechat_openid`, `union_id`, `avatar_url`, `wechat`, `location`, `buy_count`, `last_buy`, `created_at`, `updated_at`, `status`)
VALUES
	(1,'curry',NULL,NULL,NULL,2,'$2y$10$s.TrJVVQECxP.JTT.7lcD.wiFfFPo1gQk8CQSQIFC414PxQGlvI.y',NULL,0.00,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,'2020-01-03 11:09:17','2020-02-21 16:58:37',1),
	(2,'test',NULL,NULL,NULL,2,'$2y$10$rFmjgPEDDg4JeUovz4htMu08wiRhm9v6WDmYoKcbH0flsgRlZDuHi',NULL,0.00,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,'2020-01-08 13:55:57','2020-02-20 22:36:11',1),
	(3,'asf',NULL,NULL,NULL,2,'$2y$10$EyG1VqnUt4.UCszZDUm3u.f6djZBqRlZ.CzJpkIBpE4UUnSRdaBoO',NULL,0.00,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,'2020-01-08 15:29:10','2020-02-04 00:36:30',1),
	(4,'fdsa',NULL,NULL,NULL,2,'$2y$10$775aPhL3DDLDS/I1Bc0hiOWt.DYoE6Vy.p/WGBSP2mOdyzim292qy',NULL,0.00,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,'2020-02-09 01:50:39','2020-02-09 01:50:39',1);

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
