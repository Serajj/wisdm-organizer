<?php

class Wsdm_Register_Post_Types {

/**
 * The single instance of the class.
 *
 * @var self
 * @since  2.5
 */
private static $_instance = null;

/**
 * Allows for accessing single instance of class. Class should only be constructed once per call.
 *
 * @since  2.5
 * @static
 * @return self Main instance.
 */
public static function instance() {
    if ( is_null( self::$_instance ) ) {
        self::$_instance = new self();
    }
    return self::$_instance;
}
/**
 * Constructor
 */

public function __construct(){
    add_action( 'init', array( $this, 'wsdm_register_post_types' ), 0 );
    add_action('admin_menu',array( $this, 'wsdm_submenu_links' ),20);
}

/***submenu */
public function wsdm_submenu_links()
{
    add_submenu_page('edit.php?post_type=event_listing', __('Teams', 'wsdm-organizer'), __('Teams', 'wsdm-organizer'), 'manage_options', 'wsdm_teams', array($this, 'team_page_html'));
}

public function team_page_html()
{
    $all_members = get_all_team_members_array(); 
    $all_teams = get_all_event_teams();
    ob_start();
?>
<style>
.addteamform {
    margin-top: 10px;
    background-color: white;
    margin-left: 30%;
    margin-right: 30%;
}

.addteamform form {
    display: flex;
    flex-direction: column;
    padding: 20px;
}

.addteamform .submitbtn {
    width: 100%;
    padding: 10px;
    cursor: pointer;
}
.team-container{
    margin-top:20px;
}

.team-container .card {
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
    transition: 0.3s;
    width: 40%;
}

.team-container .card:hover {
    box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
}

.team-container .container {
    padding: 2px;
    width:100%;
    align-items:center;
    justify-content:center;
}
.team-container .container form{
    padding: 2px;
}
.team-container .container form input{
    width:145px;
}
.team-container .container form button{
    cursor: pointer;
}
.team-container .card {
    width:275px;
    height:377px;
    float:left;
    margin:5px;
}
.team-container .card-head{
    width:100%;
    height:260px;
}
.team-container .team_points{
    float:left;
    position: absolute;
    right:10px;
    font-size:26px;
    font-weight:900px;
    top:20px;
}
.team-container .teamcount{
    font-size: 16px;
    font-weight: 900px;
    color:black;
}
.team-container .team_medal{
    position:absolute;
    height:80px;
}
.team-container .team_medal img{
    height: 60px;
    margin-top: -33px;
    margin-left: -47px;
    transform: rotate(-40deg);
}
</style>
<div class="container">
    <div class="row">
        <div class="addteamform">
            <form method="post">
                <h2 style="text-align:center;">Add New Team</h2>
                <label for="team_name">Team Name</label>
                <input type="text" name="title" id="team_name" placeholder="Enter Team Name">
                <br />
                <label for="team_name">Select Team Members</label>
                <select type="text" name="team_ids[]" id="team_name_ids" multiple="true">
                    <?php foreach($all_members as $key=>$member) {?>
                    <option value="<?php echo $key; ?>"><?php echo $member; ?></option>
                    <?php }?>

                </select>
                <br />
                <input type="hidden" name="action" value="addteam" />
                <button class="btn submitbtn" type="submit">Add team</button>
            </form>
        </div>

    </div>
    <div class="row team-container">
        <?php foreach($all_teams as $key=>$team) {
            
            $team_size = get_post_meta( $team->ID, 'team_count', true );
            ?>
            
        <div class="card">
        <!-- <div class="team_medal">
                  <img src="<?php //echo WSDM_ORG_SITE_URL.'/assets/badge/gold.png';?>" alt="gold medal"/>
            </div> -->
            
            <div class="card-head">
                <h3><?php echo $team->post_title; ?></h3>
                <span class="team_points"><?php $points = get_post_meta( $team->ID, 'team_point', true ); echo $points;?></span>
                <hr/>
                <ul class="team_members">
                    <li class="teamcount">Team Members : (<?php echo $team_size; ?>)</li>
                <?php for($i = 0 ; $i < $team_size ; $i++) {
                    $tmid = get_post_meta( $team->ID, "member_".$i, true );
                    $team_member_name = get_the_title($tmid);
                    ?>
                     <li><?php echo $team_member_name; ?></li>
                    <?php
                }?>
                </ul>
                
            </div>
            <hr/>
            <div class="container">
                <p style="margin:2px;"><b>Update Points</b></p>
                <form method="post">
                <input type="hidden" name="team_post_id" value="<?php echo $team->ID; ?>" />
                <input type="hidden" name="action" value="update_team_points" />
                <input type="number" name="team_points" value="<?php echo $points; ?>" />
                 <button type="submit">Update</button>
                </form>
            </div>
        </div>
        <?php }?>
    </div>
</div>

<script type="text/javascript">
jQuery(document).ready(function($) {
    jQuery('#team_name_ids').select2({});
});
</script>

<?php

    echo ob_get_clean();
    
    
}

/** Register post types */
public function wsdm_register_post_types(){
    /**
            * Post types
            */
         
           $singular  = __( 'Event', 'wsdm-organizer' );
   
           $plural    = __( 'Events', 'wsdm-organizer' );
   
           
           /**
            * Set whether to add archive page support when registering the event listing post type.
            *
            * @since 2.5
            *
            * @param bool $enable_event_archive_page
            */
           if ( apply_filters( 'event_manager_enable_event_archive_page', current_theme_supports( 'event-manager-templates' ) ) ) {
               $has_archive = _x( 'events', 'Post type archive slug - resave permalinks after changing this', 'wsdm-organizer' );
           } else {
               $has_archive = false;
           }
   
           $rewrite     = array(
               
               'slug'       => _x( 'event', 'Event permalink - resave permalinks after changing this', 'wsdm-organizer' ),
   
               'with_front' => false,
   
               'feeds'      => true,
   
               'pages'      => false
           );
           register_post_type( "event_listing",
   
               apply_filters( "register_post_type_event_listing", array(
   
                   'labels' => array(
   
                       'name' 					=> $plural,
   
                       'singular_name' 		=> $singular,
   
                       'menu_name'             => __( 'WISDM Organizer', 'wsdm-organizer' ),
   
                       'all_items'             => sprintf(wp_kses( 'All %s', 'wsdm-organizer' ), $plural ),
   
                       'add_new' 				=> __( 'Add New', 'wsdm-organizer' ),
   
                       'add_new_item' 			=> sprintf(wp_kses( 'Add %s', 'wsdm-organizer' ), $singular ),
   
                       'edit' 					=> __( 'Edit', 'wsdm-organizer' ),
   
                       'edit_item' 			=> sprintf(wp_kses( 'Edit %s', 'wsdm-organizer' ), $singular ),
   
                       'new_item' 				=> sprintf(wp_kses( 'New %s', 'wsdm-organizer' ), $singular ),
   
                       'view' 					=> sprintf(wp_kses( 'View %s', 'wsdm-organizer' ), $singular ),
   
                       'view_item' 			=> sprintf(wp_kses( 'View %s', 'wsdm-organizer' ), $singular ),
   
                       'search_items' 			=> sprintf(wp_kses( 'Search %s', 'wsdm-organizer' ), $plural ),
   
                       'not_found' 			=> sprintf(wp_kses( 'No %s found', 'wsdm-organizer' ), $plural ),
   
                       'not_found_in_trash' 	=> sprintf(wp_kses( 'No %s found in trash', 'wsdm-organizer' ), $plural ),
   
                       'parent' 				=> sprintf(wp_kses( 'Parent %s', 'wsdm-organizer' ), $singular ),
                       
                       'featured_image'        => __( 'Event Thumbnail', 'wsdm-organizer' ),
                       
                       'set_featured_image'    => __( 'Set event thumbnail', 'wsdm-organizer' ),
                       
                       'remove_featured_image' => __( 'Remove event thumbnail', 'wsdm-organizer' ),
                       
                       'use_featured_image'    => __( 'Use as event thumbnail', 'wsdm-organizer' ),
                   ),
   
                   'description' => sprintf(wp_kses( 'This is where you can create and manage %s.', 'wsdm-organizer' ), $plural ),
   
                   'public' 				=> true,
   
                   'show_ui' 				=> true,
   
   
                   'map_meta_cap'          => true,
   
                   'publicly_queryable' 	=> true,
   
                   'exclude_from_search' 	=> false,
   
                   'hierarchical' 			=> false,
   
   
                   'query_var' 			=> true,
                       
                   'show_in_rest' 			=> true,
                   'has_archive' 			=> $has_archive,
                   'show_in_nav_menus' 	=> true,
                   'menu_icon' => 'dashicons-calendar' ,// It's use to display event listing icon at admin site. 
                   'supports'           => array( 'title', 'editor', 'custom-fields', 'author', 'thumbnail', 'excerpt', 'comments' ),
               ) )
           );
   
           /**
            * Feeds
            */
   
           add_feed( 'event_feed', array( $this, 'event_feed' ) );
   
           /**
            * Post status
            */
   
           register_post_status( 'expired', array(
   
               'label'                     => _x( 'Expired', 'post status', 'wsdm-organizer' ),
   
               'public'                    => true,
   
               'exclude_from_search'       => true,
   
               'show_in_admin_all_list'    => true,
   
               'show_in_admin_status_list' => true,
   
               'label_count'               => _n_noop( 'Expired <span class="count">(%s)</span>', 'Expired <span class="count">(%s)</span>', 'wsdm-organizer' )
           ) );
   
           register_post_status( 'preview', array(
   
               'public'                    => true,
   
               'exclude_from_search'       => true,
   
               'show_in_admin_all_list'    => true,
   
               'show_in_admin_status_list' => true,
       
               'label_count'               => _n_noop( 'Preview <span class="count">(%s)</span>', 'Preview <span class="count">(%s)</span>', 'wsdm-organizer' )
           ) );

           $singular  = __( 'Team', 'wsdm-team' );
               $plural    = __( 'Teams', 'wsdm-team' );
               register_post_type( 'wdm_team', apply_filters('register_wdm_team_post_type',array(
                               'labels' => array(
       
                               'name' 					=> $plural,
       
                               'singular_name' 		=> $singular,
       
                               'add_new_item' 			=> sprintf(wp_kses( 'Add %s', 'wsdm-organizer' ), $singular ),
       
                               'edit_item' 			=> sprintf(wp_kses( 'Edit %s', 'wsdm-organizer' ), $singular ),
                               
                               'featured_image'        => __( 'Team Logo', 'wsdm-organizer' ),
                               
                               'set_featured_image'    => __( 'Set team logo', 'wsdm-organizer' ),
                               
                               'remove_featured_image' => __( 'Remove team logo', 'wsdm-organizer' ),
                               
                               'use_featured_image'    => __( 'Use as team logo', 'wsdm-organizer' ),
                           ),
       
                               'public'             => true,
                               'publicly_queryable' => true,
                               'show_ui'            => true,
                               'show_in_menu'       => false,
                               'query_var'          => true,
                               'rewrite'            => array( 'slug' => 'wdm_team' ),
                               'capability_type'    => 'post',
                               'has_archive'        => true,
                               'hierarchical'       => false,
                               'menu_position'      => null,
                               
                               'supports'           => array( 'title', 'editor', 'custom-fields', 'author', 'thumbnail', 'excerpt', 'comments' ),
                                
                       ) )
                   );
   
           $singular  = __( 'Team Members', 'wsdm-organizer' );
           $plural    = __( 'Team Member', 'wsdm-organizer' );
           register_post_type( 'wdm_team_member', apply_filters('register_wdm_team_member_post_type',array(
                           'labels' => array(
   
                           'name' 					=> $plural,
   
                           'singular_name' 		=> $singular,
   
                           'add_new_item' 			=> sprintf(wp_kses( 'Add %s', 'wsdm-organizer' ), $singular ),
   
                           'edit_item' 			=> sprintf(wp_kses( 'Edit %s', 'wsdm-organizer' ), $singular ),
                           
                           'featured_image'        => __( 'Team member Logo', 'wsdm-organizer' ),
                           
                           'set_featured_image'    => __( 'Set Team member logo', 'wsdm-organizer' ),
                           
                           'remove_featured_image' => __( 'Remove team member logo', 'wsdm-organizer' ),
                           
                           'use_featured_image'    => __( 'Use as team member logo', 'wsdm-organizer' ),
                       ),
   
                           'public'             => true,
                           'publicly_queryable' => true,
                           'show_ui'            => true,
                           'show_in_menu'       => false,
                           'query_var'          => true,
                           'rewrite'            => array( 'slug' => 'wdm_team_member' ),
                           'capability_type'    => 'post',
                           'has_archive'        => true,
                           'hierarchical'       => false,
                           'menu_position'      => null,
                           'show_in_menu' => 'edit.php?post_type=event_listing',
                           'supports'           => array( 'title', 'editor', 'custom-fields', 'author', 'thumbnail', 'excerpt', 'comments' ),
                            
                   ) )
               );
   
               $singular  = __( 'Feedback', 'wsdm-organizer' );
               $plural    = __( 'Feedbacks', 'wsdm-organizer' );
               register_post_type( 'wdm_feedback', apply_filters('register_wdm_feedback_post_type',array(
                               'labels' => array(
       
                               'name' 					=> $plural,
       
                               'singular_name' 		=> $singular,
       
                               'add_new_item' 			=> sprintf(wp_kses( 'Add %s', 'wsdm-organizer' ), $singular ),
       
                               'edit_item' 			=> sprintf(wp_kses( 'Edit %s', 'wsdm-organizer' ), $singular ),
                               
                               'featured_image'        => __( 'Feedback Logo', 'wsdm-organizer' ),
                               
                               'set_featured_image'    => __( 'Set feedback logo', 'wsdm-organizer' ),
                               
                               'remove_featured_image' => __( 'Remove feedback logo', 'wsdm-organizer' ),
                               
                               'use_featured_image'    => __( 'Use as feedback logo', 'wsdm-organizer' ),
                           ),
       
                               'public'             => true,
                               'publicly_queryable' => true,
                               'show_ui'            => true,
                               'show_in_menu'       => false,
                               'query_var'          => true,
                               'rewrite'            => array( 'slug' => 'wdm_feedback' ),
                               'capability_type'    => 'post',
                               'has_archive'        => true,
                               'hierarchical'       => false,
                               'menu_position'      => null,
                               'show_in_menu' => 'edit.php?post_type=event_listing',
                               'supports'           => array( 'title', 'editor', 'custom-fields', 'author', 'thumbnail', 'excerpt', 'comments' ),
                                
                       ) )
                   );
   }
}