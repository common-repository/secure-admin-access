=== Secure Admin Access ===

Contributors: maheshkathiriya
Donate link: 
Tags: Secure Admin Access, login security, Login Attempts, login attempt, limit login attempts, login, Dashboard access ,Secure wordpress admin,Secure Admin,Admin,Scure Wordpress Admin,Rename Admin URL, Rename Wordpress Admin URL,Change wp-admin url,Change Admin URL,Change Admin Path, Restrict wp-admin
Requires at least: 3.3
Tested up to: 4.7.4
Stable tag: 4.7.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Secure Your Website Admin And Dashboard Access & Modify Login Page Design & Login Attempts for login protection

== Description ==

If you run a WordPress website, you should absolutely use "Secure-Admin-Access" to secure it against hackers.

Secure Admin Access fixes a glaring security hole in the WordPress community: the well-known problem of the admin panel URL.
Everyone knows where the admin panel, and this includes hackers as well.

Secure Admin Access helps solve this problem by allowing webmasters to customize their admin panel URL and blocking the default links.

After you setup Secure Admin Access, webmasters will be able to change the "yourwebsitename.com/wp-admin" link into something like "yourwebsitename.com/your-custom-string".
All queries for the classic "/wp-admin/" and "wp-login.php" files will be redirected to the homepage, while access to the WP backend will be allowed only for the custom URL.

The plugin also comes with some access filters, allowing webmasters to restrict guest and registered users access to wp-admin, just in case you want some of your editors to log in the classic way.

**NOTE :Back up your database before beginning the activate plugin.**
It is extremely important to back up your database before beginning the activate plugin. If, for some reason, you find it necessary to restore your database from these backups. Plugin will not work for IIS.

= Features =

 * Limit Dashboard access to admins only, admins + editors, admins + editors + authors, or limit by specific capability.
 * Create your own redirect URL
 * Optionally allow user profile access
 * Define custom wp-admin url(Like http://yourdomain.com/mypanel)
 * Define custom Logo OR change default logo on login page
 * Define body background color on login page 
 * SEO friendly URL for "Register" page (Like http://yourdomain.com/mypanel/register)
 * SEO friendly URL for "Lost Password" page (Like http://yourdomain.com/mypanel/lostpassword)
 * Restrict guest users for access to wp-admin
 * Restrict registered non-admin users from wp-admin
 * Allow admin access to non-admin users by define comma seprate multiple ids users 
 * Login Security 
 * Limit Login Attempts and track user login attempts
 * Login attempts and block IP temporarily
 * Much more!
 
== Installation ==

In most cases you can install automatically from WordPress.
  * Search ‘Secure Admin Access’ from the Install Plugins screen.
  * Install plugin, click Activate.

However, if you install this manually, follow these steps:

 * Step 1. Upload "secure-admin-access" folder to the `/wp-content/plugins/` directory
 * Step 2. Activate the plugin through the Plugins menu in WordPress
 * Step 3. Go to Settings "Secure Admin Access" and configure the plugin settings.

== Frequently Asked Questions ==

* 1.) Not work after enable and add the new wordpress admin url? 

   Don't worry, Just update the site permalink ("Settings" >> "Permalinks") and re-check,Now this time it will be work fine

* 2.) Not able to login into admin after enable plugin? 

 May be issue can come when you not give proper writable permission on htaccess file OR you have not update permalink settings to SEO friendly url from admin. You can access login page url with default wp-admin slug after disable my plugin, you can disable plugin through FTP by rename Secure-Admin-Access folder to any other one. 

* 3.) Why I am seeing an, "Please wait 10 minutes" error message when I try to login?

 You are tried to login with wrong password or username more than five times. So please wait 10 minutes, then reset password by clicking "lost your password" link.

* 4.) Am i not able to login after installation
Basicaly issues can come only in case when you will use default permalink settings. 
If your permalink will be update to any other option except default then it will be work fine. 

Go to database wp_options table and find option_name = "saa_active" and set option_value = 0 , default login wp-admin and update permalink and update secure admin access plugin 
OR
Anyway Dont' worry, manualy you can add code into your site .htaccess file.

<code>
# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteRule ^mypanel/?$ /wp-login.php [QSA,L]
RewriteRule ^mypanel/register/?$ /wp-login.php?action=register [QSA,L]
RewriteRule ^mypanel/lostpassword/?$ /wp-login.php?action=lostpassword [QSA,L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>
# END WordPress
</code>

Don not forgot to update the "mypanel" slug with your new admin slug.

== Screenshots ==

1. screenshot-1.png
2. screenshot-2.png
3. screenshot-3.png
4. screenshot-4.png
5. screenshot-5.png
6. screenshot-6.png
7. screenshot-7.png

== Changelog == 

= 1.0 = 
 * Initial release
