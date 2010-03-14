<?php
/*
Plugin Name: Insert Callout
Plugin URI: http://componentoriented.com/wordpress/insert-callout
Description: Add a callout box in a post.
Version: 1.0.3
Author: D. Lambert
Author URI: http://blog.componentoriented.com
Disclaimer: Use at your own risk. No warranty expressed or implied is provided.
*/

/*	Copyright 2009  Component Oriented  (email : dlambert@componentoriented.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

$starttag = "[callout";
$titletag = "title=";
$closetag = "]";
$endtag = "[/callout]";

$callout_options = get_option('calloutopts');

add_filter('the_content', 'callout_post');

function callout_post($the_content)
{
   
    preg_match_all("/\[callout.*\/callout\]/i",
    $the_content, $match, PREG_PATTERN_ORDER);
	
	foreach($match[0] as $value)
        {
        	$startpos = strpos($the_content, $value);
        	$endpos = $startpos + strlen($value);
        	$callout = build_callout($value);
        	
        	$newcontent = str_replace($value, $callout, $the_content);
        	$the_content = $newcontent;
        }
	return $the_content;        
}


/*
 *   We call this function once we've got a callout string to process.
 *   The $value var will contain the callout to process.  We'll process
 *   the tags in this callout, returning a ready-to-display callout.
 */
function build_callout($the_content)
{
    global $starttag, $titletag, $closetag, $endtag;
    global $callout_options;	
	
	$callout_style = $callout_options['callout_style'];
    $callout_title_style = $callout_options['callout_title_style'];
    $callout_body_style = $callout_options['callout_body_style'];

	$titlepos = strpos($the_content, $titletag, 0);  	// this could be false
	$closepos = strpos($the_content, $closetag, $titlepos); 	// this should not be false
	if ($closepos !== false)
	{
		$endpos = strpos($the_content, $endtag, $closepos);
		if (($titlepos !== false) && ($titlepos < $endpos))
		{
			$titlelen = $closepos - ($titlepos + strlen($titletag));
			$the_title = substr($the_content, $titlepos + strlen($titletag), $titlelen);
		}
		else
		{
			$the_title = 'No title';
		}
		$the_body = substr($the_content, $closepos + 1, $endpos - $closepos - 1);

		$the_callout = '<DIV style="'.$callout_style.'"><DIV style="'.$callout_title_style.
						'">'.$the_title.'</DIV><DIV style="'.$callout_body_style.
						'">'.$the_body.'</DIV></DIV>';

		return $the_callout;
    }
	else	// error parsing tags.
	{
		return $the_content;
	}
}


function metabox ($post)
{
	global $wp_meta_boxes;
	if (isset ($wp_meta_boxes['post']['normal']['sorted']['calloutstuff']))
		unset ($wp_meta_boxes['post']['normal']['sorted']['calloutstuff']);
		
	global $post;

	//	echo	'
	//		 <input type="text" ' .
	//		'name="ic__text" ' .
	//		'id="ic_text" size="75" value="" />';
	echo '<p>Add a callout to your post using the following syntax: </p>' .
		 '<p>[callout title=Callout Title]The body of your callout goes here.[/callout]</p>';
}


/**
 * Callout help on post edit screen.
 
function callout_post_options() {
	global $post;

	echo	'<div class="postbox"><div title="Click to toggle" class="handlediv"><br></div>' .
			'<h3 class="hndle"><span>' . __('Insert Callout', 'insert-callout') .
			'</span></h3><div class="inside"><p>';

		echo	'
			 <input type="text" ' .
			'name="ic__text" ' .
			'id="ic_text" size="75" value="" />';
		//if($tt_auto_text != '')
		//	echo	htmlentities($tt_auto_text);
		//elseif(tt_option('tt_auto_tweet_text') != '')
		//	echo	htmlentities(tt_option('tt_auto_tweet_text'));
	
	do_action('tt_post_options');
	echo	'</p></div></div>';
}
*/

function callout_admin_menu()
{
    add_options_page('Insert Callout', 'Insert Callout', 'manage_options', 'insert-callout/options.php');
    add_meta_box ('insertcallout', __ ('Insert Callout', 'headspace'), 'metabox', 'post', 'normal', 'high');
	add_meta_box ('insertcallout', __ ('Insert Callout', 'headspace'), 'metabox', 'page', 'normal', 'high');
    
}


add_action('admin_menu', 'callout_admin_menu');
//add_action('edit_form_advanced', 'callout_post_options');


?>
