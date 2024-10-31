<?php
/*
 * Secure Admin Access
 * @register_install_hook()
 * @register_uninstall_hook()
 * */
?>
<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $getPwaOptions;
/** Get all options value */
if(!function_exists('get_saa_setting_options')):
function get_saa_setting_options() {
		global $wpdb;
		$saaOptions = $wpdb->get_results("SELECT option_name, option_value FROM $wpdb->options WHERE option_name LIKE 'saa_%'");
								
		foreach ($saaOptions as $option) {
			$saaOptions[$option->option_name] =  $option->option_value;
		}
		return $saaOptions;	
	}
endif;	

GLOBAL  $getPwaOptions;
$getPwaOptions = get_saa_setting_options();
if(isset($getPwaOptions['saa_active']) && '1'==$getPwaOptions['saa_active'])
{
add_action('login_enqueue_scripts','saa_load_jquery');
add_action('init', 'init_saa_admin_rewrite_rules' );
add_action('init', 'saa_admin_url_redirect_conditions' );
add_action('login_head', 'saa_update_login_page_logo');
add_action('login_footer','saa_custom_script',5);
add_action('login_enqueue_scripts','check_login_status',20);

	if(isset($getPwaOptions['saa_logout']))
	{
	add_action('admin_init', 'saa_logout_user_after_settings_save');
	add_action('admin_init', 'saa_logout_user_after_settings_save');
	}
}
if(!function_exists('check_login_status')):
	function check_login_status()
	{
		$getPwaOptions = get_saa_setting_options();
		$current_uri = saa_get_current_page_url($_SERVER);
		$newadminurl = home_url($getPwaOptions['saa_rewrite_text']);
		 if ( is_user_logged_in() && $current_uri==$newadminurl) 
		 {
				wp_redirect(admin_url()); die();
			} else {
				//echo 'slient';
			}
		
		
		}
endif;

if(!function_exists('saa_logout_user_after_settings_save')):
function saa_logout_user_after_settings_save()
{
	$getPwaOptions=get_saa_setting_options();
    if(isset($_GET['settings-updated']) && $_GET['settings-updated'] && isset($_GET['page']) && $_GET['page']=='saa-settings')
    {
    flush_rewrite_rules();
	}
	
  if(isset($_GET['settings-updated']) && $_GET['settings-updated'] && isset($_GET['page']) && $_GET['page']=='saa-settings' && isset($getPwaOptions['saa_logout']) && $getPwaOptions['saa_logout']==1)
   {
     $URL=str_replace('&amp;','&',wp_logout_url());
      if(isset($getPwaOptions['saa_rewrite_text']) && isset($getPwaOptions['saa_logout']) && $getPwaOptions['saa_logout']==1 && $getPwaOptions['saa_rewrite_text']!=''){
      wp_redirect(home_url('/'.$getPwaOptions['saa_rewrite_text']));
     }else
     {
		 //silent
		 }
     //wp_redirect($URL);
   }
}
endif;
/** Create a new rewrite rule for change to wp-admin url */
if(!function_exists('init_saa_admin_rewrite_rules')):
function init_saa_admin_rewrite_rules() {
	$getPwaOptions=get_saa_setting_options();
    if(isset($getPwaOptions['saa_active']) && ''!=$getPwaOptions['saa_rewrite_text']){
	$newurl=strip_tags($getPwaOptions['saa_rewrite_text']);
    add_rewrite_rule( $newurl.'/?$', 'wp-login.php', 'top' );
    add_rewrite_rule( $newurl.'/register/?$', 'wp-login.php?action=register', 'top' );
    add_rewrite_rule( $newurl.'/lostpassword/?$', 'wp-login.php?action=lostpassword', 'top' );
    
    }
}
endif;
/** 
 * Update Login, Register & Forgot password link as per new admin url
 * */
if(!function_exists('saa_load_jquery')):
function saa_load_jquery()
{
wp_enqueue_script("jquery"); 
}
endif;

if(!function_exists('saa_custom_script')):
function saa_custom_script()
{	
$getPwaOptions=get_saa_setting_options();
if(isset($getPwaOptions['saa_active']) && ''!=$getPwaOptions['saa_rewrite_text']){

echo '<script>jQuery(document).ready(function(){
	jQuery("#login #login_error a").attr("href","'.home_url($getPwaOptions["saa_rewrite_text"].'/lostpassword').'");
	jQuery("body.login-action-resetpass p.reset-pass a").attr("href","'.home_url($getPwaOptions["saa_rewrite_text"].'/').'");
	var formId= jQuery("#login form").attr("id");
if(formId=="loginform"){
	jQuery("#"+formId).attr("action","'.home_url($getPwaOptions["saa_rewrite_text"]).'");
	}else if("lostpasswordform"==formId){
			jQuery("#"+formId).attr("action","'.home_url($getPwaOptions["saa_rewrite_text"].'/lostpassword').'");
			jQuery("#"+formId+" input:hidden[name=redirect_to]").val("'.home_url($getPwaOptions["saa_rewrite_text"].'/?checkemail=confirm').'");
		}else if("registerform"==formId){
			jQuery("#"+formId).attr("action","'.home_url($getPwaOptions["saa_rewrite_text"].'/register').'");
			}
		else
			{
				//silent
				}
				//alert(jQuery("#nav a").slice(0).attr("href"));
				';
				$currentUrl = saa_get_current_page_url($_SERVER);			
          echo 'jQuery("#nav a").each(function(){
           /* var linkText=jQuery(this).attr("href");
            
            if(linkText.indexOf("?action=register") >= 0)
            {
                //jQuery(this).attr("href","'.home_url($getPwaOptions["saa_rewrite_text"].'/register').'");
            }
            
            if(linkText.indexOf("?action=lostpassword") >= 0)
            {
               // jQuery(this).attr("href","'.home_url($getPwaOptions["saa_rewrite_text"].'/lostpassword').'");
            }
            */
            var linkText = jQuery(this).attr("href").match(/[^/]*(?=(\/)?$)/)[0];
            if(linkText=="wp-login.php"){jQuery(this).attr("href","'.home_url($getPwaOptions["saa_rewrite_text"]).'");}
			else if(linkText=="wp-login.php?action=register"){jQuery(this).attr("href","'.home_url($getPwaOptions["saa_rewrite_text"].'/register').'");}else if(linkText=="wp-login.php?action=lostpassword"){jQuery(this).attr("href","'.home_url($getPwaOptions["saa_rewrite_text"].'/lostpassword').'");}else { 
				//silent
				}	
        });});</script>';
}

}
endif;

if(!function_exists('saa_admin_url_redirect_conditions')):
function saa_admin_url_redirect_conditions()
{
	$getPwaOptions=get_saa_setting_options();
	$saaActualURLAry =array
	                       (
                           home_url('/wp-login.php'),
                           home_url('/wp-login.php/'),
                           home_url('/wp-login'),
                           home_url('/wp-login/'),
                           home_url('/wp-admin'),
                           home_url('/wp-admin/'),
                           );
    $request_url = saa_get_current_page_url($_SERVER);
    $newUrl = explode('?',$request_url);
	//print_r($saaActualURLAry); echo $newUrl[0];exit;
if(! is_user_logged_in() && in_array($newUrl[0],$saaActualURLAry) ) 
	{

/** is forgot password link */
if( isset($_GET['login']) && isset($_GET['action']) && $_GET['action']=='rp' && $_GET['login']!='')
{
$username = $_GET['login'];
if(username_exists($username))
{
//silent
}else{ wp_redirect(home_url('/'),301); //exit;
}
}elseif(isset($_GET['action']) && $_GET['action']=='rp')
{
	//silent
	}
elseif(isset($_GET['action']) && isset($_GET['error']) && $_GET['action']=='lostpassword' && $_GET['error']=='invalidkey')
{
	$redirectUrl=home_url($getPwaOptions["saa_rewrite_text"].'/?action=lostpassword&error=invalidkey');
	wp_redirect($redirectUrl,301);//exit;
	}
elseif(isset($_GET['action']) && $_GET['action']=='resetpass')
{
// silent 
	}
	else{

	wp_redirect(home_url('/'),301);//exit;
	   }


		//exit;
		}
		else if(isset($getPwaOptions['saa_restrict']) && $getPwaOptions['saa_restrict']==1 && is_user_logged_in())
		{
			global $current_user;
	        $user_roles = $current_user->roles;
	        $user_ID = $current_user->ID;
	        $user_role = array_shift($user_roles);
	        
	        if(isset($getPwaOptions['saa_allow_custom_users']) && $getPwaOptions['saa_allow_custom_users']!='')
	        {
				$userids=explode(',' ,$getPwaOptions['saa_allow_custom_users']);
				
				if(is_array($userids))
				{
					$userids=explode(',' ,$getPwaOptions['saa_allow_custom_users']);
					}else
					{
						$userids[]=$getPwaOptions['saa_allow_custom_users'];
						}
				}else
				{
					$userids=array();
					}
	        
			if($user_role=='administrator' || in_array($user_ID,$userids))
			{
				//silent is gold
				}else
				{
					wp_redirect(home_url('/'));//exit;
					}
			}else
			{
				//silent is gold
				}
	
}
endif;
/** Get the current url*/
if(!function_exists('saa_current_path_protocol')):
function saa_current_path_protocol($s, $use_forwarded_host=false)
{
    $saahttp = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
    $saasprotocal = strtolower($s['SERVER_PROTOCOL']);
    $saa_protocol = substr($saasprotocal, 0, strpos($saasprotocal, '/')) . (($saahttp) ? 's' : '');
    $port = $s['SERVER_PORT'];
    $port = ((!$saahttp && $port=='80') || ($saahttp && $port=='443')) ? '' : ':'.$port;
    $host = ($use_forwarded_host && isset($s['HTTP_X_FORWARDED_HOST'])) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
    $host = isset($host) ? $host : $s['SERVER_NAME'] . $port;
    return $saa_protocol . '://' . $host;
}
endif;
if(!function_exists('saa_get_current_page_url')):
function saa_get_current_page_url($s, $use_forwarded_host=false)
{
    return saa_current_path_protocol($s, $use_forwarded_host) . $s['REQUEST_URI'];
}
endif;
/* Change Wordpress Default Logo */
if(!function_exists('saa_update_login_page_logo')):
	function saa_update_login_page_logo() 
	{
	  
	  $getPwaOptions=get_saa_setting_options();
	   // get logo height and width
	   $imagelogo = $getPwaOptions['saa_logo_path'];
	   if($imagelogo!=''){
	   $logoimagesize = getimagesize($imagelogo);
	   $logwigdth =$logoimagesize[0]; 
	   $logheight = $logoimagesize[1];
	   }
		echo '<style type="text/css"> /* Secure Admin Access Style*/';
		
		if(isset($getPwaOptions['saa_logo_path']) && $getPwaOptions['saa_logo_path']!=''){
		  echo ' h1 a { background-image:url('.$getPwaOptions['saa_logo_path'].') !important; width:'.$logwigdth.'px !important; height:'.$logheight.'px !important;background-size: inherit !important;}';
	  }
		  
		if(isset($getPwaOptions['saa_login_page_bg_color']) && $getPwaOptions['saa_login_page_bg_color']!='')
		echo ' body.login-action-login,html{ background:'.$getPwaOptions['saa_login_page_bg_color'].' !important; height: 100% !important;}';
		
		echo '</style>';
	   
	}
endif;
?>
