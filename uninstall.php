<?php 

//if uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}

    global $wpdb;
    $tablename = $wpdb->prefix."saa_limit_login"; 

    if($wpdb->get_var("SHOW TABLES LIKE '$tablename'") == $tablename ){
        $sql = "DROP TABLE `$tablename`;";  
        $wpdb->query($sql);
    }

    //Delete options 
    delete_option('no_of_saa_login_attepts');
    delete_option('saa_limit_login_attepts_delay_time');
    delete_option('saa_limit_login_install_date');
    delete_option('saa_active_login_attempt');
?>