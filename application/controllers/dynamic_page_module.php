<?php  if ( ! defined('INDEX')) exit('No direct script access allowed');

class Dynamic_page_module extends Main {

    function __construct() {
        $this->auth->permission();
    }

    function index() {
		$this->load->data('header_text','Build Dynamic Page Module');
        $this->load->data('project', load_file('project'));
        $this->load->data('nama_tabel', $this->db->get_table());
		
        $this->load->app_view('header-footer/app_header');
		if(segment(4)=="") $this->load->app_view('app/dynamic_page_module/view_build');
		else{
			$a = load_file('project');
			if(in_array(segment(4),$a) && count($a)>2){
				$this->load->app_view('app/dynamic_page_module/view_build2');
			}
			else{
				$this->load->app_view('app/dynamic_page_module/view_build');	
			}
		}
        $this->load->app_view('header-footer/footer');
    }

    function validation() {
        if (!empty($_POST['exproject']))
            $this->set->validation("exproject", "required");
        else
            $this->set->validation("project", "required");
        $this->set->validation("modul", "required");
        $this->set->validation("title", "required");
		
		if (preg_replace('/^[\p{L}_]+$/', '', $_POST['project']))
            $this->set->data['error']['project'] = 'must a valid name.!';
		if (preg_replace('/^[\p{L}_]+$/', '', $_POST['modul']))
            $this->set->data['error']['modul'] = 'must a valid name.!';
		
		if($this->controller_is_exists($_POST['modul']))
			$this->set->data['error']['modul'] = 'controller name is exists.!';
    }
	
	function controller_is_exists($controller){
		$a = load_file('project');
		$b = false;
        foreach ($a as $value) {
			if ($value != '.' && $value != '..' && $value!='header-footer') {
				$c = load_file('project/' . $value . '/controllers');
				if(in_array($controller.'.php',$c)) $b=true;
			}
		}		
		return $b;
	}

    function build() {
        $this->validation();
        if (isset($this->set->data['error'])) {
            $this->set->render_error();
        } else {
            $project = strtolower($_POST['project']);
            $modul = strtolower($_POST['modul']);

            // --------------------------------------------------------
            $config['__controller'] = $modul;
            $config['__primary_key'] = $primary_key;
            $config['__title'] = str_replace("'","\'",$_POST['title']);

            $config['__main_controller'] = 'home';
            $config['__default_method'] = 'index';

            // -----------------------------------------------------------
            if (!empty($_POST['exproject']))
                $project = strtolower($_POST['exproject']);

            $this->module->build_page_dynamic_controller($project, $config);
            $this->module->build_config($project, $config);
            // -------------------------------------------------------------

            $this->set->redirect($project . '/' . $modul, false);
        }
    }

}

?>