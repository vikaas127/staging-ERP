<div class="row">
	<div class="col-md-12">
		<?php 
		$value_s = '';
		if(isset($value)){
			$value_s = $value;
		}

		$title_s = '';
		if(isset($title)){
			$title_s = $title;
		}

		$id_s = '';
		if(isset($id)){
			$id_s = $id;
		}
		$required_s = '';
		if(isset($required)){
			$required_s = $required;
		}

		?>
		<div class="form-group" app-field-wrapper="customfield[<?php echo fe_htmldecode($id_s) ?>]">
			<label for="customfield[<?php echo fe_htmldecode($id_s) ?>]" class="control-label">
				<?php 		
				if($required_s == 1){ ?>
					<small class="req text-danger">* </small>
				<?php }
				echo fe_htmldecode($title_s);
				?>
			</label>
			<textarea name="customfield[<?php echo fe_htmldecode($id_s) ?>]" id="customfield[<?php echo fe_htmldecode($id_s) ?>]" class="form-control" cols="30" rows="4" <?php echo (($required_s == 1) ? 'required' : '') ?>><?php echo fe_htmldecode($value) ?></textarea>
		</div>
	</div>
</div>