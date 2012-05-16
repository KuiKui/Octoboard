<?php foreach($entities as $entity): ?>
  <table class="highchart" data-graph-container-before="1" data-graph-type="line">
    <caption><?php echo $entity['name'] ?></caption>
    <thead>
      <tr>
        <th>Languages</th>
        <th>Count</th>
        <th>%</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($entity['values'] as $date => $value): ?>
        <tr>
          <td colspan="3" class="date"><?php echo $date ?> - <?php echo $value['c'] ?></td>
        </tr>
        <?php foreach($value['l'] as $language => $content): ?>
          <tr>
            <td><?php echo $language ?></td>
            <td><?php echo $content['c'] ?></td>
            <td><?php echo $content['p'] ?></td>
          </tr>
        <?php endforeach; ?>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endforeach; ?>
