<?php require '_header.php';	?>
	<body>
		<nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
			<a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Tracking</a>
<!--
			<input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">
			<ul class="navbar-nav px-3">
				<li class="nav-item text-nowrap">
					<a class="nav-link" href="#">Sign out</a>
				</li>
			</ul>
-->
		</nav>
		<div class="container-fluid">
			<div class="row">
<?php	require '_project_list.php';	?>
				<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
					<div class="row">
						<div class="col-12 progress hide" id="progress_status">
							<div id="progress_value" class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
						<div class="col-12" id="tree"></div>
					</div>
				</main>
			</div>
		</div>
		<script>
$(document).ready(function(){
	update_progress_status(0);
	build_days();
	update_progress_status(5);
	build_query_strings();
	update_progress_status(10);
	// Query anaytics
	// Query adsense
	//build_analytics_adsense_pairing(
	//	window.days.the_1_days_before_today, window.days.today,
	//	window.days.the_2_days_before_today, window.days.the_1_days_before_today,
	//	//window.days.the_3_days_before_today, window.days.the_2_days_before_today
	//);

	$.when(
		build_analytics_adsense_pairing(
			window.days.the_2_days_before_today, window.days.the_1_days_before_today,
			window.days.the_4_days_before_today, window.days.the_3_days_before_today,
			'1-day',
			function() {
				update_progress_status(40);
			}
		),
		build_analytics_adsense_pairing(
			window.days.the_7_days_before_today, window.days.the_1_days_before_today,
			window.days.the_14_days_before_today, window.days.the_8_days_before_today,
			'7-day',
			function() {
				update_progress_status(70);
			}

		),
		build_analytics_adsense_pairing(
			window.days.the_28_days_before_today, window.days.the_1_days_before_today,
			window.days.the_56_days_before_today, window.days.the_29_days_before_today,
			'28-day',
			function() {
				update_progress_status(95);
			}
		)
	).done(function(ret1, ret2) {
		// calc adsense
		//calc_project_data_adsense(window.project_info, '');
		// 
		build_treeview_data();
		update_progress_status(100);
	});
});
		</script>
	</body>
</html>

