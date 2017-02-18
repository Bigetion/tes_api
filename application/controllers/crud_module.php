<?php  if ( ! defined('INDEX')) exit('No direct script access allowed');

class Crud_module extends Main {

    function __construct() {
        $this->auth->permission();
    }

    function index() {
		$this->load->data('header_text','Build CRUD Module');
        $this->load->data('project', load_file('project'));
        $this->load->data('nama_tabel', $this->db->get_table());
        $this->load->app_view('header-footer/app_header');
		if(segment(4)=="") $this->load->app_view('app/crud_module/view_build');
		else{
			$a = load_file('project');
			if(in_array(segment(4),$a) && count($a)>2){
				$this->load->app_view('app/crud_module/view_build2');
			}
			else{
				$this->load->app_view('app/crud_module/view_build');	
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
        $this->set->validation("tabel", "required");
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

    function form_valid() {
        $this->load->data('project', segment(4));
        $this->load->data('modul', segment(5));
        $this->load->data('tabel', segment(6));
        $this->load->data('title', urldecode(segment(7)));
        $tabel = segment(6);
        $this->load->data('field', $this->db->query("select * from $tabel")->get_field());

        $this->load->app_view('app/crud_module/view_form_valid');
    }

    function create() {
        $this->validation();
        if (isset($this->set->data['error'])) {
            $this->set->render_error();
        } else {
            if (!empty($_POST['exproject']))
                $_POST['project'] = $_POST['exproject'];
            $project = $_POST['project'];
            $modul = $_POST['modul'];
            $tabel = $_POST['tabel'];
            $title = urlencode($_POST['title']);

            $this->set->show_page('#form_valid', base_url . 'crud_module/form_valid/' . $project . '/' . $modul . '/' . $tabel . '/' . $title);
        }
    }

    function build() {
        $field = array();
        $valid = '';
        foreach ($_POST as $key => $value) {
            if ($key !== 'project' && $key !== 'modul' && $key !== 'tabel' && $key !== 'primary_key' && $key !== 'title') {
                if (is_array($_POST[$key])) {
                    foreach ($_POST[$key] as $val) {
                        $valid .= $val . '|';
                    }
                    $field[$key] = substr($valid, 0, -1);
                    $valid = '';
                } else
                    $field[$key] = $value;
            }
        }

        $project = strtolower($_POST['project']);
        $modul = strtolower($_POST['modul']);
        $title = $_POST['title'];
        $tabel = $_POST['tabel'];
        $primary_key = $_POST['primary_key'];

        // --------------------------------------------------------
        $config['__controller'] = $modul;
        $config['__title'] = str_replace("'","\'",$title);
        $config['__nama_tabel'] = $tabel;
        $config['__primary_key'] = $primary_key;

        $config['__main_controller'] = 'home';
        $config['__default_method'] = 'index';

        $config['__query_search'] = a_query_search($field);
        $config['__validation'] = a_validation($field);
        $config['__post'] = a_post($field);
        $config['__tr_tabel_ubah'] = a_tr_tabel_ubah($field);
        $config['__tr_tabel_tambah'] = a_tr_tabel_tambah($field);
        $config['__tr_field'] = a_tr_field($field);
        $config['__tr_row'] = a_tr_row($field);
        // -----------------------------------------------------------------------------------------
        // -----------------------------------------------------------
        if (!empty($_POST['exproject']))
            $project = strtolower($_POST['exproject']);

        $this->module->build_crud_controller($project, $config);
        $this->module->build_config($project, $config);
        //$this->module->build_model($project,$config);
        // -------------------------------------------------------------

        redirect($project . '/' . $modul, false);
    }

}

?>