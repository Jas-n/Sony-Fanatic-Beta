					</div>
				</div>
			</div>
		</div>
		<div class="hidden" id="loading">
			<svg class="loading_svg" width="40" height="40" viewbox="0 0 40 40">
			  <polygon points="0 0 0 40 40 40 40 0" class="rect" />
			</svg>
		</div>
		<?php if($html_help){ ?>
			<a class="help_modal" data-toggle="modal" data-target="#help_modal">Help</a>
			<div class="modal fade" id="help_modal" tabindex="-1" role="dialog" aria-labelledby="help_modal" aria-hidden="true">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
								<span class="sr-only">Close</span>
							</button>
							<h4 class="modal-title"><?=$page->title?$page->title:'Help'?></h4>
						</div>
						<div class="modal-body">
							<?=$html_help?>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
		<div id="sn_notifications"></div>
		<div class="modal fade" id="error_report" tabindex="-1" role="dialog" aria-labelledby="error_report_label" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title" id="error_report_label">Report an Issue</h4>
					</div>
					<div class="modal-body">
						<p>We're sorry you had an issue when using glowt.
						<?php if($page->has_feature_permission('administration')){ ?>
                        	<p>Please ensure you have <a href="/update" target="_blank">updated the software</a> to the latest version before submitting.</p>
						<?php } ?>
                        <p>Please fill in the details below in as much detail as possible, what the error says and the steps taken to reproduce the issue and we'll investigate your issue.</p>
						<?php $error_report=new error_report();
						$error_report->get_form();?>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-sm btn-primary submit_issue">Submit Issue</button>
					</div>
				</div>
			</div>
		</div>
		<?php $app->get_foot_js();?>
		<footer class="text-xs-center">
			<div class="col-xs-4">Build: <span class="build_number"><?=software_version()?></span></div>
			<div class="col-xs-4 error_report">Report Issue</div>
			<div class="col-xs-4"><span class="hidden" id="render_time"><?=microtime(true)-$render_start?></span></div>
		</footer>
	</body>
</html>