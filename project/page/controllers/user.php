<?php  if ( ! defined('INDEX')) exit('No direct script access allowed');
class user extends Controller {
		function getAll(){
			$data['data'] = $this->db->query("select * from users")->get_data();
			$this->render->json($data);
		}

		function getWithPage(){

		}

		function getDataLogin(){

		}

		function getProfile(){

		}
}

?>