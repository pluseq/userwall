<?php
/*
Plugin Name: UserWall
Plugin URI: http://pluseq.com/wp-plugins/userwall/
Description: Protect part of the page depending on level
Version: 1.0.0.
Author: Constantin Pogorelov

-----------------------------------------------------------

If you want the page (or part of the page) to be displayed
to registered users only, just enclose the content into the
shortcode like this:

  some content ...
  [userwall]
     this can only be seen to registered users
  [/userwall]
  another content...

You can optionally require certain userlevel, instead of
just being registered. See userlevel docs here: 
http://codex.wordpress.org/User_Levels

  [userwall level="1"]
      this can only be seen to editors only
  [/userwall]

*/

add_shortcode('userwall', 'shortcode_userwall');

function shortcode_userwall($atts, $content=null) {
	global $current_user;
    get_currentuserinfo();
    if (isset($current_user->user_login) && strlen($current_user->user_login)) {
    	if (isset($atts['level'])) {
    	    if (@$current_user->allcaps['level_' . $atts['level']]) {
    	    	return $content;
    	    }
    	} else {
    	    return $content;
    	}
    }
       
    $message = (isset($atts['message']))?
        $atts['message'] :
        'You are forbidden to view this content. (login)Log in(/login)';
    
    $openTag = '<a href="' . attribute_escape(site_url('wp-login.php?redirect_to=' . $_SERVER['REQUEST_URI'])) . '">';     
        
    $message = str_replace('(login)', $openTag, $message);
    $message = str_replace('(/login)', '</a>', $message);    
    return $message;
}