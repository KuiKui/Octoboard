<div id="header">
  <span class="mega-icon blacktocat"></span>
  <?php echo link_to('Octoboard', '/', array('title' => 'GitHub activity dashboard', 'id' => 'octoboard')) ?>
  <ul id="menu">
    <?php foreach($stats as $stat => $params): ?>
      <li class="<?php if($currentStat == $stat) echo 'selected'?>"><?php echo link_to($params['menu'], '/'.$stat, array('title' => $params['title'])) ?></li>
    <?php endforeach; ?>
  </ul>
</div>
<div id="content">
  <table class="highchart" data-graph-container-before="1" style="display:none"
  data-graph-datalabels-enabled="1"
  data-graph-yaxis-1-stacklabels-enabled="1"
  data-graph-color-1="#cb5653"
  data-graph-color-2="#9090ce"
  <?php if($entity['params']['languages']): ?>
    data-graph-type="column"
    data-graph-datalabels-color="white"
    data-graph-yaxis-1-title-text="Count"
    data-graph-margin-bottom="80"
  <?php else: ?>
    data-graph-type="column" 
    data-graph-datalabels-color="#555"
    data-graph-yaxis-1-title-text="Total"
    data-graph-legend-disabled="1"
    data-graph-margin-bottom="40"
  <?php endif; ?> 
  data-graph-height="650" 
  >
    <caption><?php echo $entity['params']['title'] ?></caption>
    <thead>
      <tr>
        <th></th>
        <th data-graph-type="area" data-graph-datalabels-enabled="0">Average</th>
        <?php if($entity['params']['languages']): ?>
          <?php foreach($entity['pattern'] as $language => $count): ?>
            <th data-graph-stack-group="stat"><?php echo $language ?></th>
          <?php endforeach; ?>
        <?php else: ?>
            <th></th>
        <?php endif; ?>
      </tr>
    </thead>
    <tbody>
        <?php foreach($entity['values'] as $date => $content): ?>
          <tr>
            <td><?php echo date("D d M", strtotime($date)); ?></td>
            <td><?php echo $average ?></td>
            <?php if($entity['params']['languages']): ?>
              <?php foreach($content as $language => $count): ?>
                <td><?php echo $count ?></td>
              <?php endforeach; ?>
            <?php else: ?>
              <td><?php echo $content ?></td>
            <?php endif; ?>
          </tr>
        <?php endforeach; ?>
    </tbody>
  </table>
  <form action="/<?php echo $currentStat ?>" method="get" id="languagesForm">
    <label for="languages">Number of displayed languages</label>
    <select name="languages" id="languages">
      <option value="0" <?php if($nbLanguages == 0) echo 'selected'; ?>>None</option>
      <?php for($nbLanguages = 1; $nbLanguages < 10; $nbLanguages++): ?>
        <option value="<?php echo $nbLanguages ?>" <?php if($nbLanguages == $entity['params']['languages']) echo 'selected'; ?>><?php echo $nbLanguages ?></option>
      <?php endfor; ?>
    </select>
  </form>
</div>
