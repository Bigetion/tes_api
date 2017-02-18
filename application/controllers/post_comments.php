<?php  if ( ! defined('INDEX')) exit('No direct script access allowed');

class Post_comments extends Main {
	
	function __construct() {
        $this->auth->permission();
        $this->load->data('tab', "post");
    }
	
    function index() {
        $this->load->data('title', 'Comments');
		$this->load->data('header_text', 'Manage Comments');
        
		$this->db->query("select * from post_comments order by comment_date desc");
        $this->load->data('data', $this->db->get_data());
            
        $this->load->app_view('header-footer/app_header');
        $this->load->app_view('app/comments/view_data');
        $this->load->app_view('header-footer/footer');
    }

    function validation() {
        $this->set->validation("comment_post_id","comment_post_id");
		$this->set->validation("comment_author","comment_author");
		$this->set->validation("comment_author_email","comment_author_email");
		$this->set->validation("comment_author_url","comment_author_url");
		$this->set->validation("comment_author_ip","comment_author_ip");
		$this->set->validation("comment_date","comment_date");
		$this->set->validation("comment_date_gmt","comment_date_gmt");
		$this->set->validation("comment_content","required");
		$this->set->validation("comment_karma","comment_karma");
		$this->set->validation("comment_approved","comment_approved");
		$this->set->validation("comment_agent","comment_agent");
		$this->set->validation("comment_type","comment_type");
		$this->set->validation("comment_parent","comment_parent");
		$this->set->validation("user_id","user_id");
    }
	
	function update_approved(){
		$id = segment(4);		
		$data = $this->db->select()->from("post_comments")->where("comment_id",$id)->fetch_first();		
		
		if($data["comment_approved"] == "1") $update["comment_approved"] = "0"; else $update["comment_approved"] = "1";
		$this->db->where("comment_id", $id)->update('post_comments', $update);
	}

    function update() {
		$this->load->data('header_text', 'Update Comments');
		
        $id = segment(4);
        $this->load->data('title', 'Update Comment');

        $this->db->query("select * from post_comments where comment_id='$id'");
        $this->load->data('data', $this->db->get_data());
        $this->load->data('segment', $id);

        $this->load->app_view('header-footer/app_header');
        $this->load->app_view('app/comments/view_ubah');
        $this->load->app_view('header-footer/footer');

        if (isset($_POST['submit'])) {
            $this->validation();
            if (isset($this->set->data['error'])) {
                $this->set->render_error();
            } else {
                $id = segment(5);
                $this->set->post("comment_id");
				$this->set->post("comment_post_id");
				$this->set->post("comment_author");
				$this->set->post("comment_author_email");
				$this->set->post("comment_author_url");
				$this->set->post("comment_author_ip");
				$this->set->post("comment_date");
				$this->set->post("comment_date_gmt");
				$this->set->post("comment_content");
				$this->set->post("comment_karma");
				$this->set->post("comment_approved");
				$this->set->post("comment_agent");
				$this->set->post("comment_type");
				$this->set->post("comment_parent");
				$this->set->post("user_id");

                $this->db->where("comment_id", $id)->update('post_comments', $this->set->data);
                $this->set->redirect('post_comments',false);
            }
        }
    }

    function confirm() {
        $this->load->data('title', 'Konfirmasi Hapus Comment');
        $this->load->data('id', segment(4));
        $this->load->app_view('app/comments/view_confirm');
    }

    function delete() {
        $id = segment(5);
        $this->db->delete_data('post_comments', "where comment_id ='$id'");
        redirect('comment');
    }
}

?>