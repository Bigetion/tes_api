<?php if (!defined('INDEX')) exit('No direct script access allowed');

class Project {
    var $project = '';
    var $controller = '';
    var $method = '';
	
	var $is_project = true;

    var $jwt_payload = false;
	
	function __construct(){		
		$auto = & load_class('Autotable');
		$auto->set_autotable();
	}

    function is_exist_project($project) {
        if (!in_array($project, load_file('project')))
            return false;
        else
            return true;
    }

    function is_exist_app_controller($controller) {
        if (!file_exists('application/controllers/' . $controller . '.php'))
            return false;
        else
            return true;
    }

    function is_exist_project_controller($project, $controller) {
        if (!file_exists('project/' . $project . '/controllers/' . $controller . '.php'))
            return false;
        else
            return true;
    }

    function set_project($project) {
        $this->project = $project;
        return $this;
    }

    function set_controller($controller) {
        $this->controller = $controller;
        return $this;
    }

    function set_method($method) {
        $this->method = $method;
        return $this;
    }

    function set_user() {
        $db = & load_class('DB');
        $render = & load_class('Render');

        if(get_header('Authorization')){
            $authorization_header = get_header('Authorization');
            $bearer_header_list = explode('Bearer',get_header('Authorization'));
            $bearer_pos = stripos($authorization_header, 'bearer ');
            if ($authorization_header !== false && ($bearer_pos !== false)) {
                $jwt = & load_class('JWT');
                $this->jwt_payload = $jwt->decode(Bearer, base64_decode(secret_key));
                    
                $payload = json_decode(json_encode($this->jwt_payload), true);;
                $username = $payload['data']['user'];

                $header_origin_payload = $payload['iss'];
                $header_origin = get_header('origin');

                if($header_origin_payload == $header_origin){
                    if (!defined('app_username'))
                        define('app_username', $username);
                                
                    $data = $db->query("select * from users where username = '$username'")->fetchAll();
                    $id_role = $data[0]['id_role'];
                    $id_user = $data[0]['id_user'];
                                
                    if (!defined('id_role')) define('id_role', $id_role);
                    if (!defined('id_user')) define('id_user', $id_user);

                    $data2 = $db->query("select * from roles where id_role = '$id_role'")->fetchAll();
                    if (!defined('app_rolename'))
                        define('app_rolename', $data2[0]["role_name"]);
                }else{
                    show_error('Permission','Origin unauthorized');
                }
            }else{
                show_error('Authentication','Bearer undefined');
            }
        }else{
            if (!defined('id_role')) define('id_role', 2);
        }
    }

    function check_permission() {        
        $db = & load_class('DB');
        $permission = $this->project . '.' . $this->controller . '.' . $this->method;
        $data = $db->query("select * from roles where id_role='" . id_role . "'")->fetchAll();
        $permission_list = $data[0]['permission'];
        if (id_role != 1) {
            if (!in_array($permission, explode('---', $permission_list))) {
                show_error('Permission', 'You dont have permission to access this page');
            }
        }
    }

    function render() {
		// Jika meload base url saja 
        if (empty($this->project) && empty($this->controller)) {
            $this->set_user();

            if (!$this->is_exist_project(default_project)) 
				show_error('Page not found', 'Project ' . default_project . ' was not found');
				
		    if ($this->is_exist_project_controller(default_project,default_project_controller))
				require_once('project/' . default_project . '/controllers/' . default_project_controller . '.php');
			else
				show_error('Page not found','Main project controller ' . default_project_controller . ' was not found');
				
			$this->project = default_project;
            $this->controller = default_project_controller;
			$this->method = default_project_method;
        }
		else{							
			if($this->is_exist_app_controller($this->project)){
				$this->is_project = false;
				require_once('application/controllers/' . $this->project . '.php');
				
				if(empty($this->controller) && empty($this->method)){
					$this->controller = $this->project;
					$this->method = default_app_method;
				}else{
					$this->method = $this->controller;
					$this->controller = $this->project;
				}
			}elseif($this->is_exist_project($this->project)){
                $this->set_user();

				if ($this->is_exist_project_controller(default_project,default_project_controller))
					require_once('project/' . default_project . '/controllers/' . default_project_controller . '.php');
				else
					show_error('Page not found','Main project controller ' . default_project_controller . ' was not found');
							
				foreach (load_recursive('project/' . $this->project . '/config') as $value) {
					$project_config = include($value);
                    foreach ($project_config as $key => $value) {
                        define($key, $value);
                    }
				}		
				
				if(empty($this->controller) && empty($this->method)){
                    if(defined('main_controller')){
                        $this->controller = main_controller;
                        if(defined('default_method')) $this->method = default_method;
                    }
				}
                else if(empty($this->method)) $this->method = "index";
				
				if($this->is_exist_project_controller($this->project,$this->controller)) {
					require_once('project/' . $this->project . '/controllers/' . $this->controller . '.php');
				}else show_error('Page not found','Controller ' . $this->controller . ' was not found');

				$this->check_permission();
			}else show_error('Page not found', 'Project '. $this->project . ' was not found');
		}
		
		define('base_url_project', base_url . $this->project . '/');
        $this->_render();
    }

    function _render() {
        $controller = $this->controller;
        $method = $this->method;
		
		$base_directory = array('application','project','system');

		if(in_array($this->controller,$base_directory)){
			show_error('Permission', 'You dont have permission to access this page');	
		}

        $Render = & load_class($controller);
        if (method_exists($controller, $method)){
			if($this->is_project) {
				if(method_exists(default_project_controller,"__global")){
					$Render->load->url(default_project."/".default_project_controller."/__global");	
				}
			}else{
				
			}			
            $Render->$method();
		}
        else show_error('Page not found','Controller '.$this->controller.' with function '. $this->method .' was not found');
    }
}

?>