<?php
if (sizeof($path) > 0){
	$counter = 0;
	foreach($path as $paths)
	{

		if($counter==0)
		{
			$limiter ='';
		}
		else
		{
			$limiter =' > ';
		}
		if($paths['Type']['parent_id']==0)
		{
			$linkScript ='';
		}
		else
		{
			$linkScript = 'takeContent('.$paths['Type']['id'].')';
		}
		echo $limiter.$html->link(__($paths['Type']['title'], true),'#',array('style'=>'text-decoration:none;','onclick' => $linkScript));
		$counter++;
	}
}
if (sizeof($parent) > 0){
	echo '<div class="actions"><ul>';
	foreach($parent as $key => $value)
	{
		echo '<li>'.$html->link(__($value, true),'#',array('onclick' => 'takeContent('.$key.')'));// 
		//echo '<li>'.$html->link(__($value, true), array('plugin'=>'module','controller'=>'types','action' => 'show', $key));
	}
	echo '</ul></div>';
}
?>
