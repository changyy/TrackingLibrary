<html>
	<head>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
		<!--
		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		-->
		<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>

		<!-- https://github.com/jonmiles/bootstrap-treeview -->
<!--
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-treeview/1.2.0/bootstrap-treeview.min.css" integrity="sha256-ULjuiZ9iqqf97EETp/mZrnLusfOwISiI6AIL0IXShbc=" crossorigin="anonymous" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-treeview/1.2.0/bootstrap-treeview.min.js" integrity="sha256-rmZZb5ESAjCE4Al5RfENzQBpw1VbShzLes76aW8c+kc=" crossorigin="anonymous"></script>
-->
		<!-- https://github.com/vakata/jstree/ -->
		<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jstree/3.3.7/themes/default/style.min.css" />
		<script src="//cdnjs.cloudflare.com/ajax/libs/jstree/3.3.7/jstree.min.js"></script>

		<style>
body {
  font-size: .875rem;
}

.feather {
  width: 16px;
  height: 16px;
  vertical-align: text-bottom;
}

/*
 * Sidebar
 */

.sidebar {
  position: fixed;
  top: 0;
  bottom: 0;
  left: 0;
  z-index: 100; /* Behind the navbar */
  padding: 48px 0 0; /* Height of navbar */
  box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
}

.sidebar-sticky {
  position: relative;
  top: 0;
  height: calc(100vh - 48px);
  padding-top: .5rem;
  overflow-x: hidden;
  overflow-y: auto; /* Scrollable contents if viewport is shorter than content. */
}

@supports ((position: -webkit-sticky) or (position: sticky)) {
  .sidebar-sticky {
    position: -webkit-sticky;
    position: sticky;
  }
}

.sidebar .nav-link {
  font-weight: 500;
  color: #333;
}

.sidebar .nav-link .feather {
  margin-right: 4px;
  color: #999;
}

.sidebar .nav-link.active {
  color: #007bff;
}

.sidebar .nav-link:hover .feather,
.sidebar .nav-link.active .feather {
  color: inherit;
}

.sidebar-heading {
  font-size: .75rem;
  text-transform: uppercase;
}

/*
 * Content
 */

[role="main"] {
  padding-top: 48px; /* Space for fixed navbar */
}

/*
 * Navbar
 */

.navbar-brand {
  padding-top: .75rem;
  padding-bottom: .75rem;
  font-size: 1rem;
  background-color: rgba(0, 0, 0, .25);
  box-shadow: inset -1px 0 0 rgba(0, 0, 0, .25);
}

.navbar .form-control {
  padding: .75rem 1rem;
  border-width: 0;
  border-radius: 0;
}

.form-control-dark {
  color: #fff;
  background-color: rgba(255, 255, 255, .1);
  border-color: rgba(255, 255, 255, .1);
}

.form-control-dark:focus {
  border-color: transparent;
  box-shadow: 0 0 0 3px rgba(255, 255, 255, .25);
}
		</style>
		<script>
window.project_info = {};
window.project_data = {};
function update_progress_status(value) {
	if (value < 100) {
		$('#progress_status').show();
	} else {
		setTimeout(function(){ 
			$('#progress_status').hide();
		}, 1200);
	}
	$('#progress_value').css('width', value+'%').attr('aria-valuenow', value).html(value+'%');
}
function build_query_strings() {
	window.query_strings = {};
	var query = window.location.search.substring(1);
	var vars = query.split('&');
	for (var i = 0; i < vars.length; i++) {
		var pair = vars[i].split('=');
		window.query_strings[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1]);
	}
}
function getQueryDate(d) {
	return d.getUTCFullYear()+"-"+( (d.getUTCMonth() < 9 ? '0' : '') + (d.getUTCMonth()+1))+"-"+( (d.getUTCDate() < 10 ? '0' : '') + d.getUTCDate() ) ;
}

function build_days() {
	var today_object = new Date();
	var firstDayOfYear = new Date(today_object.getFullYear(), 0, 1);
	var pastDaysOfYear = (today_object - firstDayOfYear) / 86400000;
	
	window.days = {
		year: today_object.getFullYear(),
		weekNumber: Math.ceil((pastDaysOfYear + firstDayOfYear.getDay() + 1) / 7),

		today: getQueryDate(new Date()),
		yesterday: getQueryDate( new Date(new Date().setDate(today_object.getDate()-1)) ),

		the_1_days_before_today: getQueryDate( new Date(new Date().setDate(today_object.getDate()-1)) ),
		the_2_days_before_today: getQueryDate( new Date(new Date().setDate(today_object.getDate()-2)) ),
		the_3_days_before_today: getQueryDate( new Date(new Date().setDate(today_object.getDate()-3)) ),
		the_4_days_before_today: getQueryDate( new Date(new Date().setDate(today_object.getDate()-3)) ),
		the_7_days_before_today: getQueryDate( new Date(new Date().setDate(today_object.getDate()-7)) ),
		the_8_days_before_today: getQueryDate( new Date(new Date().setDate(today_object.getDate()-8)) ),
		the_14_days_before_today: getQueryDate( new Date(new Date().setDate(today_object.getDate()-14)) ),
		the_15_days_before_today: getQueryDate( new Date(new Date().setDate(today_object.getDate()-15)) ),
		the_28_days_before_today: getQueryDate( new Date(new Date().setDate(today_object.getDate()-28)) ),
		the_29_days_before_today: getQueryDate( new Date(new Date().setDate(today_object.getDate()-29)) ),
		the_56_days_before_today: getQueryDate( new Date(new Date().setDate(today_object.getDate()-56)) ),
		the_57_days_before_today: getQueryDate( new Date(new Date().setDate(today_object.getDate()-57)) ),
	};

}
function return_ajax_ga_query(date_begin, date_end, ga_metrics, ga_dimensions, ga_sort, ga_filter) {
	var option = {
		date_start: date_begin,
		date_end: date_end,
		metrics: ga_metrics,
		dimensions: ga_dimensions,
		sort: ga_sort,
	};
	if (window.query_strings && window.query_strings["project"])
		option["project"] = window.query_strings["project"];
	if (ga_filter && ga_filter.length > 0)
		option.filters = ga_filter;
	return $.ajax({
		url: '/dashboard/query_analytics',
		data: option,
		dataType: 'json',
		cache: false,
	});
}
function return_ajax_adsense_query(date_begin, date_end, adsense_metrics, adsense_dimensions, adsense_sort, adsense_filter) {
	var option = {
		date_start: date_begin,
		date_end: date_end,
		metric: adsense_metrics,
		dimension: adsense_dimensions,
		sort: adsense_sort,
	};
	if (window.query_strings && window.query_strings["project"])
		option["project"] = window.query_strings["project"];
	if (adsense_filter && adsense_filter.length > 0)
		option.filters = adsense_filter;
	return $.ajax({
		url: '/dashboard/query_adsense',
		data: option,
		dataType: 'json',
		cache: false,
	});
}

function switch_build_user_report(country_code) {
	build_user_report('active_user_trend', '活躍用戶/新使用者 佔比 與 28 天前數據比較', ['日期', '活躍人數','新使用者', '佔比', '28天前活躍人數', '28天前新使用者'], country_code == '' ? null : country_code, days.the_29_days_before_today, days.the_1_days_before_today, days.the_57_days_before_today, days.the_29_days_before_today);
}
function build_user_report(div_id, title, field_name, country, date_begin, date_end, date_prev_begin, date_prev_end) {
	if ($('#active_user_trend_filter_list').val() == 0) {
		// get country_list
		$.when(
			return_ajax_ga_query(date_prev_begin, date_end, 'ga:users', 'ga:countryIsoCode', '-ga:users'),
		).done(function(ret) {
			if (ret['status']) {
				var obj = $('#active_user_trend_filter_list');
				obj.append( $( '<a class="dropdown-item" href="#" onclick="switch_build_user_report(\'\'); return false;">顯示全部</a>' ));
				obj.append( $( '<a class="dropdown-item" href="#" onclick="switch_build_user_report(\'\'); return false;">顯示全部</a>' ));
				for( var i=0, cnt = ret['data'].length ; i<cnt ; ++i) {
					obj.append( $( '<a class="dropdown-item" href="#" onclick="switch_build_user_report(\''+ret['data'][i][0]+'\');return false;">國家代碼: '+ret['data'][i][0]+', 活躍用戶: '+ret['data'][i][1]+'</a>' ) );
				}
			}
		});
	}
	$('#active_user_trend_filter').html("讀取中...");
	// get ga report
	$.when(
		return_ajax_ga_query(date_begin, date_end, 'ga:users', 'ga:date', 'ga:date', (country ? 'ga:countryIsoCode=='+country : '' )),
		return_ajax_ga_query(date_begin, date_end, 'ga:newUsers', 'ga:date', 'ga:date', (country ? 'ga:countryIsoCode=='+country : '' )),

		return_ajax_ga_query(date_prev_begin, date_prev_end, 'ga:users', 'ga:date', 'ga:date', (country ? 'ga:countryIsoCode=='+country : '' )),
		return_ajax_ga_query(date_prev_begin, date_prev_end, 'ga:newUsers', 'ga:date', 'ga:date', (country ? 'ga:countryIsoCode=='+country : '' )),
	).done(function(ret1, ret2, ret3, ret4) {
		if (ret1[0]['status'] && ret2[0]['status'] && ret1[0]['data'].length == ret2[0]['data'].length && ret1[0]['data'].length == ret3[0]['data'].length && ret1[0]['data'].length == ret4[0]['data'].length) {
			var output=[];
			for( var i=0, cnt=ret1[0]['data'].length ; i<cnt ; i++ ) {
				var d = ret1[0]['data'][i][0];
				d = d.slice(0,4)+'-'+d.slice(4,6)+'-'+d.slice(6,8);

				var au = parseInt(ret1[0]['data'][i][1]);
				var nu = parseInt(ret2[0]['data'][i][1]);

				var prev_au = parseInt(ret3[0]['data'][i][1]);
				var prev_nu = parseInt(ret4[0]['data'][i][1]);

				output.push( [ 
					new Date(d),
					au,
					((au-prev_au)/prev_au).toFixed(2).replace('0.0','').replace('0.','')+'%',
					nu,
					(nu/au).toFixed(2).replace('0.0','').replace('0.','')+'%',
					prev_au,
					prev_nu,
				] );
			}
			google.charts.load("current", {packages:['corechart', 'line']});
			google.charts.setOnLoadCallback(function(){
				var data = new google.visualization.DataTable();
				data.addColumn('date', field_name[0] );
				data.addColumn('number', field_name[1] );
				data.addColumn( {type: 'string', role: 'annotation'} );
				data.addColumn('number', field_name[2] );
				data.addColumn( {type: 'string', role: 'annotation'} );
				data.addColumn('number', field_name[4] );
				data.addColumn('number', field_name[5] );
				data.addRows(output);
				var view = new google.visualization.DataView( data );
				var obj = document.getElementById(div_id);
				var options = {
					title: title,
					height: 600,
				};
				var chart = new google.visualization.LineChart(obj);
				chart.draw(view, options);
			});

			$('#active_user_trend_filter').html(country ? 'CountryCode=' + country : '全世界');
		}
	});
}

function build_top_country_report(div_id, title, field_name, date_begin, date_end, date_prev_begin, date_prev_end, ga_target) {
	$.when(
		return_ajax_ga_query(date_begin, date_end, ga_target, 'ga:country,ga:countryIsoCode', '-'+ga_target),
		return_ajax_ga_query(date_prev_begin, date_prev_end, ga_target, 'ga:country,ga:countryIsoCode', '-'+ga_target),
	).done(function(ret1, ret2) {
		if (ret1[0]['status'] && ret2[0]['status']) {
			var output=[];
			var lookup=[];
			for( var i=0, cnt=ret1[0]['data'].length ; i<cnt && i<15 ; i++ ) {
				lookup[ret1[0]['data'][i][1]] = [i, parseInt(ret1[0]['data'][i][2]), 0, 0 ];
				output.push( [ ret1[0]['data'][i][1] , parseInt(ret1[0]['data'][i][2]) , 0, '' ] );
			}
			for( var i=0, cnt=ret2[0]['data'].length ; i<cnt ; i++ ) {
				var country_code = ret2[0]['data'][i][1];
				var value = parseInt(ret2[0]['data'][i][2]);
				if (lookup[country_code]) {
					lookup[country_code][2] = value;
					lookup[country_code][3] = ((lookup[country_code][1] - lookup[country_code][2]) / lookup[country_code][2]).toFixed(2);
					lookup[country_code][3] = lookup[country_code][3].replace('0.0','').replace('0.','')+'%';
					output[ lookup[country_code][0] ][2] = lookup[country_code][2];
					output[ lookup[country_code][0] ][3] = lookup[country_code][3];
				}
			}

			google.charts.load("current", {packages:['corechart']});
			google.charts.setOnLoadCallback(function(){
				var data = new google.visualization.DataTable();
				data.addColumn('string', field_name[0] );
				data.addColumn('number', field_name[1] );
				data.addColumn('number', field_name[2] );
				data.addColumn( {type: 'string', role: 'annotation'} );
				data.addRows(output);
				var view = new google.visualization.DataView( data );

				view.setColumns([0, 1, 3, 2]);
				var obj = document.getElementById(div_id);
				var options = {
					title: title,
					//width: obj.width,
					height: 100,
					bar: {groupWidth: "90%"},
					legend: { position: "none" },
				};
				var chart = new google.visualization.ColumnChart(obj);
				chart.draw(view, options);
			});
		}
	});
}
function build_project_data(group, labels, date_info, data) {
	var key = group;
	if (labels && labels.length > 0)
		key = key+","+labels;
	if (!window.project_data[key])
		window.project_data[key] = {};
	if (!window.project_data[key][date_info]) {
		window.project_data[key][date_info] = {};
	}

	if (data["analytics"]) {
		if (!window.project_data[key][date_info]["analytics"])
			window.project_data[key][date_info]["analytics"] = {
				"new_user" : 0,
				"user" : 0,
			};
		window.project_data[key][date_info]["analytics"]["new_user"] += data["analytics"]['new_user'];
		window.project_data[key][date_info]["analytics"]["user"] += data["analytics"]['user'];
	}
	if (data["adsense"]) {
		if (!window.project_data[key][date_info]["adsense"])
			window.project_data[key][date_info]["adsense"] = {
				"new_user": {
					"earnings": 0,
					"cpc": 0,
					"clicks": 0,
					"ecpm": 0,
					"coverage": 0,
					"ctr": 0,
					"ad_show": 0,
					"ad_request": 0,
					"ad_get": 0,
				},
				"user": {
					"earnings": 0,
					"cpc": 0,
					"clicks": 0,
					"ecpm": 0,
					"coverage": 0,
					"ctr": 0,
					"ad_show": 0,
					"ad_request": 0,
					"ad_get": 0,
				}
			};
		var fields = ["earnings", "cpc", "clicks", "ecpm", "ad_request", "coverage", "ctr", "ad_show", "ad_get"];
		for (var i=0, cnt=fields.length ; i<cnt ; ++i) {
			var tag = fields[i];
			window.project_data[key][date_info]["adsense"]["new_user"][tag] += data["adsense"]['new_user'][tag];
			window.project_data[key][date_info]["adsense"]["user"][tag] += data["adsense"]['user'][tag];
		}
	}
}
function calc_project_data_adsense(target, label){
	if (target) {
		for (var item in target) {
			if (target.hasOwnProperty(item)) {
				var lookup_key = (label.length == 0 ? item : label+','+item );
				if (window.project_data[lookup_key]) {
					calc_project_data_adsense(target[item], lookup_key);

					for (var date_info in window.project_data[lookup_key]) {
						if (window.project_data[lookup_key].hasOwnProperty(date_info)) {
							if (!window.project_data[lookup_key][date_info]['adsense']) {

							} else {
								var fields = ['new_user', 'user'];
								for (var i=0 ; i<fields.length ; ++i) {
									var obj = window.project_data[lookup_key][date_info]['adsense'][fields[i]];
//console.log("before:",obj);
									if (obj['earnings'] > 0) {
										if (obj['ecpm'] == 0 && obj['ad_show'] > 0) 
											obj['ecpm'] = obj['earnings'] / obj['ad_show'] * 1000.0;
										if (obj['cpc'] == 0 && obj['clicks'] > 0)
											obj['cpc'] = obj['earnings'] / obj['clicks'];
										if (obj['coverage'] == 0 && obj['ad_request'] > 0)
											obj['coverage'] = obj['ad_get'] / obj['ad_request'];
										if (obj['ctr'] == 0 && obj['ad_show'] > 0)
											obj['ctr'] = obj['clicks'] / obj['ad_show'];
									}
//console.log("after:",obj);
								}
							}
						}
					}

				}
			}
		}
	}
}
function parse_analytis_data(ret_data, date_range_style, date_flag) {
				var field_lookup = {};
				for( var i=0, cnt = ret_data['fields'].length ; i<cnt ; ++i)
					field_lookup[ret_data['fields'][i]] = i;
				for( var i=0, cnt = ret_data['data'].length ; i<cnt ; ++i) {
//console.log(ga_time_target);
//console.log(ret_data['data'][i][ field_lookup[ga_time_target] ]);
					var date_info = '';
					date_info = date_range_style + '_' + date_flag;
//console.log(date_info);
					var group = ret_data['data'][i][ field_lookup['ga:eventAction'] ];
					var labels = ret_data['data'][i][ field_lookup['ga:eventLabel'] ].split(',');
					var value_new_user = parseInt(ret_data['data'][i][ field_lookup['ga:newUsers'] ]);
					var value_user = parseInt(ret_data['data'][i][ field_lookup['ga:users'] ]);
					var value_data = {
						"analytics": {
							"new_user": value_new_user,
							"user": value_user,
						}
					}
					if (!window.project_info[group] )
						window.project_info[group] = {}
					var target = window.project_info[group];
					var key_group = group;
					var key_labels = '';
					build_project_data(key_group,key_labels,date_info,value_data);
					for( var j=0 ; j<labels.length ; ++j ) {
						if (labels[j] && labels[j].length >0) {
							key_labels = key_labels.length > 0 ? key_labels+','+labels[j] : labels[j];
							build_project_data(key_group,key_labels,date_info,value_data);
	
							if (!target[labels[j]]) {
								target[labels[j]] = {};
								target = target[labels[j]];
							} else {
								target = target[labels[j]];
							}
						}
					}
				}

}
function parse_adsense_data(ret_data, date_range_style, date_flag) {
				var field_lookup = {};
				for( var i=0, cnt = ret_data['fields'].length ; i<cnt ; ++i)
					field_lookup[ret_data['fields'][i]] = i;
				for( var i=0, cnt = ret_data['data'].length ; i<cnt ; ++i) {
					var date_info = '';
					date_info = date_range_style + '_' + date_flag;
					//date_info = ret_data['data'][i][ field_lookup['DATE'] ].replace(/\-/g,'');
					var raw_labels = ret_data['data'][i][ field_lookup['AD_UNIT_NAME'] ];
					//console.log(raw_labels);
					var labels = raw_labels.split(",");
					labels.shift();	// remove first info
					var group = labels.shift();

					var value_earnings = parseFloat(ret_data['data'][i][ field_lookup['EARNINGS'] ]);
					if (isNaN(value_earnings))
						value_earnings = 0;	
					var value_clicks = parseInt(ret_data['data'][i][ field_lookup['CLICKS'] ]);
					var value_cpc = parseFloat(ret_data['data'][i][ field_lookup['COST_PER_CLICK'] ]);
					if (isNaN(value_cpc))
						value_cpc = 0;
					var value_ecpm = parseFloat(ret_data['data'][i][ field_lookup['AD_REQUESTS_RPM'] ]);
					if (isNaN(value_ecpm))
						value_ecpm = 0;
					var value_ctr = parseFloat(ret_data['data'][i][ field_lookup['AD_REQUESTS_CTR'] ]);
					if (isNaN(value_ctr))
						value_ctr = 0;
					var value_coverage = parseFloat(ret_data['data'][i][ field_lookup['AD_REQUESTS_COVERAGE'] ]);
					if (isNaN(value_coverage))
						value_coverage = 0;
					var value_ad_request = parseInt(ret_data['data'][i][ field_lookup['AD_REQUESTS'] ]);
					var value_ad_show = parseInt(ret_data['data'][i][ field_lookup['MATCHED_AD_REQUESTS'] ]);
					var value_ad_get = parseInt(value_ad_request * value_coverage);

					var is_new_user = raw_labels.indexOf('NU,') >= 0;
					var is_old_user = raw_labels.indexOf('OU,') >= 0;
					var value_data = {
						"adsense": {
							"new_user": {
								"earnings": 0,
								"cpc": 0,
								"clicks": 0,
								"ecpm": 0,
								"coverage": 0,
								"ctr": 0,
								"ad_show": 0,
								"ad_request": 0,
								"ad_get": 0,
							},
							"user": {
								"earnings": 0,
								"cpc": 0,
								"clicks": 0,
								"ecpm": 0,
								"coverage": 0,
								"ctr": 0,
								"ad_show": 0,
								"ad_request": 0,
								"ad_get": 0,
							}
						}
					};

					// handle: copy by reference
					if (is_new_user) {
						value_data["adsense"]["new_user"] = {
							"earnings": value_earnings,
							"cpc": value_cpc,
							"clicks": value_clicks,
							"ecpm": value_ecpm,
							"coverage": value_coverage,
							"ctr": value_ctr,
							"ad_show": value_ad_show,
							"ad_request": value_ad_request,
							"ad_get": value_ad_get,
						};
						value_data["adsense"]["user"] = {
							"earnings": value_earnings,
							"cpc": 0,
							"clicks": value_clicks,
							"ecpm": 0,
							"coverage": 0,
							"ctr": 0,
							"ad_show": value_ad_show,
							"ad_request": value_ad_request,
							"ad_get": value_ad_get,
						};
					} else if (is_old_user) {
						value_data["adsense"]["user"] = {
							"earnings": value_earnings,
							"cpc": 0,//value_cpc,
							"clicks": value_clicks,
							"ecpm": 0,//value_ecpm,
							"coverage": 0,//value_coverage,
							"ctr": 0,//value_ctr,
							"ad_show": value_ad_show,
							"ad_request": value_ad_request,
							"ad_get": value_ad_get,
						};
					}
					
					var reset_fields = ['cpc','ecpm','coverage','ctr'];
					//var reset_data = Object.assign({}, value_data);
					var reset_data = JSON.parse(JSON.stringify(value_data));
					// reset value
					for (var k=0 ; k<reset_fields.length ; ++k) {
						var reset_tag = reset_fields[k];
						reset_data["adsense"]["new_user"][reset_tag] = 0;
						reset_data["adsense"]["user"][reset_tag] = 0;
					}

					if (!window.project_info[group] )
						window.project_info[group] = {}
					var target = window.project_info[group];
					var key_group = group;
//*
					for( var j=0 ; j<labels.length ; ++j ) {
						if (labels[j] && labels[j].length >0) {
							if (!target[labels[j]]) {
								target[labels[j]] = {};
								target = target[labels[j]];
							} else {
								target = target[labels[j]];
							}
						}
					}

					for( var j=labels.length - 1 ; j >= 0  ; --j ) {
						if (labels[j] && labels[j].length >0) {
							var key_labels = labels.slice(0, j+1).join(',');
							// reset value
							if (j != (labels.length - 1)) {
								build_project_data(key_group,key_labels,date_info,reset_data);
							} else {
								build_project_data(key_group,key_labels,date_info,value_data);
							}
						}
					}
					build_project_data(key_group,'',date_info,reset_data);
// */

//					var key_labels = '';
//					build_project_data(key_group,key_labels,date_info,value_data);
//					for( var j=0 ; j<labels.length ; ++j ) {
//						if (labels[j] && labels[j].length >0) {
//							key_labels = key_labels.length > 0 ? key_labels+','+labels[j] : labels[j];
//							build_project_data(key_group,key_labels,date_info,value_data);
//	
//							if (!target[labels[j]]) {
//								target[labels[j]] = {};
//								target = target[labels[j]];
//							} else {
//								target = target[labels[j]];
//							}
//						}
//					}
				}
}
function build_analytics_adsense_pairing(date_begin, date_end, date_prev_begin, date_prev_end, date_range_style, status_report_callback) {
	var $d = $.Deferred();
	//var ga_time_target = 'ga:date';
	var ga_dimensions = [
		'ga:eventAction',
		'ga:eventLabel',
	];
	var ga_sort = '';
	var adsense_dimensions = [
		'AD_UNIT_NAME',
	];
	var adsense_time_target = 'DATE';
	switch(date_range_style) {
		case '1-day':
			//ga_dimensions.unshift('ga:date');
			//ga_sort = '-ga:date';
			//adsense_dimensions.unshift('DATE');
			break;
		case '7-day':
			//ga_time_target = 'ga:week';
			break;
	}

	$.when(
		// https://developers.google.com/analytics/devguides/reporting/core/dimsmets
		return_ajax_ga_query(date_begin, date_end, 
			[
				'ga:users',
				'ga:newUsers'
			], 
			ga_dimensions,
			ga_sort
		),
		// https://developers.google.com/adsense/management/metrics-dimensions
		return_ajax_adsense_query(date_begin, date_end, 
			[
				'MATCHED_AD_REQUESTS',
				'AD_REQUESTS', 
				'AD_REQUESTS_CTR',	// CTR
				'COST_PER_CLICK',	// CPC
				'AD_REQUESTS_RPM',	// eCPM
				'AD_REQUESTS_COVERAGE',
				'CLICKS',
				'EARNINGS',
			], 
			adsense_dimensions
		),
		return_ajax_ga_query(date_prev_begin, date_prev_end,
			[
				'ga:users',
				'ga:newUsers'
			], 
			ga_dimensions,
			ga_sort
		),
		return_ajax_adsense_query(date_prev_begin, date_prev_end,
			[
				'MATCHED_AD_REQUESTS',
				'AD_REQUESTS', 
				'AD_REQUESTS_CTR',	// CTR
				'COST_PER_CLICK',	// CPC
				'AD_REQUESTS_RPM',	// eCPM
				'AD_REQUESTS_COVERAGE',
				'CLICKS',
				'EARNINGS',
			], 
			adsense_dimensions
		),
	).done(function(ret1, ret2, ret3, ret4) {
		if (ret1[0]['status'] && ret2[0]['status']) {
			// GA
			parse_analytis_data(ret1[0], date_range_style, date_begin.replace(/\-/g,''));
			// adsense
			parse_adsense_data(ret2[0], date_range_style, date_begin.replace(/\-/g,''));

			// calc adsense
			calc_project_data_adsense(window.project_info, '');
			// 
			//build_treeview_data();
		}
		if (ret3[0]['status'] && ret4[0]['status']) {
			// GA
			parse_analytis_data(ret3[0], date_range_style, date_prev_begin.replace(/\-/g,''));
			// adsense
			parse_adsense_data(ret4[0], date_range_style, date_prev_begin.replace(/\-/g,''));

			// calc adsense
			calc_project_data_adsense(window.project_info, '');
			// 
			//build_treeview_data();
		}

		if (status_report_callback && {}.toString.call(status_report_callback) === '[object Function]' ) {
			status_report_callback();
		}

		$d.resolve({
			ga_query: [ret1, ret3],
			adsense_query: [ret2, ret4],
		});
	});
	return $d.promise();
}
function formated_number(input, float_precision) {
	var output = '';
	output = input.toString();
	if (float_precision > 0) {
		var parts = output.split('.');
		if (parts.length == 2) {
			output = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
			if (parts[1].length > 0) {
 				if (parts[1].length > float_precision)
					output += '.' + parts[1].substring(0, float_precision);
				else
					output += '.' + parts[1];
			}
			
		} else {
			output = output.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
		}
	} else 
		output = output.replace(/\B(?=(\d{3})+(?!\d))/g, ",") ;
	return output;
}
// jonmiles/bootstrap-treeview
function build_treeview_node(target, label) {
	var output = [];
	if (target) {
		for (var item in target) {
			if (target.hasOwnProperty(item)) {
				var lookup_key = (label.length == 0 ? item : label+','+item );
				var tags = [];
				if (window.project_data[lookup_key]) {
					var objSorted = Object.keys(window.project_data[lookup_key]).sort(function (a, b) {
						//return a - b;
						return b - a;
					});
					//for (var i=0, cnt = objSorted.length ; i<cnt ; ++i) {
					//}
					var obj = window.project_data[lookup_key][objSorted[0]];
					var tag = '[1-day: ';
					if (obj['adsense']) {
						//tag += 'Monetize: $'+Math.round(obj['adsense']['user']['earnings']*100)/100;
						tag += 'Monetize: $'+formated_number(obj['adsense']['user']['earnings'], 2);
						tag += ', eCPM: $'+formated_number(obj['adsense']['user']['ecpm'], 2);

						tag += ', (AD_Show: '+formated_number(obj['adsense']['user']['ad_show'], 0);
						tag += ', Clicks: '+formated_number(obj['adsense']['user']['clicks'], 0);
						tag += ', CTR: '+formated_number(obj['adsense']['user']['ctr']*100, 2)+'%';
						tag += ')';

						tag += ', (AD_Request: '+formated_number(obj['adsense']['user']['ad_request'], 0);
						tag += ', AD_Get: '+formated_number(obj['adsense']['user']['ad_get'], 0);
						tag += ', Coverage: '+formated_number(obj['adsense']['user']['coverage']*100.0, 2)+'%';
						tag += ')';

						tag += ' @ ' + objSorted[0];
					} else {
						tag += 'Monetize: $0, eCPM: $0, CTR: 0%';
					}
					if (obj['analytics']) {
						tag += ' / user: ' + formated_number(obj['analytics']['user'], 0);
					}
					tag += ']';
					tags.push( tag );
				}
				var info = {
					"text": item + ':',
					"tags": tags,
					"nodes": build_treeview_node(target[item], label.length == 0 ? item : label+','+item),
				};
				output.push(info);
			}
		}
	}
	return output;
}
function number_diff_percentage(num1, num2) {
	if (num2 == 0)
		return '';
	var value = num1 - num2;
	if (value >= 0) {
		return '(+'+formated_number( parseFloat(value)/parseFloat(num2) , 3 )+'%)';
	} else {
		return '('+formated_number( parseFloat(value)/parseFloat(num2) , 3 )+'%)';
	}
}
function build_analytics_x_adsense_data_diff_info( obj1, obj2 ) {
	var output = [];
	var node = null;
	if (obj1) {
		if (obj2) {
			if (obj1['adsense'] && obj1['analytics'] && obj2['adsense'] && obj2['analytics']) {
				node = { 
					'text': 
						'New User: '+ formated_number(obj1['analytics']['new_user'], 0) + number_diff_percentage(obj1['analytics']['new_user'], obj2['analytics']['new_user'])
						+ ', Monetize: $' + formated_number(obj1['adsense']['new_user']['earnings'], 2) + number_diff_percentage(obj1['adsense']['new_user']['earnings'], obj2['adsense']['new_user']['earnings'])
						+ ', eCPM: $'+formated_number(obj1['adsense']['new_user']['ecpm'], 2) + number_diff_percentage(obj1['adsense']['new_user']['ecpm'], obj2['adsense']['new_user']['ecpm'])
					,
					'state' : { 'opened': false },
					'children': [
						{ 
							'text': ''
								+ 'AD_Show: '+formated_number(obj1['adsense']['new_user']['ad_show'], 0) + number_diff_percentage(obj1['adsense']['new_user']['ad_show'], obj2['adsense']['new_user']['ad_show'])
								+ ', Clicks: '+formated_number(obj1['adsense']['new_user']['clicks'], 0) + number_diff_percentage(obj1['adsense']['new_user']['clicks'], obj2['adsense']['new_user']['clicks'])
								+ ' = CTR: '+formated_number(obj1['adsense']['new_user']['ctr']*100, 2)+'%' + number_diff_percentage(obj1['adsense']['new_user']['ctr'], obj2['adsense']['new_user']['ctr'])
							,
							'state' : { 'opened': true }
						},
						{ 
							'text': ''
								+ 'AD_Request: '+formated_number(obj1['adsense']['new_user']['ad_request'], 0) + number_diff_percentage(obj1['adsense']['new_user']['ad_request'], obj2['adsense']['new_user']['ad_request'])
								+ ', AD_Get: '+formated_number(obj1['adsense']['new_user']['ad_get'], 0) + number_diff_percentage(obj1['adsense']['new_user']['ad_get'], obj2['adsense']['new_user']['ad_get'])
								+ ' = Coverage: '+formated_number(obj1['adsense']['new_user']['coverage']*100.0, 2)+'%'+number_diff_percentage(obj1['adsense']['new_user']['coverage'], obj2['adsense']['new_user']['coverage'])
							,
							'state' : { 'opened': true }
						},
					],
				}
				if (parseInt(obj1['analytics']['new_user']) > 0) {
					node['children'].push(
						{ 
							'text': ''
								+ 'Value per person: $' + formated_number( parseFloat(obj1['adsense']['new_user']['earnings']) / parseInt(obj1['analytics']['new_user']), 6) + (
									parseInt(obj2['analytics']['new_user']) == 0 ? '' : number_diff_percentage( parseFloat(obj1['adsense']['new_user']['earnings']) / parseInt(obj1['analytics']['new_user']), parseFloat(obj2['adsense']['new_user']['earnings']) / parseInt(obj2['analytics']['new_user']))
								)
							,
						},
					);
				}
				output.push(node);

				node = { 
					"text": 
						"Active User: "+ formated_number(obj1['analytics']['user'], 0) + number_diff_percentage(obj1['analytics']['user'], obj2['analytics']['user'])
						+ ', Monetize: $' + formated_number(obj1['adsense']['user']['earnings'], 2) + number_diff_percentage(obj1['adsense']['user']['earnings'], obj2['adsense']['user']['earnings'])
						+ ', eCPM: $'+formated_number(obj1['adsense']['user']['ecpm'], 2) + number_diff_percentage(obj1['adsense']['user']['ecpm'], obj2['adsense']['user']['ecpm'])
					,
					'tags': [],
					'state' : { 'opened': false },
					'children': [
						{ 
							'text': ''
								+ 'AD_Show: '+formated_number(obj1['adsense']['user']['ad_show'], 0) + number_diff_percentage(obj1['adsense']['user']['ad_show'], obj2['adsense']['user']['ad_show'])
								+ ', Clicks: '+formated_number(obj1['adsense']['user']['clicks'], 0) + number_diff_percentage(obj1['adsense']['user']['clicks'], obj2['adsense']['user']['clicks'])
								+ ' = CTR: '+formated_number(obj1['adsense']['user']['ctr']*100, 2)+'%'+ number_diff_percentage(obj1['adsense']['user']['ctr'], obj2['adsense']['user']['ctr'])
							,
							'state' : { 'opened': true }
						},
						{ 
							'text': ''
								+ 'AD_Request: '+formated_number(obj1['adsense']['user']['ad_request'], 0) + number_diff_percentage(obj1['adsense']['user']['ad_request'], obj2['adsense']['user']['ad_request'])
								+ ', AD_Get: '+formated_number(obj1['adsense']['user']['ad_get'], 0) + number_diff_percentage(obj1['adsense']['user']['ad_get'], obj2['adsense']['user']['ad_get'])
								+ ' = Coverage: '+formated_number(obj1['adsense']['user']['coverage']*100.0, 2)+'%' + number_diff_percentage(obj1['adsense']['user']['coverage'], obj2['adsense']['user']['coverage'])
							,
							'state' : { 'opened': true }
						},
					],
				};
				if (parseInt(obj1['analytics']['user']) > 0) {
					node['children'].push(
						{ 
							'text': ''
								+ 'Value per person: $' + formated_number( parseFloat(obj1['adsense']['user']['earnings']) / parseInt(obj1['analytics']['user']), 6) + (
									parseInt(obj2['analytics']['user']) == 0 ? '' :
									number_diff_percentage(parseFloat(obj1['adsense']['user']['earnings']) / parseInt(obj1['analytics']['user']), parseFloat(obj2['adsense']['user']['earnings']) / parseInt(obj2['analytics']['user']))
								)
							,
						},
					);
				}
				output.push(node);
			}
		} else {
			var obj = obj1;
			if (obj['adsense'] && obj['analytics']) {
				node = { 
					'text': 'New User: '+ formated_number(obj['analytics']['new_user'], 0)
						+ ', Monetize: $' + formated_number(obj['adsense']['new_user']['earnings'], 2)
						+ ', eCPM: $'+formated_number(obj['adsense']['new_user']['ecpm'], 2)
					,
					'state' : { 'opened': true },
					'children': [
						{ 
							'text': ''
								+ 'AD_Show: '+formated_number(obj['adsense']['new_user']['ad_show'], 0)
								+ ', Clicks: '+formated_number(obj['adsense']['new_user']['clicks'], 0)
								+ ' = CTR: '+formated_number(obj['adsense']['new_user']['ctr']*100, 2)+'%'
							,
							'state' : { 'opened': true }
						},
						{ 
							'text': ''
								+ 'AD_Request: '+formated_number(obj['adsense']['new_user']['ad_request'], 0)
								+ ', AD_Get: '+formated_number(obj['adsense']['new_user']['ad_get'], 0)
								+ ' = Coverage: '+formated_number(obj['adsense']['new_user']['coverage']*100.0, 2)+'%'
							,
							'state' : { 'opened': true }
						},
					],
				};
				if (parseInt(obj['analytics']['new_user']) > 0) {
					node['children'].push(
						{ 
							'text': ''
								+ 'Value per person: $' + formated_number( parseFloat(obj['adsense']['new_user']['earnings']) / parseInt(obj['analytics']['new_user']), 6)
							,
							'state' : { 'opened': true }
						},
					);
				}
	
				output.push(node);
				node = { 
					"text": "Active User: "+ formated_number(obj['analytics']['user'], 0) 
						+ ', Monetize: $' + formated_number(obj['adsense']['user']['earnings'], 2)
						+ ', eCPM: $'+formated_number(obj['adsense']['user']['ecpm'], 2)
					,
					'tags': [],
					'state' : { 'opened': true },
					'children': [
						{ 
							'text': ''
								+ 'AD_Show: '+formated_number(obj['adsense']['user']['ad_show'], 0)
								+ ', Clicks: '+formated_number(obj['adsense']['user']['clicks'], 0)
								+ ' = CTR: '+formated_number(obj['adsense']['user']['ctr']*100, 2)+'%'
							,
							'state' : { 'opened': true }
						},
						{ 
							'text': ''
								+ 'AD_Request: '+formated_number(obj['adsense']['user']['ad_request'], 0)
								+ ', AD_Get: '+formated_number(obj['adsense']['user']['ad_get'], 0)
								+ ' = Coverage: '+formated_number(obj['adsense']['user']['coverage']*100.0, 2)+'%'
							,
							'state' : { 'opened': true }
						},
					],
				};
				if (parseInt(obj['analytics']['user']) > 0) {
					node['children'].push(
						{ 
							'text': ''
								+ 'Value per person: $' + formated_number( parseFloat(obj['adsense']['user']['earnings']) / parseInt(obj['analytics']['user']), 6)
							,
							'state' : { 'opened': true }
						},
					);
				}
				output.push(node);
			} else if (obj['adsense']) {
				node = { "text": "" };
			} else if (obj['analytics']) {
				node = { "text": "New User: "+ formated_number(obj['analytics']['new_user'], 0) };
				output.push(node);
				node = { "text": "Active User: "+ formated_number(obj['analytics']['user'], 0) };
				output.push(node);
	
			}
		}
	}
	return output;
}
function build_analytics_x_adsense_node(label, item) {
	var output = [];
	var lookup_key = (label.length == 0 ? item : label+','+item );
	if (window.project_data[lookup_key]) {
		var objSorted = Object.keys(window.project_data[lookup_key]).sort(function (a, b) {
			var a_part = a.split('_');
			var b_part = b.split('_');

			var a_subpart = a_part[0].split('-');
			var b_subpart = b_part[0].split('-');

			var a_level_one = parseInt(a_subpart[0]);
			var a_level_two = a_part[1];
			var b_level_one = parseInt(b_subpart[0]);
			var b_level_two = b_part[1];

			if (a_level_one != b_level_one) {
				//return b_level_one - a_level_one;
				return a_level_one - b_level_one;
			}
			return b_level_two - a_level_two;
			//return a_level_two - b_level_two;

			//return a - b;
			//return b - a;
		});

		var info_label_lookup = {};
		var info_label = [];
		for( var i=0, cnt=objSorted.length ; i<cnt ; ++i) {
			var part = objSorted[i].split('_');
			if (!info_label.includes(part[0]))
				info_label.push(part[0]);
			if (!info_label_lookup[ part[0] ])
				info_label_lookup[ part[0] ] = [];
			info_label_lookup[ part[0] ].push(objSorted[i]);
		}
//console.log(objSorted);
//console.log(info_label);
//console.log(info_label_lookup);

		var node = null;
		var children_node = null;
		for (var i=0, cnt=info_label.length ; i<cnt ; ++i) {
			children_node = null;
			if (info_label_lookup[info_label[i]]) {
				if (info_label_lookup[info_label[i]].length == 2) {
					children_node = build_analytics_x_adsense_data_diff_info(
						window.project_data[lookup_key][ info_label_lookup[info_label[i]][0] ],
						window.project_data[lookup_key][ info_label_lookup[info_label[i]][1] ]
					);
				} else if (info_label_lookup[info_label[i]].length == 1) {

					children_node = build_analytics_x_adsense_data_diff_info(window.project_data[lookup_key][ info_label_lookup[info_label[i]][0] ]);
				}
			}

			node = {
				'text' : info_label[i] ,
				//'state' : { 'opened': info_label[i] == '7-day' },
				'state' : { 'opened': true },
				'children': children_node,
			}
			output.push(node);
		}
		return output;
	}
	return output;
}
function build_jstree_node(target, label) {
	var output = [];
	if (target) {
		for (var item in target) {
			if (target.hasOwnProperty(item)) {
				var lookup_key = (label.length == 0 ? item : label+','+item );
				var tags = [];
				var info = {
					"text": item ,
					"tags": tags,
					"children": build_jstree_node(target[item], label.length == 0 ? item : label+','+item),
				};
				var children = build_analytics_x_adsense_node(label, item);
				//for (var i=0, cnt=children.length ; i<cnt ; ++i) {
				//	info['children'].unshift(children[i]);
				//}
				info['children'].unshift({
					"text": "Google Analytics x Google Adsense Report",
					"children": children,
					"state":  {"opened" : true },
				});
				if (label.length == 0)
					info['state'] = {"opened" : true };
				output.push(info);
			}
		}
	}
	return output;
}

function build_treeview_data() {
	/*
	window.project_treeview = [];
	if (window.project_info) {
		window.project_treeview = build_treeview_node(window.project_info, '');
	}
	console.log(window.project_treeview);
	$('#tree').treeview({
		data: window.project_treeview,
		levels: 10,
		showTags: true,
	});
	*/

	window.project_treeview = [];
	if (window.project_info) {
		window.project_treeview = build_jstree_node(window.project_info, '');
	}
	$('#tree').jstree({
		'core' : {
			'data' : window.project_treeview,
		},
		"types" : {
			"default" : {
				"icon" : "glyphicon glyphicon-plus",//"glyphicon glyphicon-flash",
			},
			"demo" : {
				"icon" : "glyphicon glyphicon-ok",
			}
  
		},
		"plugins" : ["types"],
	});
}

		</script>
<!--
<?php
	if (isset($debug)) { 
		print_r($debug);
	}
?>
-->
	</head>

