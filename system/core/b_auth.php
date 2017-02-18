<?php  if ( ! defined('INDEX')) exit('No direct script access allowed');

class Auth {
    function permission() {
        $crypt = & load_class('Crypt');
        if ($_SESSION[base_url.'login']!="aktif" || id_role!=1) {
            show_error('Authentication', 'Please login first to access this page');
        }
    }
}

?>