<?php $this->load->view("template_header"); ?>

<?php if ($no_results) { ?>

	<p>
		<?php 
			if (isset($message) && strlen($message)) 
			{ 
				echo $message;
			} else { 
		?>
				You have not joined a project yet...
		<?php 
			} 
		?>
	</p>

	<?php 
		if (isset($lSuggestedProjects) && count($lSuggestedProjects) > 0)
		{
			$this->load->view('subviews/project_summary_list', array('lProjects' => $lSuggestedProjects, 'list_title' => 'Suggested Projects') );
		}
	?>

<?php } else { ?>

	<ul class="project_list unstyled">
		<?php if (isset($list_title) && strlen($list_title) > 0) { ?>
			<lh><h2><?php echo $list_title ?></h2></lh>
		<?php } ?>

		<?php foreach ($lProjects as $iProject) { ?>
			<li class="well">

				<h4>
					<?php echo anchor('project/'.$iProject->project->id, $iProject->project->title) ?>
				</h4>

				<p>
					<?php echo $iProject->getShortDescription() ?>
					<?php echo anchor('project/'.$iProject->project->id, 'More Info...') ?>
				</p>
			</li>
		<?php } ?>
	</ul>

<?php }?>

<?php $this->load->view("template_footer"); ?>