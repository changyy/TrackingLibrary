<?php
// https://developers.google.com/apis-explorer/?hl=zh_TW#p/analyticsreporting/v4/
class AdsenseQuery_lib {
	public function __construct($params = array()) {
		if (isset($params['vendor/autoload.php']))
			require_once($params['vendor/autoload.php']);
		// https://developers.google.com/analytics/devguides/reporting/core/v3/quickstart/service-php
		// https://developers.google.com/analytics/devguides/config/mgmt/v3/mgmtReference/
		$this->client = new Google_Client();
		if (isset($params['AuthConfig']))
			$this->client->setAuthConfig($params['AuthConfig']);
		if (isset($params['access_token']))
			$this->client->setAccessToken($params['access_token']);
		if (isset($params['Scopes']))
			$this->client->setScopes($params['Scopes']);
		else
			$this->client->setScopes(['https://www.googleapis.com/auth/adsense.readonly']);
		$this->service = new Google_Service_AdSense($this->client);
	}

	public function check() {
		$output = array( 'status' => false, 'error' => 0, 'message' => NULL, 'data' => array());

		try {
			$output['data']['account'] = $this->service->accounts->listAccounts();
			$output['data']['adclient'] = $this->service->adclients->listAdclients();
			$output['status'] = true;
		} catch (Google_Service_Exception $e) {
			$output['message'] = $e->getErrors();
			$output['error'] = -1;
		}

		return $output;
	}

	public function getAdUnit($adClientId) {
		$output = array( 'status' => false, 'error' => 0, 'message' => NULL, 'data' => array());
		$adunits = NULL;
		try {
		 	$adunits = $this->service->adunits->listAdunits($adClientId);
			$output['status'] = true;
		} catch (Google_Service_Exception $e) {
			$output['message'] = $e->getErrors();
			$output['error'] = -1;
		}

		if ($output['status']) {
			$output['status'] = false;
			$output['data']['adunit'] = array();
			try {
				foreach ($adunits->getItems() as $item) {
					array_push($output['data']['adunit'], array(
						'id' => $item->getId(),
						'code' => $item->getCode(),
						'name' => $item->getName(),
						'kind' => $item->getKind(),
					));
				}
				$output['status'] = true;
			} catch (Google_Service_Exception $e) {
				$output['message'] = $e->getErrors();
				$output['error'] = 1;
			}
		}

		return $output;
	}
	// https://developers.google.com/adsense/management/v1.4/reference/accounts/reports/generate
	// 	Date pattern: /\d{4}-\d{2}-\d{2}|(today|startOfMonth|startOfYear)(([\-\+]\d+[dwmy]){0,3}?)|(latest-(\d{2})-(\d{2})(-\d+y)?)|(latest-latest-(\d{2})(-\d+m)?)/
	//		- startOfMonth-3m
	public function getReport($startDate = "startOfMonth", $endDate = "today" , $params = array()) {
		$output = array('status' => false, 'data' => NULL, 'query' => array(
			'startDate' => $startDate,
			'endDate' => $endDate,
		));
		if (!is_array($params) || count($params) == 0) {
			$params = array(
				// https://developers.google.com/adsense/management/metrics-dimensions
				// https://developers.google.com/adsense/management/reporting/filtering?hl=zh-tw
				//'filter' => [
				//	'COUNTRY_CODE==TW,COUNTRY_CODE==JP,COUNTRY_CODE==US',
				//],
			);
		} 
		if (!isset($params['dimension']))
			$params['dimension'] = 'DATE';
		if (!isset($params['currency']))
			$params['currency'] = 'USD';
		if (!isset($params['useTimezoneReporting']))
			$params['useTimezoneReporting'] = true;
		if (!isset($params['metric']))
			$params['metric'] = array(
				'AD_REQUESTS',
				'AD_REQUESTS_COVERAGE',
				'AD_REQUESTS_CTR',
				'CLICKS',
				'COST_PER_CLICK',
				'AD_REQUESTS_RPM',
				'EARNINGS',
				'TOTAL_EARNINGS',
			);

		try {
			$query = $this->service->reports->generate(
				$startDate,
				$endDate,
				$params
			);
			$output['data'] = $query->getRows();
			$output['fields'] = array();

			if (isset($params['dimension'])) {
				$output['fields'] = array_merge($output['fields'], $params['dimension']);
			}
			if (isset($params['metric'])) {
				$output['fields'] = array_merge($output['fields'], $params['metric']);
			}

			$output['status'] = true;
		} catch (Exception $e) {
			$output['error'] = $e;
		}
		return $output;
	}
}
