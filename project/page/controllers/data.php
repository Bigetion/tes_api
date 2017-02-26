<?php  if ( ! defined('INDEX')) exit('No direct script access allowed');
class data extends Controller {

    function getByOptions() {
			$table = $_GET['table'];
			$page = $_GET['page'];
			$page_limit = $_GET['page_limit'];
			$where = $_GET['where'];
			
			$column = $_GET['column'];
			$query = $_GET['query'];
			
			if(isset($query)){
				$data['data'] = $this->db->query($query)->get_data();
				$data["total_record"] = count($data['data']);
			}else{
				$w = "";
				$o = "";
				if(isset($where)) {
					$where = urldecode($where);
					$w = " where $where";	
				}		
				$total_record = $this->db->query("select count(*) as total_record from $table $w")->get_data();
				$total_record = $total_record[0]["total_record"];
				
				if(isset($_GET['page_limit'])){			
					if($page==1) $offset = 0;
					else $offset = (($page-1)*$page_limit);
					
					$data['total_page'] = ceil($total_record / $page_limit);
					
					if($total_record > $page_limit) $o = "limit $offset, $page_limit";
					else $o = "";
				}
				
				$c = "*";
				if(isset($column)) $c = $column;
				
				$data['data'] = $this->db->query("select $c from $table $w $o")->get_data();
				$data["total_record"] = $total_record + 0;
			}
			$data['query'] = $this->db->get_query_string();

			$this->render->json($data);
    }

		function getByQuery(){
			$query = $_GET['query'];
			if(isset($query)){
				$data['data'] = $this->db->query($query)->get_data();
				$data['totalRecord'] = count($data['data']);
			}else{
				$data['errorMessage'] = 'query not found';
			}
			$this->render->json($data);
		}

		function getByQueryPaging(){
			$query = $_GET['query'];
			$page = $_GET['page'];
			$page_limit = $_GET['pageLimit'];

			if(isset($query)){
				if(!isset($page)){
					$data['errorMessage'] = 'page not found';
				}else if(!isset($page_limit)){
					$data['errorMessage'] = 'page limit not found';
				}else if(!isset($page) && !isset($page_limit)){
					$data['errorMessage'] = 'page and page limit not found';
				}else{
					$data['data'] = $this->db->query($query)->get_data();
					$data['totalRecord'] = count($data['data']);

					if($page==1) $offset = 0;
					else $offset = (($page-1)*$page_limit);
					
					$data['totalPage'] = ceil($data['totalRecord'] / $page_limit);
					
					if($data['totalRecord'] > $page_limit) $o = " limit $offset, $page_limit";
					else $o = "";

					$data['data'] = $this->db->query($query . $o)->get_data();
				}
			}else{
				$data['errorMessage'] = 'query not found';
			}
			$this->render->json($data);
		}

		function getUser(){
			$data['data'] = $this->db->select("users", ["id_user","username"]);
			$this->render->json($data);		
		}
}

?>