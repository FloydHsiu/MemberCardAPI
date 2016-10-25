<?php

session_start();
echo json_encode(array('valid'=> $_SESSION['valid']));

?>