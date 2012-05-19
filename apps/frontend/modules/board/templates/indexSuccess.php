<div id="header">
  <?php echo link_to('Octoboard', '@home', array('title' => 'Octoboard', 'id' => 'octoboard')) ?>
  <ul id="menu">
    <?php foreach($stats as $stat => $params): ?>
      <li class="<?php if($currentStat == $stat) echo 'selected'?>"><?php echo link_to($params['menu'], '/'.$stat, array('title' => $params['title'])) ?></li>
    <?php endforeach; ?>
  </ul>
</div>
<div id="content">
  <h2>GitHub activity dashboard.</h2>
  <p id="intro">Octoboard is based on <a href="http://www.githubarchive.org/">GitHub Archive</a> : each day, it scans new GitHub events archives and computes a few stats, with a 15 days history. You can see some general data on this page, or use menu for more information about language and history.</p>
  <p>Since march 11th, 2012 :</p>
  <ul>
    <li><em><?php echo $info['repository']['total'] ?></em> repositories have been created, which means <em><?php echo $info['repository']['by-day'] ?></em> repositories a day. Yesterday was a <span class="<?php echo $info['repository']['gap']['class'] ?>"><?php echo $info['repository']['gap']['state'] ?></span> day, they were <?php echo $info['repository']['gap']['sign-word-1'] ?> <?php echo $info['repository']['gap']['percentage'] ?>% <?php echo $info['repository']['gap']['sign-word-2'] ?> average.</li>
    <li><em><?php echo $info['pullrequest']['total'] ?></em> pull requests have been opened, which means <em><?php echo $info['pullrequest']['by-day'] ?></em> pull requests a day. Yesterday was a <span class="<?php echo $info['pullrequest']['gap']['class'] ?>"><?php echo $info['pullrequest']['gap']['state'] ?></span> day, they were <?php echo $info['pullrequest']['gap']['sign-word-1'] ?> <?php echo $info['pullrequest']['gap']['percentage'] ?>% <?php echo $info['pullrequest']['gap']['sign-word-2'] ?> average.</li>
    <li><em><?php echo $info['public']['total'] ?></em> projects have been open sourced, which means <em><?php echo $info['public']['by-day'] ?></em> projects a day. Yesterday was a <span class="<?php echo $info['public']['gap']['class'] ?>"><?php echo $info['public']['gap']['state'] ?></span> day, they were <?php echo $info['public']['gap']['sign-word-1'] ?> <?php echo $info['public']['gap']['percentage'] ?>% <?php echo $info['public']['gap']['sign-word-2'] ?> average.</li>
    <li><em><?php echo $info['ghpages']['total'] ?></em> GitHub Pages have been created, which means <em><?php echo $info['ghpages']['by-day'] ?></em> GitHub Pages a day. Yesterday was a <span class="<?php echo $info['ghpages']['gap']['class'] ?>"><?php echo $info['ghpages']['gap']['state'] ?></span> day, they were <?php echo $info['ghpages']['gap']['sign-word-1'] ?> <?php echo $info['ghpages']['gap']['percentage'] ?>% <?php echo $info['ghpages']['gap']['sign-word-2'] ?> average.</li>
    <li><em><?php echo $info['gist']['total'] ?></em> Gists have been created, which means <em><?php echo $info['gist']['by-day'] ?></em> gists a day. Yesterday was a <span class="<?php echo $info['gist']['gap']['class'] ?>"><?php echo $info['gist']['gap']['state'] ?></span> day, they were <?php echo $info['gist']['gap']['sign-word-1'] ?> <?php echo $info['gist']['gap']['percentage'] ?>% <?php echo $info['gist']['gap']['sign-word-2'] ?> average.</li>
  </ul>
  <p>Octoboard is an <a href="https://github.com/KuiKui/Octoboard">open source project</a> built for the <a href="https://github.com/blog/1118-the-github-data-challenge" title="GitHub Data Challenge">GitHub Data Challenge</a>. Feel free to fork it and add some news stats !<br />By the way, it's a very simple and sketchy project, quickly built with : PHP, MySql, Symfony, <a href="http://lesscss.org/">LESS</a>, <a href="http://www.highcharts.com/">Highcharts</a> and <a href="http://pmsipilot.github.com/jquery-highchartTable-plugin/">highchartTable</a>.</p>
</div>
