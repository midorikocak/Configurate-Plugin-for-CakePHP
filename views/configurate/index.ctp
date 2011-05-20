<div class='index'>
	<h2>Configurate Plugin for CakePHP</h2>
	<h3>About</h3>
	<p>Configurate plugin is designed for you to easily setup the configuration of CakePHP configuration quickly. Most essential tasks like editing of Core configuration, backup of Database and Configuration files and folders, data backup from database are implemented. There is too much future tasks to implement. You are welcomed to make any change and advises.</p>
	<h3>Author</h3>
	<p>May 2011, Mutlu Tevfik Kocak. You are free to ask any questions about the plugin and CakePHP bakery. For any other information about me, please check out my website: <a href="http://www.mtkocak.net">mtkocak.net</a></p>
	<h3>License</h3>
	<p>Configurate is distributed in the hope that it will be useful,<br/>
    but WITHOUT ANY WARRANTY; without even the implied warranty of<br/>
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the<br/>
    GNU General Public License for more details.<br/><br/>
    You should have received a copy of the GNU General Public License<br/>
    along with Configurate.  If not, see <a href="http://www.gnu.org/licenses/">gnu.org/licenses</a>.</p>
	<h3>Warning</h3>
	<p>USE RESPONSIBLY! THIS PLUGIN COMES ABSOLUTELY WITH NO WARRANTY. YOU MAY LOSE ALL OF YOUR CORE CONFIGURATION OR DATA. IF YOU ARE NOT SURE  WHAT IS ALL ABOUT, DO NOT USE IT!</p>
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