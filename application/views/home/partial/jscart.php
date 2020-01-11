<script>
var sam = 0;
var id_trans;
var omkir = 0;
var tot;
var taxs;
    $(document).ready(function(){
        // CALL FUNCTION SHOW PRODUCT
        // $('#cart_itung').html('html');
        show_country();
        show_product();
        set_tax();
        grand_tot();
        //
        // $('#paypal-button-container').hide();

        //Pusher
        Pusher.logToConsole = true;

        var pusher = new Pusher('47980f8443159a27e646', {
          cluster: 'ap1',
          forceTLS: true
        });

        var channel = pusher.subscribe('notif-cart');
        channel.bind('my-event', function(data) {
          if (data.status == 'success') {
            $('#cart_itung').html(data.itung_cart);
            show_product();
            set_tax();
            grand_tot();
          }else if (data.status == 'error') {
            alert(data.ket);
          }
        });

        itung_cart();
        function itung_cart(){
          $.ajax({
            url : '<?php echo site_url("user/cart/hitung_cart")?>',
            type : 'GET',
            success : function(data){

            }
          });
        }

        function set_tax(){
          $.ajax({
            url : '<?php echo base_url("user/cart/tax");?>',
            type : 'GET',
            dataType : 'json',
            success : function(tax){
              var html = 'USD ';
              html += tax;
              taxs = tax;
              $('#tax').html(html);
            }
          });
        }

        function show_country(){
          $.ajax({
            url : '<?php echo site_url("shipping/country")?>',
            type : 'GET',
            dataType  : 'json',
            success : function(option){
              var html = '';
              html += '<option value="&nbsp"> Select Country </option>';
              html += '<option value="idn">Indonesia</option>';
              var i;
              for(i=0; i<option.length; i++){
                html += '<option value="'+option[i].id_country+'">'+option[i].country+'</option>';
              }
              $('#country').html(html);
            }
          });
        }

        $('#country').change(function(){
          var cn = $('#country').val();
          if (cn === 'idn') {
            $('.indo').fadeIn();
            $('.inter').hide();
            show_province();
            var html ="";
            html += '<select name="kurir" id="kurir">'+
                    '<option value="" selected > select Courier</option>'+
                    '<option value="pos" > POS </option>'+
                    '<option value="jne" > JNE </option>'+
                    '<option value="tiki" > TIKI </option>'+
                    '</select>';
            $('#kurir').html(html);
            var ht = ''
            ht += '<option value="&nbsp"> Select Service </option>';
            $('#service').html(ht);
          }else {
            $('.indo').hide();
            $('.inter').fadeIn();
            var html ="";
            html += '<select name="kurir" id="kurir">'+
                    '<option value="" selected > select Courier</option>'+
                    '<option value="jne" > JNE </option>'+
                    '</select>';
            $('#kurir').html(html);
            var ht = ''
            ht += '<option value="&nbsp"> Select Service </option>';
            $('#service').html(ht);
          }
        });

        function show_province(){
          $.ajax({
            url : '<?php echo site_url("shipping/province")?>',
            type : 'GET',
            dataType  : 'json',
            success : function(option){
              var html = '';
              html += '<option value="&nbsp"> Select Province </option>'
              var i;
              for(i=0; i<option.length; i++){
                html += '<option value="'+option[i].id_province+'">'+option[i].province+'</option>';
              }
              $('#province').html(html);
            }
          });
        }

        // province on change
        $('#province').change(function(){
          html = '<option value="&nbsp"> Select City </option>'
          $('#city').html(html);

          var prov = $('#province').val();

          $.ajax({
            type  : 'POST',
            url : '<?php echo site_url("shipping/city")?>',
            dataType  : 'json',
            data : {id_province : prov},
            success : function(city){
              // console.log('data : ', city);
              var html = '';
              html += '<option value="&nbsp"> Select City </option>'

              var i;
              for(i=0; i<city.length; i++){
                html += '<option value="'+city[i].city_id+'">'+city[i].type+'&nbsp'+city[i].city_name+'</option>';
              }
              $('#city').html(html);
            }
          });
        });

        //cek ongkir
        $('#kurir').change(function(){

          var kurir = $('#kurir').val();
          var city = $('#city').val();
          // if ($('#city').val() == null) {
          //   var city = document.getElementById('default_city').value;
          // } else {
          //   var city = $('#city').val();
          // }

          $.ajax({
            type  : 'POST',
            url : '<?php echo site_url("shipping/cek_ongkir")?>',
            dataType  : 'json',
            data  : {'kurir' : kurir, 'city' :city},
            success : function(ongkir){
              var html = '';
              html += '<option value="&nbsp"> Select Service </option>';

              var i;
              for(i=0; i<ongkir.length; i++){
                html +='<option value="'+ongkir[i].cost+'">'+ongkir[i].service+'&nbsp;-&nbsp; USD '+ongkir[i].cost+'</option>';
              }
              $('#service').html(html);
            }
          });
        });

        $('#service').change(function(){
          var html;
          var ship = 'USD '
          ship += $('#service').val();
          $('#shipping').html(ship);
          omkir = $('#service').val();

          var cn = $('#country').val();
          if (cn === 'idn') {
            $.ajax({
              type  : 'POST',
              url : '<?php echo base_url("shipping/set_ship_session");?>',
              dataType  : 'json',
              data  : {'cost':$('#service').val(),
              'method':$('#kurir option:selected').text()+'-'+$('#service option:selected').text(),
              'a1':document.getElementById('detail_address').value,
              // 'a2':'Sukosari',
              'a3':$('#city').val(),
              'a4':$('#province').val(),
              'a5':$('#zip').val(),
              'a6':'ID',},
            });
          }else {
            $.ajax({
              type  : 'POST',
              url : '<?php echo base_url("shipping/set_ship_session");?>',
              dataType  : 'json',
              data  : {'cost':$('#service').val(),
              'method':$('#kurir option:selected').text()+'-'+$('#service option:selected').text(),
              'a1':document.getElementById('detail_address').value,
              // 'a2':'Sukosari',
              'a3':$('#city_int').val(),
              'a4':$('#province_int').val(),
              'a5':$('#zip').val(),
              'a6':'US',},
            });
          }

        });

        //grand_total
        function grand_tot(){
          gr = tot+taxs+omkir;
          $('#grand_total').html(gr);
        }

        //input detail address

        $('#detail_address').on('input',function(){
          $('#detail_address').css("backgrounColor", "green");
          $('#address_from_db').hide();
          html = 'The package will deliver to '+document.getElementById('detail_address').value+
                  ', '+$('#city option:selected').text()+', '+$('#province option:selected').text();
          $('#address_from_this').html(html);
        });

        // FUNCTION SHOW PRODUCT
        function show_product(){
            $.ajax({
                url   : '<?php echo site_url("user/cart/get_cart");?>',
                type  : 'GET',
                async : true,
                dataType : 'json',
                success : function(data){
                    var html = '';
                    var count = 1;
                    var i;
                    var sum = 'USD ';
                    sum += data.data[0].total;
                    tot = data.data[0].total;

                    $('#result').text(sum);

                    if (data.status == 'gagal') {
                      html += "Nothing here";
                    }else {
                      for(i=0; i<data.data.length; i++){
                        html += '<tr>'+
                                '<td class="product-thumbnail"><a href="#" title="'+ data.data[i].nama_barang +'"><img class="product-thumbnail" src="<?php echo base_url('img/barang/') ?>'+ data.data[i].gambar +'" alt="" ></a></td>'+
                                '<td class="product-name pull-left mt-20">'+
                                  '<a href="#" title="'+ data.data[i].nama_barang +'">'+ data.data[i].nama_barang + '</a>'+
                                  '<p class="w-color m-0">'+
                                      '<label> Color : </label>'+
                                      'black'+
                                  '</p>'+
                                  '<p class="w-size m-0">'+
                                      '<label> Size :  </label>'+
                                      ' '+data.data[i].size+
                                  '</p>'+
                                '</td>'+
                                '<td class="product-prices price-'+ count++ + '"><span class="amount">'+ data.data[i].harga + '</span></td>'+
                                '<td class="product-value">'+
                                  '<span>'+data.data[i].qty+'</span>'+
                                '</td>'+
                                '<td class="product-stock-status"><span class="whislist-in-stok">'+ data.data[i].subtotal +'</span></td>'+
                                '<td class="product-remove">'+
                                  '<a href="javascript:void(0);" class="delete_cart" data-id="' + data.data[i].id_detail_temp_transaksi +'"><i class="zmdi zmdi-delete"></i></a>'+
                                  '<td>'+
                                '</tr>';
                    }
                    }
                    $('.show_cart').html(html);
                }

            });
        }
        // DELETE PRODUCT
        $('#mytable').on('click','.delete_cart',function(){
            var id_detail_temp_transaksi = $(this).data("id");
            $('#ModalDelete').modal('show');
            $('.id_detail_temp_transaksi').val(id_detail_temp_transaksi);
        });

        $('.btn-delete').on('click',function(){
            var id_detail_temp_transaksi = $('.id_detail_temp_transaksi').val();
            $.ajax({
                url    : '<?php echo site_url("user/cart/delete");?>',
                method : 'POST',
                data   : {id: id_detail_temp_transaksi},
                success: function(){
                    $('.show_cart').html('');
                    $('#ModalDelete').modal('hide');
                }
            });
        });
        // END DELETE PRODUCT

        // country_name
        $('#country').change(function(){
          document.getElementById('cn_name').value = $('#country option:selected').text();
        });

        //prov name
        $('.prv').change(function(){
          document.getElementById('prov_name').value = $('#province option:selected').text();
        });

        //city name
        $('.city').change(function(){
          document.getElementById('city_name').value = $('#city option:selected').text();
        });


      });
</script>
<script>
  paypal.Buttons({
    createOrder: function() {
        return fetch('<?php echo base_url('payment/paypal/createorder/order');?>', {
          method: 'post',
          headers: {
            'content-type': 'application/json'
          }
        }).then(function(res) {
          return res.json();
        }).then(function(data) {
          return data.result.id; // Use the same key name for order ID on the client and server
        });
      },
      onApprove: function(data, actions) {
      return actions.order.capture().then(function(details) {
        // alert('Transaction completed by ' + details.payer.name.given_name);
        // Call your server to save the transaction
        return fetch('../payment/paypal/getorder/get', {
          method: 'post',
          headers: {
            'content-type': 'application/json'
          },
          body: JSON.stringify({
            orderID: data.orderID
          })
        });
      });
    }
  }).render('#paypal-button-container');
</script>