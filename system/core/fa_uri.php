<?php  if ( ! defined('INDEX')) exit('No direct script access allowed');
class URI {
	function __construct()
	{
		// ---------------------------------------------------------------------------------
        foreach (load_recursive('application/config') as $value) {
            require_once($value);
        }
		
        foreach ($main_config as $key => $value) {
            define($key, $value);
        }
		
		define('app_style_path',base_url. 'application/style/app/');
		define('base_style_path',base_url. 'application/style/project/');
        
        foreach (load_recursive('application/helpers') as $value) {
            require_once($value);
        }
        // ---------------------------------------------------------------------------------------------	
		
		$db = & load_class('DB');
		$tabel = $db->get_table();
		if(!in_array('short_link', $tabel)){
			$db->exec_query("CREATE TABLE `short_link` (
							  `id_link` int(11) NOT NULL AUTO_INCREMENT,
							  `link` varchar(100) NOT NULL,
							  `short_link` varchar(50) NOT NULL,
							  PRIMARY KEY (`id_link`),
							  UNIQUE KEY `short_link` (`short_link`),
							  UNIQUE KEY `link` (`link`)
							) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;");
		}
	}
	
    function segment($nomor){
		$db = & load_class('DB');		
		$uri_base = explode('?', $_SERVER['REQUEST_URI']);
		$uri_link = explode('/',$uri_base[0]);
		$uri_new = $uri_base[0];
		
		$ext = array(".html", ".aspx", ".asp");
		
		for($i=0;$i<count($uri_link);$i++){
			$link = $db->query("select * from short_link where short_link='".str_replace($ext, '', $uri_link[$i])."'")->fetch_first();

			if(count($link)>0) {
				$uri_new = str_replace($uri_link[$i],$link["link"],$uri_new);
			}
		}
		
		$data = explode('/', $uri_new); $c=4;
		if(strpos(base_url,"http://")===false) $c=2;
		
		if(count(explode('/',base_url))==$c) $nomor = $nomor-1;
		
		if ($nomor > count($data) - 1) return "";
		else return str_replace($ext, '', $data[$nomor]);		
	}
}

?>