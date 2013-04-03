<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class NotificationsController extends CI_Controller 
{
    
    public function __construct()
    {
        parent::__construct();

        $this->load->model('SPW_Notification_View_Model');
        $this->load->model('spw_notification_model');
        $this->load->model('spw_user_model');
        $this->load->model('spw_project_model');
        $this->load->helper('request');
    }

    public function display_notifications()
    {
        //$this->output->set_output('you have notifications');

        if (!isUserLoggedIn($this))
        {
            redirect('/');
        }
        else
        {
            $currentUserId = getCurrentUserId($this);
            $lNotifications = $this->getPendingNotificationsForUserInternal($currentUserId);

            if (isset($lNotifications) && count($lNotifications) > 0)
            {
                $data['no_results'] = false;
                $data['lNotifications'] = $lNotifications;
            }
            else
            {
                $data['no_results'] = true;
            }

            $data['title'] = 'Notifications';
            $this->load->view('notifications_display_notifications', $data);
        }
    }



    public function accept_notification($notification_id)
    {
        if (!is_POST_request($this))
        {
            redirect('/');
        }
        else
        {
            $this->spw_notification_model->set_notification_to_read($notification_id);
            $spw_notification_model = $this->spw_notification_model->get_notification_by_id($notification_id);
            $approver_user = $spw_notification_model->to_user;
            $approved_user = $spw_notification_model->from;
            $project_id = $spw_notification_model->to_project;

            $project_title = $this->spw_project_model->get_project_title($project_id);
            
            if($spw_notification_model->type == 'join')
            {
                $from_fullname = $this->spw_user_model->get_fullname($spw_notification_model->from);  

                $this->spw_notification_model->create_join_approved_notification_for_user($approver_user, $approved_user, $project_id);
                $this->spw_notification_model->create_member_added_notification_for_project($approver_user, $approved_user,  $project_id);

                $team_members = $this->spw_project_model->get_team_members($spw_notification_model->to_project);
                for($i = 0; $i < count($team_members); $i++)
                {
                    if(!($team_members[$i] == $approver_user) && !($team_members[$i] == $approved_user))
                    {
                        $this->spw_notification_model->set_join_notification_to_read($approved_user, $team_members[$i], $project_id);
                    }
                }

                $this->spw_notification_model->create_member_added_notification_for_project($approver_user,$approved_user, $project_id);

                $this->spw_project_model->add_member_to_project($approved_user, $project_id);

                $reject_details_msg = $from_fullname." has been automatically added to the project ".$project_title.'\n';
                $reject_details_msg =  $reject_details_msg.$from_fullname." will be notified promptly of your decision";
                setFlashMessage($this, $reject_details_msg);

                
            }else if($spw_notification_model->type == 'professor_approval'){

                $this->spw_notification_model->create_professor_approval_approved_notification($spw_notification_model->to_project);
                $this->spw_project_model->add_member_to_project($spw_notification_model->from,$spw_notification_model->to_project);

                $reject_details_msg = $project_title." has been rejected\\n";
                $reject_details_msg =  $reject_details_msg."All team members will be notified promptly of your decision";
                setFlashMessage($this, $reject_details_msg);
            } 
  

            $pbUrl = $this->input->post('pbUrl');
            if (isset($pbUrl) && strlen($pbUrl))
            {
                redirect($pbUrl);
            }
        }
    }

    public function reject_notification($notification_id)
    {
        if (!is_POST_request($this))
        {
            redirect('/');
        }
        else
        {
            $this->spw_notification_model->set_notification_to_read($notification_id);
            
            $spw_notification_model = $this->spw_notification_model->get_notification_by_id($notification_id);
            $approver_user = $spw_notification_model->to_user;
            $approved_user = $spw_notification_model->from;
            $project_id = $spw_notification_model->to_project;

            if($spw_notification_model->type == 'join')
            {
                $from_fullname = $this->spw_user_model->get_fullname($spw_notification_model->from);
                $project_title = $this->spw_project_model->get_project_title($project_id);

                $this->spw_notification_model->create_member_added_notification_for_project($approver_user,$approved_user, $project_id);

                $team_members = $this->spw_project_model->get_team_members($project_id);
                for($i = 0; $i < count($team_members); $i++)
                {
                    if($team_members[$i] != $approver_user)
                    {
                        $this->spw_notification_model->set_join_notification_to_read($spw_notification_model->from, $team_members[$i],$spw_notification_model->to_project);
                    }
                }

                $this->spw_notification_model->create_member_rejected_notification_for_project($approver_user,$approved_user, $project_id);
                $this->spw_notification_model->create_join_rejected_notification_for_user($approver_user,$approved_user, $project_id);

                $reject_details_msg = 'Request to join project '.$project_title.' has been denied\n';
                $reject_details_msg = $reject_details_msg.$from_fullname." will be notified promptly of your decision";
                setFlashMessage($this, $reject_details_msg);
            }else if($spw_notification_model->type == 'professor_approval')
            {
                $reject_details_msg = $project_title.' has been rejected\n';
                $reject_details_msg =  $reject_details_msg."All team members will be notified promptly of your decision";
                setFlashMessage($this, $reject_details_msg);

                $this->spw_notification_model->create_professor_approval_rejected_notification($spw_notification_model->to_project);
            } 

            $pbUrl = $this->input->post('pbUrl');
            if (isset($pbUrl) && strlen($pbUrl))
            {
                redirect($pbUrl);
            }
        }
    }

    public function hide_notification($notificationId)
    {
        if (!is_POST_request($this))
        {
            redirect('/');
        }
        else
        {
            if (!is_test($this))
            {
                $this->spw_notification_model->set_notification_to_read($notificationId);
            }
            
            $this->output->set_output('notification hidden');

            //redirect back to the previous page
            $pbUrl = $this->input->post('pbUrl');
            if (isset($pbUrl) && strlen($pbUrl))
            {
                redirect($pbUrl);
            }
        }
    }

    private function getPendingNotificationsForUserInternal($userId)
    {
        if (is_test($this))
        {
            return $this->getPendingNotificationsForUserInternalTest($userId);
        }
        else
        {
            return $this->getPendingNotificationsForUser($userId);
        }
    }

  
    private function getPendingNotificationsForUser($user_id)
    {
        $result = $this->spw_notification_model->get_active_notification_for_user($user_id); 

        if(isset($result))
        {
            $notifications = array();
            foreach ($result as $row)
            {
                if($row->type == "join" || $row->type == "professor_approval"){
                    $displayTwoButtons  = true;
                }else{
                    $displayTwoButtons  = false;
                }

                $notification = new SPW_Notification_View_Model();
                $notification->id = $row->id;
                $msg = $row->body;
                $notification->message = $msg;
                $notification->displayTwoButtons = $displayTwoButtons;
                array_push($notifications, $notification);
            }

            return $notifications;
        }else{
            return null;
        }
    }

    private function is_a_leave_msg($msg)
    {
        if(strpos($msg, 'leave') != false){
            return true;
        }else{
            return false;
        }
    }


    private function getPendingNotificationsForUserInternalTest($userId)
    {
        $notification_vm1 = new SPW_Notification_View_Model();
        $notification_vm1->id = 1;
        $notification_vm1->message = 'Lolo Gonzalez wants to join your team';
        $notification_vm1->displayTwoButtons = true;


        $notification_vm2 = new SPW_Notification_View_Model();
        $notification_vm2->id = 2;
        $notification_vm2->message = 'Kyle Broflovsky left your team';


        $notification_vm3 = new SPW_Notification_View_Model();
        $notification_vm3->id = 3;
        $notification_vm3->message = 'You have been invited to join the Facebook Moodle integration project';
        $notification_vm3->displayTwoButtons = true;


        $notification_vm4 = new SPW_Notification_View_Model();
        $notification_vm4->id = 4;
        $notification_vm4->message = 'Your new project NASA in FIU has been approved';


        $lNotifications = array(
                $notification_vm1,
                $notification_vm2,
                $notification_vm3,
                $notification_vm4
            );
        return $lNotifications;
    }
}