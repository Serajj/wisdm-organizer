<?php
/*
* This file is use to create a sortcode of wp event manager plugin. 
* This file include sortcode of event/organizer/venue listing,event/organizer/venue submit form and event/organizer/venue dashboard etc.
*/

if (!defined('ABSPATH')) exit; // Exit if accessed directly
/**
 * Wsdm_Shortcode class.
 */
class Wsdm_Shortcode
{
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
        	add_shortcode('submit_registration_form', array($this, 'submit_registration_form'),10);
            add_shortcode( 'wdm_event_leaderboard', array($this, 'wdm_event_leaderboard_tb_shortcode') );
			
	}

    public function wdm_event_leaderboard_tb_shortcode() {
        ob_start();
        include WSDM_ORG_PATH. '/templates/wsdm-leaderboard-display.php';
        return ob_get_clean();
    }
	

	/**
	 * Show the organizer submission form
	 */
	public function submit_registration_form($atts = array())
	{
		 ob_start();
        include WSDM_ORG_PATH. '/templates/register-event-user.php';
        return ob_get_clean();
		
	}

	
}

new Wsdm_Shortcode(); ?>