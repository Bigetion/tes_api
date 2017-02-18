<?php  if ( ! defined('INDEX')) exit('No direct script access allowed');

class role extends Main {

    function __construct() {
        $this->auth->permission();
        $this->load->data('tab', "role");
		$this->load->data("feedback",$this->feedback->get());
    }

    function index() {
		$this->load->data('header_text','Manage Data Roles');
        $this->db->query('select * from roles');
        $this->load->data('data', $this->db->get_data());
        $this->load->app_view('header-footer/app_header');
        $this->load->app_view('app/role/view_data');		
        $this->load->app_view('header-footer/footer');
    }

    function validation() {
        $this->set->validation("role_name", "required");
        $this->set->validation("description", "required");
    }

    function create() {
		$this->load->data('header_text','Create Role');
        $this->load->app_view('header-footer/app_header');
        $this->load->app_view('app/role/view_tambah');
        $this->load->app_view('header-footer/footer');

        if (isset($_POST['submit'])) {
            $this->validation();
            if (isset($this->set->data['error'])) {
                $this->set->render_error();
            } else {
                $this->set->post("id_role");
                $this->set->post("role_name");
                $this->set->post("description");
                $this->set->post("permission");

                $this->db->insert('roles', $this->set->data);				
                $this->set->redirect('role', false);
            }
        }
    }

    function update() {
        $id = segment(4);
		$this->load->data('header_text','Update Role');
        $this->db->query("select * from roles where id_role='$id'");
        $this->load->data('data', $this->db->get_data());
        $this->load->data('segment', $id);
        $this->load->app_view('header-footer/app_header');
        $this->load->app_view('app/role/view_ubah');
        $this->load->app_view('header-footer/footer');

        if (isset($_POST['submit'])) {
            $this->validation();
            if (isset($this->set->data['error'])) {
                $this->set->render_error();
            } else {
                $id = segment(4);
                $this->set->post("id_role");
                $this->set->post("role_name");
                $this->set->post("description");

                if ($id == 1 || $id == 2)
                    ;
                else
                    $this->db->where("id_role",$id)->update('roles', $this->set->data);
                $this->set->redirect('role', false);
            }
        }
    }

    function confirm() {
        $this->load->data('id', segment(4));
        $this->load->app_view('app/role/view_confirm');
    }

    function delete() {
        $id = segment(4);
        if ($id == 1 || $id == 2)
            ;
        else
            $this->db->delete_data('roles', "where id_role ='$id'");
        redirect('role', false);
    }

    function permission() {
        $data['tab'] = 'permission';
        $this->db->query("select * from roles");
        $data['data'] = $this->db->get_data();
        $data['jumlah'] = $this->db->total_rows();

        $a = load_file('project');
		if(count($a)>0){
        foreach ($a as $value) {
            if ($value != '.' && $value != '..') {
                $data['project'][] = $value;
                $b = load_file('project/' . $value);
                if (in_array('controllers', $b)) {
                    $b = load_recursive('project/' . $value . '/controllers');
					if(count($b)>0){ foreach ($b as $value2) {
                        require($value2);
                        $c = get_class_methods(substr(basename($value2), 0, -4));
						if(count($c)>0){ foreach ($c as $value3) {
                            /*if ($value3 != 'index' && $value3 != 'validation' && $value3 != 'page' && $value3 != 'confirm' && $value3 != '__construct' && $value3 != '__get')
                                $data['function'][substr(basename($value2), 0, -4)][] = $value3;

                            if ($value3 == 'index')
                                $data['function'][substr(basename($value2), 0, -4)][] = 'view';*/
							if ($value3 != '__construct' && $value3 != '__get')
                                $data['function'][substr(basename($value2), 0, -4)][] = $value3;
                        }}
                        $data['controller'][$value][] = substr(basename($value2), 0, -4);
                    }}
                } else
                    $data['controller'][$value][] = ' ----- ';
            }
        }}
        if (empty($data))
            $data[] = '-----';
        $this->load->vars = $data;
		$this->load->data('header_text','Manage Role Permissions');
        $this->load->app_view('header-footer/app_header');
        $this->load->app_view('app/role/view_permission');
        $this->load->app_view('header-footer/footer');
    }

    function update_permission() {
        if (isset($_POST['submit'])) {
            $role = $_POST['role'];
            if (isset($_POST['role'])) {
                $permission = array();
                foreach ($role as $value) {
                    $a = explode('___', $value);
                    /*$b = explode('.', $a[1]);
                    if ($b[2] == 'view')
                        $a[1] = $a[1] . '---' . $b[0] . '.' . $b[1] . '.' . 'page';
                    if ($b[2] == 'delete')
                        $a[1] = $a[1] . '---' . $b[0] . '.' . $b[1] . '.' . 'confirm';*/

                    $permission[$a[0]] .= $a[1] . '---';
                }

                foreach ($permission as $key => $val) {
                    $this->db->exec_query('update roles set permission=\'' . substr($val, 0, -3) . '\' where id_role=' . $key);
                }
            } else
                $this->db->exec_query('update roles set permission=\'\'');
        }
		
		$this->set->render_message("success","Berhasil update.");

        //redirect('role/permission', false);
    }

}

?>