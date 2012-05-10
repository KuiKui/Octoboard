<?php

require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');
$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'dev', true);
sfContext::createInstance($configuration);

if(count($argv) < 2)
{
  $day = strtotime('yesterday');
  $day = date('Y-m-d', $day);
}
else
{
  $day = $argv[1]; 
}

$importDir = sfConfig::get('sf_data_dir').DIRECTORY_SEPARATOR.'import';

if(chdir($importDir) === false)
{
  echo("No directory : ".$importDir."\n");
  exit(1);
}

echo "Delete old files\n\n";
exec('rm -f *');

$result = array(
  'repository-new' => 0,
  'pullrequest-new' => 0,
  'pullrequest-merged' => 0,
  'issue-new' => 0,
  'issue-fixed' => 0,
  'open-sourced' => 0,
  'gist-new' => 0
);

for($hour = 14; $hour <= 15; $hour++)
{
  $archiveName = sprintf('%s-%s', $day, $hour);

  echo "Archive : ".$archiveName."\n";
  echo "Download archive\n";
  exec(sprintf('wget http://data.githubarchive.org/%s.json.gz >/dev/null 2>&1', $archiveName));
  echo "Unzip archive\n";  
  exec(sprintf('gunzip %s.json.gz >/dev/null', $archiveName));
  echo "Split archive\n";  
  exec(sprintf("sed %s %s.json > %s.json.line\n", escapeshellarg('s/}{/}\n{/g'), $archiveName, $archiveName));
  echo "Import archive\n";
  
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
  echo "Done.\n\n";
}

var_dump($result);
