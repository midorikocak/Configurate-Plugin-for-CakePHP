<div class="file form"><table cellpadding="0" cellspacing="0">
  <tr>
    <th>Filename</th>
    <th>Actions</th>
  </tr>
<?php
foreach($folders as $folder)
{
	$time = substr($folder,-12,12);
	$nums = str_split($time,2);
	echo "<tr><td>$nums[0].$nums[1].$nums[2] $nums[3]:$nums[4] $folder</a></td>";
	echo "<td>";
	if(substr($folder,0,3)=="sql")
	{
		echo "<a href='recoverSQL/$nums[0]$nums[1]$nums[2]$nums[3]$nums[4]$nums[5]'>Recover</a> | ";
	}
	echo "<a href='deleteFolder/$folder'>Delete</a></td></tr>";
}
foreach($files as $file)
{
	$time = substr($file,-12,12);
	$nums = str_split($time,2);
	echo "<tr><td>$nums[0].$nums[1].$nums[2] $nums[3]:$nums[4] $file</a></td>";
	echo "<td><a href='deleteFile/$file'>Delete</a></td></tr>";
}
?>
</table>
<h3>Note</h3>
<p>Due to file security reasons, you have to recover manually configuration files or folders from configurate/backups folder to your app/config folder.</p>
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
