<?php


function bids_meta_values_html() {

	global $post_id;

	$valuetable			= get_post_meta($post_id, 'valuetable', true);

	?>
	<!-- <style type="text/css">
        table, td {
            border: solid 1px black;
        }
		table {
			border-collapse: collapse;
			border-spacing: 0;
		}
        td {
            padding: 8px;
        }
        .s {
            background: #d0eaf9;    
        }
        #rows, #cols {
			width: 20px;
        }
		td div {
			outline: none;
		}
		#tableWrap {
			padding: 10px;
		}
		input[type="button"] {
			width: 80px;
		}
	</style>
			 <input type="button" id="generate" value="Generate" />
		Rows:<input id="rows" type="text" value="5" maxlength="2" />
		Cols:<input id="cols" type="text" value="10" maxlength="2" />

		<br />

		<input type="button" id="merge" value="Merge" />

		<br />
		</div>

		<hr />

		<div>
		<div id="tableWrap"></div>

		<hr />

		<textarea cols="60" rows="30" id="export"></textarea> -->
	
	<textarea name="valuetable" class="valuetable" ><?php echo $valuetable; ?></textarea>
	
	<?php

}


function bids_meta_html() {

	global $post_id;

	$option = '';

	$client 	= get_post_meta($post_id, 'client', true);

	$employeeincharge 	= get_post_meta($post_id, 'employeeincharge', true);
	$clientincharge 	= get_post_meta($post_id, 'clientincharge', true);
	$contactinfo 		= get_post_meta($post_id, 'contactinfo', true);
	$prologue 			= get_post_meta($post_id, 'prologue', true);
	$epilogue 			= get_post_meta($post_id, 'epilogue', true);
	$deadline 			= get_post_meta($post_id, 'deadline', true);

	$clients = get_posts(array(
	'post_type' => 'client',
	'posts_per_page' => -1,
	'orderby' => 'title',
	'order' => 'ASC'
	));

	$option = "<option value='0' class='client'>Choose a client</option>";

	foreach ($clients as $cli) {

		($client == $cli->ID ? $sel = 'selected="selected"' : $sel ='');

		$option .= "<option $sel value='$cli->ID' class='client'>$cli->post_title</option>";
	}

	wp_nonce_field( 'clientaccess_save_post', 'clientaccess_nonce' );
	?>
	<style>

	#bids { width: 100%; }
	#bids .left { width: 30%; }
	#bids select, #bids input, #bids textarea { width: 100%; }

	</style>
	<table id="bids">
		<fieldset>
			<tr>
				<td class="left">Client</td>
				<td>
					<select name="client" id="client">
						<?php echo $option; ?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="left">Employee that signs this bid</td>
				<td>
					<input type="text" class="person" name="employeeincharge" value="<?php echo $employeeincharge; ?>">
				</td>
			</tr>
			<tr>
				<td class="left">Client in charge of this bid</td>
				<td>
					<input type="text" class="person" name="clientincharge" value="<?php echo $clientincharge; ?>">
				</td>
			</tr>
			<tr>
				<td class="left">Contact info for this bid</td>
				<td>
					<textarea name="contactinfo" id="" cols="30" rows="10" class="contactinfo" ><?php echo $contactinfo; ?></textarea>
				</td>
			</tr>
			<tr>
				<td class="left">Prologue</td>
				<td>
					<textarea name="prologue" id="" cols="30" rows="10" class="prologue" ><?php echo $prologue; ?></textarea>
				</td>
			</tr>
			<tr>
				<td class="left">Epilogue</td>
				<td>
					<textarea name="epilogue" id="" cols="30" rows="10" class="epilogue" ><?php echo $epilogue; ?></textarea>
				</td>
			</tr>
			<tr>
				<td class="left">Deadline or Estimated Time</td>
				<td>
					<input type="text" name="deadline" id="" class="deadline" value="<?php echo $deadline; ?>" />
				</td>
			</tr>
		</fieldset>
	</table>
	
	<?
}

?>