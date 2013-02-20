<ul class="project_list unstyled">
	<?php if (isset($list_title) && strlen($list_title) > 0) { ?>
		<lh><h2><?php echo $list_title ?></h2></lh>
	<?php } ?>

	<?php foreach ($lProjects as $iProject) { ?>
		<li class="well">
			
			<div class="pull-right"> <?php echo $iProject->getlSkillNames() ?> </div>

			<h4>
				<?php echo anchor('project/'.$iProject->project->id, $iProject->project->title) ?>
			</h4>

			<p>
				<?php echo $iProject->getShortDescription() ?>
				<?php echo anchor('project/'.$iProject->project->id, 'More Info...') ?>
			</p>
			
			<?php if ($iProject->displayJoin) { ?>
				<button class="btn btn-primary pull-right" type="button">Join</button>
			<?php } ?>
			
			<?php if ($iProject->displayLeave) { ?>
				<button class="btn btn-warning pull-right" type="button">Leave</button>
			<?php } ?>
			
			<ul class="unstyled inline">
				<lh>Team Leader:</lh>
				<li>
					<?php $this->load->view('user_summary_full_name', array('user_summary' => $iProject->teamLeaderSummary) )?>
				</li>
			</ul>

			<ul class="unstyled inline">
				<lh>Team Mentors:</lh>

				<?php if (isset($iProject->lMentorSummaries) && count($iProject->lMentorSummaries) > 0) { ?>
					
					<?php foreach ($iProject->lMentorSummaries as $iMentorSumm) { ?>
						<li>
							<?php $this->load->view('user_summary_full_name', array('user_summary' => $iMentorSumm) )?>
						</li>
					<?php } ?>

				<?php } else { ?>

					<li>This team needs a mentor...</li>

				<?php }?>
			</ul>


			<ul class="unstyled inline">
				<lh>Team Members:</lh>

				<?php if (isset($iProject->lTeamMemberSummaries) && count($iProject->lTeamMemberSummaries) > 0) { ?>

					<?php foreach ($iProject->lTeamMemberSummaries as $iMemberSumm) { ?>
						<li>
							<?php $this->load->view('user_summary_full_name', array('user_summary' => $iMemberSumm) )?>
						</li>
					<?php } ?>

				<?php } else { ?>

					<li>Join this team for a free beer! Not really...</li>

				<?php }?>
			</ul>

			Close Date: <?php echo $iProject->term->end_date ?>
			Project Status: <?php echo $iProject->project->status->name ?>
		</li>
	<?php } ?>
</ul>