-- データベースとユーザーを作成する
DROP DATABASE IF EXISTS millitheta;
CREATE DATABASE millitheta CHARACTER SET utf8 COLLATE utf8_general_ci;
GRANT SELECT,INSERT,UPDATE,DELETE,CREATE,DROP,INDEX,ALTER,PROCESS
ON millitheta.* TO 'millitheta'@'localhost'
IDENTIFIED BY '92xUCSRgoBqZ0qyB';

-- テスト用
DROP DATABASE IF EXISTS millitheta_test;
CREATE DATABASE millitheta_test CHARACTER SET utf8 COLLATE utf8_general_ci;
GRANT SELECT,INSERT,UPDATE,DELETE,CREATE,DROP,INDEX,ALTER,PROCESS
ON millitheta_test.* TO 'millitheta'@'localhost'
IDENTIFIED BY '92xUCSRgoBqZ0qyB';

-- デバッグキット用
DROP DATABASE IF EXISTS millitheta_debug;
CREATE DATABASE millitheta_debug CHARACTER SET utf8 COLLATE utf8_general_ci;
GRANT SELECT,INSERT,UPDATE,DELETE,CREATE,DROP,INDEX,ALTER,PROCESS
ON millitheta_debug.* TO 'millitheta'@'localhost'
IDENTIFIED BY '92xUCSRgoBqZ0qyB';

FLUSH PRIVILEGES;
