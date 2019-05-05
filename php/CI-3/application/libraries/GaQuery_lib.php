<?php
// https://developers.google.com/apis-explorer/?hl=zh_TW#p/analyticsreporting/v4/
class GaQuery_lib {
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
			$this->client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
		$this->analytics = new Google_Service_Analytics($this->client);
	}

	public function check() {
		$output = array( 'status' => false, 'error' => 0, 'message' => NULL, 'data' => array());

		try {
			$output['data']['account'] = $this->analytics->management_accounts->listManagementAccounts();
			$output['status'] = true;
		} catch (Google_Service_Exception $e) {
			$output['message'] = $e->getErrors();
			$output['error'] = -1;
		}

		return $output;
	}

	public function getProperties() {
		$output = array( 'status' => false, 'error' => 0, 'message' => NULL, 'data' => array());
		$account_manager = NULL;
		try {
		 	$account_manager = $this->analytics->management_accounts->listManagementAccounts();
			$output['status'] = true;
		} catch (Google_Service_Exception $e) {
			$output['message'] = $e->getErrors();
			$output['error'] = -1;
		}

		if ($output['status']) {
			$output['status'] = false;
			$output['data']['account'] = array();
			$output['data']['profile'] = array();
			try {
				foreach ($account_manager->getItems() as $account) {
					array_push($output['data']['account'], array(
						'id' => $account->getId(),
						'name' => $account->getName(),
					));
					$account_id = $account->getId();
					$output['data']['profile'][$account_id] = array(
						'name' => $account->getName(), 'properties' => array()
					);
					$properties = $this->analytics->management_webproperties->listManagementWebproperties($account_id);
					foreach($properties->getItems() as $property) {
						$profiles = array();
						$profiles_manager = $this->analytics->management_profiles->listManagementProfiles($account_id, $property->getId());
						foreach($profiles_manager->getItems() as $profile) {
							array_push($profiles, array(
								'name' => $profile->getName(),
								'id' => $profile->getId(),
							));
						}
						array_push($output['data']['profile'][$account_id]['properties'], array(
							'name' => $property->getName(),
							'id' => $property->getId(),
							'profiles' => $profiles,
						));
					}
				}
				$output['status'] = true;
			} catch (Google_Service_Exception $e) {
				$output['message'] = $e->getErrors();
				$output['error'] = 1;
			}

		}

		return $output;

	}

	public function query($analyticsViewId, $startDate, $endDate, $metrics, $params = array()) {
		$output = array('status' => false, 'data' => NULL, 'query' => array(
			'analyticsViewId' => $analyticsViewId,
			'startDate' => $startDate,
			'endDate' => $endDate,
			'metrics' => $metrics,
			'params' => $params,
		));
		try {
			$query = $this->analytics->data_ga->get(
				$analyticsViewId,
				$startDate,
				$endDate,
				$metrics,
				$params
			);
			$output['data'] = $query->getRows();
			$output['fields'] = array();
			if (isset($params['dimensions'])) {
				foreach(explode(",", $params['dimensions']) as $field)
					array_push($output['fields'], $field);
			}
			if (!empty($metrics)) {
				foreach(explode(",", $metrics) as $field)
					array_push($output['fields'], $field);
			}
			$output['status'] = true;
		} catch (Exception $e) {
			$output['error'] = $e;
		}
		return $output;
	}
}
