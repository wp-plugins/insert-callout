<?php
/*
Plugin Name: Insert Callout
Plugin URI: http://componentoriented.com/wordpress/insert-callout
Description: Add a callout box in a post.
Version: 1.0.0
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
    global $starttag, $titletag, $closetag, $endtag;
    global $callout_options;
	
    $callout_style = $callout_options['callout_style'];
    $callout_title_style = $callout_options['callout_title_style'];
    $callout_body_style = $callout_options['callout_body_style'];

    $startpos = strpos($the_content, $starttag);
    if ($startpos !== false)
    {
		$titlepos = strpos($the_content, $titletag, $startpos);  	// this could be false
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

            $new_content = substr($the_content,0,$startpos);
            $new_content .= $the_callout;
            $new_content .= substr($the_content,($endpos + strlen($endtag)));

			return $new_content;
        }
		else	// error parsing tags.
		{
			return $the_content;
		}
	}
    else
    {
        return $the_content;
    }

}

add_action('admin_head', 'callout_admin_head');
function callout_admin_head()
{
    add_options_page('Insert Callout', 'Insert Callout', 'manage_options', 'insert-callout/options.php');
}

?>
