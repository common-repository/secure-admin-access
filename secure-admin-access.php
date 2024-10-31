<?php
/**
  Plugin Name: Secure Admin Access
  Plugin URI:
  Description: "secure-admin-access" is a very help full plugin to make wordpress admin more secure. Secure Admin Access plugin is provide the options for change the wp-admin url and make the login page private(directly user can't access the login page) And Limit Dashboard access to admins only.
  Author: Mahesh Kathiriya
  Author URI: https://easywordpresslearn.blogspot.com
  Version: 1.0
 */
if (!defined('ABSPATH'))
    exit;
/**
 * Initialize "Secure Admin Access" plugin admin menu 
 * @create new menu
 * @create plugin settings page
 */
add_action('admin_menu', 'init_saa_admin_menu');
if (!function_exists('init_saa_admin_menu')):

    function init_saa_admin_menu() {
        add_menu_page('Secure Admin Access', 'Secure Admin Access', 'manage_options', 'saa-plugin', 'init_saa_admin_option_page');
    }

endif;

/* Add the media uploader script for logo */

function saa_media_lib_uploader_enqueue() {
    wp_enqueue_media();
}

add_action('admin_enqueue_scripts', 'saa_media_lib_uploader_enqueue');


/** Define Action to register "Secure Admin Access" Options */
add_action('admin_init', 'init_saa_options_fields');
/** Register "Secure Admin Access" options */
if (!function_exists('init_saa_options_fields')):

    function init_saa_options_fields() {
        register_setting('saa_setting_options', 'saa_active');
        register_setting('saa_setting_options', 'saa_rewrite_text');
        register_setting('saa_setting_options', 'saa_restrict');
        register_setting('saa_setting_options', 'saa_logout');
        register_setting('saa_setting_options', 'saa_allow_custom_users');
        register_setting('saa_setting_options', 'saa_logo_path');
        register_setting('saa_setting_options', 'saa_login_page_bg_color');
    }

endif;
/** Add settings link to plugin list page in admin */
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'saa_action_links');
if (!function_exists('saa_action_links')):

    function saa_action_links($links) {
        $links[] = '<a href="' . get_admin_url(null, 'options-general.php?page=saa-plugin') . '">Settings</a>';
        return $links;
    }

endif;

/** Options Form HTML for "Secure Admin Access" plugin */
if (!function_exists('init_saa_admin_option_page')):

    function init_saa_admin_option_page() {

        if (isset($_POST['submit'])) {

            $saa_active = sanitize_text_field($_POST['saa_active']);
            $saa_active_login_attempt = sanitize_text_field($_POST['saa_active_login_attempt']);

            $saa_rewrite_text = sanitize_text_field($_POST['saa_rewrite_text']);
            $saa_logo_path = sanitize_text_field($_POST['saa_logo_path']);
            $saa_login_page_bg_color = sanitize_text_field($_POST['saa_login_page_bg_color']);
            $saa_restrict = sanitize_text_field($_POST['saa_restrict']);
            $saa_logout = sanitize_text_field($_POST['saa_logout']);
            $saa_allow_custom_users = sanitize_text_field($_POST['saa_allow_custom_users']);

            update_option('saa_active', $saa_active);
            update_option('saa_active_login_attempt', $saa_active_login_attempt);
            update_option('saa_rewrite_text', $saa_rewrite_text);
            update_option('saa_logo_path', $saa_logo_path);
            update_option('saa_login_page_bg_color', $saa_login_page_bg_color);
            update_option('saa_restrict', $saa_restrict);
            update_option('saa_logout', $saa_logout);
            update_option('saa_allow_custom_users', $saa_allow_custom_users);
        }

        if (get_option('permalink_structure')) {
            $permalink_structure_val = 'yes';
        } else {
            $permalink_structure_val = 'no';
        }
        ?>
    
<div class="saa-page-design"> 
    <div class="saa-heading">
    <h1 class="saa-title">Secure Admin Access Settings </h1>
    </div>
            <!-- Start Options Form action="options.php"-->
            <form  method="post" name="saa-plugin-form-admin" id="saa-plugin-form-admin">
                <input type="hidden"  id="check_permalink" value="<?php echo $permalink_structure_val; ?>">	
                <div id="saa-tab-menu">
                    <a id="saa-general" class="saa-tab-links active" >General</a> 
                    <a  id="saa-admin-login-attempt" class="saa-tab-links">Login Attempt Settings </a> 
                    <a  id="saa-admin-style" class="saa-tab-links">Login Page Style </a> 
                    <a  id="saa-advance" class="saa-tab-links">Advance Settings</a> 
                    <a  id="saa-support" class="saa-tab-links">Support</a> 
                </div>

                <div class="saa-setting">
                    <!-- General Setting -->	
                    <div class="first saa-tab" id="div-saa-general">
                        <h2>General Settings</h2>
                        <table class="saa-settings-form-table">  
                            <tr>
                                <td><label>Secure Admin </label></td>                           
                                <td><input type="checkbox" id="saa_active" name="saa_active" value='1' <?php
                                    if (get_option('saa_active') != '') {
                                        echo ' checked="checked"';
                                    }
                                    ?>/> Enable</td>
                            </tr> 
                            <tr>
                                <td><label> URL Slug</label></td>                             
                                <td><p id="adminurl"><input type="text" id="saa_rewrite_text" name="saa_rewrite_text" value="<?php echo esc_attr(get_option('saa_rewrite_text')); ?>"  placeholder="mypanel" size="30"> <br/>(<i>Add New Secure WP-Admin URL Slug ( example : mypanel )</i>)</p></td>
                            </tr>                         
                        </table> 
                        <p style="color: red;">Important : Don't forget to new admin url after update new slug.</p>
                        <?php
                        $getPwaOptions = get_saa_setting_options();
                        if ((isset($getPwaOptions['saa_active']) && '1' == $getPwaOptions['saa_active']) && (isset($getPwaOptions['saa_rewrite_text']) && $getPwaOptions['saa_rewrite_text'] != '')) {
                            echo "<p><strong>Your New Admin URL : </strong>" . home_url($getPwaOptions['saa_rewrite_text']) . " | <strong><blink><a href='" . home_url($getPwaOptions['saa_rewrite_text'] . '?preview=1') . "' target='_blank'>CLICK HERE</a></blink></strong> for preview new admin URL. <br/> If Not work please update Permalink Settings.</p>";
                        }
                        ?>
                    </div>
                    <!-- Login Attempt Settings --> 
                    <div class="saa-tab" id="div-saa-admin-login-attempt">
                        <h2>Login Attempt Settings </h2>
                        <table class="saa-settings-form-table"> 
                            <tr>
                                <td><label>Enable</label></td>                           
                                <td><input type="checkbox" id="saa_active_login_attempt" name="saa_active_login_attempt" value='1' <?php
                                    if (get_option('saa_active_login_attempt') != '') {
                                        echo ' checked="checked"';
                                    }
                                    ?>/></td>
                            </tr>
                            <tr>
                                <td>Number of login attempts</td>
                                <td><input disabled type="number" value="5" name="attempts" class="attempts" ></td>
                            </tr>
                            <tr>
                                <td>Lockdown time in minutes</td>
                                <td><input type="number" value="10"  name="delay" disabled class="delay" ></td>
                            </tr>
                        </table>
                    </div>
                    <!-- Admin Style -->
                    <div class="last author saa-tab" id="div-saa-admin-style">
                        <h2>WP-Admin Login Page Style Settings</h2>
                        <table class="saa-settings-form-table">  
                            <tr>
                                <td><label>Preview Logo</label></td>
                                <td ><img class="saa_custom_logo_src" src="<?php echo esc_attr(get_option('saa_logo_path')); ?>" width="100" height="100"/></td>

                            </tr>
                            <tr>
                                <td><label>Logo </label></td>
                                <td><p id="adminurl">                            
                                        <input type="text" id="saa_logo_path" name="saa_logo_path" value="<?php echo esc_attr(get_option('saa_logo_path')); ?>"  placeholder="Add Custom Logo Image Path" size="30">
                                        <a href="#" class="button saa_logo_upload">Upload</a>
                                        <br/>(<i>Change WordPress Default Login Logo </i>)</p></td>
                            </tr>                             
                            <tr>
                                <td><label>Background Color </label></td>
                                <td><p id="adminurl"><input type="color" id="saa_login_page_bg_color" name="saa_login_page_bg_color" value="<?php echo esc_attr(get_option('saa_login_page_bg_color')); ?>"  placeholder="#0000" size="30"></p></td>
                            </tr>
                        </table> 
                    </div>

                    <!-- Advance Setting -->	
                    <div class="saa-tab" id="div-saa-advance">
                        <h2>Advance Settings</h2>
                        <table class="saa-settings-form-table">  
                            <tr>
                                <td><label>Restrict registered non-admin users from wp-admin </label></td>
                                <td><input type="checkbox" id="saa_restrict" name="saa_restrict" value='1' <?php
                                    if (get_option('saa_restrict') != '') {
                                        echo ' checked="checked"';
                                    }
                                    ?>/></td>
                            </tr>
                            <tr>
                                <td><label>Logout Admin After Add/Update New Admin URL(Optional) </label> <br/>(This is only for security purpose)</td>
                                <td><input type="checkbox" id="saa_logout" name="saa_logout" value='1' <?php
                                    if (get_option('saa_logout') == '') {
                                        echo '';
                                    } else {
                                        echo 'checked="checked"';
                                    }
                                    ?>/> </td>
                            </tr>
                            <tr>
                                <td><label>Allow access to non-admin users</label><br/>(<i>Add comma seprated ids</i>)</td>
                                <td><input type="text" id="saa_allow_custom_users" name="saa_allow_custom_users" value="<?php echo esc_attr(get_option('saa_allow_custom_users')); ?>"  placeholder="42,33"></td>
                            </tr>
                        </table>
                    </div>
                    <!-- Support-->
                    <div class="saa-tab" id="div-saa-support">
                        <br/>
                        <b>Email:</b> <span>phpmk888@gmail.com</span>
                        <br/><br/>
                    </div>    

                </div>
                <span class="submit-btn"><?php echo get_submit_button('Save Settings', 'button-primary', 'submit', '', ''); ?></span>

        <?php settings_fields('saa_setting_options'); ?>
            </form>

            <!-- End Options Form -->
        </div>

        <?php
    }

endif;
/** add js into admin footer */
// better use get_current_screen(); or the global $current_screen
if (isset($_GET['page']) && $_GET['page'] == 'saa-plugin') {
    add_action('admin_footer', 'init_saa_admin_scripts');
}
if (!function_exists('init_saa_admin_scripts')):

    function init_saa_admin_scripts() {
        wp_register_style('saa_admin_style', plugins_url('admin/css/saa-admin-min.css', __FILE__));
        wp_enqueue_style('saa_admin_style');
       
        /* check .htaccess file writeable or not */
        $csbwfsHtaccessfilePath = getcwd() . "/.htaccess";
        $csbwfsHtaccessfilePath = str_replace('/wp-admin/', '/', $csbwfsHtaccessfilePath);

        if (file_exists($csbwfsHtaccessfilePath)) {
            if (is_writable($csbwfsHtaccessfilePath)) {
                $htaccessWriteable = "1";
            } else {
                $htaccessWriteable = "0";
            }
        } else {
            $htaccessWriteable = "0";
        }
        $localHostIP = $_SERVER['REMOTE_ADDR'];
        $saaActive = get_option('saa_active');
//$saaNewSlug=get_option('saa_rewrite_text');
//print_r($_SERVER); exit;
        echo $script = '<script type="text/javascript">
	/* Secure Admin Access js for admin */
	jQuery(document).ready(function(){
                jQuery(".saa_logo_upload").click(function(e) {
                    e.preventDefault();

                    var saa_custom_logo_uploader = wp.media({
                        title: "Upload Custom Login Page Logo",
                        button: {
                            text: "Upload Custom Login Page Logo"
                        },
                        multiple: false  // Set this to true to allow multiple files to be selected
                    })
                    .on("select", function() {
                        var attachment = saa_custom_logo_uploader.state().get("selection").first().toJSON();
                        jQuery(".saa_custom_logo_src").attr("src", attachment.url);
                        jQuery("#saa_logo_path").val(attachment.url);
                    })
                    .open();
                });

		jQuery(".saa-tab").hide();
		jQuery("#div-saa-general").show();
	    jQuery(".saa-tab-links").click(function(){
		var divid=jQuery(this).attr("id");
		jQuery(".saa-tab-links").removeClass("active");
		jQuery(".saa-tab").hide();
		jQuery("#"+divid).addClass("active");
		jQuery("#div-"+divid).fadeIn();
		});
		   
	   jQuery("#saa-plugin-form-admin .button-primary").click(function(){
		 var $el = jQuery("#saa_active");
		 var $vlue = jQuery("#saa_rewrite_text").val();
		 var saaActive ="' . $saaActive . '";
		 /*if((!$el[0].checked) && $vlue=="")
		 {
			 	 alert("Please enable plugin");
			 	 return false;
			 }*/
			 
		 if(($el[0].checked) && $vlue=="")
		 {
			 	 jQuery("#saa_rewrite_text").css("border","1px solid red");
			 	 jQuery("#adminurl").append(" <strong style=\'color:red;\'>Please enter admin url slug</strong>");
			 	 return false;
			 }
			
			if(($el[0].checked) && saaActive==""){
				//alert(saaActive);
	if (confirm("1. Have you updated your permalink settings?\n\n2. Have you checked writable permission on htaccess file?\n\nIf your answer is YES then Click OK to continue")){
          return true;
      }else
      {
		  return false;
		  }
		 }
			var seoUrlVal=jQuery("#check_permalink").val();
			var htaccessWriteable ="' . $htaccessWriteable . '";
			var hostIP ="' . $localHostIP . '";
		//	alert(hostIP);
			if(seoUrlVal=="no")
			{
			alert("Please update permalinks before activate the plugin. permalinks option should not be default!.");
			document.location.href="' . admin_url('options-permalink.php') . '";
			return false;
				}
				/*else if(htaccessWriteable=="0" && hostIP!="127.0.0.1"){
					alert("Error : .htaccess file is not exist OR may be htaccess file is not writable, So please double check it before enable the plugin");
					return false;
					}*/
				else
				{
					return true;
					}
			});
	
		})
	</script>';
    }

endif;

// Add Check if permalinks are set on plugin activation
register_activation_hook(__FILE__, 'is_permalink_activate');
if (!function_exists('is_permalink_activate')):

    function is_permalink_activate() {
        //add notice if user needs to enable permalinks
        if (!get_option('permalink_structure'))
            add_action('admin_notices', 'permalink_structure_admin_notice');
    }

endif;
if (!function_exists('permalink_structure_admin_notice')):

    function permalink_structure_admin_notice() {
        echo '<div id="message" class="error"><p>Please Make sure to enable <a href="options-permalink.php">Permalinks</a>.</p></div>';
    }

endif;
/** register_install_hook */
if (function_exists('register_install_hook')) {
    register_uninstall_hook(__FILE__, 'init_install_saa_plugins');
}
//flush the rewrite
if (!function_exists('init_install_saa_plugins')):

    function init_install_saa_plugins() {
        flush_rewrite_rules();
    }

endif;
/** register_uninstall_hook */
/** Delete exits options during disable the plugins */
if (function_exists('register_uninstall_hook')) {
    register_uninstall_hook(__FILE__, 'flush_rewrite_rules');
    register_uninstall_hook(__FILE__, 'init_uninstall_saa_plugins');
}

//Delete all options after uninstall the plugin
if (!function_exists('init_uninstall_saa_plugins')):

    function init_uninstall_saa_plugins() {
        delete_option('saa_active');
        delete_option('saa_rewrite_text');
        delete_option('saa_restrict');
        delete_option('saa_logout');
        delete_option('saa_allow_custom_users');
        delete_option('saa_logo_path');
        delete_option('saa_login_page_bg_color');
        
        
    }

endif;
require dirname(__FILE__) . '/saa-class.php';

/** register_deactivation_hook */
/** Delete exits options during deactivation the plugins */
if (function_exists('register_deactivation_hook')) {
    register_deactivation_hook(__FILE__, 'init_deactivation_saa_plugins');
}

//Delete all options after uninstall the plugin
if (!function_exists('init_deactivation_saa_plugins')):

    function init_deactivation_saa_plugins() {
        delete_option('saa_active');
        delete_option('saa_logout');
        delete_option('saa_active_login_attempt');
        remove_action('init', 'init_saa_admin_rewrite_rules');
        flush_rewrite_rules();
    }

endif;


/** register_activation_hook */
/** Delete exits options during disable the plugins */
if (function_exists('register_activation_hook')) {
    register_activation_hook(__FILE__, 'init_activation_saa_plugins');
}
//Delete all options after uninstall the plugin
if (!function_exists('init_activation_saa_plugins')):

    function init_activation_saa_plugins() {
        //Secure Login Limit
        global $wpdb;
        $tablename = $wpdb->prefix . "saa_limit_login";
        if ($wpdb->get_var("SHOW TABLES LIKE '$tablename'") != $tablename) {

            $sql = "CREATE TABLE `$tablename`  (
		`login_id` INT(11) NOT NULL AUTO_INCREMENT,
		`login_ip` VARCHAR(50) NOT NULL,
        `login_attempts` INT(11) NOT NULL,
		`attempt_time` DATETIME,
		`locked_time` VARCHAR(100) NOT NULL,
		PRIMARY KEY  (login_id)
		);";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
        //Add options 
        add_option('no_of_saa_login_attepts', '5', '', 'no');
        add_option('saa_limit_login_attepts_delay_time', '10', '', 'no');
        add_option('saa_limit_login_install_date', date('Y-m-d G:i:s'), '', 'yes');
        //end login 

        delete_option('saa_logout');
        flush_rewrite_rules();
    }

endif;

add_action('admin_init', 'saa_flush_rewrite_rules');
//flush_rewrite_rules after update value
if (!function_exists('saa_flush_rewrite_rules')):

    function saa_flush_rewrite_rules() {
        if (isset($_POST['option_page']) && $_POST['option_page'] == 'saa_setting_options' && $_POST['saa_active'] == '') {
            flush_rewrite_rules();
            flush_rewrite_rules();
        }
    }

endif;

/**
 * Saa Login Attempts Function 
 */
$saaActiveLoginAttempt = get_option('saa_active_login_attempt');
if ($saaActiveLoginAttempt) {
    add_action('plugins_loaded', 'saa_login_init', 1);
}

function saa_login_init() {

    add_action('wp_login_failed', 'saa_login_failed');
    add_action('login_errors', 'saa_login_errors');
    add_filter('authenticate', 'saa_login_auth_signon', 30, 3);
    add_action('admin_init', 'saa_login_admin_init');

    function saa_login_failed($username) {

        global $msg, $ip, $wpdb;

        $ip = saa_getip();

        $tablename = $wpdb->prefix . "saa_limit_login";
        $tablerows = $wpdb->get_results("SELECT `login_id`, `login_ip`,`login_attempts`,`attempt_time`,`locked_time` FROM  `$tablename`   WHERE `login_ip` =  '$ip'  ORDER BY `login_id` DESC LIMIT 1 ");

        if (count($tablerows) == 1) {
            $attempt = $tablerows[0]->login_attempts;
            $noofattmpt = get_option('no_of_saa_login_attepts', 5);
            if ($attempt <= $noofattmpt) {
                $attempt = $attempt + 1;
                $update_table = array(
                    'login_id' => $tablerows[0]->login_id,
                    'login_attempts' => $attempt
                );
                $wpdb->update($tablename, $update_table, array('login_id' => $tablerows[0]->login_id));
                $no_ofattmpt = $noofattmpt + 1;
                $remain_attempt = $no_ofattmpt - $attempt;
                $msg = $remain_attempt . ' attempts remaining..!';
                return $msg;
            } else {
                if (is_numeric($tablerows[0]->locked_time)) {
                    $attempt = $attempt + 1;
                    $update_table = array(
                        'login_id' => $tablerows[0]->login_id,
                        'login_attempts' => $attempt,
                        'locked_time' => date('Y-m-d G:i:s')
                    );
                    $wpdb->update($tablename, $update_table, array('login_id' => $tablerows[0]->login_id));
                } else {
                    $attempt = $attempt + 1;
                    $update_table = array(
                        'login_id' => $tablerows[0]->login_id,
                        'login_attempts' => $attempt
                    );
                    $wpdb->update($tablename, $update_table, array('login_id' => $tablerows[0]->login_id));
                }
                $delay_time = get_option('saa_limit_login_attepts_delay_time');
                $msg = "The maximum number of login attempts has been reached. Please try again in " . $delay_time . " minutes";
                return $msg;
            }

            $time_now = date_create(date('Y-m-d G:i:s'));
            $attempt_time = date_create($tablerows[0]->attempt_time);
            $interval = date_diff($attempt_time, $time_now);
        } else {
            global $wpdb;
            $tablename = $wpdb->prefix . "saa_limit_login";
            $newdata = array(
                'login_ip' => $ip,
                'login_attempts' => 1,
                'attempt_time' => date('Y-m-d G:i:s'),
                'locked_time' => 0
            );
            $wpdb->insert($tablename, $newdata);
            $remain_attempt = get_option('no_of_saa_login_attepts', 5);
            $msg = $remain_attempt . ' attempts remaining!';
            return $msg;
        }
    }

    function saa_login_admin_init() {
        if (is_user_logged_in()) {
            global $wpdb;
            $tablename = $wpdb->prefix . "saa_limit_login";
            $ip = saa_getip();
            saa_login_nag_ignore();
            $tablerows = $wpdb->get_results("SELECT `login_id`, `login_ip`,`login_attempts`,`locked_time` FROM  `$tablename`   WHERE `login_ip` =  '$ip'  ORDER BY `login_id` DESC LIMIT 1 ");
            if (count($tablerows) == 1) {
                $update_table = array(
                    'login_id' => $tablerows[0]->login_id,
                    'login_attempts' => 0,
                    'locked_time' => 0
                );
                $wpdb->update($tablename, $update_table, array('login_id' => $tablerows[0]->login_id));
            }
        }
    }

    function saa_login_errors($error) {
        global $msg;
        $pos_first = strpos($error, 'Proxy');
        $pos_second = strpos($error, 'wait');

        if (is_int($pos_first)) {
            $error = "Sorry! Proxy detected..!";
        } else if ($pos_second) {
            $delay_time = get_option('saa_limit_login_attepts_delay_time', 10);
            $error = "Sorry! Please wait " . $delay_time . " minutes!";
        } else {
            $error = "<strong>Login Failed</strong>: Sorry! Wrong login or password!  </br>" . $msg;
        }
        return $error;
    }

    function saa_login_auth_signon($user, $username, $password) {

        global $ip, $msg, $wpdb;
        $ip = saa_getip();

        if (empty($username) || empty($password)) {
            //do_action( 'wp_login_failed' );
        }

        $tablename = $wpdb->prefix . "saa_limit_login";
        $tablerows = $wpdb->get_results("SELECT `login_id`, `login_ip`,`login_attempts`,`attempt_time`,`locked_time` FROM  `$tablename`   WHERE `login_ip` =  '$ip'  ORDER BY `login_id` DESC LIMIT 1 ");
        if (count($tablerows) == 1) {
            $time_now = date_create(date('Y-m-d G:i:s'));
            $attempt_time = date_create($tablerows[0]->attempt_time);
            $interval = date_diff($attempt_time, $time_now);

            if (($interval->format("%s")) <= 1) {
                if (($tablerows[0]->login_attempts) != 0) {
                    wp_redirect(home_url());
                    exit;
                } else {
                    return $user;
                }
            } else {

                if (!is_numeric($tablerows[0]->locked_time)) {
                    $locked_time = date_create($tablerows[0]->locked_time);
                    $time_now = date_create(date('Y-m-d G:i:s'));
                    $interval = date_diff($locked_time, $time_now);

                    $delay_time = get_option('saa_limit_login_attepts_delay_time', 10);
                    if (($interval->format("%i")) <= $delay_time) {

                        $msg = "Sorry! Please wait" . $delay_time . " minutes!";
                        $error = new WP_Error();
                        $error->add('wp_to_many_try', $msg);
                        return $error;
                    } else {

                        $update_table = array(
                            'login_id' => $tablerows[0]->login_id,
                            'login_attempts' => 0,
                            'attempt_time' => date('Y-m-d G:i:s'),
                            'locked_time' => 0
                        );
                        $wpdb->update($tablename, $update_table, array('login_id' => $tablerows[0]->login_id));
                        return $user;
                    }
                } else {
                    return $user;
                }
            }
        } else {
            return $user;
        }
    }

    function saa_getip() {

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = esc_sql($_SERVER['HTTP_CLIENT_IP']);
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = esc_sql($_SERVER['HTTP_X_FORWARDED_FOR']);
        } else {
            $ip = esc_sql($_SERVER['REMOTE_ADDR']);
            if ($ip == '::1') {
                $ip = '127.0.1.6';
            }
        }

        if ((!isset($_SESSION["IP_hash"]) ) && (empty($_SESSION["IP_hash"]) )) {
            $_SESSION["IP_hash"] = md5($ip);
        } else {
            if (!empty($_SESSION["IP_hash"]) && ( $_SESSION["IP_hash"] != md5($ip) )) {
                session_unset();
            }
        }

        return $ip;
    }

    function saa_login_nag_ignore() {
        global $current_user;
        $user_id = $current_user->ID;
        /* user to ignore the notice, add that to their user meta */
        if (isset($_GET['saa_login_nag_ignore']) && '0' == $_GET['saa_login_nag_ignore']) {
            add_user_meta($user_id, 'saa_login_nag_ignore', 'true', true);
        }
    }

}
?>
