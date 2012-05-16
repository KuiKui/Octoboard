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

  $results = array();
  
  for($hour = 0; $hour <= 23; $hour++)
  {
    $archiveName = sprintf('%s-%s', $day, $hour);
  
    echo "Archive : ".$archiveName."\n";
    exec(sprintf('curl http://data.githubarchive.org/%s.json.gz 2>/dev/null | gunzip | sed -e %s > %s.json.line', $archiveName, escapeshellarg('s/}{/}\
{/g'), $archiveName));
    
    $filename = $importDir.DIRECTORY_SEPARATOR.$archiveName.'.json.line';
  
    $fp = @fopen($filename, "r");
    if ($fp) {
      while (($line = fgets($fp, 8192)) !== false) {
        $data = json_decode($line, true);
        if(isset($data['type']))
        {
          $language = isset($data['repository']['language']) ? $data['repository']['language'] : 'Undefined';

          addOne($results, 'global-activity', $language);

          switch($data['type'])
          {
            case 'CreateEvent':
              if($data['payload']['ref_type'] == 'repository')
              {
                addOne($results, 'repository-new', $language);
              }
              else if($data['payload']['ref'] == 'gh-pages')
              {
                addOne($results, 'ghpages-new', $language);
              }
              break;
    
            case 'PullRequestEvent':
              if($data['payload']['action'] == 'opened')
              {
                addOne($results, 'pullrequest-new', $language);
              }
              break;
    
            case 'WatchEvent':
              addOne($results, 'watch-new', $language);
              break;
    
            case 'PublicEvent':
              addOne($results, 'open-sourced', $language);
              break;
    
            case 'ForkEvent':
              addOne($results, 'fork-new', $language);
              break;

            default:
              //echo $data['type']."\n";
              break;
          }
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
  foreach($results as $eventName => $languages)
  {
    $entity = EntityQuery::create()
      ->filterByName($eventName)
      ->findOne()
    ;
    
    if(!$entity)
    {
      $entity = new Entity();
      $entity
        ->setName($eventName)
        ->save();
    }
    
    if($entity)
    {
      $allLanguageCount = 0;
      foreach($languages as $languageName => $language)
      {
        $allLanguageCount += $language['count'];
      }

      $dayEntity = array(
        'c' => $allLanguageCount,
        'l' => array()
      );

      foreach($languages as $languageName => $language)
      {
        $dayEntity['l'][$languageName] = array(
          'c' => $language['count'],
          'p' => floatval(round(($allLanguageCount) ? $language['count'] * 100 / $allLanguageCount : 0, 2))
        );
      }

      $history = json_decode($entity->getHistory(), true);
      $history[$day] = $dayEntity;
      if(count($history) > 8)
      {
        array_shift($history);
      }
  
      $entity
        ->setValue($entity->getValue() + $allLanguageCount)
        ->setNbDay($entity->getNbDay() + 1)
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

function addOne(&$results, $key, $language)
{
  if(isset($results[$key][$language]['count']) && is_integer($results[$key][$language]['count']))
  {
    $results[$key][$language]['count']++;
  }
  else
  {
    $results[$key][$language]['count'] = 1;
  }
}
