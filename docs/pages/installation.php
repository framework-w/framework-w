<h2>Installation</h2>
<h3>Prérequis</h3>
<ul>
	<li>PHP >= 7.1</li>
	<li>Composer</li>
	<li>MySQL avec PDO</li>
	<li><span class="code">mbstring</span> extension de PHP</li>
</ul>

<h3>Étapes pour l'installation</h3>
<p>Pour installer le framework :</p>
<p>1. Dans un terminal, naviguez vers votre dossier contenant vos projets web (htdocs/ ou www/).</p>
<pre class="command-line language-powershell" data-prompt="PS C:\Users\Axel"><code class="language-powershell">cd C:/xampp/htdocs/</code></pre>
<p>2. Installer le framework :</p>
<pre class="command-line language-powershell" data-prompt="PS C:\Users\Axel"><code class="language-powershell">composer create-project axessweb/w-framework-skeleton nom_de_mon_projet</code></pre>

<p>3. Créez une copie de <span class="code">app/config.dist.php</span> et nommez-la <span class="code">app/config.php</span></p>
<p>5. <a href="?p=configuration" title="Référence des configurations">Configurez votre application</a> dans <span class="code">app/config.php</span> et <span class="code">app/routes.php</span></p>

<h3>Tester l'installation</h3>
<p>Naviguez vers <span class="code">http://localhost/nom_de_mon_projet/public/</span></p>

<h4>Conseil</h4>
<p>Afin d'utiliser votre application dans des conditions optimales, il est conseillé de configurer et d'utiliser un VirtualHost et de définir le DocumentRoot sur le répertoire <code class="code">public/</code></p>
