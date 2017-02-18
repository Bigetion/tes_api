<?php  if ( ! defined('INDEX')) exit('No direct script access allowed');

class App extends Main {

    function __construct() {
        $this->auth->permission();
    }

    function getModules() {		
        $a = load_file('project');

        foreach ($a as $value) {
            if ($value != '.' && $value != '..') {
                $data['project'][] = $value;
                $b = load_file('project/' . $value);
                if (in_array('controllers', $b)) {
                    $b = load_recursive('project/' . $value . '/controllers');
                    foreach ($b as $value2) {
                        $data[$value][] = substr(basename($value2), 0, -4);
                    }
                } else
                    $data[$value][] = ' -----  ';
            }
        }
        if (empty($data))
            $data[] = '-----';

        $this->render->json($data);
    }

    function getRoleList(){
        $data['data'] = $this->db->query("select * from roles")->get_data();
        $this->render->json($data);
    }

    function getUserList(){
        $data['data'] = $this->db->query("SELECT users.id_role,users.username, roles.role_name FROM users LEFT JOIN roles ON users.id_role=roles.id_role")->get_data();
        $this->render->json($data);
    }

    function getPermissions() {
        $data['data'] = $this->db->query("select * from roles")->get_data();
        $a = load_file('project');
		if(count($a)>0){
        foreach ($a as $value) {
            if ($value != '.' && $value != '..') {
                $data['project'][] = $value;
                $b = load_file('project/' . $value);
                if (in_array('controllers', $b)) {
                    $b = load_recursive('project/' . $value . '/controllers');
					if(count($b)>0){ 
                        foreach ($b as $value2) {
                            require($value2);
                            $c = get_class_methods(substr(basename($value2), 0, -4));
                            if(count($c)>0){ 
                                foreach ($c as $value3) {
                                    if ($value3 != '__construct' && $value3 != '__get') $data['function'][substr(basename($value2), 0, -4)][] = $value3;
                                }
                            }
                            $data['controller'][$value][] = substr(basename($value2), 0, -4);
                        }
                    }
                } else $data['controller'][$value][] = ' ----- ';
            }
        }}
        if (empty($data)) $data[] = '-----';
        $this->render->json($data);
    }

    function updatePermissions(){
        $this->render->json_post();
        $permissions = $_POST['permissions'];
        $data['data'] = '';
        foreach ($permissions as $key => $val) {
            if($key=='1') $this->db->exec_query('update roles set permission=\'\' where id_role=' . $key);
            else $this->db->exec_query('update roles set permission=\'' .$val. '\' where id_role=' . $key);
        }
    }

}    
?>