<?php 

//select2 library
function enqueue_select2_jquery() {
    wp_register_style( 'select2css', '//cdnjs.cloudflare.com/ajax/libs/select2/3.4.8/select2.css', false, '1.0', 'all' );
    wp_register_script( 'select2', '//cdnjs.cloudflare.com/ajax/libs/select2/3.4.8/select2.js', array( 'jquery' ), '1.0', true );
    wp_enqueue_style( 'select2css' );
    wp_enqueue_script( 'select2' );
}
add_action( 'admin_enqueue_scripts', 'enqueue_select2_jquery' );

//adding admin menu

/**
 * 
 * @since 3.1.14
 * @param null
 * @return string
 */
function get_all_team_members_array($user_id = '', $args = [], $blank_option = false) {
	$all_venue =get_all_event_members_team($user_id, $args);
	
	$venue_array =array();

	if( is_array($all_venue) && !empty($all_venue) ) {
		if($blank_option) {
			$venue_array[''] = __( 'Select Team Member', 'wp-event-manager' );
		}

		foreach ($all_venue as $venue) {
			$venue_array[$venue->ID] = $venue->post_title;
		}	
	}
	return $venue_array;
}

/**
 * 
 * @since 3.1.14
 * @param null
 * @return string
 */
function get_all_event_members_team($user_id = '', $args = []) {


	$query_args = array(
					'post_type'   => 'wdm_team_member',
					'post_status' => 'publish',
					'posts_per_page'=> -1,
					'suppress_filters' => 0,
				);

	if( isset($user_id) && !empty($user_id) && !is_admin() ) {
		$query_args['author'] = $user_id;	
	}

	if( isset($args) && !empty($args) ) {
		$query_args = array_merge($query_args,$args);
	}

	$query_args = apply_filters('get_all_event_team_member_args', $query_args);

	$all_venue = get_posts( $query_args );

	if(!empty($all_venue)) {
		return $all_venue;	
	} else {
		return false;	
	}
}

function get_all_event_teams($user_id = '', $args = []) {


	$query_args = array(
					'post_type'   => 'wdm_team',
					'post_status' => 'publish',
					'posts_per_page'=> -1,
					'suppress_filters' => 0,
                    'meta_key' => 'team_point',
                    'orderby' => 'meta_value_num',
                    'order' => 'DESC',
				);

	if( isset($user_id) && !empty($user_id) && !is_admin() ) {
		$query_args['author'] = $user_id;	
	}

	if( isset($args) && !empty($args) ) {
		$query_args = array_merge($query_args,$args);
	}

	$query_args = apply_filters('get_all_event_wdm_team_args', $query_args);

	$all_venue = get_posts( $query_args );

	if(!empty($all_venue)) {
		return $all_venue;	
	} else {
		return [];	
	}
}


function add_post_data(){

    if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == "teammember") {
        $title     = $_POST['title'];
        $post_type = 'wdm_team_member';
        //the array of arguements to be inserted with wp_insert_post
        $front_post = array(
        'post_title'    => $title,
        'post_status'   => 'publish',          
        'post_type'     => $post_type 
        );
    
        //insert the the post into database by passing $new_post to wp_insert_post
        //store our post ID in a variable $pid
        $post_id = wp_insert_post($front_post);
        //we now use $pid (post id) to help add out post meta data
        update_post_meta($post_id, "email", @$_POST["email"]);
        update_post_meta($post_id, "specification", @$_POST["specification"]);
        update_post_meta($post_id, "team_id",0);
    }

    if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == "addteam") {
        $title     = $_POST['title'];
        $team_ids =$_POST['team_ids'];
        $post_type = 'wdm_team';
        //the array of arguements to be inserted with wp_insert_post
        $front_post = array(
        'post_title'    => $title,
        'post_status'   => 'publish',          
        'post_type'     => $post_type 
        );
        
        //insert the the post into database by passing $new_post to wp_insert_post
        //store our post ID in a variable $pid
        $post_id = wp_insert_post($front_post);
        //we now use $pid (post id) to help add out post meta data
        foreach ($team_ids as $key => $tvalue) {
            update_post_meta($post_id, "member_".$key, $tvalue);
        }
        // update_post_meta($post_id, "email", @$_POST["email"]);
        update_post_meta($post_id, "team_point", 0);
        update_post_meta($post_id, "team_count",count($team_ids));
    }

    if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == "update_team_points") {

        $t_post_id = $_POST['team_post_id'];
        $team_point = $_POST['team_points'];
        update_post_meta( $t_post_id, 'team_point', sanitize_text_field( $team_point ) );

    }

    
}

//add_action('wp' , 'add_post_data');
add_action('init' , 'add_post_data');

add_filter('manage_wdm_team_member_posts_columns', 'wdm_team_member_manage_event_table_head');
function wdm_team_member_manage_event_table_head( $defaults ) {
    $defaults['email']  = 'Email';
    $defaults['specification']    = 'Specification';
    $defaults['team']   = 'Team';
    return $defaults;
}

add_action( 'manage_wdm_team_member_posts_custom_column', 'bs_event_table_content', 10, 2 );

function bs_event_table_content( $column_name, $post_id ) {
    if ($column_name == 'email') {
    $email = get_post_meta( $post_id, 'email', true );
      echo  $email;
    }
    if ($column_name == 'specification') {
    $status = get_post_meta( $post_id, 'specification', true );
    echo $status;
    }

    if ($column_name == 'team') {
    echo get_post_meta( $post_id, 'team_id', true );
    }

}