<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ProjectController extends CI_Controller 
{
	private $is_test = true;

	public function __construct()
	{
		parent::__construct();

		$this->load->helper('project_summary_view_model');
		load_project_summary_models($this);

		//$this->output->cache(60);
	}


	public function details($project_id='')
	{
		$this->output->set_output('project details '.$project_id);
	}

	public function current_project()
	{
		$current_project_id = $this->getCurrentProjectId();
		$this->details($current_project_id);
	}

	public function past_projects()
	{
		$lProjects = $this->getPastProjectsInternal();
		if ( (!isset($lProjects) || count($lProjects) == 0) )
		{
			$no_results = true;
		}
		else
		{
			$no_results = false;
		}

		$data['no_results'] = $no_results;
		$data['lProjects'] = $lProjects;

		$this->load->view('past_projects', $data);
	}


	private function getCurrentProjectId()
	{
		if ($this->is_test)
		{
			return 100;
		}
		else
		{
			throw new Exception('not implemented');
		}
	}

	private function getPastProjectsInternal()
	{
		if ($this->is_test)
		{
			return $this->getPastProjectsInternalTest();
		}
		else
		{
			throw new Exception('not implemented');
		}
	}
	private function getPastProjectsInternalTest()
	{
		$projStatus = new SPW_Project_Status_Model();
		$projStatus->id = 2;
		$projStatus->name = 'Closed';		

		$term1 = new SPW_Term_Model();
		$term1->id = 2;
		$term1->name = 'Summer 2012';
		$term1->description = 'Summer 2012';
		$term1->start_date = '5-1-2012';
		$term1->end_date = '8-24-2012';


		$skill1 = new SPW_Skill_Model();
		$skill1->id = 0;
		$skill1->name = 'Ruby on Rails';

		$skill2 = new SPW_Skill_Model();
		$skill2->id = 1;
		$skill2->name = 'jQuery';

		$skill3 = new SPW_Skill_Model();
		$skill3->id = 2;
		$skill3->name = 'HTML';

		$skill4 = new SPW_Skill_Model();
		$skill4->id = 3;
		$skill4->name = 'CSS';

		$lSkills1 = array(
			$skill1,
			$skill2,
			$skill3,
			$skill4
		);


		$skill5 = new SPW_Skill_Model();
		$skill5->id = 4;
		$skill5->name = 'PHP';

		$skill6 = new SPW_Skill_Model();
		$skill6->id = 5;
		$skill6->name = 'Moodle';

		$lSkills2 = array(
			$skill5,
			$skill6
		);


		$user1 = new SPW_User_Model();
		$user1->id = 0;
		$user1->first_name = 'Steven';
		$user1->last_name = 'Luis';

		$user_summ_vm1 = new SPW_User_Summary_View_Model();
		$user_summ_vm1->user = $user1;

		$user2 = new SPW_User_Model();
		$user2->id = 1;
		$user2->first_name = 'Lolo';
		$user2->last_name = 'Gonzalez';

		$user_summ_vm2 = new SPW_User_Summary_View_Model();
		$user_summ_vm2->user = $user2;

		$user3 = new SPW_User_Model();
		$user3->id = 2;
		$user3->first_name = 'Karen';
		$user3->last_name = 'Rodriguez';

		$user_summ_vm3 = new SPW_User_Summary_View_Model();
		$user_summ_vm3->user = $user3;

		$user4 = new SPW_User_Model();
		$user4->id = 3;
		$user4->first_name = 'Gregory';
		$user4->last_name = 'Zhao';

		$user_summ_vm4 = new SPW_User_Summary_View_Model();
		$user_summ_vm4->user = $user4;




		$project1 = new SPW_Project_Model();
		$project1->id = 1;
		$project1->title = 'Free Music Sharing Platform';
		$project1->description = 'Poor students need an easy way to access all the music in the world for free.';
		$project1->status = $projStatus;

		$project_summ_vm1 = new SPW_Project_Summary_View_Model();
		$project_summ_vm1->project = $project1;
		$project_summ_vm1->term = $term1;
		$project_summ_vm1->lSkills = $lSkills1;
		$project_summ_vm1->lMentorSummaries = array($user_summ_vm1);
		$project_summ_vm1->teamLeaderSummary = $user_summ_vm3;


		$project2 = new SPW_Project_Model();
		$project2->id = 2;
		$project2->title = 'Moodle on Facebook';
		$project2->description = 'Poor students need an easy way to access all the music in the world for free. This Project will make every student really happy.';
		$project2->status = $projStatus;

		$project_summ_vm2 = new SPW_Project_Summary_View_Model();
		$project_summ_vm2->project = $project2;
		$project_summ_vm2->term = $term1;
		$project_summ_vm2->lSkills = $lSkills2;
		$project_summ_vm2->lMentorSummaries = array($user_summ_vm1);
		$project_summ_vm2->lTeamMemberSummaries = array($user_summ_vm4);
		$project_summ_vm2->teamLeaderSummary = $user_summ_vm2;

		$lProjects = array(
			$project_summ_vm1,
			$project_summ_vm2,
			$project_summ_vm1
		);

		return $lProjects;
	}
}