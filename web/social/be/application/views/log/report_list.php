<?php 
    $controller = $this->router->class;
    $this->load->helper('cms_report_entity_subject');        
?>
<h1><?= $title?>   </h1>
<br />
<br />
<div id="listContainer">
    <table class="table">
        <tr>
            <th>#</th>
            <th>Reporting User Name</th>
            <th>Reported User Name</th>
            <th>Subject</th>
            <th>Title</th>
            <th>Message</th>
            <th>Reasons</th>
            <th>Email</th>
            <th>Date</th>
        </tr>
        <?php foreach($reports as $report){ 
            $reasons= get_cms_report_reason_reasontitle($report->reason);
            $reasons_title ="";
            $entity_subject = "";
            
            switch($report->entity_type){
                case 33:
                    //SOCIAL_ENTITY_PROFILE_ACCOUNT
                    $entity_subject = "Deleted account: " . get_cms_users_YourUserName($report->entity_id); //YourUserName field from cms_users table
                    break;
                case 2:
                    //SOCIAL_ENTITY_USER
                    $entity_subject = "User: " . get_cms_users_YourUserName($report->entity_id); //YourUserName field from cms_users table
                        
                    break;
                case 14:
                    //SOCIAL_ENTITY_CHANNEL
                    $entity_subject = "Channel: " . get_cms_channel_channel_name($report->entity_id); //channel_name field from cms_channel table
                    break;
                case 45:
                    //SOCIAL_ENTITY_REPORT_BUG
                    $entity_subject = "Bug: " . $report->title;
                    break;
                case 24:
                    //SOCIAL_ENTITY_USER_EVENTS
                    $entity_subject = "User event: " . get_cms_users_event_name($report->entity_id); //name field from cms_users_event table
                    break;
                case 1:
                    //SOCIAL_ENTITY_MEDIA
                    $entity_subject = "Media: " . get_cms_videos_title($report->entity_id); //title field from cms_videos table
                    break;
                case "12":
                    //SOCIAL_ENTITY_EVENTS
                    $entity_subject = "Channel event: " . get_cms_channel_event_name($report->entity_id); //name field from cms_channel_event table
                    break;
                case "46":
                    //SOCIAL_ENTITY_REPLY
                    $entity_subject = "Reply";
                    break;
                case "9":
                    //SOCIAL_ENTITY_COMMENT
                    $entity_subject = "Comment";
                    break;
                case "15":
                    //SOCIAL_ENTITY_POST
                    $entity_subject = "Post";
                    break;
            }
        ?>
        <tr>
            <td><?= $report->id;?></td>    
            <td><?= $report->reporting_user;?></td>    
            <td><?= $report->owner;?></td>
            <td><?= $entity_subject; ?></td>
            <td><?= $report->title; ?></td>
            <td><?= $report->msg; ?></td>
            <td><?php  
                    foreach ($reasons as $key=>$value){
                        echo $reasons_title = $value['reason'].' , ';
                    } ?>
            </td>
            <td><?= $report->email; ?></td>
            <td><?= $report->create_ts;?></td>    
        </tr> 
    <?php  } ?>
    </table>
</div>