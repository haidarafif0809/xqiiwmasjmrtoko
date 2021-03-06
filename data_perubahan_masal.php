<?php include_once 'session_login.php';

// memasukan file session login,  header, navbar, db.php,
include 'header.php';
include 'navbar.php';
include 'db.php';
include 'sanitasi.php';

$nomor = stringdoang($_GET['nomor']);

// THESE QUERY, DON'T BE DELETE !!
$select = $db->query("SELECT * FROM data_perubahan_masal WHERE nomor = '$nomor'");
$out = mysqli_fetch_array($select);

 ?>
<div class="container">


<!--START MODAL UNTUK CONFIRMASI PERUBAHAN HARGA MASAL-->
<div id="modal_confirm" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title"><center><b>Konfirmasi Perubahan Harga Masal</b></center></h4>
      </div>

      <div class="modal-body">
   
   <p>Apakah Anda yakin Ingin Mengkonfirmasi Perubahan Harga ini ?</p>
   <form >
   <div class="form-group">
		<label> Nomor :</label>
		<input type="text" id="confirmasi_nomor" class="form-control" readonly="">		
    </div>
   
   </form>
   
  <div class="alert alert-success" style="display:none">
   <strong>Berhasil!</strong> Data berhasil Di Ubah
  </div>
 

     </div>

      <div class="modal-footer">
        <button type="button" data-id="" class="btn btn-info" id="btn_confirmasi"> <span class='glyphicon glyphicon-ok-sign'> </span> Ya</button>
        <button type="button" class="btn btn-warning" data-dismiss="modal"> <span class='glyphicon glyphicon-remove-sign'> </span> Batal</button>
      </div>
    </div>

  </div>
</div>
<!--END MODAL UNTUK CONFIRMASI PERUBAHAN HARGA MASAL-->




<h1><b>Hasil Data Perubahan Harga Masal</b></h1>
<br>

<form>
	<input type="hidden" id="data_nomor" value="<?php echo $out['nomor']; ?>">
</form>




<table>
  <tbody>

      <tr><td width="50%">Nomor</td> <td> :&nbsp;</td> <td> <?php echo $out['nomor']; ?> </td></tr>
      <tr><td width="50%">Perubahan Pada</td> <td> :&nbsp;</td> <td> Harga <?php echo $out['perubahan_harga']; ?> </td></tr>

      <tr><td width="50%">Pola Perubahan</td> <td> :&nbsp;</td> <td> <?php echo $out['pola_perubahan']; ?> Dari <?php echo $out['acuan_harga']; ?></td></tr>

<?php if ($out['nilai'] == 'Persentase'): ?>

	     <tr><td width="50%">Nilai</td> <td> :&nbsp;</td> <td> <?php echo rp($out['jumlah_nilai']); ?> %</td></tr>
<?php else: ?>

   <tr><td width="50%">Nilai</td> <td> :&nbsp;</td> <td> Rp. <?php echo rp($out['jumlah_nilai']); ?> </td></tr>

<?php endif ?>

      <tr><td width="50%">Pembulatan</td> <td> :&nbsp;</td> <td> <?php echo $out['pembulatan']; ?> </td></tr>

      <tr><td  width="50%">Tanggal</td> <td> :&nbsp;</td> <td> <?php echo tanggal($out['tanggal']);?> </td>
      </tr>
            
  </tbody>
</table>
<br>

<div class="row">

	<div class="col-sm-2">
<button type="submit" id="submit_simpan" class="btn btn-info" ><i class='fa fa-save'> </i> Simpan</button>
	</div>
	
	<div class="col-sm-10">
	</div>
	
</div>
 <!--start tampilan annoucment data barang yang akan di ubah-->
<br>
 <div class="card card-block">
<span id="perubahan_data">      
<div class="table-responsive">
      <!--tag untuk membuat garis pada tabel-->       
<table id="table_data" class="table table-bordered">
<center><h4><b>Data Barang</b></h4></center>
    <thead>
			<th style='background-color: #4CAF50; color:white'> Kode Barang </th>
			<th style='background-color: #4CAF50; color:white'> Nama Barang </th>
			<th style='background-color: #4CAF50; color:white'> Kategori Produk </th>
			<th style='background-color: #4CAF50; color:white'> HPP </th>
			<th style='background-color: #4CAF50; color:white'> Harga Lama </th>
			<th style='background-color: #4CAF50; color:white'> Harga Baru </th>
			<th style='background-color: #4CAF50; color:white'> Pembulatan </th>
    </thead>
    
    <tbody>
    
    </tbody>

   </table>
  </div>
 </span>
</div>
</div><!--Close Countainer-->


<!--start ajax datatable-->
    <script type="text/javascript" language="javascript" >
      $(document).ready(function() {
     $('#table_data').DataTable().destroy();

          var dataTable = $('#table_data').DataTable( {
          "processing": true,
          "serverSide": true,
          "info":     false,
          "language": {
        "emptyTable":     "My Custom Message On Empty Table"
    },
          "ajax":{
            url :"show_data_perubahan_harga_masal.php", // json datasource
             "data": function ( d ) {
                d.nomor = $("#data_nomor").val();
                // d.custom = $('#myInput').val();
                // etc
            },
                type: "post",  // method  , by default get
            error: function(){  // error handling
              $(".tbody").html("");
              $("#table_data").append('<tbody class="tbody"><tr><th colspan="3"></th></tr></tbody>');
              $("#table_data_processing").css("display","none");
              
            }
          }
    
        } );
          $("#perubahan_data").show()

   
  $("#perubahan_data").submit(function(){
      return false;
  });
  function clearInput(){
      $("#perubahan_data :input").each(function(){
          $(this).val('');
      });
  };
  } );
    </script>
<!--end ajax datatable-->

<script type="text/javascript">
$(document).on('click', '#submit_simpan', function (e) {		
		
   	var nomor = $("#data_nomor").val();

	$("#confirmasi_nomor").val(nomor);	

	$("#modal_confirm").modal('show');
	$("#btn_confirmasi").attr("confirmasi_nomor", nomor);	
		
});
		
$(document).on('click', '#btn_confirmasi', function (e) {

		var nomor = $("#confirmasi_nomor").val();

		$.post("proses_perubahan_harga.php",{nomor:nomor},function(data){
		$(".alert-success").show();
		$("#modal_confirm").modal('hide');
		window.location.href="perubahan_harga_masal.php";

		});
		
		
});
</script>

 <?php 
include 'footer.php';
 ?>