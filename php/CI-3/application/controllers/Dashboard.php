<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->cache_expired_time = 6000; // 100 min

		$this->client_id = 'YOUR-Project.apps.googleusercontent.com';
		//$this->ga_profile_id = 'ga:your-ga-view';
		$this->ga_profile_id = array(
			'default' => 'ga:your-ga-view'
		);

		$this->project = array();
		$this->google_api_usage = array();
		$this->ga_adsense_rule = array();
		$path = FCPATH . '..' . DIRECTORY_SEPARATOR . 'ga_adsense_rule.json';
		if (file_exists(FCPATH . '..' . DIRECTORY_SEPARATOR . 'dev.json'))
			$path = FCPATH . '..' . DIRECTORY_SEPARATOR . 'dev.json';

		if (file_exists($path)) {
			$json = @json_decode(@file_get_contents($path), true);
			if (isset($json['dashboard']) && is_array($json['dashboard'])) {
				// reset
				$this->client_id = NULL;
				$this->ga_profile_id = array(
					'default' => NULL,
				);
				foreach($json['dashboard'] as $project_info) {
					if (!isset($project_info['id']))
						continue;
					array_push($this->project, $project_info['id']);
					$this->google_api_usage[$project_info['id']] = NULL;
					if (isset($project_info['project'])) {
						if (isset($project_info['project']['google_api_project'])) {
							$this->google_api_usage[$project_info['id']] = $project_info['project']['google_api_project'];
							if (empty($this->client_id))
								$this->client_id = $project_info['project']['google_api_project'];
						}
						if (isset($project_info['project']['analytics']) && isset($project_info['project']['analytics']['ga_profile_id'])) {

							if (!is_array($project_info['project']['analytics']['ga_profile_id'])) 
								$project_info['project']['analytics']['ga_profile_id'] = array( $project_info['project']['analytics']['ga_profile_id'] );
							foreach( $project_info['project']['analytics']['ga_profile_id'] as $ga_profile_id) {
								if (empty($this->ga_profile_id['default']))
									$this->ga_profile_id['default'] = $ga_profile_id;
								if (!isset($this->ga_profile_id[$project_info['id']]))
									$this->ga_profile_id[$project_info['id']] = array();
								if (!in_array($ga_profile_id, $this->ga_profile_id[$project_info['id']]))
									array_push($this->ga_profile_id[$project_info['id']], $ga_profile_id);
							}

							//if (empty($this->ga_profile_id))
							//	$this->ga_profile_id = $project_info['project']['analytics']['ga_profile_id'];
						}
					}
					if (isset($project_info['rule'])) {
						if (isset($project_info['rule']['analytics']) && isset($project_info['rule']['adsense']) ) {
							$this->ga_adsense_rule[$project_info['id']] = $project_info['rule'];
						}
					}
				}
			}
		}
	}

	public function query_adsense() {
		$access_token = $this->input->cookie('ga_access_token');
		$project_id = $this->input->get_post('project');
		$project_id = str_replace('+', ' ', $project_id);
		if (!empty($access_token)) {
			$this->load->library('AdsenseQuery_lib', array(
				'vendor/autoload.php' => 'vendor/autoload.php',
				'access_token' => $access_token,
			));
		} 

		$adsense_filter = array();
		if (
			isset($this->ga_adsense_rule[$project_id])
			&& isset($this->ga_adsense_rule[$project_id]['adsense'])
			&& isset($this->ga_adsense_rule[$project_id]['adsense']['filter'])
		) {
			if (is_array($this->ga_adsense_rule[$project_id]['adsense']['filter'])) {
				foreach($this->ga_adsense_rule[$project_id]['adsense']['filter'] as $key => $value) {
					if (!empty($value) && !empty($key)) {
						array_push($adsense_filter, "$key=@$value");
					}
				}
			}
		}

		$output = array( 'status' => false);
		if (
			NULL != $this->input->get_post('date_start')
			&& NULL != $this->input->get_post('date_end')
		) {
			$options = array(
				'dimension' => array(
					'DATE',
					'AD_UNIT_CODE',
					'AD_UNIT_NAME',
				),
				'metric' => array(
					'AD_REQUESTS',
					'AD_REQUESTS_COVERAGE',
					'AD_REQUESTS_CTR',
					'CLICKS',
					'COST_PER_CLICK',
					'AD_REQUESTS_RPM',
					'EARNINGS',
				),
				'filter' => $adsense_filter,
			);
			foreach( array('metric','dimension', 'sort', 'max-results', 'filters') as $field)
				if (NULL != $this->input->get_post($field))
					$options[$field] = $this->input->get_post($field);

			$date_begin = $this->input->get_post('date_start');
			$date_end = $this->input->get_post('date_end');

			if (preg_match('/([0-9]{4})([0-9]{2})([0-9]{2})/', $date_begin, $match)) {
				$date_begin = $match[1]."-".$match[2]."-".$match[3];
			}
			if (preg_match('/([0-9]{4})([0-9]{2})([0-9]{2})/', $date_end, $match)) {
				$date_end = $match[1]."-".$match[2]."-".$match[3];
			}

			$cache_key = 'query_adsense_'.$date_begin.'_'.$date_end.'_'.md5(json_encode($options));
			$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
			$cache_response = $this->cache->get($cache_key);
			if (!empty($cache_response)) {
				$output = json_decode($cache_response, true);
			} else {
				$output = $this->adsensequery_lib->getReport(
					$date_begin,
					$date_end,
					$options
				);
				$this->cache->save($cache_key, json_encode($output), $this->cache_expired_time);
			}
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($output));
	}

	public function query_analytics() {
		$access_token = $this->input->cookie('ga_access_token');
		$project_id = $this->input->get_post('project');
		$project_id = str_replace('+', ' ', $project_id);
		if (!empty($access_token)) {
			$this->load->library('GaQuery_lib', array(
				'vendor/autoload.php' => 'vendor/autoload.php',
				'access_token' => $access_token,
			));
		} 
		$ga_filter = array();
		if (
			isset($this->ga_adsense_rule[$project_id])
			&& isset($this->ga_adsense_rule[$project_id]['analytics'])
			&& isset($this->ga_adsense_rule[$project_id]['analytics']['filter'])
		) {
			if (is_array($this->ga_adsense_rule[$project_id]['analytics']['filter'])) {
				foreach($this->ga_adsense_rule[$project_id]['analytics']['filter'] as $key => $value) {
					if (!empty($key) && !strncmp('ga:', $key, 3)) {
						if (!empty($value)) {
							array_push($ga_filter, "$key==$value");
						}
					} else if (!empty($value)) {
						 array_push($ga_filter, $value);
					}
				}
			}
		}

		$output = array( 'status' => false);
		if (
			NULL != $this->input->get_post('date_start')
			&& NULL != $this->input->get_post('date_end')
			&& NULL != $this->input->get_post('metrics')
		) {
			$options = array();
			foreach( array('dimensions', 'sort', 'max-results', 'filters') as $field)
				if (NULL != $this->input->get_post($field))
					$options[$field] = $this->input->get_post($field);

			$output['status'] = 'pass';

			$metrics = $this->input->get_post('metrics');
			if (is_array($metrics))
				$metrics = implode(",", $metrics);

			if (isset($options['dimensions'])) {
				if (is_array($options['dimensions']))
					$options['dimensions'] = implode(",", $options['dimensions']);
			}
			if (isset($options['filters']) && !empty($options['filters'])) {
				if (is_array($options['filters']))
					$ga_filter = array_merge($ga_filter, $options['filters']);
				else
					array_push($ga_filter, $options['filters']);
			}
			if (count($ga_filter) > 0)
				$options['filters'] = implode(',', $ga_filter);
			$ga_profile_tag = 'default';
			if (isset($this->ga_profile_id[$project_id]))
				$ga_profile_tag = $project_id;

			if (is_array($this->ga_profile_id[$ga_profile_tag])) {
				foreach($this->ga_profile_id[$ga_profile_tag] as $ga_profile_id) {
					$api_response = NULL;
					$cache_key = 'query_analytics_'.$ga_profile_id.'_'.$this->input->get_post('date_start').'_'.$this->input->get_post('date_end').'_'.md5(json_encode($options).'-'.json_encode($metrics));
					$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
					$cache_response = $this->cache->get($cache_key);
					if (!empty($cache_response)) {
						$api_response = json_decode($cache_response, true);
					} else {
						$api_response = $this->gaquery_lib->query(
							$ga_profile_id,
							$this->input->get_post('date_start'),
							$this->input->get_post('date_end'),
							$metrics,
							$options
						);
						$this->cache->save($cache_key, json_encode($api_response), $this->cache_expired_time);
					}
					if ($api_response !== NULL) {
						if (!strcmp($output['status'], 'pass')) {
							$output = $api_response;
						} else if ($api_response['status']) {
							$output['data'] = array_merge($output['data'], $api_response['data']);
						}
					}
				}
			}
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($output));
	}

	public function index() {
		$access_token = $this->input->cookie('ga_access_token');
		if (!empty($access_token)) {
			$this->load->library('GaQuery_lib', array(
				'vendor/autoload.php' => 'vendor/autoload.php',
				'access_token' => $access_token,
			));
		} else {
			$this->load->library('GaQuery_lib', array(
				'vendor/autoload.php' => 'vendor/autoload.php',
			));
		}
		$ret = $this->gaquery_lib->check();
		if ($ret['status'] == false) {
			$this->load->view('js_call_oauth', array(
				'client_id' => $this->client_id,
				'callback_url' => 'location.href',
			));
			return;
		} 
		//echo "<pre>";
		//print_r($ret);
		//echo "</pre>";

		//$ret = $this->gaquery_lib->getProperties();
		//echo "<pre>";
		//print_r($ret);
		//echo "</pre>";
		//return;

		$this->_default_web();
	}

	function _default_web() {
		$data = array( 
			'project_list' => $this->project,
			'rules' => $this->ga_adsense_rule,
			'selected' => NULL,
			'debug' => array(),
		);
		$selected_project = $this->input->get('project');
		$view_base_dir = 'dashboard';
		$view = 'default';
		if (!empty($selected_project) && in_array($selected_project, $this->project)) {
			$data['selected'] = $selected_project;
			$check_view = str_replace(array(' ', '+'), '', basename(strtolower($selected_project)));
			$check_view_path = APPPATH.'views/'.$view_base_dir.'/'.$check_view.'.php';
			if (file_exists($check_view_path))
				$view = $check_view;
			$data['debug']['check_view'] = $check_view;
		}
		$data['debug']['selected_view'] = $view;
		$data['debug']['selected_project'] = $selected_project;
		$this->load->view($view_base_dir.'/'.$view, $data);
	}

	function _js_login_call($client_id, $after_location_url = 'location.href') {
		$this->load->view('js_call_oauth', array(
			'client_id' => $client_id,
			'callback_url' => $after_location_url,
		));
	}
}

