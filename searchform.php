<div class="searchbardiv bg-light d-xl-none d-xs-block" id="formsearch">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<form role="search" method="get" id="searchform" action="<?php echo home_url(); ?>">
					<div class="input-group mb-3">
						<input class="form-control" type="search" name="s" placeholder="<?php _e( 'To search, type and hit enter.', 'pan-bootstrap' ); ?>">
						<div class="input-group-append">
							<button class="btn btn-outline-secondary"  id="searchsubmit"  type="submit">
								<?php _e( 'Search', 'pan-bootstrap' ); ?>
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
