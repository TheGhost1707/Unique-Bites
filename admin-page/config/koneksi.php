<?php
$koneksi = new mysqli("localhost", "root", "", "uniquebites_kasir");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
session_start();
