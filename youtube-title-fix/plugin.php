<?php
/*
Plugin Name: YouTube Title Fix
Plugin URI: https://github.com/joshp23/YOURLS-YouTube-title-fix
Description: Fetch YouTube Titles via Google API
Version: 1.0.0
Author: Josh Panter
Author URI: https://unfettered.net
*/

// No direct call
if( !defined( 'YOURLS_ABSPATH' ) ) die();
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
		if( !defined( 'YOUTUBE_TITLE_FIX_API_KEY' ) ) 
			return false;
			
		$API_KEY = YOUTUBE_TITLE_FIX_API_KEY;
		$vid  = $array['v'];
		$data = json_decode( file_get_contents( "https://www.googleapis.com/youtube/v3/videos?part=snippet&id=" . $vid ."&key=" . $API_KEY ) );
		
		$title = $data->items[0]->snippet->title;
		return $title;
		
	} else
		return false;
}
