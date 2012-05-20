<div id="header">
  <span class="mega-icon blacktocat"></span>
  <?php echo link_to('Octoboard', '/', array('title' => 'Octoboard', 'id' => 'octoboard')) ?>
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
    <?php foreach($info as $name => $params): ?>
      <li class="<?php echo $name ?>">
        <div class="title top"><span class="mini-icon <?php echo $params['icon'] ?>"></span><a href="/<?php echo $name ?>" title="More details"><?php echo $params['title'] ?></a></div>
        <div class="info"><div class="total"><?php echo $params['total'] ?></div><div>+ <em><?php echo $params['by-day'] ?></em> / day</div></div>
        <div class="title">Yesterday</div>
        <div class="yesterday">It was a <span class="<?php echo $params['gap']['class'] ?>"><?php echo $params['gap']['state'] ?></span> day, they were <?php echo $params['gap']['sign-word-1'] ?> <?php echo $params['gap']['percentage'] ?>% <?php echo $params['gap']['sign-word-2'] ?> average.</div>
      </li>
    <?php endforeach; ?>
  </ul>
  <p>Octoboard is an <a href="https://github.com/KuiKui/Octoboard">open source project</a> built for the <a href="https://github.com/blog/1118-the-github-data-challenge" title="GitHub Data Challenge">GitHub Data Challenge</a>. Feel free to fork it and add some new stats !<br />By the way, it's a very simple and sketchy project, quickly built with : PHP, MySql, Symfony, <a href="http://lesscss.org/">LESS</a>, <a href="http://www.highcharts.com/">Highcharts</a> and <a href="http://pmsipilot.github.com/jquery-highchartTable-plugin/">highchartTable</a>.</p>
</div>
<div id="footer">by <a title="on GitHub" href="https://github.com/KuiKui">Denis Roussel</a> for <a href="https://github.com/blog/1118-the-github-data-challenge" title="GitHub Data Challenge">GitHub Data Challenge</a></div>
