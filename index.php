<?php
include("functions/data-base.php");

if ( $isactivedb == false ) {
 echo "Le site n'est pas encore installé";
} else {
echo "Oui";
}

?>