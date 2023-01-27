<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/Firebase/JWT/JWT.php';
use \Firebase\JWT\JWT;

class Api_pcs extends REST_Controller {

   private $secret_key = "dfeqgrwgwrhgesgreagrrsthgerthrthw";

    function __construct()
        {
                parent::__construct();
                $this->load->model('M_admin');
                $this->load->model('M_produk');
                $this->load->model('M_transaksi');
                $this->load->model('M_item_transaksi');
                  
        }


        public function cekToken(){
            try {
                $token=$this->input->get_request_header('Authorization');
    
                if(!empty($token)){
                    $token=explode(' ',$token)[1];
                }
    
                $token_decode=JWT::decode($token,$this->secret_key,array('HS256'));
            } catch (Exception $e) {
                $data_json=array(
                    "success"=>false,
                    "message"=>"Token tidak valid",
                    "error_code"=>1204,
                    "data"=>null
                );
    
                $this->response($data_json,REST_Controller::HTTP_OK);
                $this->output->_display();
                exit();
            }
        }

        public function admin_get(){
            $result = $this->M_admin->getAdmin();
            
            $data_json = array(
                "success" => true,
                "massage" => "Data Found",
                "data" => array(
                    "admin" => $result
                )
                
            );

            $this->response($data_json,REST_Controller::HTTP_OK);
        }

        public function admin_post(){
            //Validasi
            $validation_message = [];

            if($this->input->post("email")==""){
                array_push($validation_message,"Email tidak boleh kosong");
            }

            if($this->input->post("email")!=="" && !filter_var($this->input->post("email"),FILTER_VALIDATE_EMAIL)){
                array_push($validation_message,"Email tidak valid");
            }
            if($this->input->post("password")==""){
                array_push($validation_message,"Password tidak boleh kosong");
            }
            if($this->input->post("nama")==""){
                array_push($validation_message,"Nama tidak boleh kosong");
            }

            if(count($validation_message)>0){
                $data_json = array(
                    "success" => false,
                    "massage" => "Data tidak valid",
                    "data" => $validation_message
                    
                );

                $this->response($data_json,REST_Controller::HTTP_OK);
                $this->output->_display();
                exit();

            }

            //Jika Lolos Validasi
            $data = array(
                "email" => $this->input->post("email"),
                "password" => md5($this->input->post("password")),
                "nama" => $this->input->post("nama"),
            );

            $result = $this->M_admin->insertAdmin($data);

            $data_json = array(
                "success" => true,
                "massage" => "Insert Berhasil",
                "data" => array(
                    "admin" => $result
                )
                
            );

            $this->response($data_json,REST_Controller::HTTP_OK);
           
        }


        public function admin_put(){
            //Validasi
            $this->cekToken();
            $validation_message = [];

            if($this->put("email")==""){
                array_push($validation_message,"Email tidak boleh kosong");
            }

            if($this->put("email")!=="" && !filter_var($this->put("email"),FILTER_VALIDATE_EMAIL)){
                array_push($validation_message,"Email tidak valid");
            }
            if($this->put("password")==""){
                array_push($validation_message,"Password tidak boleh kosong");
            }
            if($this->put("nama")==""){
                array_push($validation_message,"Nama tidak boleh kosong");
            }

            if(count($validation_message)>0){
                $data_json = array(
                    "success" => false,
                    "massage" => "Data tidak valid",
                    "data" => $validation_message
                    
                );

                $this->response($data_json,REST_Controller::HTTP_OK);
                $this->output->_display();
                exit();

            }

            //Jika Lolos Validasi
            $data = array(
                "email" => $this->put("email"),
                "password" => md5($this->put("password")),
                "nama" => $this->put("nama"),
            );

            $id = $this->put("id");

            $result = $this->M_admin->updateAdmin($data,$id);

            $data_json = array(
                "success" => true,
                "massage" => "Update Berhasil",
                "data" => array(
                    "admin" => $result
                )
                
            );

            $this->response($data_json,REST_Controller::HTTP_OK);
           
        }

        public function admin_delete(){
            $this->cekToken();
            $id = $this->delete("id");

            $result = $this->M_admin->deleteAdmin($id);

            if(empty($result)){
                $data_json = array(
                    "success" => false,
                    "massage" => "Data id tidak valid",
                    "data" => null
                    
                );

                $this->response($data_json,REST_Controller::HTTP_OK);
                $this->output->_display();
                exit();                
            }

            $data_json = array(
                "success" => true,
                "massage" => "Delete Berhasil",
                "data" => array(
                    "admin" => $result
                )
                
            );

            $this->response($data_json,REST_Controller::HTTP_OK);
        }

        public function login_post(){
            $data = array(
                "email" => $this->input->post("email"),
                "password" => md5($this->input->post("password"))
            );

            $result = $this->M_admin->cekLoginAdmin($data);

            if(empty($result)){
                $data_json = array(
                    "success" => false,
                    "massage" => "Email dan Password tidak valid",
                    "error_code"=> 1308,
                    "data" => null
                    
                );

                $this->response($data_json,REST_Controller::HTTP_OK);
                $this->output->_display();
                exit();                
            }else{
                $date = new Datetime();

                $payload["id"] = $result["id"];
                $payload["email"] = $result["email"];
                $payload["iat"] = $date->getTimestamp();
                $payload["exp"] = $date->getTimestamp()+3600;

                $data_json = array(
                    "success" => true,
                    "massage" => "Otentikasi Berhasil",
                    "data" => array(
                        "admin" => $result,
                        "token" => JWT::encode($payload,$this->secret_key)
                    )
                    
                );
    
                $this->response($data_json,REST_Controller::HTTP_OK);
            }
     
        }     


//Api produk start
            public function produk_get(){
            $result = $this->M_produk->getProduk();
            
            $data_json = array(
                "success" => true,
                "massage" => "Data Found",
                "data" => array(
                    "produk" => $result
                )
                
            );

            $this->response($data_json,REST_Controller::HTTP_OK);
        }

        public function produk_post(){
            //Validasi
            $validation_message = [];

            if($this->input->post("admin_id")==""){
                array_push($validation_message,"admin_id tidak boleh kosong");
            }
            if($this->input->post("nama")=="" ){
                array_push($validation_message,"nama tidak boleh kosong");
            }
            if($this->input->post("harga")==""){
                array_push($validation_message,"harga tidak boleh kosong");
            }
            if($this->input->post("harga")!="" && !is_numeric($this->input->post("harga"))){
                array_push($validation_message,"harga harus di isi angka");
            }
            if($this->input->post("stok")==""){
                array_push($validation_message,"stok tidak boleh kosong");
            }
            if($this->input->post("stok")!="" && !is_numeric($this->input->post("stok"))){
                array_push($validation_message,"stok harus di isi angka");
            }

            if(count($validation_message)>0){
                $data_json = array(
                    "success" => false,
                    "massage" => "Data tidak valid",
                    "data" => $validation_message
                    
                );

                $this->response($data_json,REST_Controller::HTTP_OK);
                $this->output->_display();
                exit();

            }

            //Jika Lolos Validasi
            $data = array(
                "admin_id" => $this->input->post("admin_id"),
                "nama" => $this->input->post("nama"),
                "harga" => $this->input->post("harga"),
                "stok" => $this->input->post("stok")
            );

            $result = $this->M_produk->insertProduk($data);

            $data_json = array(
                "success" => true,
                "massage" => "Insert Berhasil",
                "data" => array(
                    "produk" => $result
                )
                
            );

            $this->response($data_json,REST_Controller::HTTP_OK);
        
        }


        public function produk_put(){
            //Validasi
          //  $this->cekToken();
            $validation_message = [];

            if($this->put("id")==""){
                array_push($validation_message,"id tidak boleh kosong");
            }
            if($this->put("admin_id")==""){
                array_push($validation_message,"admin tidak boleh kosong");
            }

            if($this->put("nama")=="" ){
                array_push($validation_message,"nama tidak valid");
            }
            if($this->put("harga")==""){
                array_push($validation_message,"harga tidak boleh kosong");
            }
            if($this->put("harga")!="" && !is_numeric($this->put("harga"))){
                array_push($validation_message,"harga harus di isi angka");
            }
            if($this->put("stok")==""){
                array_push($validation_message,"stok tidak boleh kosong");
            }
            if($this->put("stok")!="" && !is_numeric($this->put("stok"))){
                array_push($validation_message,"stok harus di isi angka");
            }

            if(count($validation_message)>0){
                $data_json = array(
                    "success" => false,
                    "massage" => "Data tidak valid",
                    "data" => $validation_message
                    
                );

                $this->response($data_json,REST_Controller::HTTP_OK);
                $this->output->_display();
                exit();

            }

            //Jika Lolos Validasi
            $data = array(
                "admin_id" => $this->put("admin_id"),
                "nama" => $this->put("nama"),
                "harga" =>$this->put("harga"),
                "stok" => $this->put("stok"),
            );

            $id = $this->put("id");

            $result = $this->M_produk->updateProduk($data,$id);

            $data_json = array(
                "success" => true,
                "massage" => "Update Berhasil",
                "data" => array(
                    "produk" => $result
                )
                
            );

            $this->response($data_json,REST_Controller::HTTP_OK);
        
            }

        public function produk_delete(){
           // $this->cekToken();
            $id = $this->delete("id");

            $result = $this->M_produk->deleteProduk($id);

            if(empty($result)){
                $data_json = array(
                    "success" => false,
                    "massage" => "Data id tidak valid",
                    "data" => null
                    
                );

                $this->response($data_json,REST_Controller::HTTP_OK);
                $this->output->_display();
                exit();                
            }

            $data_json = array(
                "success" => true,
                "massage" => "Delete Berhasil",
                "data" => array(
                    "produk" => $result
                )
                
            );

            $this->response($data_json,REST_Controller::HTTP_OK);
        }
    //Api produk end

    //Api transaksi start


    public function transaksi_get(){
        $result = $this->M_transaksi->getTransaksi();
        
        $data_json = array(
            "success" => true,
            "massage" => "Data Found",
            "data" => array(
                "transaksi" => $result
            )
            
        );

        $this->response($data_json,REST_Controller::HTTP_OK);
    }



    public function transaksi_bulan_ini_get(){
        $result = $this->M_transaksi->getTransaksiBulanIni();
        
        $total = 0;
        foreach($result as $row){
            $total = $total + $row['total'];
        }

        $data_json = array(
            "success" => true,
            "massage" => "Data Found",
            "data" => array(
                "total" => $total,
                "transaksi" => $result
            )
            
        );

        $this->response($data_json,REST_Controller::HTTP_OK);
    }
    

    public function transaksi_post(){
       // $this->cekToken();
        //Validasi
        $validation_message = [];

        if($this->input->post("admin_id")==""){
            array_push($validation_message,"admin_id tidak boleh kosong");
        }
        if($this->input->post("total")==""){
            array_push($validation_message,"total tidak boleh kosong");
        } 
        if($this->input->post("total")!="" && !is_numeric($this->input->post("total"))){
            array_push($validation_message,"total harus di isi angka");
        }
   

        if(count($validation_message)>0){
            $data_json = array(
                "success" => false,
                "massage" => "Data tidak valid",
                "data" => $validation_message
                
            );

            $this->response($data_json,REST_Controller::HTTP_OK);
            $this->output->_display();
            exit();

        }

        //Jika Lolos Validasi
        $data = array(
            "admin_id" => $this->input->post("admin_id"),
            "total" => $this->input->post("total"),
            'tanggal' => date("Y-m-d H:i:s")
        );

        $result = $this->M_transaksi->insertTransaksi($data);

        $data_json = array(
            "success" => true,
            "massage" => "Insert Berhasil",
            "data" => array(
                "transaksi" => $result
            )
            
        );

        $this->response($data_json,REST_Controller::HTTP_OK);
    
    }


    public function transaksi_put(){
        //Validasi
       // $this->cekToken();
        $validation_message = [];

        if($this->put("id")==""){
            array_push($validation_message,"id tidak boleh kosong");
        }
        if($this->put("admin_id")==""){
            array_push($validation_message,"admin tidak boleh kosong");
        }
        if($this->put("total")==""){
            array_push($validation_message,"total tidak boleh kosong");
        }
        if($this->put("total")!="" && !is_numeric($this->put("total"))){
            array_push($validation_message,"total harus di isi angka");
        }

        if(count($validation_message)>0){
            $data_json = array(
                "success" => false,
                "massage" => "Data tidak valid",
                "data" => $validation_message
                
            );

            $this->response($data_json,REST_Controller::HTTP_OK);
            $this->output->_display();
            exit();

        }

        //Jika Lolos Validasi
        $data = array(
            "admin_id" => $this->put("admin_id"),
            "total" => $this->put("total"),
            'tanggal' => date("Y-m-d H:i:s")
        );

        $id = $this->put("id");

        $result = $this->M_transaksi->updateTransaksi($data,$id);

        $data_json = array(
            "success" => true,
            "massage" => "Update Berhasil",
            "data" => array(
                "transaksi" => $result
            )
            
        );

        $this->response($data_json,REST_Controller::HTTP_OK);
    
        }

    public function transaksi_delete(){
        $this->cekToken();
        $id = $this->delete("id");

        $result = $this->M_transaksi->deleteTransaksi($id);

        if(empty($result)){
            $data_json = array(
                "success" => false,
                "massage" => "Data id tidak valid",
                "data" => null
                
            );

            $this->response($data_json,REST_Controller::HTTP_OK);
            $this->output->_display();
            exit();                
        }

        $data_json = array(
            "success" => true,
            "massage" => "Delete Berhasil",
            "data" => array(
                "transaksi" => $result
            )
            
        );

        $this->response($data_json,REST_Controller::HTTP_OK);
    }



    //Api transaksi end



    //Api item_transaksi start


    public function item_transaksi_get(){
        $result = $this->M_item_transaksi->getItemTransaksi();
        
        $data_json = array(
            "success" => true,
            "massage" => "Data Found",
            "data" => array(
                "item_transaksi" => $result
            )
            
        );

        $this->response($data_json,REST_Controller::HTTP_OK);
    }



    public function item_transaksi_by_transaksi_id_get(){
        $result = $this->M_item_transaksi->getItemTransaksiByTransaksiId($this->input->get('transaksi_id'));
        
        $data_json = array(
            "success" => true,
            "massage" => "Data Found",
            "data" => array(
                "item_transaksi" => $result
            )
            
        );

        $this->response($data_json,REST_Controller::HTTP_OK);
    }


   

    public function item_transaksi_post(){
        //$this->cekToken();
        //Validasi
        $validation_message = [];

        if($this->input->post("transaksi_id")==""){
            array_push($validation_message,"transaksi_id tidak boleh kosong");
        }
        if($this->input->post("produk_id")==""){
            array_push($validation_message,"produk_id tidak boleh kosong");
        } 

        if($this->input->post("qty")==""){
            array_push($validation_message,"qty tidak boleh kosong");
        }
        if($this->input->post("qty")!="" && !is_numeric($this->input->post("qty"))){
            array_push($validation_message,"qty harus di isi angka");
        } 

        if($this->input->post("harga_saat_transaksi")==""){
            array_push($validation_message,"harga tidak boleh kosong");
        }
        if($this->input->post("harga_saat_transaksi")!="" && !is_numeric($this->input->post("harga_saat_transaksi"))){
            array_push($validation_message,"harga harus di isi angka");
        }      
   

        if(count($validation_message)>0){
            $data_json = array(
                "success" => false,
                "massage" => "Data tidak valid",
                "data" => $validation_message
                
            );

            $this->response($data_json,REST_Controller::HTTP_OK);
            $this->output->_display();
            exit();

        }

        //Jika Lolos Validasi
        $data = array(
            "transaksi_id" => $this->input->post("transaksi_id"),
            "produk_id" => $this->input->post("produk_id"),
            "qty" => $this->input->post("qty"),
            "harga_saat_transaksi" => $this->input->post("harga_saat_transaksi"),
            "sub_total" => $this->input->post("qty") * $this->input->post("harga_saat_transaksi")       
         );

        $result = $this->M_item_transaksi->insertItemTransaksi($data);

        $data_json = array(
            "success" => true,
            "massage" => "Insert Berhasil",
            "data" => array(
                "item_transaksi" => $result
            )
            
        );

        $this->response($data_json,REST_Controller::HTTP_OK);
    
    }


    public function item_transaksi_put(){
        //Validasi
        $this->cekToken();
        $validation_message = [];

        if($this->put("id")==""){
            array_push($validation_message,"id tidak boleh kosong");
        }
       
        if($this->put("transaksi_id")==""){
            array_push($validation_message,"transaksi_id tidak boleh kosong");
        }
        if($this->put("produk_id")==""){
            array_push($validation_message,"produk_id tidak boleh kosong");
        } 

        if($this->put("qty")==""){
            array_push($validation_message,"qty tidak boleh kosong");
        }
        if($this->put("qty")!="" && !is_numeric($this->put("qty"))){
            array_push($validation_message,"qty harus di isi angka");
        } 

        if($this->put("harga_saat_transaksi")==""){
            array_push($validation_message,"harga tidak boleh kosong");
        }
        if($this->put("harga_saat_transaksi")!="" && !is_numeric($this->put("harga_saat_transaksi"))){
            array_push($validation_message,"harga harus di isi angka");
        }


        if(count($validation_message)>0){
            $data_json = array(
                "success" => false,
                "massage" => "Data tidak valid",
                "data" => $validation_message
                
            );

            $this->response($data_json,REST_Controller::HTTP_OK);
            $this->output->_display();
            exit();

        }

        //Jika Lolos Validasi
        $data = array(
            "transaksi_id" => $this->put("transaksi_id"),
            "produk_id" => $this->put("produk_id"),
            "qty" => $this->put("qty"),
            "harga_saat_transaksi" => $this->put("harga_saat_transaksi"),
            "sub_total" => $this->put("qty") * $this->put("harga_saat_transaksi")       
         );

        $id = $this->put("id");

        $result = $this->M_item_transaksi->updateItemTransaksi($data,$id);

        $data_json = array(
            "success" => true,
            "massage" => "Update Berhasil",
            "data" => array(
                "item_transaksi" => $result
            )
            
        );

        $this->response($data_json,REST_Controller::HTTP_OK);
    
        }

    public function item_transaksi_delete(){
        $this->cekToken();
        $id = $this->delete("id");

        $result = $this->M_item_transaksi->deleteItemTransaksi($id);

        if(empty($result)){
            $data_json = array(
                "success" => false,
                "massage" => "Data id tidak valid",
                "data" => null
                
            );

            $this->response($data_json,REST_Controller::HTTP_OK);
            $this->output->_display();
            exit();                
        }

        $data_json = array(
            "success" => true,
            "massage" => "Delete Berhasil",
            "data" => array(
                "item_transaksi" => $result
            )
            
        );

        $this->response($data_json,REST_Controller::HTTP_OK);
    }


    public function item_transaksi_by_transaksi_id_delete(){
        $this->cekToken();
        $transaksi_id = $this->delete("transaksi_id");

        $result = $this->M_item_transaksi->deleteItemTransaksiByTransaksiId($transaksi_id);

        if(empty($result)){
            $data_json = array(
                "success" => false,
                "massage" => "Data id tidak valid",
                "data" => null
                
            );

            $this->response($data_json,REST_Controller::HTTP_OK);
            $this->output->_display();
            exit();                
        }

        $data_json = array(
            "success" => true,
            "massage" => "Delete Berhasil",
            "data" => array(
                "item_transaksi" => $result
            )
            
        );

        $this->response($data_json,REST_Controller::HTTP_OK);
    }

    //Api item_transaksi end


}
