<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">

		<title>SVS-EFIE Solver</title>

		<!-- Bootstrap core CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

		<!-- Custom styles for this template -->
		<link href="styles/index.css" rel="stylesheet" type="text/css" />
	</head>

	<body class="text-center">

		<div class="cover-container d-flex h-100 p-3 mx-auto flex-column">
			<header class="masthead mb-auto">
				<div class="inner">
					<h3 class="masthead-brand">SVS Solver</h3>
					<nav class="nav nav-masthead justify-content-center">
						<a class="nav-link active" href="#" data-backdrop="static" data-toggle="modal" data-target="#modal-submit-job">Submit Job</a>
						<a class="nav-link" href="#" data-toggle="modal" data-target="#modal-solver-about">About</a>
					</nav>
				</div>
			</header>

			<main role="main" class="inner cover">
				<h1 class="cover-heading">SVS-EFIE Solver</h1>
				<p class="lead">The Surface-Volume-Surface Electric Field Integral Equation (SVS-EFIE) is applicable to the solution of the scattering problems on both lossless dielectric and highly conducting metal objects. </p>
				<p class="lead">
					<a href="#" class="btn btn-lg btn-secondary" data-backdrop="static" data-toggle="modal" data-target="#modal-submit-job">
						Submit a Job!
					</a>
				</p>
			</main>
			
			<footer class="mastfoot mt-auto">
				<div class="inner">
					<p>Copyright &copy; <?= date('Y'); ?></p>
				</div>
			</footer>
		</div>

		<?php // include modals ?>
		<?php include('modals/modal-about-solver.php'); ?>
		<?php include('modals/modal-submit-job.php'); ?>

		<!-- Bootstrap core JavaScript -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
		<script src="scripts/index.js"></script>
	</body>
</html>