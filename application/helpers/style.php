<?php 

function add_app_css($nama_folder, $data) {
    foreach ($data as $value) {
        echo '<link rel="stylesheet" href="' . base_url . 'application/style/app/' . $nama_folder . '/' . $value . '.css"/>';
    }
}

function add_project_css($nama_folder, $data) {
    foreach ($data as $value) {
        echo '<link rel="stylesheet" href="' . base_url . 'application/style/project/' . $nama_folder . '/' . $value . '.css"/>';
    }
}

function add_app_js($nama_folder, $data) {
    foreach ($data as $value) {
        echo '<script src="' . base_url . 'application/style/app/' . $nama_folder . '/' . $value . '.js" charset="utf-8"></script>';
    }
}

function add_project_js($nama_folder, $data) {
    foreach ($data as $value) {
        echo '<script src="' . base_url . 'application/style/project/' . $nama_folder . '/' . $value . '.js" charset="utf-8"></script>';
    }
}

?>