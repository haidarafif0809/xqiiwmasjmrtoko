<?php session_start();

// memasukan file db.php
include 'db.php';
include 'sanitasi.php';
session_start();
// mengirim data no faktur menggunakan metode POST
 $no_faktur = $_POST['no_faktur'];
 $potongan = $_POST['potongan'];
 $tax = $_POST['tax'];

// menampilakn hasil penjumlah subtotal ALIAS total penjualan dari tabel tbs_penjualan berdasarkan data no faktur
 $query = $db->query("SELECT SUM(subtotal) AS total_penjualan FROM detail_penjualan WHERE no_faktur = '$no_faktur'");
 
 // menyimpan data sementara yg ada pada $query
 $data = mysqli_fetch_array($query);
 $total = $data['total_penjualan'] ;

$tax_bener = $tax * $total / 100;

  $total_akhir = $total  -$potongan + $tax_bener;

$a = intval($total_akhir);

echo rp($a);


        //Untuk Memutuskan Koneksi Ke Database

        mysqli_close($db); 
        
        
  ?>


