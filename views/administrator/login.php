<div style="display: inline-block;" class="panel panel-primary col-sm-12 col-md-12">
	    <div class="panel-heading">
		     <h3 class="panel-title">Login</h3>
	    </div>
		    <div class="panel-body">
		    <div class="hide alert alert-info login-message"></div>
		<?php echo  $this->create_form( $this->set_url('user/login'), 'form_submit' ); ?>

		<div class="form-group">
			<label>Username: </label>
			<input type="text" name="username" class="form-input" required="required"/>
		</div>
		<div class="form-group">
			<label>Password: </label>
			<input type="password" name="password" class="form-input"  required="required"/>
		</div>
		<div class="btn-group">
			<input type="submit" name="submit" value="Submit" class="btn btn-danger" />
		</div>
	</div>
</div>
<?php echo $this->end_form(); ?>