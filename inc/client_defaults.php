<?php

	$option = '';

	$client = get_option('defaultclient');

	$employeeincharge 	= get_option('defaultemployeeincharge');
	$clientincharge 	= get_option('defaultclientincharge');
	$contactinfo 		= get_option('defaultcontactinfo');
	$prologue 			= get_option('defaultprologue');
	$epilogue 			= get_option('defaultepilogue');
	$deadline 			= get_option('defaultdeadline');

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

	#bids { width: 80%; }
	#bids .left { width: 30%; }
	#bids select, #bids input, #bids textarea { width: 100%; }

	</style>
	<h1>Default Values for Bids</h1>
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