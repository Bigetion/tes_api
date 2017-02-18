<?php 
function a_validation($field){
	$valid='';
	foreach($field as $key=>$value){
		if($value != 'primary'){
			$valid .= '$this->set->validation("'.$key.'","'.$value.'");
		';
		}
	}
	return substr($valid,0,-5);
}

function a_post($field){
	$post='';
	foreach($field as $key=>$value){
		$post .= '$this->set->post("'.$key.'");
				';
	}
	return substr($post,0,-7); 	
}

function a_tr_tabel_ubah($field){
	$tr = '';
	foreach($field as $key=>$value){
		if($value != 'primary'){
		$tr  .='
		<div class="form-group">
          <label for="'.$key.'">'.ucwords(str_replace('_',' ',$key)).'</label>
          <input type="text" class="form-control input-sm" id="'.$key.'" name="'.$key.'" value="<?php echo $row[\''.$key.'\'] ?>" >
		  <a id="error_'.$key.'" class="error"></a>
        </div>
		';
		}else $tr .= '<input type="hidden" name="'.$key.'" value="<?php echo $row[\''.$key.'\'] ?>"/>';
	}
		
	return $tr;
}

function a_tr_tabel_tambah($field){
	$tr = '';
	foreach($field as $key=>$value){
		if($value != 'primary'){
		$tr  .='
		<div class="form-group">
          <label for="'.$key.'">'.ucwords(str_replace('_',' ',$key)).'</label>
          <input type="text" class="form-control input-sm" id="'.$key.'" name="'.$key.'" >
		  <a id="error_'.$key.'" class="error"></a>
        </div>
		';
		}else $tr .= '<input type="hidden" name="'.$key.'" />';
	}
		
	return $tr;
}

function a_tr_field($field){
	$tr = '';
	foreach($field as $key=>$value){
		if($value != 'primary'){
		$tr  .='<th>'.ucwords(str_replace('_',' ',$key)).'</th>
		';
		}
	}
	return $tr;
}

function a_tr_row($field){
	$tr = '';
	foreach($field as $key=>$value){
		if($value != 'primary'){
		$tr  .='<td><?php echo $value[\''.$key.'\'] ?></td>
		';
		}
	}
	return $tr;	
}

function a_query_search($field){
	$qs = '';
	foreach($field as $key=>$value){
		$qs  .=$key.' like \'%$search%\' or ';
	}
	$qs = substr($qs,0,-3);
	return $qs;	
}

?>