<?php  if ( ! defined('INDEX')) exit('No direct script access allowed');

class Post_tags extends Main {
	
	function __construct() {
        $this->auth->permission();
        $this->load->data('tab', "post");
    }
	
    function index() {
		$this->load->data('header_text','Manage Tags');
        $this->load->data('title', 'Tags');
        
		$this->db->query("select * from post_terms,post_term_taxonomy where post_terms.term_id = post_term_taxonomy.term_id and post_term_taxonomy.taxonomy = 'post_tag' ");
        $this->load->data('data', $this->db->get_data());

        $this->load->app_view('header-footer/app_header');
		$this->load->app_view('app/tags/view_data');
		$this->load->app_view('header-footer/footer');
    }

    function validation() {
        $this->set->validation("name","required");
    }

    function create() {
        if (isset($_POST['submit'])) {
            $this->validation();
            if (isset($this->set->data['error'])) {
                $this->set->render_error();
            } else {
				$this->set->post("name");
				$this->set->data["slug"] = $_POST["name"];

                $this->db->insert('post_terms', $this->set->data);
				
				$tbl_max = $this->db->query("select max(term_id) as max from post_terms")->get_data();
				$id = $tbl_max[0]['max'];	
				
				$data["term_id"] = $id;
				$data["taxonomy"] = "post_tag";
				$data["description"] = "-";
				$this->db->insert('post_term_taxonomy', $data);
				
                $this->set->redirect('post_tags',false);
            }
        }
    }

    function update() {
        $id = segment(4);
        $this->load->data('title', 'Update Tags');
		$this->load->data('header_text','Update Post Tags');
		
        $this->db->query("select * from post_terms where term_id='$id'");
        $this->load->data('data', $this->db->get_data());
        $this->load->data('segment', $id);

        $this->load->app_view('header-footer/app_header');
        $this->load->app_view('app/tags/view_ubah');
        $this->load->app_view('header-footer/footer');

        if (isset($_POST['submit'])) {
            $this->validation();
            if (isset($this->set->data['error'])) {
                $this->set->render_error();
            } else {
                $id = segment(4);
				
				$this->set->post("name");

                $this->db->where("term_id", $id)->update('post_terms', $this->set->data);
                $this->set->redirect('post_tags',false);
            }
        }
    }

    function confirm() {
        $this->load->data('title', 'Konfirmasi Hapus Categories');
        $this->load->data('id', segment(4));
        $this->load->app_view('app/tags/view_confirm');
    }

    function delete() {
        $id = segment(4);
        $this->db->delete_data('post_terms', "where term_id ='$id'");
        redirect('post_tags',false);
    }

    function page() {
        $this->index();
    }

}

?>