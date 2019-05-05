				<nav class="col-md-2 d-none d-md-block bg-light sidebar">
					<div class="sidebar-sticky">
<?php
	$active_target = NULL;
	if (isset($_SERVER['REQUEST_URI'])) {
		$url_info = parse_url($_SERVER['REQUEST_URI']);
		$param_info = false;
		if (false !== $url_info && isset($url_info['query'])) {
			parse_str($url_info['query'], $param_info);
		}
		if (false !== $param_info && isset($param_info['project']))
			$active_target = $param_info['project'];
	}
?>
						<div class="list-group">
							<a href="/dashboard" class="list-group-item list-group-item-action<?php if (empty($active_target)) echo " active" ;?>">Home</a>
<?php
	if (isset($project_list) && is_array($project_list)) {

		foreach($project_list as $info) {

?>
							<a href="/dashboard?<?php echo http_build_query( array('project' => $info ));?>" class="list-group-item list-group-item-action<?php if (!strcasecmp($active_target, $info)) echo " active"; ?>"><?php echo htmlspecialchars($info); ?></a>
<?php
		}
	}
?>
						</div>
					</div>
				</nav>
