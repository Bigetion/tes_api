<?php  if ( ! defined('INDEX')) exit('No direct script access allowed');

class Post_categories extends Main {
	
	function __construct() {
        $this->auth->permission();
        $this->load->data('tab', "post");
    }
	
	private function count_post(){
		$category = $this->db->query("select term_id from post_term_taxonomy where taxonomy='category'")->fetch();
		if(count($category)>0){
			foreach($category as $row){
				$data = $this->db->query("select id_category from posts where id_category like '%,".$row["term_id"].",%'")->fetch();	
				//echo $row["term_id"]." = ".count($data)."<br />";	
				$c = count($data);
				//$this->db->where("term_id",$row["term_id"])->update("post_term_taxonomy",$term);	
				$this->db->exec_query("update post_term_taxonomy set count=$c where term_id='".$row["term_id"]."'");
			}
		}
	}
	
    function index() {
		$this->load->data('header_text','Manage Categories');
        $this->load->data('title', 'Categories');
        $this->count_post();
		$this->db->query("select * from post_terms,post_term_taxonomy where post_terms.term_id = post_term_taxonomy.term_id and post_term_taxonomy.taxonomy = 'category' order by post_term_taxonomy.count desc ");
        $this->load->data('data', $this->db->get_data());

        $this->load->app_view('header-footer/app_header');
		$this->load->app_view('app/categories/view_data');
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
				$this->set->data["slug"] = sanitize_title_with_dashes($_POST["name"]);

                $this->db->insert('post_terms', $this->set->data);
				
				$tbl_max = $this->db->query("select max(term_id) as max from post_terms")->get_data();
				$id = $tbl_max[0]['max'];	
							
				$data["term_id"] = $id;
				$data["taxonomy"] = "category";
				$data["description"] = "-";
				$this->db->insert('post_term_taxonomy', $data);
				
                $this->set->redirect('post_categories',false);
            }
        }
    }

    function update() {
        $id = segment(4);
        $this->load->data('title', 'Update Categories');
		$this->load->data('header_text','Update Post Categories');
		
        $this->db->query("select * from post_terms where term_id='$id'");
        $this->load->data('data', $this->db->get_data());
        $this->load->data('segment', $id);

        $this->load->app_view('header-footer/app_header');
        $this->load->app_view('app/categories/view_ubah');
        $this->load->app_view('header-footer/footer');

        if (isset($_POST['submit'])) {
            $this->validation();
            if (isset($this->set->data['error'])) {
                $this->set->render_error();
            } else {
                $id = segment(4);
				$this->set->post("name");
				$this->set->data["slug"] = sanitize_title_with_dashes($_POST["name"]);

                $this->db->where("term_id", $id)->update('post_terms', $this->set->data);
                $this->set->redirect('post_categories',false);
            }
        }
    }

    function confirm() {
        $this->load->data('title', 'Konfirmasi Hapus Categories');
        $this->load->data('id', segment(4));
        $this->load->app_view('app/categories/view_confirm');
    }

    function delete() {
        $id = segment(4);
        $this->db->delete_data('post_terms', "where term_id ='$id'");
        redirect('post_categories',false);
    }

    function page() {
        $this->index();
    }

}

?>