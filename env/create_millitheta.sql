-- データベースとユーザーを作成する
DROP DATABASE IF EXISTS millitheta;
CREATE DATABASE millitheta CHARACTER SET utf8 COLLATE utf8_general_ci;
GRANT SELECT,INSERT,UPDATE,DELETE,CREATE,DROP,INDEX,ALTER
ON millitheta.* TO 'millitheta'@'localhost'
IDENTIFIED BY '92xUCSRgoBqZ0qyB';

-- テスト用
DROP DATABASE IF EXISTS millitheta_test;
CREATE DATABASE millitheta_test CHARACTER SET utf8 COLLATE utf8_general_ci;
GRANT SELECT,INSERT,UPDATE,DELETE,CREATE,DROP,INDEX,ALTER
ON millitheta_test.* TO 'millitheta'@'localhost'
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
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `mail` varchar(255) NOT NULL COMMENT 'メールアドレス',
  `password` varchar(255) NOT NULL COMMENT 'パスワード',
  `privilege` json DEFAULT NULL COMMENT '権限',
  `created` datetime DEFAULT NULL COMMENT '作成日時',
  `modified` datetime DEFAULT NULL COMMENT '更新日時',
  `deleted` datetime DEFAULT NULL COMMENT '削除日時',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
