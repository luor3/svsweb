<!-- Modal Submit Job -->
<div class="modal fade bd-example-modal-lg" id="modal-submit-job" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content bg-white text-dark">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Submit a Job</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form name="form_job" action="server/execute-job.php" method="POST" enctype="multipart/form-data">
					<div id="output-screen" class="hidden"></div>
					<div class="form-content">
						<div class="form-group">
							<label for="job-email" class="col-form-label">Email:</label>
							<input type="email" name="email" class="form-control" id="job-email" required="required">
						</div>
						<div class="form-group">
							<label for="job-mesh" class="col-form-label">Mesh File <small>(*.msh)</small>:</label>
							<input type="file" name="mesh" class="form-control" id="job-mesh" accept=".msh,.mesh" required="required">
						</div>
						<div class="form-group">
							<label for="job-frequency" class="col-form-label">Frequency File <small>(*.freq)</small>:</label>
							<input type="file" name="frequency" class="form-control" id="job-frequency" accept=".freq,.frq,.frequency" required="required">
						</div>
						<div class="form-group">
							<label for="job-material" class="col-form-label">Material File <small>(*.mat)</small>:</label>
							<input type="file" name="material" class="form-control" id="job-material" accept=".mat,.mtl,.material" required="required">
						</div>
						<div class="form-group">
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="checkbox" name="job-print-mesh" id="job-print-mesh" value="job-print-mesh">
								<label class="form-check-label" for="job-print-mesh">Print Mesh</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="checkbox" name="job-refine-mesh" id="job-refine-mesh" value="job-refine-mesh">
								<label class="form-check-label" for="job-refine-mesh">Refine Mesh Test</label>
							</div>
							<div class="form-check form-check-inline">
								<input class="form-check-input" type="checkbox" name="job-print-wave" id="job-print-wave" value="job-print-wave" checked>
								<label class="form-check-label" for="job-print-wave">Print Wave No</label>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-dark">Submit Job</button>
						<button type="button" class="btn btn-info output-screen-toggle">Switch</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
			
			