DROP TABLE IF EXISTS `characters`;
CREATE TABLE `characters` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(255) DEFAULT NULL COMMENT '名前',
  `created` datetime DEFAULT NULL COMMENT '作成日時',
  `modified` datetime DEFAULT NULL COMMENT '更新日時',
  `delete_flag` char(1) NOT NULL DEFAULT '0' COMMENT '削除フラグ',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='キャラクター';

-- 初期データ登録
INSERT INTO `characters` VALUES ('1', '天海春香', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('2', '萩原雪歩', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('3', '菊地真', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('4', '我那覇響', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('5', '如月千早', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('6', '水瀬伊織', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('7', '四条貴音', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('8', '秋月律子', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('9', '星井美希', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('10', '高槻やよい', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('11', '三浦あずさ', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('12', '双海亜美', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('13', '双海真美', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('14', '春日未来', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('15', '田中琴葉', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('16', '佐竹美奈子', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('17', '徳川まつり', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('18', '七尾百合子', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('19', '高山紗代子', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('20', '松田亜利沙', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('21', '高坂海美', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('22', '中谷育', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('23', 'エミリー', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('24', '矢吹可奈', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('25', '横山奈緒', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('26', '福田のり子', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('27', '最上静香', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('28', '所恵美', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('29', 'ロコ', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('30', '天空橋朋花', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('31', '北沢志保', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('32', '舞浜歩', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('33', '二階堂千鶴', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('34', '真壁瑞希', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('35', '百瀬莉緒', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('36', '永吉昴', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('37', '周防桃子', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('38', 'ジュリア', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('39', '白石紬', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('40', '伊吹翼', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('41', '島原エレナ', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('42', '箱崎星梨花', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('43', '野々原茜', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('44', '望月杏奈', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('45', '木下ひなた', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('46', '馬場このみ', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('47', '大神環', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('48', '豊川風花', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('49', '宮尾美也', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('50', '篠宮可憐', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('51', '北上麗花', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
INSERT INTO `characters` VALUES ('52', '桜守歌織', '2020-01-03 13:00:00', '2020-01-03 13:00:00', '0');
