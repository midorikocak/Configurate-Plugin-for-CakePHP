<div class="file form">
<?php echo $form->create('config',array('enctype' => 'multipart/form-data'));?>
	<fieldset id="fileFieldset">
 		<legend><?php __(ucfirst($fileName)." configuration");?></legend>
	<?php
	//var_dump($confArray);
	$field ="";
	$counter = 0;
	foreach($confArray as $key => $value)
	{
	$counter++;
	if(isset($value['text']))
	{
		$config = explode('(',$value['text'],2);
		$configValue = explode(',',$config[1],2);
		if(($field!=$config[0]) && ($field!=""))
		{
		echo "</fieldset>";
		$field=$config[0];
		//echo $field."<br/>";
		echo "<fieldset id='$field'>";
		echo "<legend>$field</legend>";
		}
		elseif ($field =="")
		{
		$field=$config[0];
		//echo $field."<br/>";
		echo "<fieldset id='$field'>";
		echo "<legend>$field</legend>";
		}
		//var_dump($configValue[0]);
		//var_dump(substr($configValue[1],0,-2));
		echo $form->input(base64_encode($config[0]."(".$configValue[0]), array('label'=>$configValue[0],'value'=>substr($configValue[1],0,-2)));
		//echo "<br/>\n";
	}
	elseif(isset($value['comment']))
	{
	echo $form->hidden("comment".$counter,array('value'=>$value['comment']));
	}
	}
	echo $form->hidden("fileName",array('value'=>$fileName));
	/*
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
	}*/
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
