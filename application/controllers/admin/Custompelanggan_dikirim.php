<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Custompelanggan_dikirim extends CI_Controller
{

  function __construct()
  {
    parent::__construct();
    $this->load->model('admin/Custompelanggan_dikirim_model');
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

  function index()
  {
    // $data["view_transaksi"] = $this->Transaksi_diterima_model->getAll();
    //   $this->load->view("admin/transaksi_diterima/list",$data);
    $bulan = $this->input->post('bulan');
    $tahun = $this->input->post('tahun');
    if ($bulan  == null && $tahun == null) {
      $data['custompelanggan_dikirim'] = $this->Custompelanggan_dikirim_model->getview_custom();
      # code...
    }else{
      $data['custompelanggan_dikirim'] = $this->Custompelanggan_dikirim_model->getview_custompelanggan_bulan($bulan,$tahun);

    }
    $data['custom'] = $this->Custompelanggan_dikirim_model->getdate_year();
    $this->load->view('admin/custompelanggan_dikirim/list',$data);

  }

  public function edit($id = null)
    {
      if (!isset($id)) redirect('admin/custompelanggan_dikirim');
      $custompelanggan_dikirim = $this->Custompelanggan_dikirim_model;
      $validation = $this->form_validation;
      $validation->set_rules($custompelanggan_dikirim->rules());

      if ($validation->run()){
        $custompelanggan_dikirim->update();
        $this->session->set_flashdata('success','Berhasil Disimpan');
        redirect(site_url("admin/custompelanggan_dikirim"));
      }
      $data["tabel_custom"] = $custompelanggan_dikirim->getById($id);
      if (!$data["tabel_custom"]) show_404();

      $this->load->view("admin/custompelanggan_dikirim/edit_form",$data);
    }

  // public function delete($id=null)
  //   {
  //     if (!isset($id)) show_404();

  //     if ($this->Transaksi_diterima_model->delete($id)) {
  //       redirect(site_url('admin/transaksi_diterima'));
  //     }
  //   }
}
?>