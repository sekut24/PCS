<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk extends CI_Controller {

    public function index(){
        $this->load->model('M_produk');
        $data['produk'] = $this->M_produk->getProduk();
        
        $this->load->view('v_produk', $data);
    }
}