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
						<h3 class="h4">國家資訊: TOP 15</h3>
						<div class="col-md-12">
							<div id="top_country_1day_29day_users"></div>
						</div>
						<div class="col-md-12">
							<div id="top_country_1day_8day_users"></div>
						</div>
						<div class="col-md-12">
							<div id="top_country_1day_2day_users"></div>
						</div>
						<hr class="col-md-10">
						<div class="col-md-12">
							<div id="top_country_1day_29day_new_users"></div>
						</div>
						<div class="col-md-12">
							<div id="top_country_1day_8day_new_users"></div>
						</div>
						<div class="col-md-12">
							<div id="top_country_1day_2day_new_users"></div>
						</div>
						<hr class="col-md-12">
						
						<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">活躍用戶成長變化: <span id="active_user_trend_filter">Loading...</span></button>
						<div id="active_user_trend_filter_list" class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						</div>
						<div class="col-md-12">
							<div id="active_user_trend"></div>
						</div>
					</div>
				</main>
			</div>
		</div>
		<script>
$(document).ready(function(){
	build_days();
	build_query_strings();

	build_top_country_report('top_country_1day_29day_users', '活躍用戶 28天: ' + days.the_29_days_before_today + ' ~ ' + days.the_1_days_before_today, ['國家', '人數', ' 變化'] , days.the_29_days_before_today, days.the_1_days_before_today, days.the_57_days_before_today, days.the_29_days_before_today, 'ga:users');
	build_top_country_report('top_country_1day_8day_users', '活躍用戶 7天: ' + days.the_8_days_before_today + ' ~ ' + days.the_1_days_before_today, ['國家', '人數', '變化'] , days.the_8_days_before_today, days.the_1_days_before_today, days.the_15_days_before_today, days.the_8_days_before_today, 'ga:users');
	build_top_country_report('top_country_1day_2day_users', '活躍用戶 昨天: ' + days.the_2_days_before_today + ' ~ ' + days.the_1_days_before_today, ['國家', '人數', '變化'] , days.the_2_days_before_today, days.the_1_days_before_today, days.the_3_days_before_today, days.the_2_days_before_today, 'ga:users');

	build_top_country_report('top_country_1day_29day_new_users', '新使用者 28天: ' + days.the_29_days_before_today + ' ~ ' + days.the_1_days_before_today, ['國家', '人數', ' 變化'] , days.the_29_days_before_today, days.the_1_days_before_today, days.the_57_days_before_today, days.the_29_days_before_today, 'ga:newUsers');
	build_top_country_report('top_country_1day_8day_new_users', '使用者者 7天: ' + days.the_8_days_before_today + ' ~ ' + days.the_1_days_before_today, ['國家', '人數', '變化'] , days.the_8_days_before_today, days.the_1_days_before_today, days.the_15_days_before_today, days.the_8_days_before_today, 'ga:newUsers');
	build_top_country_report('top_country_1day_2day_new_users', '新使用者 昨天: ' + days.the_2_days_before_today + ' ~ ' + days.the_1_days_before_today, ['國家', '人數', '變化'] , days.the_2_days_before_today, days.the_1_days_before_today, days.the_3_days_before_today, days.the_2_days_before_today, 'ga:newUsers');

	build_user_report('active_user_trend', '活躍用戶/新使用者 佔比 與 28 天前數據比較', ['日期', '活躍人數','新使用者', '佔比', '28天前活躍人數', '28天前新使用者'], null, days.the_29_days_before_today, days.the_1_days_before_today, days.the_57_days_before_today, days.the_29_days_before_today);

});
		</script>
	</body>
</html>

