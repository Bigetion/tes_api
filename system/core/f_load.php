<?php  if ( ! defined('INDEX')) exit('No direct script access allowed');

class Load {

    var $cached_vars = array();
    var $vars = array();

    function data($var_name, $value) {
        $this->vars[$var_name] = $value;
    }

    /* //a.Fungsi Untuk Load Halaman (untuk view Project)................................................
      function view($view){
      return $this->get_load(array('view' => $view, 'vars' => $this->object_to_array($this->vars)));
      } */

    //a.Fungsi Untuk Load Halaman (untuk view Project)................................................
    function view($view) {
        return $this->get_load(array('view' => $view, 'vars' => $this->object_to_array($this->vars)));
    }
	
	function url($url){
		$c = explode('/',$url);
		if(count($c)>=3){
			$project = $c[0];
			$controller = $c[1];
			$method = $c[2];
			
			if (in_array($project, load_file('project')) && require_once('project/' . $project . '/controllers/' . $controller . '.php')){
				require_once('project/' . $project . '/controllers/' . $controller . '.php');
				$Render = & load_class($controller);
				if (method_exists($controller, $method)){
					$Render->$method();
					$this->vars = $Render->load->vars;
				}
				else
					echo "Url $url not found.";
			}else
				echo "Controller $controller not found.";
		}
	}

    function get_load($data) {
        // Set the default data variables
        foreach (array('view', 'vars') as $val) {
            $$val = (!isset($data[$val])) ? FALSE : $data[$val];
        }

        if (is_array($vars)) {
            $this->cached_vars = array_merge($this->cached_vars, $vars);
        }
        extract($this->cached_vars);

        ob_start();

        //include('view/'.$view.'.php'); // include() vs include_once() allows for multiple views with the same name
        $pro = & load_class('Project');

        $a = explode('/', $view);
        if (in_array('header-footer', $a))
            include('project/' . $view . '.php');
        else include('project/'.$pro->project.'/views/'.$view.'.php');
    }
	
//a.....................................................................................................................
    //b.Fungsi Untuk Load Halaman (untuk view App Project)................................................
    function app_view($view) {
        return $this->app_get_load(array('view' => $view, 'vars' => $this->object_to_array($this->vars)));
    }

    function app_get_load($data) {
        // Set the default data variables
        foreach (array('view', 'vars') as $val) {
            $$val = (!isset($data[$val])) ? FALSE : $data[$val];
        }

        if (is_array($vars)) {
            $this->cached_vars = array_merge($this->cached_vars, $vars);
        }
        extract($this->cached_vars);

        ob_start();

        include('application/views/' . $view . '.php');
    }

//b.....................................................................................................................

    function libraries($file_name) {
        include('application/libraries/' . $file_name . '.php');
    }

    function object_to_array($object) {
        return (is_object($object)) ? get_object_vars($object) : $object;
    }

}

?>