<?php  if ( ! defined('INDEX')) exit('No direct script access allowed');

class Backup_restore extends Main {

    function __construct() {
        $this->auth->permission();
        $this->load->data('tab', 'backup_restore');
		ini_set('max_execution_time',0);
    }

    function index() {
		$this->load->data('header_text','Backup and Restore Database');
		$data = load_file('application/file-backup');
		
		foreach($data as $value){
			$exts = explode('.', $value);
			$ext = $exts[count($exts) - 1];
			if($ext=="sql") $backup[] = $value;
		}
		
        $this->load->data('file_backup', $backup);
		$this->load->data('tabel',$this->db->get_table());
        $this->load->app_view('header-footer/app_header');
        $this->load->app_view('app/backup-restore/view_backup_restore');
        $this->load->app_view('header-footer/footer');
    }
	
	function confirm(){
		$this->load->data('id',segment(4));
		$this->load->app_view('app/backup-restore/view_confirm_delete');
	}

    function backup() {
		$tables = $_POST['tables'];
        if (isset($_POST['submit']) && (count($tables)>0)) {
			echo 'Please wait, backup in proccess..';
            $return = '';
            foreach ($tables as $table) {
                $return.= 'DROP TABLE IF EXISTS ' . $table . ';';

                $this->db->query('SHOW CREATE TABLE ' . $table);
                $create_table = $this->db->get_data();

                $this->db->query('select * from ' . $table);
                $data = $this->db->get_data();
                $jumlah_data = $this->db->total_rows();

                $ha = -1;
                foreach ($create_table[0] as $key => $val) {
                    $ha++;
                    if ($ha == 1)
                        $return.= "\n" . $create_table[0][$key] . ";\n";
                }
				
				if(count($data)>0){
					foreach ($data as $row) {
						$return.= 'INSERT INTO ' . $table . ' VALUES(';
						$field = $this->db->get_field();
	
						$h = 0;
						foreach ($field as $value) {
							$h++;
							$return.= '"' . str_replace('"','\"',$row[$value]) . '"';
							if ($h < count($field))
								$return.=",";
						}
						$return.= ");\n";
					}
				}
                $return.="\n";
            }
			
            $file = ucfirst(database) . '_' . date('d-m-Y_H.i.s') . '.sql';
			if($_POST['filename']!="") $file = $_POST['filename']. '.sql';
            file_put_contents('application/file-backup/' . $file, $return);
        }
        redirect('backup_restore', false);
    }

    function restore() {
        $filename = 'application/file-backup/' . segment(4) . '';

        // Temporary variable, used to store current query
        $templine = '';
        // Read in entire file
        $lines = file($filename);
        // Loop through each line
        foreach ($lines as $line) {
            // Skip it if it's a comment
            if (substr($line, 0, 2) == '--' || $line == '')
                continue;

            // Add this line to the current segment
            $templine .= $line;
            // If it has a semicolon at the end, it's the end of the query
            if (substr(trim($line), -1, 1) == ';') {
                // Perform the query
                $this->db->exec_query($templine);
                // Reset temp variable to empty
                $templine = '';
            }
        }

        redirect('backup_restore', false);
    }

    function remove() {
        $file = segment(4);
        unlink('application/file-backup/' . $file);
        redirect('backup_restore', false);
    }

    function download() {
        $file = segment(4);
        $content = file_get_contents('application/file-backup/' . $file);
        Header('Content-Type: application/force-download');
        Header('Content-Length: ' . strlen($content));
        Header('Content-disposition: attachment; filename=' . $file);
        echo $content;
    }

}

?>