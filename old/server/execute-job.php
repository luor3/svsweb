<?php
// API Endpoint
// CRON
// LAMP
header('Content-Type: text/html');
$response = '<pre>';

define('MESH_FILE_LOCATION', __DIR__.'/../Resources/mesh.msh');
define('FREQ_FILE_LOCATION', __DIR__.'/../Resources/frequency.txt');
define('MAT_FILE_LOCATION', __DIR__.'/../Resources/material.txt');

@unlink(MESH_FILE_LOCATION);


$solverParams = array_map(function ($param) {
	return str_replace('job-', '', $param);
}, array_filter([$_POST['job-print-mesh'], $_POST['job-refine-mesh'], $_POST['job-print-wave']]));
$solverParams = implode(' ', $solverParams);

$uploadMesh = move_uploaded_file($_FILES['mesh']['tmp_name'], MESH_FILE_LOCATION);
$uploadFreq = move_uploaded_file($_FILES['frequency']['tmp_name'], FREQ_FILE_LOCATION);
$uploadMat = move_uploaded_file($_FILES['material']['tmp_name'], MAT_FILE_LOCATION);

if ($uploadMesh && $uploadFreq && $uploadMat) {
	exec('EMSolver.exe '.$solverParams, $output);
	
	$output = array_values(array_filter($output));

	//unset($output[0]);

	$response .= implode(PHP_EOL, $output);

	if (empty($output)) {
		$response .= '<div class="w-100 alert alert-danger">Internal Error! Please review the input params.</div>';
	} else {
		$response .= '<br><br>Download your VTK file from <a target="_blank" href="/output/output.vtk" class="text-primary">this link</a>.';
	}

} else {
	$response .= '<div class="w-100 alert alert-danger">Ooops! Something went wrong. Review your files.</div>';
}

$response .= '</pre>';

echo $response;