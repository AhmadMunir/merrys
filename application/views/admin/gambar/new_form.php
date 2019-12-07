<!doctype html>
<html class="no-js" lang="en">

<head>
   <?php $this->load->view('admin/partial/head') ?>
   <script src="<?php echo base_url("js/jquery.min.js"); ?>" type="text/javascript"></script>
</head>

<body>
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <?php $this->load->view('admin/partial/sidebar') ?>

    <!-- Start Welcome area -->
    <div class="all-content-wrapper">
        <?php $this->load->view('admin/partial/header') ?>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                     <br>
                  <br>
                    <div class="col-lg-11 col-md-11 col-sm-11 col-xs-12" style="margin-bottom:1px;">

                          <br>
                          <br>
                          <div class="admin-content analysis-progrebar-ctn res-mg-t-100">

                      <form action="<?php base_url('admin/gambar/add') ?>" method="post" enctype="multipart/form-data">

                         

                            <div class="form-group">
                              <label for="harga">Gambar</label>
                              <?php for ($i=1; $i <=5 ; $i++) :?>
                                <input type="file" name="gambar<?php echo $i;?>"><br/>
                                  <?php endfor;?>
                              <div class="invalid-feedback">
                                <?php echo form_error('gambar')?>
                              </div>
                            <input class="btn btn-success" type="submit" name="btn"value="Simpan">
                            <a href="<?php echo site_url('admin/gambar') ?>" class="btn btn-primary">Back</a>
                          </form>
                          <input type="hidden" id="jumlah-form" value="1">

                        </div>
                      </div>

                    </div>
                  </div>
                </div>
              </div>


        </div>
    </div>

    <!-- jquery
        ============================================ -->
     <?php $this->load->view('admin/partial/jquery') ?>
               <script>
            $(document).ready(function(){ // Ketika halaman sudah diload dan siap
              $("#btn-tambah-form").click(function(){ // Ketika tombol Tambah Data Form di klik
                var jumlah = parseInt($("#jumlah-form").val()); // Ambil jumlah data form pada textbox jumlah-form
                var nextform = jumlah + 1; // Tambah 1 untuk jumlah form nya

                // Kita akan menambahkan form dengan menggunakan append
                // pada sebuah tag div yg kita beri id insert-form
                $("#insert-form").append(
                  "<b>Ukuran ke " + nextform + " :</b>" +
                  "<div class='row'>"+
                    "<div class='col-md-3'>"+
                      "<input class='col-md-12' type='text' name='size[]' required placeholder='Ukuran'>"+
                    "</div>"+
                    "<div class='col-md-6'>"+
                      "<input class='col-md-12' type='text' name='desk[]' required placeholder='Deskripsi Singkat'>"+
                    "</div>"+
                    "<div class='col-md-3'>"+
                      "<input class='col-md-12' type='text' name='stok[]' required placeholder='Jumlah Stok'>"+
                    "</div>"+
                  "</div>");

                $("#jumlah-form").val(nextform); // Ubah value textbox jumlah-form dengan variabel nextform
              });

              // Buat fungsi untuk mereset form ke semula
              $("#btn-reset-form").click(function(){
                $("#insert-form").html(""); // Kita kosongkan isi dari div insert-form
                $("#jumlah-form").val("1"); // Ubah kembali value jumlah form menjadi 1
              });
            });
            </script>
            <script type="text/javascript" src="<?php echo base_url().'assets/js/bootstrap.js'?>"></script>
</body>

</html>