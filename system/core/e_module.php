<?php  if ( ! defined('INDEX')) exit('No direct script access allowed');

class Module {

    function build_crud_controller($nama_project, $array = array()) {
        $nama_project = strtolower($nama_project);
        $a = array();
        $b = array();
        foreach ($array as $key => $value) {
            $a[] = $key;
            $b[] = $value;
        }
        $get = file_get_contents('application/project/controllers/crud_controller.php');
        $new = str_replace($a, $b, $get);

        $data = load_file('project');
        if (!in_array($nama_project, $data))
            mkdir('project/' . $nama_project);

        $data = load_file('project/' . $nama_project);
        if (!in_array('controllers', $data))
            mkdir('project/' . $nama_project . '/controllers');

        if (!file_exists('project/' . $nama_project . '/controllers/' . $array['__controller'] . '.php'))
            file_put_contents('project/' . $nama_project . '/controllers/' . $array['__controller'] . '.php', $new);

        $this->build_crud_view($array['__controller'], $nama_project, $array);
    }

    function build_crud_view($nama_controller, $nama_project, $array = array()) {
        $nama_project = strtolower($nama_project);
        $a = array();
        $b = array();
        foreach ($array as $key => $value) {
            $a[] = $key;
            $b[] = $value;
        }
        $get = file_get_contents('application/project/views/crud/view_confirm.php');
        $new1 = str_replace($a, $b, $get);
        $get = file_get_contents('application/project/views/crud/view_data.php');
        $new2 = str_replace($a, $b, $get);
        $get = file_get_contents('application/project/views/crud/view_tambah.php');
        $new3 = str_replace($a, $b, $get);
        $get = file_get_contents('application/project/views/crud/view_ubah.php');
        $new4 = str_replace($a, $b, $get);
		$get = file_get_contents('application/project/views/crud/view_per_page.php');
        $new5 = str_replace($a, $b, $get);


        $data = load_file('project/' . $nama_project);
        if (!in_array('views', $data))
            mkdir('project/' . $nama_project . '/views');

        $data = load_file('project/' . $nama_project . '/views');
        if (!in_array($nama_controller, $data))
            mkdir('project/' . $nama_project . '/views/' . $nama_controller);

        if (!file_exists('project/' . $nama_project . '/views/' . $nama_controller . '/view_confirm.php'))
            file_put_contents('project/' . $nama_project . '/views/' . $nama_controller . '/view_confirm.php', $new1);
        if (!file_exists('project/' . $nama_project . '/views/' . $nama_controller . '/view_data.php'))
            file_put_contents('project/' . $nama_project . '/views/' . $nama_controller . '/view_data.php', $new2);
        if (!file_exists('project/' . $nama_project . '/views/' . $nama_controller . '/view_tambah.php'))
            file_put_contents('project/' . $nama_project . '/views/' . $nama_controller . '/view_tambah.php', $new3);
        if (!file_exists('project/' . $nama_project . '/views/' . $nama_controller . '/view_ubah.php'))
            file_put_contents('project/' . $nama_project . '/views/' . $nama_controller . '/view_ubah.php', $new4);
		if (!file_exists('project/' . $nama_project . '/views/' . $nama_controller . '/view_per_page.php'))
            file_put_contents('project/' . $nama_project . '/views/' . $nama_controller . '/view_per_page.php', $new5);
    }
	
	function build_crud2_controller($nama_project, $array = array()) {
        $nama_project = strtolower($nama_project);
        $a = array();
        $b = array();
        foreach ($array as $key => $value) {
            $a[] = $key;
            $b[] = $value;
        }
        $get = file_get_contents('application/project/controllers/crud2_controller.php');
        $new = str_replace($a, $b, $get);

        $data = load_file('project');
        if (!in_array($nama_project, $data))
            mkdir('project/' . $nama_project);

        $data = load_file('project/' . $nama_project);
        if (!in_array('controllers', $data))
            mkdir('project/' . $nama_project . '/controllers');

        if (!file_exists('project/' . $nama_project . '/controllers/' . $array['__controller'] . '.php'))
            file_put_contents('project/' . $nama_project . '/controllers/' . $array['__controller'] . '.php', $new);

        $this->build_crud2_view($array['__controller'], $nama_project, $array);
    }

    function build_crud2_view($nama_controller, $nama_project, $array = array()) {
        $nama_project = strtolower($nama_project);
        $a = array();
        $b = array();
        foreach ($array as $key => $value) {
            $a[] = $key;
            $b[] = $value;
        }
        $get = file_get_contents('application/project/views/crud2/view_confirm.php');
        $new1 = str_replace($a, $b, $get);
        $get = file_get_contents('application/project/views/crud2/view_data.php');
        $new2 = str_replace($a, $b, $get);
        $get = file_get_contents('application/project/views/crud2/view_tambah.php');
        $new3 = str_replace($a, $b, $get);
        $get = file_get_contents('application/project/views/crud2/view_ubah.php');
        $new4 = str_replace($a, $b, $get);
		$get = file_get_contents('application/project/views/crud2/view_per_page.php');
        $new5 = str_replace($a, $b, $get);


        $data = load_file('project/' . $nama_project);
        if (!in_array('views', $data))
            mkdir('project/' . $nama_project . '/views');

        $data = load_file('project/' . $nama_project . '/views');
        if (!in_array($nama_controller, $data))
            mkdir('project/' . $nama_project . '/views/' . $nama_controller);

        if (!file_exists('project/' . $nama_project . '/views/' . $nama_controller . '/view_confirm.php'))
            file_put_contents('project/' . $nama_project . '/views/' . $nama_controller . '/view_confirm.php', $new1);
        if (!file_exists('project/' . $nama_project . '/views/' . $nama_controller . '/view_data.php'))
            file_put_contents('project/' . $nama_project . '/views/' . $nama_controller . '/view_data.php', $new2);
        if (!file_exists('project/' . $nama_project . '/views/' . $nama_controller . '/view_tambah.php'))
            file_put_contents('project/' . $nama_project . '/views/' . $nama_controller . '/view_tambah.php', $new3);
        if (!file_exists('project/' . $nama_project . '/views/' . $nama_controller . '/view_ubah.php'))
            file_put_contents('project/' . $nama_project . '/views/' . $nama_controller . '/view_ubah.php', $new4);
		if (!file_exists('project/' . $nama_project . '/views/' . $nama_controller . '/view_per_page.php'))
            file_put_contents('project/' . $nama_project . '/views/' . $nama_controller . '/view_per_page.php', $new5);
    }
	
	function build_view_controller($nama_project, $array = array()) {
        $nama_project = strtolower($nama_project);
        $a = array();
        $b = array();
        foreach ($array as $key => $value) {
            $a[] = $key;
            $b[] = $value;
        }
        $get = file_get_contents('application/project/controllers/view_controller.php');
        $new = str_replace($a, $b, $get);

        $data = load_file('project');
        if (!in_array($nama_project, $data))
            mkdir('project/' . $nama_project);

        $data = load_file('project/' . $nama_project);
        if (!in_array('controllers', $data))
            mkdir('project/' . $nama_project . '/controllers');

        if (!file_exists('project/' . $nama_project . '/controllers/' . $array['__controller'] . '.php'))
            file_put_contents('project/' . $nama_project . '/controllers/' . $array['__controller'] . '.php', $new);

        $this->build_view_view($array['__controller'], $nama_project, $array);
    }

    function build_view_view($nama_controller, $nama_project, $array = array()) {
        $nama_project = strtolower($nama_project);
        $a = array();
        $b = array();
        foreach ($array as $key => $value) {
            $a[] = $key;
            $b[] = $value;
        }
        $get = file_get_contents('application/project/views/view/view_data.php');
        $new1 = str_replace($a, $b, $get);
		$get = file_get_contents('application/project/views/view/view_per_page.php');
        $new2 = str_replace($a, $b, $get);


        $data = load_file('project/' . $nama_project);
        if (!in_array('views', $data))
            mkdir('project/' . $nama_project . '/views');

        $data = load_file('project/' . $nama_project . '/views');
        if (!in_array($nama_controller, $data))
            mkdir('project/' . $nama_project . '/views/' . $nama_controller);

        if (!file_exists('project/' . $nama_project . '/views/' . $nama_controller . '/view_data.php'))
            file_put_contents('project/' . $nama_project . '/views/' . $nama_controller . '/view_data.php', $new1);
        if (!file_exists('project/' . $nama_project . '/views/' . $nama_controller . '/view_per_page.php'))
            file_put_contents('project/' . $nama_project . '/views/' . $nama_controller . '/view_per_page.php', $new2);
    }

    function build_article_controller($nama_project, $array = array()) {
        $nama_project = strtolower($nama_project);
        $a = array();
        $b = array();
        foreach ($array as $key => $value) {
            $a[] = $key;
            $b[] = $value;
        }
        $get = file_get_contents('application/project/controllers/article_controller.php');
        $new = str_replace($a, $b, $get);

        $data = load_file('project');
        if (!in_array($nama_project, $data))
            mkdir('project/' . $nama_project);

        $data = load_file('project/' . $nama_project);
        if (!in_array('controllers', $data))
            mkdir('project/' . $nama_project . '/controllers');

        if (!file_exists('project/' . $nama_project . '/controllers/' . $array['__controller'] . '.php'))
            file_put_contents('project/' . $nama_project . '/controllers/' . $array['__controller'] . '.php', $new);

        $this->build_article_view($array['__controller'], $nama_project, $array);
    }

    function build_article_view($nama_controller, $nama_project, $array = array()) {
        $nama_project = strtolower($nama_project);
        $a = array();
        $b = array();
        foreach ($array as $key => $value) {
            $a[] = $key;
            $b[] = $value;
        }
        $get = file_get_contents('application/project/views/article/view_confirm.php');
        $new1 = str_replace($a, $b, $get);
        $get = file_get_contents('application/project/views/article/view_data.php');
        $new2 = str_replace($a, $b, $get);
        $get = file_get_contents('application/project/views/article/view_tambah.php');
        $new3 = str_replace($a, $b, $get);
        $get = file_get_contents('application/project/views/article/view_ubah.php');
        $new4 = str_replace($a, $b, $get);
        $get = file_get_contents('application/project/views/article/view_read.php');
        $new5 = str_replace($a, $b, $get);

        $data = load_file('project/' . $nama_project);
        if (!in_array('views', $data))
            mkdir('project/' . $nama_project . '/views');

        $data = load_file('project/' . $nama_project . '/views');
        if (!in_array($nama_controller, $data))
            mkdir('project/' . $nama_project . '/views/' . $nama_controller);

        if (!file_exists('project/' . $nama_project . '/views/' . $nama_controller . '/view_confirm.php'))
            file_put_contents('project/' . $nama_project . '/views/' . $nama_controller . '/view_confirm.php', $new1);
        if (!file_exists('project/' . $nama_project . '/views/' . $nama_controller . '/view_data.php'))
            file_put_contents('project/' . $nama_project . '/views/' . $nama_controller . '/view_data.php', $new2);
        if (!file_exists('project/' . $nama_project . '/views/' . $nama_controller . '/view_tambah.php'))
            file_put_contents('project/' . $nama_project . '/views/' . $nama_controller . '/view_tambah.php', $new3);
        if (!file_exists('project/' . $nama_project . '/views/' . $nama_controller . '/view_ubah.php'))
            file_put_contents('project/' . $nama_project . '/views/' . $nama_controller . '/view_ubah.php', $new4);
        if (!file_exists('project/' . $nama_project . '/views/' . $nama_controller . '/view_read.php'))
            file_put_contents('project/' . $nama_project . '/views/' . $nama_controller . '/view_read.php', $new5);
    }

    function build_page_static_controller($nama_project, $array = array()) {
        $nama_project = strtolower($nama_project);
        $a = array();
        $b = array();
        foreach ($array as $key => $value) {
            $a[] = $key;
            $b[] = $value;
        }
        $get = file_get_contents('application/project/controllers/page_static_controller.php');
        $new = str_replace($a, $b, $get);

        $data = load_file('project');
        if (!in_array($nama_project, $data))
            mkdir('project/' . $nama_project);

        $data = load_file('project/' . $nama_project);
        if (!in_array('controllers', $data))
            mkdir('project/' . $nama_project . '/controllers');

        if (!file_exists('project/' . $nama_project . '/controllers/' . $array['__controller'] . '.php'))
            file_put_contents('project/' . $nama_project . '/controllers/' . $array['__controller'] . '.php', $new);

        $this->build_page_static_view($array['__controller'], $nama_project, $array);
    }

    function build_page_static_view($nama_controller, $nama_project, $array = array()) {
        $nama_project = strtolower($nama_project);
        $a = array();
        $b = array();
        foreach ($array as $key => $value) {
            $a[] = $key;
            $b[] = $value;
        }
        $get = file_get_contents('application/project/views/page_static/view_page.php');
        $new1 = str_replace($a, $b, $get);

        $data = load_file('project/' . $nama_project);
        if (!in_array('views', $data))
            mkdir('project/' . $nama_project . '/views');

        $data = load_file('project/' . $nama_project . '/views');
        if (!in_array($nama_controller, $data))
            mkdir('project/' . $nama_project . '/views/' . $nama_controller);

        if (!file_exists('project/' . $nama_project . '/views/' . $nama_controller . '/view_page.php'))
            file_put_contents('project/' . $nama_project . '/views/' . $nama_controller . '/view_page.php', $new1);
    }

    function build_page_dynamic_controller($nama_project, $array = array()) {
        $nama_project = strtolower($nama_project);
        $a = array();
        $b = array();
        foreach ($array as $key => $value) {
            $a[] = $key;
            $b[] = $value;
        }
        $get = file_get_contents('application/project/controllers/page_dynamic_controller.php');
        $new = str_replace($a, $b, $get);

        $data = load_file('project');
        if (!in_array($nama_project, $data))
            mkdir('project/' . $nama_project);

        $data = load_file('project/' . $nama_project);
        if (!in_array('controllers', $data))
            mkdir('project/' . $nama_project . '/controllers');

        if (!file_exists('project/' . $nama_project . '/controllers/' . $array['__controller'] . '.php'))
            file_put_contents('project/' . $nama_project . '/controllers/' . $array['__controller'] . '.php', $new);

        $db = & load_class('DB');
        $db->exec_query("insert into pages values('" . $array['__controller'] . "','Content')");

        $this->build_page_dynamic_view($array['__controller'], $nama_project, $array);
    }

    function build_page_dynamic_view($nama_controller, $nama_project, $array = array()) {
        $nama_project = strtolower($nama_project);
        $a = array();
        $b = array();
        foreach ($array as $key => $value) {
            $a[] = $key;
            $b[] = $value;
        }
        $get = file_get_contents('application/project/views/page_dynamic/view_page.php');
        $new1 = str_replace($a, $b, $get);

        $data = load_file('project/' . $nama_project);
        if (!in_array('views', $data))
            mkdir('project/' . $nama_project . '/views');

        $data = load_file('project/' . $nama_project . '/views');
        if (!in_array($nama_controller, $data))
            mkdir('project/' . $nama_project . '/views/' . $nama_controller);

        if (!file_exists('project/' . $nama_project . '/views/' . $nama_controller . '/view_page.php'))
            file_put_contents('project/' . $nama_project . '/views/' . $nama_controller . '/view_page.php', $new1);
    }

    function build_config($nama_project, $array = array()) {
        $nama_project = strtolower($nama_project);
        $a = array();
        $b = array();
        foreach ($array as $key => $value) {
            $a[] = $key;
            $b[] = $value;
        }
        $get = file_get_contents('application/project/config/config.php');
        $new = str_replace($a, $b, $get);

        $data = load_file('project');
        if (!in_array($nama_project, $data))
            mkdir('project/' . $nama_project);

        $data = load_file('project/' . $nama_project);
        if (!in_array('config', $data))
            mkdir('project/' . $nama_project . '/config');

        if (!file_exists('project/' . $nama_project . '/config/config.php'))
            file_put_contents('project/' . $nama_project . '/config/config.php', $new);
    }

    function build_model($nama_project, $array = array()) {
        $nama_project = strtolower($nama_project);
        $a = array();
        $b = array();
        foreach ($array as $key => $value) {
            $a[] = $key;
            $b[] = $value;
        }
        $get = file_get_contents('application/project/models/my_model.php');
        $new = str_replace($a, $b, $get);

        $data = load_file('project');
        if (!in_array($nama_project, $data))
            mkdir('project/' . $nama_project);

        $data = load_file('project/' . $nama_project);
        if (!in_array('models', $data))
            mkdir('project/' . $nama_project . '/models');

        if (!file_exists('project/' . $nama_project . '/models/my_model.php'))
            file_put_contents('project/' . $nama_project . '/models/my_model.php', $new);
    }

}
