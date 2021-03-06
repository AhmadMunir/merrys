<?php
  defined ('BASEPATH') OR exit('No direct script access allowed');

  class baner extends CI_Controller
  {
  	public function __construct()
  	{
  		parent::__construct();
  		$this->load->model("admin/baner_model");
  		$this->load->library('form_validation');
      $this->load->model('m_login');
   if($this->session->userdata('status') != "login"){

         redirect(base_url("login"));
     }else{
       $where = array(
         'username' => $this->session->userdata('nama'));
       // $cekadmin2 = $this->m_login->cek_user("tabel_admin", $where)->result();
       $cekadmin = $this->m_login->cek_user("tabel_admin", $where)->num_rows();
       // echo $cekadmin;
          if($cekadmin <=0){
             // echo"anda bukan admin";
             redirect(base_url("login"));
         }
     }
  	}

  	public function index()
  	{
  		$data["tabel_baner"] = $this->baner_model->getAll();
  		$this->load->view("admin/baner/list",$data);

  	}

  	public function add()
  	{
  		$baner = $this->baner_model;
  		$validation = $this->form_validation;
  		$validation->set_rules($baner->rules());

  		if ($validation->run()){
  			$baner->save();
  			$this->session->set_flashdata('success','Berhasil Disimpan');
        redirect(site_url("admin/baner"));
  		}

  		$this->load->view("admin/baner/new_form");

  	}
  	public function edit($id = null)
  	{
  		if (!isset($id)) redirect('admin/baner');
  		$baner = $this->baner_model;
  		$validation = $this->form_validation;
  		$validation->set_rules($baner->rules());

  		if ($validation->run()){
  			$baner->update();
  			$this->session->set_flashdata('success','Berhasil Disimpan');
        redirect(site_url("admin/baner"));
  		}
  		$data["tabel_baner"] = $baner->getById($id);
  		if (!$data["tabel_baner"]) show_404();

  		$this->load->view("admin/baner/edit_form",$data);
  	}

  	public function delete($id=null)
  	{
  		if (!isset($id)) show_404();

  		if ($this->baner_model->delete($id)) {
        redirect(site_url('admin/baner'));
  		}
  	}
  }

 ?>
