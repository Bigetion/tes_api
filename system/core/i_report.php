<?php  if ( ! defined('INDEX')) exit('No direct script access allowed');

class Report {

    var $paper_size = 'A4';
    var $orientation = 'P';
    var $file_name = 'report';

    function set_paper_size($paper_size = 'A4') {
        $this->paper_size = $paper_size;
    }

    function set_orientation($orientation = 'P') {
        $this->orientation = $orientation;
    }

    function set_file_name($file_name = 'report') {
        $this->file_name = $file_name;
    }

    function fpdf($url, $data) {
        $load = & load_class('Load');
        $load->libraries('fpdf/html2fpdf');

        $pdf = new HTML2FPDF($this->orientation, 'mm', $this->paper_size);
        $pdf->AddPage();

        ob_start();
        $load->vars = $data;
        $load->view($url);
        $contents = ob_get_clean();

        $pdf->WriteHTML($content);
        if (empty($file_name))
            $pdf->Output($this->file_name . ".pdf");
        else
            $pdf->Output($this->file_name . ".pdf");
    }

    function topdf($url, $data) {
        $load = & load_class('Load');
        $load->libraries('htmltopdf/html2pdf.class');

        ob_start();
        $load->vars = $data;
        $load->view($url);

        $contents = ob_get_clean();
        try {
            $html2pdf = new HTML2PDF($this->orientation, $this->paper_size, 'fr', true, 'UTF-8', 3);
            $html2pdf->pdf->SetDisplayMode('fullpage');
            $html2pdf->writeHTML($contents);
            $html2pdf->Output($this->file_name . '.pdf');
        } catch (HTML2PDF_exception $e) {
            echo $e;
            exit;
        }
    }

}

?>