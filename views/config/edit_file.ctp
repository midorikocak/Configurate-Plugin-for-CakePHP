<div class="file form">
<?php echo $form->create('config',array('enctype' => 'multipart/form-data'));?>
	<fieldset id="fileFieldset">
 		<legend><?php __('Core Configuration');?></legend>
	<?php
	foreach($configs as $legend => $values)
	{
	$formArray = array();
	$formArray['legend'] = $legend;
	foreach($values as $value)
	{
	if($value['enabled']==0)
	{
	$disabled =true;
	}
	else
	{
	$disabled = false;
	}
	$formArray[$value['offset']."%DD%".$legend."%DD%".strlen($value['text'])."%DD%".str_replace('.','A$A$',$value['name'])] = array('label'=>ucwords($value['name']),'value'=>$value['value'],'disabled'=>$disabled);
	}
	echo $form->inputs($formArray);
	}
		?>
	</fieldset>

<?php echo $form->end('Submit');

//Buraya galeri ekleme formunu dinamik getirmek laz?m
?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $html->link(__('Home', true), array('action' => 'index'));?></li>
		<li><?php echo $html->link(__('Core Configuration', true), array('controller' => 'configs', 'action' => 'editConfig/core.php')); ?> </li>
		<li><?php echo $html->link(__('Route Configuration', true), array('controller' => 'configs', 'action' => 'editConfig/routes.php')); ?> </li>
		<li><?php echo $html->link(__('Backup SQL', true), array('controller' => 'configs', 'action' => 'backupSQL')); ?> </li>
		<li><?php echo $html->link(__('Backup Config Folder', true), array('controller' => 'configs', 'action' => 'backupConfigFolder')); ?> </li>
		<li><?php echo $html->link(__('Recover SQL or Files', true), array('controller' => 'configs', 'action' => 'recoverFiles')); ?> </li>
	</ul>
</div>