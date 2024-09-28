<?php

if (isset($success_msgs)) {

    echo '<script> swal( "' . $success_msgs . '" , "" , "success" )  </script>';
}


if (isset($warning_msgs)) {

    echo '<script> swal( "' . $warning_msgs . '" , "" , "warning" )  </script>';
}

if (isset($error_msgs)) {

    echo '<script> swal( "' . $error_msgs . '" , "" , "error" )  </script>';
}


if (isset($info_msgs)) {

    echo '<script> swal( "' . $info_msgs . '" , "" , "info" )  </script>';
}
