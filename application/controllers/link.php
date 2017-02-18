<?php  if ( ! defined('INDEX')) exit('No direct script access allowed');

class link extends Main {
	
	function __construct() {
        $this->auth->permission();
    }
	
    function index() {
		$this->load->data('title', 'Link');
		$this->load->data('header_text','Manage Link');
		
        $this->db->query("select * from short_link");
        $this->load->data('data', $this->db->get_data());
			
        $this->load->app_view('header-footer/app_header');
        $this->load->app_view('app/link/view_data');
        $this->load->app_view('header-footer/footer');
    }

    function validation() {
        $this->set->validation("link","required");
		$this->set->validation("short_link","required");
    }

    function create() {
		$this->load->data('header_text','Create Link');
        $this->load->data('title', 'Tambah Link');
		
        $this->load->app_view('header-footer/app_header');
        $this->load->app_view('app/link/view_tambah');
        $this->load->app_view('header-footer/footer');

        if (isset($_POST['submit'])) {
            $this->validation();
            if (isset($this->set->data['error'])) {
                $this->set->render_error();
            } else {
                $this->set->post("link");
				$this->set->post("short_link");

                $this->db->insert('short_link', $this->set->data);
                $this->set->redirect('link',false);
            }
        }
    }

    function update() {
		$this->load->data('header_text','Update Link');
        $id = segment(4);
        $this->load->data('title', 'Update Link');

        $this->db->query("select * from short_link where id_link='$id'");
        $this->load->data('data', $this->db->get_data());
        $this->load->data('segment', $id);

        $this->load->app_view('header-footer/app_header');
        $this->load->app_view('app/link/view_ubah');
        $this->load->app_view('header-footer/footer');

        if (isset($_POST['submit'])) {
            $this->validation();
            if (isset($this->set->data['error'])) {
                $this->set->render_error();
            } else {
                $id = segment(4);
                $this->set->post("link");
				$this->set->post("short_link");

                $this->db->where("id_link", $id)->update('short_link', $this->set->data);
                $this->set->redirect('link',false);
            }
        }
    }

    function confirm() {
        $this->load->data('title', 'Konfirmasi Hapus Link');
        $this->load->data('id', segment(4));
        $this->load->view('link/view_confirm');
    }

    function delete() {
        $id = segment(4);
        $this->db->delete_data('short_link', "where id_link ='$id'");
        redirect('link',false);
    }

    function page() {
        $this->index();
    }

}

?>