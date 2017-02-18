<?php
function get_all($table){
	$m = load_class('DB');
    $m->query("select * from $table");
    $data = $m->get_data();
    return $data;
}

function get_rows_by($field, $tabel, $where){
	$m = load_class('DB');
    $m->query("select $field from $tabel where $where ");
    $data = $m->get_data();
	return $data;
}


function get_field_by($field, $tabel, $where) {
    $m = load_class('DB');
    $m->query("select $field from $tabel where $where ");
    $data = $m->get_data();
    if (count($data) == 0)
        return ' - ';
    else
        return $data[0][$field];
}

?>