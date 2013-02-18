<?php $this->load->view("template_header"); ?>

<?php if ($no_results) { ?>

	<p>no data to display</p>

<?php } else { ?>

	<div class='project_search_results'>
		<ul>
			<lh>Project Results</lh>
			<?php foreach ($lProjects as $iProject) { ?>
				<li>
					<p>

						<b><?php echo $iProject->project->title ?></b>
						<br/><?php echo $iProject->getShortDescription() ?>
						<a href="<?php echo base_url().'project/'.$iProject->project->id ?>">More Info...</a>

						<br/><?php echo $iProject->getlSkillNames() ?>
						<br/>Close Date: <?php echo $iProject->term->end_date ?>
						<br/>Project Status: <?php echo $iProject->project->status->name ?>
						<br/>Team Leader: <?php $this->load->view('user_summary_full_name', array('user_summary' => $iProject->teamLeaderSummary) )?>

						<ul>
							<lh><b>Team Mentors:</b></lh>

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



						<ul>
							<lh><b>Team Members:</b></lh>

							<?php if (isset($iProject->lTeamMemberSummaries) && count($iProject->lTeamMemberSummaries) > 0) { ?>

								<?php foreach ($iProject->lTeamMemberSummaries as $iMemberSumm) { ?>
									<li>
										<?php $this->load->view('user_summary_full_name', array('user_summary' => $iMemberSumm) )?>
									</li>
								<?php } ?>

							<?php } else { ?>

								<li>Join this team for a bree beer! Not really...</li>

							<?php }?>
						</ul>

					</p>
				</li>
			<?php } ?>
		</ul>
	</div>

<?php }?>

<?php $this->load->view("template_footer"); ?>