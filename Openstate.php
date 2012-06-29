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
	public $api_key = '3c974967e9e544cfb7838844f529d208';
	public $api_url = 'http://openstates.org/api/v1';
	public $decode;
	
	public function __construct($api_key = null)
	{
		if(!empty($api_key))
		{
			$this->api_key = $api_key;
		}
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
	 * Bill search
	 * 
	 * @param mixed $params Bill search parameters. Expecting associative array with the following possible keys:
	 * 		q - the keyword string to lookup (If set, state and session are required)
	 * 		state - filter results by given state (two-letter abbreviation)
	 * 		search_window - a string representing what time period to search across. Pass 'session' to search bills from the state's current or most recent legislative session, 'term' to search the current or most recent term, 'all' to search as far back as Open States has data for, or supply 'session:SESSION_NAME' or 'term:TERM_NAME' (e.g. 'session:2009' or 'term:2009-2010') to search a specific session or term.
	 * 		chamber - filter results by given chamber ('upper' or 'lower')
	 * 		bill_id__in - return all bills with ids in a set of bill ids that are pipe | separated (ex. HB 1|HB 2|SB 10)
	 * 		updated_since - only return bills that have been updated since a given date, YYYY-MM-DD format
	 * 		subject - filter by bills that are about a given subject. If multiple subject parameters are supplied then only bills that match all of them will be returned. See list of subjects at http://openstates.org/categorization/#subjects
	 * 		sponsor_id - only return bills sponsored by the legislator with the given id (corresponds to leg_id)
	 */
	public function billSearch($params)
	{
		if ($params) {
			if(array_key_exists('q', $params) && 
			   (!array_key_exists('state', $params) && !array_key_exists('session', $params)))
			{
				return FALSE;
			}
			$bill_id = rawurlencode($bill_id);
			$url = $this->api_url.'/bills/';
			$params['apikey'] = $this->api_key;
			var_dump($params);
			$query = $this->curl->get($url, $params);
			return $this->parseQuery($query);
		}
		return FALSE;
	}
	
	/* 
	 * Committee Lookup
	 * 
	 * @param string $params Committee search parameters. Expecting associative array with the following possible keys:
	 * 		committee - name of a committee
	 * 		subcommittee - name of a subcommittee
	 * 		chamber - filter results by given chamber (upper, lower or joint)
	 * 		state - return committees for a given state (eg. ny)
	 */
	public function committeeSearch($params)
	{
		if ($params) {
			$url = $this->api_url.'/committees/';
			$params['apikey'] = $this->api_key;
			$query = $this->curl->get($url, $params);
			return $this->parseQuery($query);
		}
		return FALSE;
	}
	
	/*
	 * Committee search
	*
	* @param mixed $params Committee's Open States ID.
	*
	*/
	public function committeeLookup($committee_id)
	{
		if ($committee_id) {
			$url = $this->api_url.'/committees/'.$committee_id.'/';
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
	
	/**
	 * Legislator lookup
	 * 
	 * @param string $leg_id Legislator Open State ID
	 */
	public function legislatorLookup($leg_id)
	{
		if ($leg_id) {
			$url = $this->api_url.'/legislators/'.$leg_id.'/';
			$params['apikey'] = $this->api_key;
			$query = $this->curl->get($url, $params);
			return $this->parseQuery($query);
		}
		return FALSE;
	}

	/**
	 * Legislator Search
	 *
	 * @param mixed $params Legislator search parameters. Expecting associative array with the following possible keys:
	 * 		state - Filter by state served in (two-letter state abbreviation)
	 * 		first_name - Filter by name
	 * 		last_name - Filter by name
	 * 		chamber - Filter by legislator's chamber, i.e. 'upper' or 'lower'.
	 * 		active - Restrict the search to currently-active legislators (the default) - 'true' or 'false'.
	 * 		term - Filter by legislators who served during a certain term.
	 * 		district - Filter by legislative district.
	 * 		party - Filter by the legislator's party, e.g. 'Democratic' or 'Republican'.
	 * 		active - Boolean
	 */
	public function legislatorSearch($params)
	{
		if ($params) {
			$url = $this->api_url.'/legislators/';
			$params['apikey'] = $this->api_key;
			var_dump($params);
			$query = $this->curl->get($url, $params);
			return $this->parseQuery($query);
		}
		return FALSE;
	}
	
	/**
	 * Legislator Geo lookup
	 *
	 * @param string $lat Latitude of point to use for district lookup
	 * @param string $long Longitude of point to use for district lookup
	 */
	public function legislatorGeoLookup($lat, $long)
	{
		if ($lat && $long) {
			$url = $this->api_url.'/legislators/geo/';
			$params['apikey'] = $this->api_key;
			$params['lat'] = $lat;
			$params['long'] = $long;
			$query = $this->curl->get($url, $params);
			return $this->parseQuery($query);
		}
		return FALSE;
	}
}