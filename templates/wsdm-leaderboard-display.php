<?php 

$leaderboard_data = get_all_event_teams( );

?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<style>
    .leaderboard img{
        height:50px;
    }
    </style>
<div class="container">
    <div class="row">
        <h2 class="leaderboard-heading">LEADERBOARD</h2>
        <div class="event-lb-wrap">
            <table class="event-leaderboard-table" id="event-leaderboard-table">
                <thead>
                    <tr>
                        <td>Rank</td>
                        <td>Team Name</td>
                        <td>Points</td>
                    </tr>
                </thead>
                <tbody>
                    <?php $rank_count = 1; ?>
                    <?php foreach($leaderboard_data as $team_info): ?>
                        <tr class="leaderboard">
                            <!-- Team rank -->
                            <td class="team-rank-lb"><?php echo $rank_count; ?></td>
                            <!-- Team name -->
                            <td class="team-name-lb">
                                <?php 
                                    $team_name = $team_info->post_title;;
                                    echo $team_name;
                                ?>
                                <?php if($rank_count == 1): ?>
                                    <img class="first-place-badge" src="<?php echo WSDM_ORG_SITE_URL.'/assets/badge/gold.png';?>" height="30">
                                <?php elseif($rank_count == 2): ?>
                                    <img class="second-place-badge" src="<?php echo WSDM_ORG_SITE_URL.'/assets/badge/silver.png';?>" height="30">
                                <?php elseif($rank_count == 3): ?>
                                    <img class="third-place-badge" src="<?php echo WSDM_ORG_SITE_URL.'/assets/badge/bronze.png';?>" height="30">
                                <?php endif; ?>
                            </td>
                            <!-- Team points -->
                            <td class="team-points-lb">
                                <?php 
                                    $team_point = get_post_meta( $team_info->ID, 'team_point', true );
                                    echo $team_point;
                                ?>
                            </td>
                        </tr>
                    <?php $rank_count++; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>