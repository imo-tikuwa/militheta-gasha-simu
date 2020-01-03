-- データベースとユーザーを作成する
DROP DATABASE IF EXISTS millitheta;
CREATE DATABASE millitheta CHARACTER SET utf8 COLLATE utf8_general_ci;
GRANT SELECT,INSERT,UPDATE,DELETE,CREATE,DROP,INDEX,ALTER
ON millitheta.* TO 'millitheta'@'localhost'
IDENTIFIED BY '92xUCSRgoBqZ0qyB';

-- デバッグキット用
DROP DATABASE IF EXISTS millitheta_debug;
CREATE DATABASE millitheta_debug CHARACTER SET utf8 COLLATE utf8_general_ci;
GRANT SELECT,INSERT,UPDATE,DELETE,CREATE,DROP,INDEX,ALTER
ON millitheta_debug.* TO 'millitheta'@'localhost'
IDENTIFIED BY '92xUCSRgoBqZ0qyB';

FLUSH PRIVILEGES;

-- 管理者テーブルを作成する
use millitheta;
CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `delete_flag` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
