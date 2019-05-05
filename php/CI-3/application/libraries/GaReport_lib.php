<?php

class GaReport_lib {
	public function get_client_ip() {
		$client  = @$_SERVER['HTTP_CLIENT_IP'];
		if(filter_var($client, FILTER_VALIDATE_IP))
			return $client;
		$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		if(filter_var($forward, FILTER_VALIDATE_IP))
			return $forward;
		return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
	}

	public function page_report($ga_project_code, $document_path, $user_id) {
		@file_get_contents('https://www.google-analytics.com/collect?'.http_build_query(array(
			'v' => 1,
			't' => 'pageview',
			'dh' => isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : NULL,
			'dp' => $document_path,
			'ua' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : NULL,
			'uip' => $this->get_client_ip(),
			'tid' => $ga_project_code, 
			'cid' => $user_id,
		)));
	}

	public function event_report($ga_project_code, $user_id, $event_category, $event_action, $event_label = NULL, $event_value = NULL) {
		$params = array(
			'v' => 1,
			'dh' => isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : NULL,
			'ua' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : NULL,
			'uip' => $this->get_client_ip(),
			'tid' => $ga_project_code, 
			'cid' => $user_id,

			't' => 'event',
			'ec' => $event_category,
			'ea' => $event_action,
		);
		if (!empty($event_label))
			$params['el'] = $event_label;
		if (!empty($event_value) && is_int($event_value))
			$params['ev'] = $event_value;
		@file_get_contents('https://www.google-analytics.com/collect?'.http_build_query($params));
	} 

	public function uuid_v4() {
		// https://stackoverflow.com/questions/2040240/php-function-to-generate-v4-uuid
		$data = NULL;
		if (function_exists('random_bytes'))
			$data = random_bytes(16);
		else if (function_exists('openssl_random_pseudo_bytes'))
			$data = openssl_random_pseudo_bytes(16);
		else
			$data = md5(getmypid().'-'.microtime(), true);
		if (strlen($data) == 16 && function_exists('bin2hex')) {
			$data[6] = chr(ord($data[6]) & 0x0f | 0x40); //set version to 0100
			$data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
			return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
		}
		return NULL;
	}
}
