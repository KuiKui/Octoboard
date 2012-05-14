<?php

require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');
$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'dev', true);
sfContext::createInstance($configuration);

if(count($argv) < 2)
{
  $firstDay = strtotime('yesterday');
  $firstDay = date('Y-m-d', $firstDay);
}
else
{
  $firstDay = $argv[1]; 
}

if(count($argv) < 3)
{
  $lastDay = $firstDay;
}
else
{
  $lastDay = $argv[2];
}

$days = myDate::dateRangeArray($firstDay, $lastDay);

$importDir = sfConfig::get('sf_data_dir').DIRECTORY_SEPARATOR.'import';

if(chdir($importDir) === false)
{
  echo("No directory : ".$importDir."\n");
  exit(1);
}

foreach($days as $day)
{
  echo "Import [".$day."]\n";
  cleanImport();

  $result = array(
    'repository-new' => 0,
    'pullrequest-new' => 0,
    'pullrequest-merged' => 0,
    'issue-new' => 0,
    'issue-fixed' => 0,
    'ghpages-new' => 0,
    'open-sourced' => 0,
    'gist-new' => 0
  );
  
  for($hour = 0; $hour <= 23; $hour++)
  {
    $archiveName = sprintf('%s-%s', $day, $hour);
  
    echo "Archive : ".$archiveName."\n";
    exec(sprintf('curl http://data.githubarchive.org/%s.json.gz | gunzip | sed -e %s > %s.json.line', $archiveName, escapeshellarg('s/}{/}\
{/g'), $archiveName));
    
    $filename = $importDir.DIRECTORY_SEPARATOR.$archiveName.'.json.line';
  
    $fp = @fopen($filename, "r");
    if ($fp) {
      while (($line = fgets($fp, 4096)) !== false) {
        $data = json_decode($line, true);
        switch($data['type'])
        {
          case 'CreateEvent':
            if($data['payload']['ref_type'] == 'repository')
            {
              $result['repository-new']++;
            }
            else if($data['payload']['ref'] == 'gh-pages')
            {
              $result['ghpages-new']++;
            }
            break;
  
          case 'PullRequestEvent':
            if($data['payload']['action'] == 'opened')
            {
              $result['pullrequest-new']++;
            }
            break;
  
          case 'IssuesEvent':
            if($data['payload']['action'] == 'opened')
            {
              $result['issue-new']++;
            }
            else if($data['payload']['action'] == 'closed')
            {
              $result['issue-fixed']++;
            }
            break;
          
          case 'ForkApplyEvent':
            $result['pullrequest-merged']++;
            break;
  
          case 'PublicEvent':
            $result['open-sourced']++;
            break;
  
          case 'GistEvent':
            if($data['payload']['action'] == 'create')
            {
              $result['gist-new']++;
            }
            break;
  
          default:
            //echo $data['type']."\n";
            break;
        }
      }
      if (!feof($fp)) {
        echo "Error: unexpected fgets() fail\n";
        exit(1);
      }
      fclose($fp);
    }
  }

  echo "Save in DB...";
  foreach($result as $entityName => $entityValue)
  {
    $entity = EntityQuery::create()
      ->filterByName($entityName)
      ->findOne()
    ;
    
    if(!$entity)
    {
      $entity = new Entity();
      $entity
        ->setName($entityName)
        ->setValue(0)
        ->setAverageValue(0)
        ->setAverageCount(0)
        ->setGapValue(0)
        ->setGapPercentage(0)
        ->save();
    }
    
    if($entity)
    {
      $tmpValue = $entity->getAverageValue() * $entity->getAverageCount();
      $newCount = $entity->getAverageCount() + 1;
      $average = ($tmpValue + $entityValue) / $newCount;
      $gapValue = $entityValue - $average;
      $gapPercentage = floatval(round(($average) ? $gapValue * 100 / $average : 0, 2));
  
      $history = json_decode($entity->getHistory(), true);
      $history[$day] = array('v' => $entityValue, 'gp' => $gapPercentage);
      if(count($history) > 8)
      {
        array_shift($history);
      }
  
      $entity
        ->setValue($entityValue)
        ->setAverageCount($newCount)
        ->setAverageValue($average)
        ->setGapValue($gapValue)
        ->setGapPercentage($gapPercentage)
        ->setHistory(json_encode($history))
        ->save()
      ;
    }
  }
  echo "Done.\n\n";
}

cleanImport();

function cleanImport()
{
  exec(sprintf('%s/symfony dci', sfConfig::get('sf_root_dir')));
}
