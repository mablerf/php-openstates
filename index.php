<?php

require_once('Curl.php');
require_once('Openstate.php');

$os = new Openstate();

echo $os->getStateInfo('me');

//echo $os->legislatorGeoLookup('35.81336', '-78.76648');

//echo $os->legislatorLookup('MDL000210');

// $leg_search = array(
// 		'state' => 'ca',
// 		'party' => 'democratic',
// 		'first_name' => 'Bob',
// 		'active' => 'true'
// 		);

// echo $os->legislatorSearch($leg_search);

// $bill_search = array(
// 		'q' => 'agriculture',
// 		'state' => 'vt',
// 		'chamber' => 'upper' 
// 		);
// echo $os->billSearch($bill_search);

// echo $os->committeeLookup('MDC000065');

// $committee_search = array(
// 		'state' => 'md', 
// 		'chamber' => 'upper'
// 		);

// echo $os->committeeSearch($committee_search);

// $event_search = array(
// 		'state' => 'tx',
// 		'type' => 'committee:meeting'
// 		);
// echo $os->eventSearch($event_search);

//echo $os->eventLookup('TXE00004925');

?>