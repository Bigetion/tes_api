<?php  if ( ! defined('INDEX')) exit('No direct script access allowed');

class user extends Main {

    function __construct() {
        $this->auth->permission();
        $this->load->data('tab', "user");
    }

    function index() {
		$this->load->data('header_text','Manage Data User');
        $this->db->query('select * from users');
        $this->load->data('data', $this->db->get_data());
        $this->load->app_view('header-footer/app_header');
        $this->load->app_view('app/user/view_data');
        $this->load->app_view('header-footer/footer');
    }

    function validation() {
        $this->set->validation("username", "required");
        $this->set->validation("type", "required");
    }

    function create() {
		$this->load->data('header_text','Create User');
        $this->load->app_view('header-footer/app_header');
        $this->load->app_view('app/user/view_tambah');
        $this->load->app_view('header-footer/footer');

        if (isset($_POST['submit'])) {
            $this->validation();
            if ($_POST['password'] != $_POST['repassword'])
                $this->set->data['error']['repassword'] = 'Password tidak cocok.!';

            if (isset($this->set->data['error'])) {
                $this->set->render_error();
            } else {
                $this->set->post("id_user");
                $this->set->post("username");
                $this->set->data['password'] = md5($_POST['password']);
                $this->set->post("type");

                $this->db->insert('users', $this->set->data);
                $this->set->redirect('user', false);
            }
        }
    }

    function update() {
        $id = segment(4);
		$this->load->data('header_text','Update User');
        $this->db->query("select * from users where id_user='$id'");
        $this->load->data('data', $this->db->get_data());
        $this->load->data('segment', $id);

        $this->load->app_view('header-footer/app_header');
        $this->load->app_view('app/user/view_ubah');
        $this->load->app_view('header-footer/footer');

        if (isset($_POST['submit'])) {
            $this->validation();
            if (isset($this->set->data['error'])) {
                $this->set->render_error();
            } else {
                $id = segment(4);
                $this->set->post("id_user");
                $this->set->post("username");
                $this->set->post("type");

                $this->db->where("id_user",$id)->update('users', $this->set->data);
                $this->set->redirect('user', false);
            }
        }
    }
	
	function change_password() {
        $id = segment(4);
		$this->load->data('header_text','Change Password');
        $this->db->query("select * from users where id_user='$id'");
        $this->load->data('data', $this->db->get_data());
        $this->load->data('segment', $id);

        $this->load->app_view('header-footer/app_header');
        $this->load->app_view('app/user/view_ubah_password');
        $this->load->app_view('header-footer/footer');

        if (isset($_POST['submit'])) {
            $this->set->validation("password", "required");
			$this->db->query("select * from users where id_user='$id'");
			$data = $this->db->get_data();
			$password = $data[0]['password'];
			
			$this->set->validation("passwordlama", "required");
            if ($password != md5($_POST['passwordlama']))
                $this->set->data['error']['passwordlama'] = 'Password lama salah.!';
            if ($_POST['password'] != $_POST['repassword'])
                $this->set->data['error']['repassword'] = 'Password baru tidak cocok.!';

            if (isset($this->set->data['error'])) {
                $this->set->render_error();
            } else {
                $id = segment(4);
                $this->set->data['password'] = md5($_POST['password']);

                $this->db->where("id_user",$id)->update('users', $this->set->data);
                $this->set->redirect('user', false);
            }
        }
    }

    function confirm() {
        $this->load->data('id', segment(4));
        $this->load->app_view('app/user/view_confirm');
    }

    function delete() {
        $id = segment(4);
        if ($id == 1)
            ;
        else
            $this->db->delete_data('users', "where id_user ='$id'");
        redirect('user', false);
    }

}

?>