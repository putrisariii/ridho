<?php

// fungsi menampilkkan data_user
function select($query) {
  
  //panggil koneksi database
  global $conn;

  $result = mysqli_query($conn, $query);
  $rows = [];

  while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
  }
  return $rows;
}





// fungsi tambah user
function create_user_admin($post) {

  global $conn;

  $username      = strip_tags($post['username']);
  $name          = strip_tags($post['nama']);
  $password      = strip_tags($post['password']);
  $role          = strip_tags($post['role']);
  $date_created  = date("Y-m-d");
  $date_modified = date("Y-m-d");
  
  // enkripsi password
  $password = password_hash($password, PASSWORD_DEFAULT);


  // query tambah
  $query = "INSERT INTO adminakun VALUES(null, '$name', '$username', '$password', '$role',  '$date_created', '$date_modified')";

  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}


// fungsi menghapus data akun
function delete_user($id_akun) {
  global $conn;

  //query delete data
  $query = "DELETE FROM adminakun WHERE id_akun = $id_akun";

  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}



// fungsi ubah akun
function update_user_admin($post) {

  global $conn;

  $id_akun       = strip_tags($post['id_akun']);
  $nama          = strip_tags($post['nama']);
  $username      = strip_tags($post['username']);
  $password      = strip_tags($post['password']);
  $date_modified = date("Y-m-d");
  
  // enkripsi password
  $password = password_hash($password, PASSWORD_DEFAULT);

 // query ubah

  $query = "UPDATE adminakun SET nama = '$nama', username = '$username',  password = '$password', date_modified = '$date_modified' WHERE id_akun = '$id_akun'";

  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}




// fungsi mengupload file
function upload_file() {
  $namaFile   = $_FILES['berkas']['name'];
  $ukuranFile = $_FILES['berkas']['size'];
  $errorFile  = $_FILES['berkas']['error'];
  $tmpName    = $_FILES['berkas']['tmp_name'];
  
  // check file yang diupload
  $extensifileValid = ['pdf', 'docx', 'doc'];
  $extensifile = explode('.', $namaFile);
  $extensifile = strtolower(end($extensifile));

  if (!in_array($extensifile, $extensifileValid)) {
    // pesan gagal
    echo "<script>
          alert('Format File Tidak Valid');
          document.location.href = 'tables_arsip.php';
    </script>";
    die();

  }

  // check ukuran file
  if($ukuranFile > 5048000) {
    // pesan gagal
    echo "<script>
          alert('Ukuran File Max 5MB');
          document.location.href = 'tables_arsip.php';
    </script>";
    die();

  }

  // generate nama file baru

  $namaFileBaru = uniqid();
  $namaFileBaru = '.';
  $namaFileBaru = $extensifile;

  // pindah ke folder local
  move_uploaded_file($tmpName, 'assets/file/'. $namaFile);
  return $namaFile;

}





// fungsi menambahkan data arsipan sppd
function create_arsip($post) {
  global $conn;

  $nama                = strip_tags($post['nama']);
  $nomor_surat         = strip_tags($post['nomor_surat']);
  $tanggal             = strip_tags($post['tanggal']);
  $perihal             = strip_tags($post['perihal']);
  $keterangan          = strip_tags($post['keterangan']);
  $berkas              = upload_file();

  // check upload berkas
  if (!$berkas) {
    return false;
  }


  // query tambah data
  $query = "INSERT INTO arsipan_spd VALUES(null, '$nama', '$nomor_surat', '$tanggal', '$perihal', '$keterangan','$berkas')";

  mysqli_query($conn, $query);
  
  
  return mysqli_affected_rows($conn);
  
}


// fungsi menghapus data arsipan sppd
function delete_arsip($id_surat) {
  global $conn;

  // mengambil berkas sesuai data yang dipilih
  $berkas = select("SELECT * FROM arsipan_spd WHERE id_surat = $id_surat")[0];
  unlink("assets/file/". $berkas['berkas']);

  //query delete data
  // $query = "DELETE FROM `arsipan_spd` WHERE `arsipan_spd`.`id_surat` ='$id_surat'";
  $query = "DELETE FROM arsipan_spd WHERE id_surat = '$id_surat'";

  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}



// fungsi update data arsipan sppd
function update_arsip($post) {
  global $conn;

  $id_surat            = strip_tags($post['id_surat']);
  $nama                = strip_tags($post['nama']);
  $nomor_surat         = strip_tags($post['nomor_surat']);
  $tanggal             = strip_tags($post['tanggal']);
  $perihal             = strip_tags($post['perihal']);
  $keterangan          = strip_tags($post['keterangan']);
  $berkasLama          = $post['berkasLama'];

  // check upload berkas baru atau tidak 
  if ($_FILES['berkas']['error'] == 4) {
    $berkas = $berkasLama;
  } else {
    $berkas = upload_file();
  }  
  
// query ubah
 $query = "UPDATE arsipan_spd SET nama = '$nama', nomor_surat = '$nomor_surat', tanggal = '$tanggal', perihal = '$perihal', keterangan = '$keterangan', berkas = '$berkas'  WHERE id_surat = '$id_surat'";

 mysqli_query($conn, $query);

 return mysqli_affected_rows($conn);
}

// fungsi filter tanggal
// function filter_tgl($post) {
//   global $conn;

//   $mulai               = strip_tags($post['tgl_mulai']);
//   $selesai             = strip_tags($post['tgl_selesai']);

//  // query filter
//  $query = "SELECT * FROM arsipan_spd WHERE tanggal BETWEEN $mulai and $selesai";

//  mysqli_query($conn, $query);

//  return mysqli_affected_rows($conn);
// }



// fungsi tambah catatan
function create_catatan($post) {

  global $conn;

  $nama               =  strip_tags($post['nama']);
  // $tanggal               =  strip_tags($post['d-m-Y']);
  $tempat_tujuan      = strip_tags($post['tempat_tujuan']);
  $perihal            = strip_tags($post['perihal']);

  

  // query tambah
  $query = "INSERT INTO catatan VALUES(null, '$nama', CURRENT_TIMESTAMP(), '$tempat_tujuan', '$perihal')";

  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}



// fungsi menghapus data catatan
function delete_catatan($id_catatan) {
  global $conn;

  //query delete data
  $query = "DELETE FROM catatan WHERE id_catatan = $id_catatan";

  mysqli_query($conn, $query);

  return mysqli_affected_rows($conn);
}




// fungsi ubah data catatan
function update_catatan($post) {

  global $conn;
  $id_catatan         = strip_tags($post['id_catatan']);
  $nama               = strip_tags($post['nama']);
  $tempat_tujuan      = strip_tags($post['tempat_tujuan']);
  $perihal            = strip_tags($post['perihal']);


 // query ubah
 $query = "UPDATE catatan SET   nama = '$nama', tempat_tujuan = '$tempat_tujuan', perihal = '$perihal' WHERE id_catatan = '$id_catatan'";

 mysqli_query($conn, $query);

 return mysqli_affected_rows($conn);
}