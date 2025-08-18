<div class="row">
	<div class="col-md-12">
		<?php 
		$option_array = [];
		if(isset($option)){
			$option_array = json_decode($option);
		}

		$select_s = '';
		if(isset($select)){
			$select_s = json_decode($select);
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
		<label for="customfield[<?php echo fe_htmldecode($id_s) ?>][]" class="control-label">
			<?php 		
			if($required_s == 1){ ?>
				<small class="req text-danger">* </small>
			<?php }
			echo fe_htmldecode($title_s);
			?>
		</label>

		<div class="w100 ">
			<?php
			foreach ($option_array as $key => $value) {  
				$selected = '';
				if($select_s != ''){
					if(in_array($value, $select_s)){
						$selected = 'checked';
					}
				}

				?>
				<div class="checkbox">
					<input type="checkbox" name="customfield[<?php echo fe_htmldecode($id_s) ?>][]" <?php echo fe_htmldecode($selected); ?> value="<?php echo fe_htmldecode($value); ?>" <?php echo (($required_s == 1) ? 'required' : '') ?>>
					<label><?php echo fe_htmldecode($value); ?></label>
					<br>						
				</div>
			<?php }	?>
		</div>
		<br>


	</div>
</div>