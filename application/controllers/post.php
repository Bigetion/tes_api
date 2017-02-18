<?php  if ( ! defined('INDEX')) exit('No direct script access allowed');

class Post extends Main {

    function __construct() {
        $this->auth->permission();
    }
	
	function all_posts(){
		$this->load->data('header_text','Manage All Posts');
		$this->db->query("select * from posts")->order_by("post_date","desc");
        $this->load->data('data', $this->db->get_data());
		
		$this->load->data('category',$this->db->reset()->query("select * from post_terms,post_term_taxonomy where post_terms.term_id = post_term_taxonomy.term_id and post_term_taxonomy.taxonomy = 'category' ")->get_data());
			
		$this->load->app_view('header-footer/app_header');
        $this->load->app_view('app/post/view_all');
        $this->load->app_view('header-footer/footer');
	}
	
	function validation() {
        $this->set->validation("post_title", "required");
		$this->set->validation("post_content", "required");
		$this->set->validation("post_description", "required");		
    }
	
    function add() {
		$this->load->data('header_text','Add New Post');
		$this->load->data('category',$this->db->query("select * from post_terms,post_term_taxonomy where post_terms.term_id = post_term_taxonomy.term_id and post_term_taxonomy.taxonomy = 'category' ")->get_data());
		$this->load->data('tag',$this->db->query("select * from post_terms,post_term_taxonomy where post_terms.term_id = post_term_taxonomy.term_id and post_term_taxonomy.taxonomy = 'post_tag' ")->get_data());
		
        $this->load->app_view('header-footer/app_header');
        $this->load->app_view('app/post/view_add_post');
        $this->load->app_view('header-footer/footer');

        if (isset($_POST['submit'])) {
            $this->validation();
            if (isset($this->set->data['error'])) {
                $this->set->render_error();
            } else {
				if(isset($_POST['post_category'])) 
				$category = ",".implode(",",$_POST["post_category"]).",";
				else $category = "";
				
                $this->set->data["id_post"] = $id;
				$this->set->data["post_author"] = id_user;
				$this->set->data["id_category"] = $category;
				$this->set->data["post_title"] = $_POST["post_title"];
				$this->set->data["post_content"] = $_POST["post_content"];
				$this->set->data["post_description"] = $_POST["post_description"];
				$this->set->data["post_date"] = "CURRENT_TIMESTAMP";
				$this->set->data["post_date_gmt"] = date('Y-m-d H:i:s');
				$this->set->data["post_modified"] = "CURRENT_TIMESTAMP";
				$this->set->data["post_modified_gmt"] = date('Y-m-d H:i:s');
				$this->set->data["post_image_link"] = $_POST["post_image_link"];
				$this->set->data["post_title_link"] = sanitize_title_with_dashes($_POST["post_title"]);

                $this->db->insert('posts', $this->set->data);
				$this->set->redirect('post/add', false);
            }
        }
    }
	
	function update() {
		$id = segment(4);
		$this->load->data('header_text','Update Post');
		$this->load->data('segment',$id);
		
		$this->load->data('category',$this->db->query("select * from post_terms,post_term_taxonomy where post_terms.term_id = post_term_taxonomy.term_id and post_term_taxonomy.taxonomy = 'category' ")->get_data());
		$this->load->data('tag',$this->db->query("select * from post_terms,post_term_taxonomy where post_terms.term_id = post_term_taxonomy.term_id and post_term_taxonomy.taxonomy = 'post_tag' ")->get_data());
		
		
		$this->load->data('post',$this->db->query("select * from posts where id_post = '$id'")->fetch_first());
		
        $this->load->app_view('header-footer/app_header');
        $this->load->app_view('app/post/view_update_post');
        $this->load->app_view('header-footer/footer');

        if (isset($_POST['submit'])) {
            $this->validation();
            if (isset($this->set->data['error'])) {
                $this->set->render_error();
            } else {
				$id = segment(4);
				if(isset($_POST['post_category'])) 
				$category = ",".implode(",",$_POST["post_category"]).",";
				else $category = "";
				
                $this->set->data["id_post"] = $id;
				$this->set->data["post_author"] = id_user;
				$this->set->data["id_category"] = $category;
				$this->set->data["post_title"] = $_POST["post_title"];
				$this->set->data["post_content"] = $_POST["post_content"];
				$this->set->data["post_description"] = $_POST["post_description"];
				$this->set->data["post_modified"] = "CURRENT_TIMESTAMP";
				$this->set->data["post_modified_gmt"] = date('Y-m-d H:i:s');
				$this->set->data["post_image_link"] = $_POST["post_image_link"];
				$this->set->data["post_title_link"] = sanitize_title_with_dashes($_POST["post_title"]);

                $this->db->where("id_post",$id)->update('posts', $this->set->data);
				
				$this->set->render_message('#message','<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>Update success!</div>');
            }
        }
    }
	
	function confirm(){
		$this->load->data('id', segment(4));
        $this->load->app_view('app/post/view_confirm');
	}
	
	function delete() {
        $id = segment(4);
        $this->db->delete_data('posts', "where id_post ='$id'");
        $this->set->redirect('post/all_posts', false);
    }
}

?>