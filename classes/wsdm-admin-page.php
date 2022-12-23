<?php
/**
 * Addons Page
*/

if (!defined('ABSPATH')){
	 exit;// Exit if accessed directly
} 

if (!class_exists('Wsdm_Admin_Page')) :

	/**
	 * Wsdm_Admin_Page Class
	*/
	class Wsdm_Admin_Page
	{
        public function __construct(){
            include(WSDM_ORG_SITE_URL.'includes/wsdm-functions.php' );
        }
		/**
		 * Handles output of the reports page in admin.
		 */
		public function teamsoutput() { ?>
			<div class="wrap wp_event_manager Wsdm_Admin_Page_wrap">
			<h2><?php _e('Wisdm Teams', 'wp-event-manager'); ?></h2>
			<?php 

$all_members = get_all_team_members_array();

print_r($all_members);

			 ?>
			</div>
		<?php
		} 
	}

endif;
return new Wsdm_Admin_Page();