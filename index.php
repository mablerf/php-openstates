<?php

require_once('Curl.php');
require_once('Openstate.php');

$os = new Openstate();

echo $os->getStateInfo('me');


?>