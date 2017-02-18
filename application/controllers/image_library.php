<?php  if ( ! defined('INDEX')) exit('No direct script access allowed');

class Image_library extends Main {

    function __construct() {
        $this->auth->permission();
    }

    function albums() {
		$this->load->data('header_text','Manage Album');
        if (!in_array('images', load_file('application')))
            mkdir('application/images');
        $this->load->data('images', load_recursive('application/images', 2, array('jpg', 'jpeg', 'png')));
        $this->load->data('dir', $this->dir->get_dir('application/images'));
		
		$this->load->app_view('header-footer/app_header');
        $this->load->app_view('app/image_library/view_image_library_album');
		$this->load->app_view('header-footer/footer');
    }
	
	function confirm_album(){
		$this->load->data('id',segment(4));
		$this->load->app_view('app/image_library/view_confirm_album');
	}
	
	function images() {
		$this->load->data('header_text','Manage Image');
        if (!in_array('images', load_file('application')))
            mkdir('application/images');
        $this->load->data('images', load_recursive('application/images', 2, array('jpg', 'jpeg', 'png')));
        $this->load->data('dir', $this->dir->get_dir('application/images'));
		
		$this->load->app_view('header-footer/app_header');
        $this->load->app_view('app/image_library/view_image_library_image');
		$this->load->app_view('header-footer/footer');
    }

    function upload() {
        if($this->image->upload("picture", "application/images/")) echo '{"status":"success"}';
		else echo '{"status":"error"}';
			
		exit;		
        //$this->set->redirect("image_library/images", false);
    }
	
	function ajax_upload(){
		// A list of permitted file extensions
		$allowed = array('png', 'jpg', 'gif');
		
		if(isset($_FILES['upl']) && $_FILES['upl']['error'] == 0){
		
			$extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);
		
			if(!in_array(strtolower($extension), $allowed)){
				echo '{"status":"error"}';
				exit;
			}
		
			if(move_uploaded_file($_FILES['upl']['tmp_name'], 'application/images/'.$_FILES['upl']['name'])){
				echo '{"status":"success"}';
				exit;
			}
		}
		
		echo '{"status":"error"}';
		exit;	
	}

    function upload_from_album() {
        if (is_dir("application/images/" . $_POST['txtNamaAlbum'])) {
            $this->image->upload("picture", "application/images/" . $_POST['txtNamaAlbum'] . "/");
        }
        $this->set->redirect("image_library/open_album/" . $_POST['txtNamaAlbum'], false);
    }

    function delete_image() {
        $image_name = segment(4);
        $this->dir->delete_file("application/images/" . $image_name);
        $this->dir->delete_file("application/images/" . "small_" . $image_name);
        $this->set->redirect("image_library/images", false);
    }

    function delete_image_from() {
        $album = segment(4);
        $image_name = segment(5);
        $this->dir->delete_file("application/images/" . $album . "/" . $image_name);
        $this->dir->delete_file("application/images/" . $album . "/" . "small_" . $image_name);
        $this->set->redirect("image_library/open_album/" . $album, false);
    }

    function delete_album() {
        $folder = segment(4);
        $this->dir->remove_dir("application/images/" . $folder);
        $this->set->redirect("image_library/albums", false);
    }

    function create_album() {
        $this->dir->create_dir("application/images/" . str_replace(' ', '_', $_POST['txtalbum']));
        $this->set->redirect("image_library/albums", false);
    }

    function open_album() {
		$this->load->data('header_text','Manage Album '.segment(4));
		
        if (in_array(segment(4), load_file('application/images'))) {
            if (!in_array('images', load_file('application')))
                mkdir('application/images');
            $this->load->data('images', load_recursive('application/images/' . segment(4), 3, array('jpg', 'jpeg', 'png')));
			$this->load->app_view('header-footer/app_header');
            $this->load->app_view('app/image_library/view_image_library_album_image');
			$this->load->app_view('header-footer/footer');
        } else
            $this->set->redirect("image_library/albums", false);
    }

}

?>