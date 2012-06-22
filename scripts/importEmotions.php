<?php

require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');
$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'dev', true);
sfContext::createInstance($configuration);

$emotions = array(
  'anger'     => '/\b(a+rgh|angry|annoyed|annoying|appalled|bitter|cranky|hate|hating|mad)\b/i',
  'joy'       => '/\b(yes|yay|hallelujah|hurray|bingo|amused|cheerful|excited|glad|proud)\b/i',
  'amusement' => '/\b(ha(ha)+|he(he)+|lol|rofl|lmfao|lulz|lolz|rotfl|lawl|hilarious)\b/i',
  'surprise'  => '/\b(yikes|gosh|baffled|stumped|surprised|shocked)\b/i',
  'issues'    => '/\b(bug|fix|issue)|corrected/i',
  'swearing'  => '/\b(wtf|wth|omfg|hell|ass|bitch|bullshit|bloody|fucking?|shit+y?|crap+y?)\b|\b(fuck|damn|piss|screw|suck)e?d?\b/i'
);

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

  $languages  = array();
  $results    = array();
  
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
        if(isset($data['repository']['language']) && isset($data['type']) && $data['type'] == 'PushEvent' && isset($data['payload']['shas']) && is_array($data['payload']['shas']))
        {
          $language =  $data['repository']['language'];

          foreach($data['payload']['shas'] as $commit)
          {
            $message = $commit[2];
            if(strlen($message) > 0)
            {
              addOne($languages, $language);
              foreach($emotions as $emotion => $regexp)
              {
                if(preg_match($regexp, $message))
                {
                  addOne($results[$emotion], $language);
                }
              }
            }
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

  $dayEntity = array();
  foreach($emotions as $emotion => $regexp)
  {
    if(!isset($results[$emotion]))
    {
      continue;
    }

    foreach(array_keys($results[$emotion]) as $language)
    {
      if($languages[$language] >= max($languages) / 10)
      {
        $dayEntity[$emotion][$language] = number_format($results[$emotion][$language] / $languages[$language] * 100, 2);
      }
    }

    if(isset($dayEntity[$emotion]))
    {
      asort($dayEntity[$emotion]);
    }
  }

  echo "Save in DB...";

  $entity = EntityQuery::create()
    ->filterByName('emotion')
    ->findOne()
  ;

  if(!$entity)
  {
    $entity = new Entity();
    $entity
      ->setName('emotion')
      ->setHistory('[]')
      ->save();
  }
  
  if($entity)
  {
    $history = json_decode($entity->getHistory(), true);
    $history[$day] = $dayEntity;
    if(count($history) > 15)
    {
      array_shift($history);
    }

    $entity
      ->setNbDay($entity->getNbDay() + 1)
      ->setHistory(json_encode($history))
      ->save()
    ;
  }
  echo "Done.\n\n";
}


cleanImport();

function cleanImport()
{
  exec(sprintf('%s/symfony dci', sfConfig::get('sf_root_dir')));
}

function addOne(&$results, $language)
{
  if(isset($results[$language]) && is_integer($results[$language]))
  {
    $results[$language]++;
  }
  else
  {
    $results[$language] = 1;
  }
}
