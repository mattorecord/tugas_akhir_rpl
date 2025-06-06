<?php

session_start();
if (!empty($_SESSION['admin'])) {

    require '../../config.php';
    // Ambil peran pengguna untuk mempermudah pengecekan
    $role = $_SESSION['admin']['role'];

    // ==========================================================
    // GRUP AKSI YANG HANYA BOLEH DILAKUKAN OLEH 'ADMIN'
    // ==========================================================
    if ($role == 'admin') {

        // LOGIKA TAMBAH KATEGORI
        if (!empty($_GET['kategori'])) {
            $nama = htmlentities($_POST['kategori']);
            $sql_cek = "SELECT * FROM kategori WHERE nama_kategori = ?";
            $row_cek = $config->prepare($sql_cek);
            $row_cek->execute(array($nama));

            if ($row_cek->rowCount() > 0) {
                echo '<script>window.location="../../index.php?page=kategori&duplicate=yes"</script>';
            } else {
                $tgl = date("j F Y, G:i");
                $data_kategori[] = $nama;
                $data_kategori[] = $tgl;
                $sql = 'INSERT INTO kategori (nama_kategori,tgl_input) VALUES(?,?)';
                $row = $config->prepare($sql);
                $row->execute($data_kategori);
                echo '<script>window.location="../../index.php?page=kategori&success=tambah-data"</script>';
            }
        }

        // LOGIKA TAMBAH BARANG
        if (!empty($_GET['barang'])) {
            $id = htmlentities($_POST['id']);
            $kategori = htmlentities($_POST['kategori']);
            $nama = htmlentities($_POST['nama']);
            $merk = htmlentities($_POST['merk']);
            $beli = htmlentities($_POST['beli']);
            $jual = htmlentities($_POST['jual']);
            $satuan = htmlentities($_POST['satuan']);
            $stok = htmlentities($_POST['stok']);
            $tgl = htmlentities($_POST['tgl']);

            $data_barang = []; // Menggunakan variabel baru untuk keamanan
            $data_barang[] = $id;
            $data_barang[] = $kategori;
            $data_barang[] = $nama;
            $data_barang[] = $merk;
            $data_barang[] = $beli;
            $data_barang[] = $jual;
            $data_barang[] = $satuan;
            $data_barang[] = $stok;
            $data_barang[] = $tgl;
            $sql = 'INSERT INTO barang (id_barang,id_kategori,nama_barang,merk,harga_beli,harga_jual,satuan_barang,stok,tgl_input) VALUES (?,?,?,?,?,?,?,?,?)';
            $row = $config->prepare($sql);
            $row->execute($data_barang);
            echo '<script>window.location="../../index.php?page=barang&success=tambah-data"</script>';
        }

        // LOGIKA TAMBAH PENGGUNA
        if (!empty($_GET['user'])) {
            $nm_member = htmlentities($_POST['nm_member']);
            $user = htmlentities($_POST['user']);
            $pass = md5(htmlentities($_POST['pass']));
            $role_user = htmlentities($_POST['role']); // pakai nama var beda
            $tgl = date("j F Y, G:i");

            $sql_member = 'INSERT INTO member (nm_member, tgl_input) VALUES (?,?)';
            $row_member = $config->prepare($sql_member);
            $row_member->execute([$nm_member, $tgl]);

            $id_member_baru = $config->lastInsertId();

            $sql_login = 'INSERT INTO login (user, pass, role, id_member) VALUES (?,?,?,?)';
            $row_login = $config->prepare($sql_login);
            $row_login->execute([$user, $pass, $role_user, $id_member_baru]);

            echo '<script>window.location="../../index.php?page=user&success=tambah-data"</script>';
        }
    }


    // ==========================================================
    // GRUP AKSI YANG BOLEH DILAKUKAN OLEH 'ADMIN' DAN 'KASIR'
    // ==========================================================
    if ($role == 'admin' || $role == 'kasir') {

        // LOGIKA TAMBAH KE KERANJANG JUAL
        if (!empty($_GET['jual'])) {
            $id = $_GET['id'];
            $sql = 'SELECT * FROM barang WHERE id_barang = ?';
            $row = $config->prepare($sql);
            $row->execute(array($id));
            $hsl = $row->fetch();

            if ($hsl['stok'] > 0) {
                $kasir = $_SESSION['admin']['id_member']; // Diambil dari session
                $jumlah = 1;
                $total = $hsl['harga_jual'];
                $tgl = date("j F Y, G:i");

                $data_jual = []; // Menggunakan variabel baru
                $data_jual[] = $id;
                $data_jual[] = $kasir;
                $data_jual[] = $jumlah;
                $data_jual[] = $total;
                $data_jual[] = $tgl;

                $sql1 = 'INSERT INTO penjualan (id_barang,id_member,jumlah,total,tanggal_input) VALUES (?,?,?,?,?)';
                $row1 = $config->prepare($sql1);
                $row1->execute($data_jual);
                echo '<script>window.location="../../index.php?page=jual#keranjang"</script>';
            } else {
                echo '<script>alert("Stok Barang Anda Telah Habis !");
                        window.location="../../index.php?page=jual#keranjang"</script>';
            }
        }
    }

} else {
    // Jika tidak ada session admin, redirect ke login
    echo '<script>window.location="../../login.php";</script>';
}