<?php require '_header.php';	?>
	<body>
		<nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
			<a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Tracking</a>
		</nav>
		<div class="container-fluid">
			<div class="row">
<?php	require '_project_list.php';	?>
<?php
	$notes = array(
		array(
			'title' => '1. 建立 dev.json 檔案',
			'content' => '從 ga_adsense_rule.json 複製出一份 dev.json',
		),
		array(
			'title' => '2. 建立 Google Cloud Project，來使用 Google Adsense / Google Analytics API',
			'content' => array(
				array(
					'title' => '前提',
					'content' => '此例將使用 http://tracking.changyy.org:8000/dashboard 來瀏覽此專案，且 tracking.changyy.org 解析是 127.0.0.1 位置。如要更換，可以把申請 Google Cloud Project 內的"網域"資料更換即可',
				),
				array(
					'title' => '切換到 "API 與服務" -> 憑證 -> OAuth 同意畫面',
					'content' => array(
						array(
							'title' => '應用程式名稱：Tracking',
						),
						array(
							'title' => '已授權網域：changyy.org',
						),
					),
				),
				array(
					'title' => '切換到 "API 與服務" -> 憑證 -> 建立憑證',
					'content' => array(
						array(
							'title' => '選擇 "OAuth 用戶端 ID"',
							'content' => array(
								array(
									'title' => '網路應用程式：TrackingDashboard',
								),
								array(
									'title' => '已授權的 JavaScript 來源：http://tracking.changyy.org:8000',
								),
								array(
									'title' => '已授權的重新導向 URI：http://tracking.changyy.org:8000',
								),
							),
						),
					),
				),
				array(
					'title' => '取得 "這是您的用戶端 ID" 並更新在 dev.json ',
					'content' => '將各個 project -> google_api_project 欄位更新成 XXXXXX.apps.googleusercontent.com 即可',
				),
			),
		),
		array(
			'title' => '3. 開啟 Google Adsense / Google Analytics API',
			'content' => array(
				array(
					'title' => 'API和服務 -> 資訊主頁 -> 啟用 API和服務',
					'content' => array(
						array(
							'title' => '搜尋 "Analytics API" 以及點擊啟用',
						),
						array(
							'title' => '搜尋 "AdSense Management API" 以及點擊啟用',
						),
					),
				),
			),
		),
		array(
			'title' => '4. 切換至程式碼，編輯 dev.json ，建置想要關注的 GA 專案',
			'content' => '例如單純複製 GA only 專案內容，只修改 project -> analytics -> ga_profile_id 欄位即可',
		),
		array(
			'title' => '5. 切換到專案 php/web 位置，並運行本地端 web server ，即可瀏覽',
			'content' => array(
				array(
					'title' => '$ cd TrackingLibrary/php/web',
				),
				array(
					'title' => '$ php -S tracking.changyy.org:8000 ../tools/ci-routing.php',
				),
				array(
					'title' => '$ open "http://tracking.changyy.org:8000/dashboard"',
				),
			),
		),
	);
?>
				<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
					<div class="row">
						<div class="card col-12" style="width: 18rem;">
							<div class="card-body">
								<h5 class="card-title">README</h5>
								<h6 class="card-subtitle mb-2 text-muted">Setup</h6>
								<p class="card-text">
<?php	
	function genDoc($note) {
		$output = '';
		if (!empty($note)) {
			if (is_array($note)) {
				if (isset($note['title'])) {
					$output .= '<ul>';
					if (isset($note['content'])) {
						$output .= '<li>'.$note['title'];
						if (is_array($note['content'])) {
							$output .= '<ul><li>'.genDoc($note['content']).'</li></ul>';
						} else {
							$output .= '<ul><li>'.$note['content'].'</li></ul>';
						}

						$output .= '</li>';
					} else {
						$output .= '<li>'.$note['title'].'</li>';
					}
					$output .= '</ul>';
				} else {
					foreach($note as $item) {
						if (isset($item['title'])) {
							$output .= '<ul>';
							$output .= '<li>'.$item['title'];
							if (isset($item['content'])) {
								if (is_array($item['content'])) {
									$output .= '<ul><li>'.genDoc($item['content']).'</li></ul>';
								} else {
									$output .= '<ul><li>'.$item['content'].'</li></ul>';
								}
							}
							$output .= '</li>';
							$output .= '</ul>';
						}
					}
				}
			} else {
				$output .= $note;
			}
		}
		return $output;
	}
	if (isset($notes) && is_array($notes)) {
		foreach($notes as $note) {
?>
<div class="list-group">
	<a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
		<div class="d-flex w-100 justify-content-between">
			<h5 class="mb-1"><?php echo $note['title']; ?></h5>
			<small></small>
		</div>
		<p class="mb-1">
<?php	echo genDoc($note['content']); ?>
		</p>
		<small></small>
	</a>
</div>
<?php
		}
	}
?>
								</p>
								<a href="https://cloud.google.com/" class="card-link">Google Cloud</a>
								<a href="https://analytics.google.com/" class="card-link">Google Analytics</a>
								<a href="https://www.google.com/adsense/" class="card-link">Google Analytics</a>
							</div>
						</div>
<br>
<hr />
<br>
						<div class="card col-12 text-white bg-info">
							<div class="card-body">
								<h5 class="card-title">範例: GA Only</h5>
								<h6 class="card-subtitle mb-2 text-muted"></h6>
								<p class="card-text">
在 GA only 的樣板中，只需修改 dev.json 中的 ga_profile_id 即可，程式自動會依據 1-Day, 7-Day, 28-Day 撈出世界各地的用戶變化，並且可在下方選單中，挑選想觀看的國家近 28 天的 DAU 變化
								</p>
							</div>
							<img class="card-img-top" src="/assets/image/20190505_GAOnly.png" alt="Card image cap">
						</div>
						<div class="card col-12 text-white bg-success">
							<div class="card-body">
								<h5 class="card-title">範例: Google Analytics + Google Adsense</h5>
								<h6 class="card-subtitle mb-2 text-muted"></h6>
								<p class="card-text">
在此範例中，使用的技巧是給活躍用戶跟新用戶觀看不同的廣告單元，並且透過 GA event 方式回報 pageview (event category = "pageview")，並且把 event action 當作群聚的項目，如 Service 1，而 event label 當作該 Service 中的子功能階層，並用逗號隔開，如 event label = "功能1,子功能1-a,1-a-i"。
<br />
另外，在 Google Adsense 的廣告單元，以 '[#]' 開頭作為對應規則，後續以逗號隔開資訊。其中第一欄是 NU or OU，代表 New User 和 Old User ，第二欄則是群聚項目，如 Service 1，後續欄位則可以有更多階層，都用逗號隔開，如 "[#]NU,Service1,Function1", "[#]NU,Service2,Function3,SubFunction3"。
<br />
透過上述 Google analytics 跟 Google Adsense 規則，即可擁有對應關係，從 GA event 中撈出 New User / Active User 資訊，再到 Google Adsense 撈出對應的廣告ID獲利情況，就可以算出單一用戶價值。
<br />
除此之外，此處的報表延續 GA only 服務，可以列出同時間段的變化量，例如近 7天數據，與上個七天數據的變化量，讓人方便評估服務的成長或衰退情況。以及 dev.json 中，有提到 ga_profile_id 是個陣列，代表是支援多個 ga project 的撈取。但目前不支援多個 Adsesne 帳戶。
<br />
<br />
範例：有兩個服務 Service1, Service2 ，其中 Service 1 共有 1 個功能，而 Service 2 有 3 個功能。假設 Service 2 的第三個功能還有兩個子功能。
<br />
<ul>
	<li>
		Service1
		<ul>
			<li>Function1 [GA event report = (ec="pageview",ea="Service1",el="Function1")]</li>
		</ul>
	</li>
	<li>
		Service2
		<ul>
			<li>Function1 [GA event report = (ec="pageview",ea="Service2",el="Function1")]</li>
			<li>Function2 [GA event report = (ec="pageview",ea="Service2",el="Function2")]</li>
			<li>
				Function3 [GA event report = (ec="pageview",ea="Service3",el="Function3")]
				<ul>
					<li>
						SubFunction1
						<ul>
							<li>Google Analytics event = (ec="pageview",ea="Service3",el="Function3,SubFunction1")</li>
							<li>Google Adsense AdName = 
								<ul>
									<li>New User 觀看 = "[#]NU,Service2,Function3,SubFunction1"</li>
									<li>Old User 觀看 = "[#]OU,Service2,Function3,SubFunction1"</li>
								</ul>
							</li>
						</ul>
					</li>
					<li>SubFunction2 [GA event report = (ec="pageview",ea="Service3",el="Function3,SubFunction2")]</li>
				</ul>
			</li>
		</ul>
	</li>
</ul>

								</p>
							</div>
							<img class="card-img-top" src="/assets/image/20190505_AnalyticsXAdsense.png" alt="Card image cap">
						</div>
					</div>
				</main>
			</div>
		</div>
		<script>
$(document).ready(function(){

});
		</script>
	</body>
</html>

