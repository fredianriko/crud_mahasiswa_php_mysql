<!-- script PHP -->
<?php

// koneksi database
$host = "localhost";
$user = "root";
$pass = "";
$db = "akademik";

$koneksi =  mysqli_connect($host, $user, $pass, $db);

// global variabel
$nim        = "";
$nama       = "";
$alamat     = "";
$fakultas   = "";
$sukses     = "";
$error      = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

// GET Data based on ID
if ($op == 'edit') {
    $id = $_GET['id'];
    $sql1 = "select * from mahasiswa where id = '$id'";
    $q1 = mysqli_query($koneksi, $sql1);
    $r1 = mysqli_fetch_array($q1);

    $nim = $r1['nim'];
    $nama = $r1['nama'];
    $alamat = $r1['alamat'];
    $fakultas = $r1['fakultas'];

    if ($nim == '') {
        $error = "data tidak ditemukan";
    }
}

// check whether the form is set or not
// POST untuk create data
if (isset($_POST['simpan'])) {
    $nim    = $_POST['nim'];
    $nama   = $_POST['nama'];
    $alamat   = $_POST['alamat'];
    $fakultas   = $_POST['fakultas'];


    if ($nim && $nama && $alamat && $fakultas) {
        if ($op == 'edit') {
            // query update data untuk data yang diedit
            $sql1 = "update mahasiswa set nim = '$nim', nama = '$nama', alamat = '$alamat', fakultas = '$fakultas' where id = '$id'";
            $q1 = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses = "data berhasil di update";
            } else {
                $error = "data gagal di update";
            }
        } else {
            // query insert data untuk data baru
            $sql1 =  "INSERT INTO mahasiswa(nim,nama,alamat,fakultas) values('$nim','$nama','$alamat','$fakultas')";
            // execute the query with connection string
            $q1 = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses = "berhasil memasukan data baru";
            } else {
                $error = "gagal memasukan data";
            }
        }
    } else {
        $error = "Silahkan masukan semua data";
    }
}


// DELETE DATA
if ($op == 'delete') {
    $id = $_GET['id'];

    $sql1 = "delete from mahasiswa where id = '$id'";
    $q1 = mysqli_query($koneksi, $sql1);
    if ($q1) {
        $sukses = "Berhasil menghapus data";
    } else {
        $error = "Gagal menghapus data";
    }
}


// verify connection to database
if (!$koneksi) {
    die("tidak bisa terkoneksi ke database");
}
?>

<!-- HTML SECTION -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD MAHASISWA</title>
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="mx-auto">

        <!-- Card sebagai form insert data baru dan edit data-->
        <div class="card">
            <h5 class="card-header">Create / Edit data</h5>
            <div class="card-body">
                <!-- error message ketika gagal memasukan data ke database -->
                <?php
                if ($error) {
                ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error ?>
                    </div>
                <?php
                    header("refresh:3;url=index.php");
                }
                ?>

                <!-- suscess message ketika berhasil memasukan data ke database -->
                <?php
                if ($sukses) {
                ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $sukses ?>
                    </div>
                <?php
                    header("refresh:3;url=index.php");
                }
                ?>

                <!-- form create / edit data  -->
                <form action="" method="POST">
                    <div class="mb-3 row">
                        <label for="nim" class="col-sm-2 col-form-label">NIM</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nim" name="nim" value="<?php echo $nim ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="nama" class="col-sm-2 col-form-label">NAMA</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="alamat" class="col-sm-2 col-form-label">ALAMAT</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="alamat" name="alamat" value="<?php echo $alamat ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="fakultas" class="col-sm-2 col-form-label">FAKULTAS</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="fakultas" id="fakultas">
                                <option value="">-- Pilih Fakultas -- </option>
                                <option value="saintek" <?php if ($fakultas == "saintek") echo "selected" ?>>saintek</option>
                                <option value="soshum" <?php if ($fakultas == "soshum") echo "selected" ?>>soshum</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>

        <!-- Card untuk menampilkan data dari database-->
        <div class="card">
            <h5 class="card-header text-white bg-secondary">Data Mahasiswa</h5>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">NIM</th>
                            <th scope="col">NAMA</th>
                            <th scope="col">ALAMAT</th>
                            <th scope="col">FAKULTAS</th>
                            <th scope="col">AKSI</th>
                        </tr>

                    <tbody>

                        <!-- fetch data from database -->
                        <?php
                        $sql2 = "select * from mahasiswa order by id desc";
                        $q2 = mysqli_query($koneksi, $sql2);
                        $urut = 1;

                        while ($r2 = mysqli_fetch_array($q2)) {
                            $id         = $r2['id'];
                            $nim        = $r2['nim'];
                            $nama       = $r2['nama'];
                            $alamat     = $r2['alamat'];
                            $fakultas   = $r2['fakultas'];
                        ?>
                            <tr scope="row">
                                <th scope="row"> <?php echo $urut++ ?></th>
                                <td scope="row"><?php echo $nim ?></td>
                                <td scope="row"><?php echo $nama ?></td>
                                <td scope="row"><?php echo $alamat ?></td>
                                <td scope="row"><?php echo $fakultas ?></td>
                                <td scope="row">
                                    <a href="index.php?op=edit&id=<?php echo $id ?>">
                                        <button type="button" class="btn btn-warning">Edit</button>
                                    </a>
                                    <a href="index.php?op=delete&id=<?php echo $id ?>" onclick="return confirm('Yakin mau delete data')">
                                        <button type="button" class="btn btn-danger">Delete</button>
                                    </a>

                                </td>
                            </tr>

                        <?php
                        }
                        ?>

                    </tbody>
                    </thead>
                </table>
            </div>
        </div>

    </div>
</body>

</html>