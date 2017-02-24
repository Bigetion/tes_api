<?php  if ( ! defined('INDEX')) exit('No direct script access allowed');

class Autotable {
    function set_autotable(){
		$db = & load_class('DB');
		$crypt = & load_class('Crypt');
		$tabel = $db->get_table();
		
		if (!in_array('roles', $tabel)) {
            $db->exec("CREATE TABLE `roles` (
							  `id_role` int(10) NOT NULL AUTO_INCREMENT,
							  `role_name` varchar(50) NOT NULL,
							  `description` varchar(255) NOT NULL,
							  `permission` text,
							  PRIMARY KEY (`id_role`)
							)");
            $data_role['id_role'] = '1';
            $data_role['role_name'] = 'Administrator';
            $data_role['description'] = 'Memiliki Hak Akses Tertinggi Dalam Aplikasi';
            $data_role['permission'] = '';
            $db->insert('roles', $data_role);

            $data_role['id_role'] = '2';
            $data_role['role_name'] = 'Guest';
            $data_role['description'] = 'Pengunjung Website';
            $data_role['permission'] = '';
            $db->insert('roles', $data_role);
        }
		
		if (!in_array('users', $tabel)) {
            $db->exec("CREATE TABLE `users` (
							  `id_user` int(10) NOT NULL AUTO_INCREMENT,
							  `username` varchar(10) NOT NULL,
							  `password` text NOT NULL,
							  `id_role` int(10) NOT NULL,
							  `id_type` tinyint(1),
							  `id_external` bigint(20),
							  PRIMARY KEY (`id_user`)
							)");
							
            $data_user['id_user'] = '1';
            $data_user['username'] = 'Admin';
            $data_user['password'] = password_hash("Admin",1);
            $data_user['id_role'] = '1';
            $db->insert('users', $data_user);

            $data_user['id_user'] = '2';
            $data_user['username'] = 'Guest';
            $data_user['password'] = password_hash("Guest",1);
            $data_user['id_role'] = '2';
            $db->insert('users', $data_user);
        }

		if(!in_array('short_link', $tabel)){
			$db->exec("CREATE TABLE `short_link` (
							  `id_link` int(11) NOT NULL AUTO_INCREMENT,
							  `link` varchar(100) NOT NULL,
							  `short_link` varchar(50) NOT NULL,
							  PRIMARY KEY (`id_link`),
							  UNIQUE KEY `short_link` (`short_link`),
							  UNIQUE KEY `link` (`link`)
							)");
		}
		
		if (!in_array('pages', $tabel)) {
            $db->exec("CREATE TABLE `pages` (
							  `id_page` varchar(50) NOT NULL,
							  `content` longblob NOT NULL,
							  PRIMARY KEY (`id_page`)
							) ENGINE=MyISAM DEFAULT CHARSET=latin1;");
        }
		
		if (!in_array('posts', $tabel)) {
            $db->exec("CREATE TABLE `posts` (
							  `id_post` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
							  `post_author` bigint(20) unsigned NOT NULL DEFAULT '0',
							  `id_category` varchar(50) NOT NULL DEFAULT '0',
							  `post_title` text NOT NULL,
							  `post_content` longtext NOT NULL,
							  `post_description` text NOT NULL,
							  `post_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
							  `post_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
							  `post_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
							  `post_modified_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
							  `post_image_link` text NOT NULL,
							  `post_title_link` text NOT NULL,
							  PRIMARY KEY (`id_post`),
							  KEY `type_status_date` (`post_date`,`id_post`),
							  KEY `post_author` (`post_author`)
							)");
        }	
		
		if(!in_array('post_terms', $tabel)){
			$db->exec("CREATE TABLE `post_terms` (
							  `term_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
							  `name` varchar(200) NOT NULL DEFAULT '',
							  `slug` varchar(200) NOT NULL DEFAULT '',
							  `term_group` bigint(10) NOT NULL DEFAULT '0',
							  PRIMARY KEY (`term_id`),
							  UNIQUE KEY `slug` (`slug`),
							  KEY `name` (`name`)
							)");	
		}
		
		if(!in_array('post_term_taxonomy', $tabel)){
			$db->exec("CREATE TABLE `post_term_taxonomy` (
							  `term_taxonomy_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
							  `term_id` bigint(20) unsigned NOT NULL DEFAULT '0',
							  `taxonomy` varchar(32) NOT NULL DEFAULT '',
							  `description` longtext NOT NULL,
							  `parent` bigint(20) unsigned NOT NULL DEFAULT '0',
							  `count` bigint(20) NOT NULL DEFAULT '0',
							  PRIMARY KEY (`term_taxonomy_id`),
							  UNIQUE KEY `term_id_taxonomy` (`term_id`,`taxonomy`),
							  KEY `taxonomy` (`taxonomy`)
							)");	
		}		
	}        

}

?>