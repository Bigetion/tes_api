<?php

class Image {

    function image_mark($upload_name, $img_path, $upload, $key) {
        //$image_show = "";   // watermark image
        //$image_path = $image_show; 
        $path = $img_path;   // folder upload gambar setelah proses watermark
        $oldimage_name = $path . str_replace(" ","_",$_FILES[$upload_name]['name'][$key]);
        $new_image_name = $upload;

        list($owidth, $oheight) = getimagesize($oldimage_name);
        $width = 200;
        $height = ($width / $owidth) * $oheight;    // tentukan ukuran gambar akhir, contoh: 300 x 300
        $im = imagecreatetruecolor($width, $height);
        $img_src = imagecreatefromjpeg($oldimage_name);
        imagecopyresampled($im, $img_src, 0, 0, 0, 0, $width, $height, $owidth, $oheight);
        //$watermark = imagecreatefrompng($image_path);
        //list($w_width, $w_height) = getimagesize($image_path);        
        $pos_x = $width - $w_width;
        $pos_y = $height - $w_height;
        //imagecopy($im, $watermark, $pos_x, $pos_y, 0, 0, $w_width, $w_height);
        imagejpeg($im, $new_image_name, 200);
        imagedestroy($im);
        return $new_image_name;
    }
	
	function image_mark_wh($upload_name, $img_path, $upload, $key, $thumb_width = 200, $thumb_height = 0) {
        //$image_show = "";   // watermark image
        //$image_path = $image_show; 
        $path = $img_path;   // folder upload gambar setelah proses watermark
        $oldimage_name = $path . str_replace(" ","_",$_FILES[$upload_name]['name'][$key]);
        $new_image_name = $upload;

        list($owidth, $oheight) = getimagesize($oldimage_name);
        $width = $thumb_width;
		if($thumb_height > 0) $height = $thumb_height;
		else $height = ($width / $owidth) * $oheight;    // tentukan ukuran gambar akhir, contoh: 300 x 300
        $im = imagecreatetruecolor($width, $height);
        $img_src = imagecreatefromjpeg($oldimage_name);
        imagecopyresampled($im, $img_src, 0, 0, 0, 0, $width, $height, $owidth, $oheight);
        //$watermark = imagecreatefrompng($image_path);
        //list($w_width, $w_height) = getimagesize($image_path);        
        $pos_x = $width - $w_width;
        $pos_y = $height - $w_height;
        //imagecopy($im, $watermark, $pos_x, $pos_y, 0, 0, $w_width, $w_height);
        imagejpeg($im, $new_image_name, 200);
        imagedestroy($im);
        return $new_image_name;
    }
	
	function upload_thumb($nama_upload_box, $path_upload , $config = array()) {
		
		if(!isset($config['thumb_width'])) $config['thumb_width'] = '200';
		if(!isset($config['thumb_height'])) $config['thumb_height'] = '0';
		if(!isset($config['thumb_before'])) $config['thumb_before'] = 'small_';
		
        foreach ($_FILES[$nama_upload_box]['tmp_name'] as $key => $tmp_name) {
            $fileName = str_replace(" ","_",$_FILES[$nama_upload_box]['name'][$key]);
            $fileSize = $_FILES[$nama_upload_box]['size'][$key];
            $fileError = $_FILES[$nama_upload_box]['error'][$key];
            $success = true;
			
            if ($fileSize > 0 || $fileError == 0) {
                $path = $path_upload;
                $new = $path . $config['thumb_before'] . $fileName;
				
				$upload_status = move_uploaded_file($_FILES[$nama_upload_box]['tmp_name'][$key], $path . str_replace(" ","_",$_FILES[$nama_upload_box]['name'][$key]));
				if($upload_status){
					$this->image_mark_wh($nama_upload_box, $path_upload, $new, $key, $config['thumb_width'], $config['thumb_height']);
				}
                //$success = true;
            }
            return $success;
        }
    }
	
	function single_image_mark($upload_name, $img_path,$new_image_name, $upload, $key) {
        //$image_show = "";   // watermark image
        //$image_path = $image_show; 
        $path = $img_path;   // folder upload gambar setelah proses watermark
        $upload_status = move_uploaded_file($_FILES[$upload_name]['tmp_name'][$key], $path .$new_image_name);
        $oldimage_name = $path . str_replace(" ","_",$new_image_name);
        $new_image_name = $upload;

        list($owidth, $oheight) = getimagesize($oldimage_name);
        $width = 80;
        $height = ($width / $owidth) * $oheight;    // tentukan ukuran gambar akhir, contoh: 300 x 300
        $im = imagecreatetruecolor($width, $height);
        $img_src = imagecreatefromjpeg($oldimage_name);
        imagecopyresampled($im, $img_src, 0, 0, 0, 0, $width, $height, $owidth, $oheight);
        //$watermark = imagecreatefrompng($image_path);
        //list($w_width, $w_height) = getimagesize($image_path);        
        $pos_x = $width - $w_width;
        $pos_y = $height - $w_height;
        //imagecopy($im, $watermark, $pos_x, $pos_y, 0, 0, $w_width, $w_height);
        imagejpeg($im, $new_image_name, 200);
        imagedestroy($im);
        return $new_image_name;
    }

    function upload($nama_upload_box, $path_upload , $mark_image = true) {
        foreach ($_FILES[$nama_upload_box]['tmp_name'] as $key => $tmp_name) {
            $fileName = str_replace(" ","_",$_FILES[$nama_upload_box]['name'][$key]);
            $fileSize = $_FILES[$nama_upload_box]['size'][$key];
            $fileError = $_FILES[$nama_upload_box]['error'][$key];
            $success = true;
			
            if ($fileSize > 0 || $fileError == 0) {
                $path = $path_upload;
                $new = $path . 'small_' . $fileName;
				
				$upload_status = move_uploaded_file($_FILES[$nama_upload_box]['tmp_name'][$key], $path . str_replace(" ","_",$_FILES[$nama_upload_box]['name'][$key]));
				if($upload_status){
					if($mark_image) $this->image_mark($nama_upload_box, $path_upload, $new, $key);
				}
                //$success = true;
            }
            return $success;
        }
    }
	
	function single_upload($nama_upload_box, $path_upload, $new_name) {
        foreach ($_FILES[$nama_upload_box]['tmp_name'] as $key => $tmp_name) {
            $fileName = $new_name;
            $fileSize = $_FILES[$nama_upload_box]['size'][$key];
            $fileError = $_FILES[$nama_upload_box]['error'][$key];
            $success = true;
			
            if ($fileSize > 0 || $fileError == 0) {
                $path = $path_upload;
                $new = $path . 'small_' . $new_name;
                $this->single_image_mark($nama_upload_box, $path_upload, $new_name, $new, $key);
                //$success = true;
            }
            return $success;
        }
    }
	
	function make_thumbnail($config = array('path_source'=>'','path_target'=>'','image_name'=>'','image_width'=>'128','image_height'=>'0','thumb_before'=>'small_','unlink_original'=>false))
	{
		$path_source = $config['path_source']; $image_path = $path_source;
		$path_target = $config['path_target'];
		if($path_target=='') $path_target = $path_source;
		$img = $config['image_name'];
		$img_width = $config['image_width'];
		$img_height = $config['image_height'];
		$thumb_beforeword = $config['thumb_before'];
		$unlink_original = $config['unlink_original'];
		
		$arr_image_details = getimagesize("$image_path/$img"); 
		$original_width = $arr_image_details[0];
		$original_height = $arr_image_details[1];
		
		$thumbnail_width = $img_width;
		if($img_height==0) $thumbnail_height = ($thumbnail_width / $original_width) * $original_height;
		else $thumbnail_height = $img_height;
		
		
		if ($original_width > $original_height) {
			$new_width = $thumbnail_width;
			$new_height = intval($original_height * $new_width / $original_width);
		} else {
			$new_height = $thumbnail_height;
			$new_width = intval($original_width * $new_height / $original_height);
		}
		$dest_x = intval(($thumbnail_width - $new_width) / 2);
		$dest_y = intval(($thumbnail_height - $new_height) / 2);
		if ($arr_image_details[2] == 1) {
			$imgt = "ImageGIF";
			$imgcreatefrom = "ImageCreateFromGIF";
		}
		if ($arr_image_details[2] == 2) {
			$imgt = "ImageJPEG";
			$imgcreatefrom = "ImageCreateFromJPEG";
		}
		if ($arr_image_details[2] == 3) {
			$imgt = "ImagePNG";
			$imgcreatefrom = "ImageCreateFromPNG";
		}
		if ($imgt) {
			$old_image = $imgcreatefrom("$image_path/$img");
			$new_image = imagecreatetruecolor($thumbnail_width, $thumbnail_height);
			imagecopyresized($new_image, $old_image, $dest_x, $dest_y, 0, 0, $new_width, $new_height, $original_width, $original_height);
			//if($unlink_original) unlink($image_path."/".$img);
			$imgt($new_image, "$path_target/$thumb_beforeword"."$img");
		}
	}	
}

?>