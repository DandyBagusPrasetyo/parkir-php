<?php

$konek = mysqli_connect("localhost", "root", "", "parkir");
if ($konek->connect_error) {
    die("Connection failed: " . $konek->connect_error);
}
