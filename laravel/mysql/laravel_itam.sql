CREATE DATABASE IF NOT EXISTS `en_itamapp` ;
USE en_itamapp;
CREATE USER 'root'@'127.0.0.1' IDENTIFIED WITH mysql_native_password BY 'EbHgjdK6ZoAw';
GRANT ALL PRIVILEGES ON *.* TO 'root'@'127.0.0.1';
CREATE TABLE IF NOT EXISTS `en_system_settings` (setting_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, configuration text NOT NULL, status ENUM('y', 'n') NOT NULL DEFAULT 'y' , type ENUM('ensysconfig') NOT NULL );
INSERT INTO `en_system_settings` (`configuration`, `status`, `type`) values ('{"en_sysconfig_api_url":"http://en-sysconfig-lumen-nginx-server", "en_sysconfig_config":"api", "en_sysconfig_config_path":"/var/www/ensystemconfig"}', 'y', 'ensysconfig');