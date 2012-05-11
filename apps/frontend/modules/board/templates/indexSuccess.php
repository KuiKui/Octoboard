<?php foreach($entities as $entity): ?>
  <table class="highchart" data-graph-container-before="1" data-graph-type="line">
    <caption><?php echo $entity['name'] ?></caption>
    <thead>
      <tr>
        <th>Day</th>
        <th>Count</th>
        <th>Average</th>
        <th>Gap</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($entity['values'] as $date => $value): ?>
        <tr>
          <td><?php echo $date ?></td>
          <td><?php echo $value['v'] ?></td>
          <td><?php echo $entity['average'] ?></td>
          <td><?php echo $value['gp'] ?>%</td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endforeach; ?>
