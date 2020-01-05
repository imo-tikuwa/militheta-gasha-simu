DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `delete_flag` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 初期データ登録（パスワード：password）
INSERT INTO `admins` VALUES ('1', 'admin@imo-tikuwa.com', 'lbV+4PEvS9UyIMnM5IbqzQ==:yFiuLMAJMrQO6gsizK5y0w==', '2020-01-03 22:24:33', '2020-01-03 22:24:33', '0');
