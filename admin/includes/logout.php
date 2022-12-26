<?php

// single import
require_once('header.php');
?>


<?php
$session->logout();
redirect("../login.php")
?>