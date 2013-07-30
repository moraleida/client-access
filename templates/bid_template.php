<?php

wp_head();

$client = get_post_meta($post->ID, 'client', true);
$client = get_post($client);

$employeeincharge = get_post_meta($post->ID, 'employeeincharge', true);
$clientincharge = get_post_meta($post->ID, 'clientincharge', true);

$prologue = get_post_meta($post->ID, 'prologue', true);
$epilogue = get_post_meta($post->ID, 'epilogue', true);

$contactinfo = get_post_meta($post->ID, 'contactinfo', true);

$valuetable = get_post_meta($post->ID, 'valuetable', true);

$deadline = get_post_meta($post->ID, 'deadline', true);

/**
 * Create the HTML output before, so we can create the pdf from it 
 */

// HTML Header
$html = '<!DOCTYPE html>
			<html>
			<head>
				<title>Print Invoice</title>
				<link rel="stylesheet" href="'. CLIENTACCESS_PLUGIN_URL . 'templates/css/styles.css">
				<script> var pdfstyle = ' . json_encode(CLIENTACCESS_PATH . "/templates/css/styles.css") . '</script>
				<script type="text/javascript" src="//use.typekit.net/msb1uwu.js"></script>
				<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
				'.wp_head().'
				
			</head><body class="tk-myriad-pro">';

// Fixedbar <div class="logo wrap"><img src="'. CLIENTACCESS_PLUGIN_URL . 'img/cropped.jpg" alt="" /></div>
$html .= '<div id="fixedbar">
			<div class="logo bid"><img src="'. CLIENTACCESS_PLUGIN_URL . 'img/cropped.jpg" alt="" /></div>
			<div class="buttons">';

$html .= '<a href="#" class="savepdf">Save as PDF</a>
				<a href="#" class="respond">Respond</a>
				<a href="#" class="accept">Accept</a>
				<a href="#" class="decline">Decline</a>
			</div>
		</div>';

// Content
$html .= '<div id="wrapper" >
			<htmlpageheader name="logo" class="logo"><img src="'. CLIENTACCESS_PLUGIN_URL . 'img/cropped.png" alt="" /></htmlpageheader>
		<div class="content">
			<div class="date">'.get_the_time('d/m/Y').'</div>
			<h1 class="title">Proposta Comercial</h1>
			<h2 class="client">'. $client->post_title .'<br> att: '. $clientincharge .'</h2>
			<h3 class="service">'. $post->post_title .'</h3>
			<div class="prologue">'. apply_filters('the_content', $prologue) .'</div>
			<div class="scope">
				<h4 class="scopetitle">Escopo do Serviço</h4>
				'. apply_filters('the_content', $post->post_content) .'
			</div>
			<div class="bidvalues">
				<h4 class="tabletitle">Descrição e valores</h4>
				'.htmlspecialchars_decode($valuetable).'
				</div>
			<div class="deadline">
				<h4 class="deadlinetitle">Prazo de desenvolvimento</h4>
				<p>'. $deadline .'</p>
			</div>
			<div class="epilogue">'. apply_filters('the_content', $epilogue) .'</div>
			<div class="employeeincharge"><h4>'. $employeeincharge .'</h4></div>
			<div class="contactinfo">'. apply_filters('the_content', $contactinfo) .'</div>
			<div class="bidid">'.get_the_time('Y-md').'_'.$client->post_name.'</div>
		</div>
	</div>
</body>
</html>';




echo $html;

get_footer();

?>