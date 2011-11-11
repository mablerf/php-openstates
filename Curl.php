<?php

/**
 * PHP Curl Class to interact with the Open State Project
 * Author: Nikki Snow
 * Version: 0.01
 * http://nikkisnow.com/code/php-openstate
 * License: http://opensource.org/licenses/gpl-3.0.html
 *
 * The Open State Project provides data on state legislative activities,
 * including bill summaries, votes, sponsorships and state legislator
 * information.
 *
 */ 

class Curl 
{
	  
	public function get( $url, $params=NULL ) {
		if( $params ) {
			$param = '';
			foreach( $params as $key => $value ) {
				$value = preg_replace( '/ /', '+', $value );
				$param .= $key.'='.$value.'&';
			}
			$url = $url.'?'.substr($param, 0, -1);
		}
		$ch = $this->open();
		curl_setopt($ch, CURLOPT_URL, $url );
		$contents = curl_exec( $ch ); 
		$this->close( $ch );
		return $contents;
	}
   
	private function open() {
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_HEADER, 0 );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		return $ch;
	} 
 
	private function close( $ch ) {
		return curl_close( $ch ) ? TRUE : FALSE;  
	}
   
}

?>
