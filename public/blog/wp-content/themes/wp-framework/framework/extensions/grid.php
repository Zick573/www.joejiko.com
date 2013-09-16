<?php
// This file is deprecated. Please use ryogs.php instead

// You'll need to pass a 'ver' parameter with the following structure: ?ver=22-20-20
if ( !$_GET || !isset($_GET['ver']) ) die();

header( 'Location: ryogs.php?ver=' . $_GET['ver'] );