<?php

/**
 * Open State PHP Class to interact with the Open State Project
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

class Openstate
{

	// An API key can be obtained at http://services.sunlightlabs.com/
	public $api_key = '';
	public $api_url = 'http://openstates.org/api/v1';
	public $decode;
	
	public function __construct()
	{
		$this->curl = new Curl();
	}
	
	/**
	 * State Information Metadata
	 */
	public function getStateInfo($state=NULL)
	{
		$url = $this->api_url.'/metadata/';
		if ($state) {
			$url .= $state.'/';
		}
		$params['apikey'] = $this->api_key;
		$query = $this->curl->get($url, $params);
		return $this->parseQuery($query);
	}
	
	/**
	 * Search Bills by term and state (optional)
	 */
	public function searchBills($search_term, $state=NULL)
	{
		if ($search_term) {
			$url = $this->api_url.'/bills/';
			$params['apikey'] = $this->api_key;
			$params['q'] = $search_term;
			if (!empty($state)) {
				$params['state'] = $state;
			}
			$query = $this->curl->get($url, $params);
			return $this->parseQuery($query);
		}
		return FALSE;
	}
	
	/**
	 * Bill lookup
	 */
	public function billLookup($bill_id, $state, $session, $chamber=NULL)
	{
		if ($bill_id) {
			$bill_id = rawurlencode($bill_id);
			$url = $this->api_url.'/bills/'.$state.'/'.$session.'/';
			if ($chamber) {
				$url .= $chamber.'/';
			}
			$url .= $bill_id.'/';
			$params['apikey'] = $this->api_key;
			$query = $this->curl->get($url, $params);
			return $this->parseQuery($query);
		}
		return FALSE;
	}
	
	/**
	 * Search events by state or type
	 */
	public function searchEvents($state=NULL, $type=NULL)
	{
		$url = $this->api_url.'/events/';
		if ($state) {
			$params['state'] = $state;
		}
		if ($type) {
			$params['type'] = $type;
		}
		$params['apikey'] = $this->api_key;
		$query = $this->curl->get($url, $params);
		return $this->parseQuery($query);
	}
	
	/**
	 * District lookup
	 */
	public function districtLookup($state, $chamber=NULL)
	{
		if ($state) {
			$url = $this->api_url.'/districts/'.$state.'/';
			if ($chamber) {
				$url .= $chamber.'/';
			}
			$params['apikey'] = $this->api_key;
			$query = $this->curl->get($url, $params);
			return $this->parseQuery($query);
		}
		return FALSE;
	}
	
	/**
	 * District Boundary Lookup
	 */
	public function districtBoundaryLookup($boundary_id)
	{
		if ($boundary_id) {
			$url = $this->api_url.'/districts/boundary/'.$boundary_id.'/';
			$params['apikey'] = $this->api_key;
			$query = $this->curl->get($url, $params);
			return $this->parseQuery($query);
		}
		return FALSE;
	}
	
	/**
	 * Parse query from json to an array
	 */
	private function parseQuery($query, $decode=NULL)
	{
		$decode = isset($decode) ? $decode : $this->decode;
		switch ($query) {
			case 'Not Found':
				$query = FALSE;
				break;
			default:
				if ($decode) {
					$query = json_decode($query);
				}
				break;
		}
		return !empty($query) ? $query : FALSE;
	}

}