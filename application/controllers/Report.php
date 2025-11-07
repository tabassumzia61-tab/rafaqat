<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
require_once APPPATH . '/vendor/autoload.php';
class Report extends Admin_Controller{

    public function __construct(){
        parent::__construct();
        $this->time               = strtotime(date('d-m-Y H:i:s'));
        $this->payment_mode       = $this->customlib->payment_mode();
        $this->search_type        = $this->customlib->get_searchtype();
        $this->sch_setting_detail = $this->setting_model->getSetting();
        $this->load->library('media_storage');
    }

    public function get_betweendate($type){
        $this->load->view('reports/betweenDate');
    } 
    
    public function pdfcustomerledgers(){
        $data = [];
        $customer_id = $this->input->get('customer_id');
        $date_from = $this->input->get('date_from');
        $date_to = $this->input->get('date_to');
        $data['settinglist'] = $this->setting_model->getSetting();
        $data['branchlist'] = $this->branchsettings_model->get();
        $salesresult            = $this->customers_model->getSalesBycustomers($customer_id,$date_from,$date_to);
        $paymentsresult         = $this->customers_model->getPaymentsBycustomers($customer_id,$date_from,$date_to);
        $data['ledgerlist']     = array_merge($salesresult, $paymentsresult);
        $html = $this->load->view('reports/pdfcustomerledgers', $data, true);
        $pdfFilePath = $this->time. ".pdf";
        $this->mpdf = new \Mpdf\Mpdf();
        $this->mpdf->AddPage('P', // L - landscape, P - portrait
            '', '', '', '',
            10, // margin_left
            10, // margin right
            10, // margin top
            10, // margin bottom
            10, // margin header
            12); // margin footer
        $this->mpdf->WriteHTML($html);
        $this->mpdf->Output($pdfFilePath, "D");
    }
    
    public function pdfquotations($quot_id,$brc_id){
        $data = [];
        $data['settinglist']    = $this->setting_model->getSetting();
        $data['branchlist']     = $this->branchsettings_model->get();
        $data['quotationsList'] = $this->quotations_model->printquotationsInsertID($quot_id);
        $html = $this->load->view('reports/pdfquotations', $data, true);
        $pdfFilePath = $this->time. ".pdf";
        $this->mpdf = new \Mpdf\Mpdf();
        $this->mpdf->AddPage('P', // L - landscape, P - portrait
            '', '', '', '',
            10, // margin_left
            10, // margin right
            10, // margin top
            10, // margin bottom
            10, // margin header
            12); // margin footer
        $this->mpdf->WriteHTML($html);
        $this->mpdf->Output($pdfFilePath, "D");//$pdfFilePath, "D"
    }
    
    public function pdfsaleinvoice($sale_id,$brc_id){
        $data = [];
        $data['settinglist'] = $this->setting_model->getSetting();
        $data['branchlist'] = $this->branchsettings_model->get();
        $data['salesList'] = $this->sales_model->printsalesInsertID($sale_id);
        $html = $this->load->view('reports/pdfsaleinvoice', $data, true);
        $pdfFilePath = $this->time. ".pdf";
        $this->mpdf = new \Mpdf\Mpdf();
        $this->mpdf->AddPage('P', // L - landscape, P - portrait
            '', '', '', '',
            10, // margin_left
            10, // margin right
            10, // margin top
            10, // margin bottom
            10, // margin header
            12); // margin footer
        $this->mpdf->WriteHTML($html);
        $this->mpdf->Output($pdfFilePath, "D");//$pdfFilePath, "D"
    }
    
    public function pdfsalereturn($sale_id,$brc_id){
        $data = [];
        $data['settinglist'] = $this->setting_model->getSetting();
        $data['branchlist'] = $this->branchsettings_model->get();
        $data['salereturnList'] = $this->salesreturn_model->printsalesreturnInsertID($sale_id);
        $html = $this->load->view('reports/pdfsalereturn', $data, true);
        $pdfFilePath = $this->time. ".pdf";
        $this->mpdf = new \Mpdf\Mpdf();
        $this->mpdf->AddPage('P', // L - landscape, P - portrait
            '', '', '', '',
            10, // margin_left
            10, // margin right
            10, // margin top
            10, // margin bottom
            10, // margin header
            12); // margin footer
        $this->mpdf->WriteHTML($html);
        $this->mpdf->Output($pdfFilePath, "D");//$pdfFilePath, "D"
    }
    
    public function pdfpurchasesbill($purchase_id,$brc_id){
        $data = [];
        $data['settinglist'] = $this->setting_model->getSetting();
        $data['branchlist'] = $this->branchsettings_model->get();
        $data['purchaseList'] = $this->purchases_model->printpurchasesInsertID($purchase_id);
        $html = $this->load->view('reports/pdfpurchasesbill', $data, true);
        $pdfFilePath = $this->time. ".pdf";
        $this->mpdf = new \Mpdf\Mpdf();
        $this->mpdf->AddPage('P', // L - landscape, P - portrait
            '', '', '', '',
            10, // margin_left
            10, // margin right
            10, // margin top
            10, // margin bottom
            10, // margin header
            12); // margin footer
        $this->mpdf->WriteHTML($html);
        $this->mpdf->Output($pdfFilePath, "D");//$pdfFilePath, "D"
    }
    
    public function pdfpurchasesreturnbill($purchase_id,$brc_id){
        $data = [];
        $data['settinglist']        = $this->setting_model->getSetting();
        $data['branchlist']         = $this->branchsettings_model->get();
        $data['purchasereturnList'] = $this->purchasesreturn_model->printpurchasesreturnInsertID($purchase_id);
        $html = $this->load->view('reports/pdfpurchasesreturnbill', $data, true);
        $pdfFilePath = $this->time. ".pdf";
        $this->mpdf = new \Mpdf\Mpdf();
        $this->mpdf->AddPage('P', // L - landscape, P - portrait
            '', '', '', '',
            10, // margin_left
            10, // margin right
            10, // margin top
            10, // margin bottom
            10, // margin header
            12); // margin footer
        $this->mpdf->WriteHTML($html);
        $this->mpdf->Output($pdfFilePath, "D");//$pdfFilePath, "D"
    }
    
}
