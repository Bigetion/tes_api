<?php 
function dimension_max($width, $height, $lokasi_file){
	   $hasil = true;
       $lebar_max       = $width;
       $tinggi_max      = $height;
       $lokasi_gambar   = $lokasi_file;
       $hasil           = 0;
        
       $ukuran_asli = GetImageSize($lokasi_gambar);
        
       if ( $ukuran_asli[0] > $lebar_max && $ukuran_asli[1] > $tinggi_max ) {
         $hasil = false;
       }
        
       return $hasil;
  }

function dimension_min($width, $height, $lokasi_file){
		$hasil = true;
       $lebar_min       = $width;
       $tinggi_min      = $height;
       $lokasi_gambar   = $lokasi_file;
       $hasil           = 0;
        
       $ukuran_asli = GetImageSize($lokasi_gambar);
        
       if ( $ukuran_asli[0] < $lebar_min  && $ukuran_asli[1] < $tinggi_min ) {
          $hasil = false;
       }
        
       return $hasil;
}
?>