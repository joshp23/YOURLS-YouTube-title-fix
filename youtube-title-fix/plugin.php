<?php
/*
Plugin Name: YouTube Title Fix
Plugin URI: https://github.com/joshp23/YOURLS-YouTube-title-fix
Description: Fetch YouTube Titles via Google API
Version: 2.0.0
Author: Josh Panter
Author URI: https://unfettered.net
*/

// No direct call
if( !defined( 'YOURLS_ABSPATH' ) ) die();
// Add the admin page
yourls_add_action( 'plugins_loaded', 'youtube_title_fix_add_page' );

function youtube_title_fix_add_page() {
        yourls_register_plugin_page( 'youtube_title_fix', 'YouTube API', 'youtube_title_fix_do_page' );
}

// Display admin page
function youtube_title_fix_do_page() {

	if( isset( $_POST['youtube_title_fix_api_key'] ) ) {
		yourls_verify_nonce( 'youtube_title_fix' );
		yourls_update_option( 'youtube_title_fix_api_key', $_POST['youtube_title_fix_api_key'] );
	}

	$youtube_title_fix_api_key = yourls_get_option( 'youtube_title_fix_api_key' );
	$nonce = yourls_create_nonce( 'youtube_title_fix' );

	echo <<<HTML
		<div id="wrap">
			<h2>YouTube API Key</h2>
			<form method="post">
				<input type="hidden" name="nonce" value="$nonce" />
				<p><label for="youtube_title_fix_api_key">Your Key  </label> <input type="text" size=60 id="youtube_title_fix_api_key" name="youtube_title_fix_api_key" value="$youtube_title_fix_api_key" /></p>

				<p><input type="submit" value="Submit" /></p>
			</form>
		</div>
HTML;
}


yourls_add_filter( 'shunt_get_remote_title', 'youtube_title_fix_get_remote_title' );
function youtube_title_fix_get_remote_title( $return , $url ) {

	$url = yourls_sanitize_url( $url );
	
	// only deal with http(s)
	if ( !in_array( yourls_get_protocol( $url ), array( 'http://', 'https://' ) ) )
		return 'PROTOCOL';
		
	// parse url and check host + querry string
	$parsed_url 	= parse_url( $url );	
	$host 		= isset($parsed_url['host']) ? $parsed_url['host'] : ''; 
	$query 	= isset($parsed_url['query']) ? $parsed_url['query'] : '';
	parse_str( $query , $array );
	
	if ( preg_match( '/(youtube|youtu\.be)/', $host ) && isset( $array['v'] ) ) {
		// we need an API key
		$API_KEY = yourls_get_option( 'youtube_title_fix_api_key' );
		if( $API_KEY ) {
			$vid  = $array['v'];
			$data = json_decode( file_get_contents( "https://www.googleapis.com/youtube/v3/videos?part=snippet&id=" . $vid ."&key=" . $API_KEY ) );
			$title = $data->items[0]->snippet->title;
			return $title;
		}
	}
	
	return false;
}
