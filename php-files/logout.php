<?php

include 'config.php';

session_start();
session_unset();
session_destroy();

// mysqli_close($conn);
header("location:{$baseurl}");
