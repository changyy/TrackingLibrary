<?php require '_header.php';	?>
	<body>
		<nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
			<a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Company name</a>
			<input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">
			<ul class="navbar-nav px-3">
				<li class="nav-item text-nowrap">
					<a class="nav-link" href="#">Sign out</a>
				</li>
			</ul>
		</nav>
		<div class="container-fluid">
			<div class="row">
<?php	require '_project_list.php';	?>
				<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
					<div class="row">
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
