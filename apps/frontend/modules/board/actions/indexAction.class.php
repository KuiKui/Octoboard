<?php

/**
 * home actions.
 *
 * @package    stickdown
 * @subpackage board
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class indexAction extends sfAction
{
  public function execute($request)
  {
    $this->stats = sfConfig::get('app_stat_item');

    $entities = EntityQuery::create()
      ->find()
    ;

    $this->info = array();
    $this->emotions = array();
    $order = array();

    foreach($entities as $entity)
    {
      if($entity->getName() == 'emotion')
      {
        $this->emotions = $this->generateEmotionsExtremesLanguages($entity);
      }

      if(!isset($this->stats[$entity->getName()]['home']) || !$this->stats[$entity->getName()]['home'])
      {
        continue;
      }

      $gap = $this->getLastDayGapPercentage($entity);
      $this->info[$entity->getName()] = array(
        'total' => number_format($entity->getValue()),
        'by-day' => number_format($entity->getValue() / $entity->getNbDay()),
        'gap' => $this->getGapInfo($gap),
        'icon' => $this->stats[$entity->getName()]['icon'],
        'title' => $this->stats[$entity->getName()]['title']
      );
      $order[] = $this->stats[$entity->getName()]['order'];
    }
    array_multisort($order, SORT_ASC, $this->info);
  }

  public function getLastDayGapPercentage($entity)
  {
    $history = json_decode($entity->getHistory(), true);
    if(count($history) == 0)
    {
      return 0;
    }

    $lastDay = array_slice($history, -1, 1);
    $lastDayCount = $lastDay[key($lastDay)]['c'];
    $average = ($entity->getNbDay()) ? $entity->getValue() / $entity->getNbDay() : 0;
    $gap = $lastDayCount - $average;
    $gapPercentage = round(($average) ? $gap * 100 / $average : 0, 0);

    return $gapPercentage;
  }

  public function getGapInfo($gap)
  {
    $info = array(
      'state' => 'normal',
      'sign-word-1' => '',
      'sign-word-2' => '',
      'class' => 'normal'
    );

    if($gap > 0)
    {
      $info['sign-word-1'] = 'up';
      $info['sign-word-2'] = 'above';
    }
    else
    {
      $info['sign-word-1'] = 'down';
      $info['sign-word-2'] = 'below';
    }

    if($gap > 35)
    {
      $info['state'] = 'very good';
      $info['class'] = 'positive';
    }
    else if($gap > 15)
    {
      $info['state'] = 'good';
      $info['class'] = 'positive';
    }
    else if($gap < -35)
    {
      $info['state'] = 'very bad';
      $info['class'] = 'negative';
    }
    else if($gap < -15)
    {
      $info['state'] = 'bad';
      $info['class'] = 'negative';
    }

    $info['percentage'] = abs($gap);

    return $info;
  }

  public function generateEmotionsExtremesLanguages($entity)
  {
    $history = json_decode($entity->getHistory(), true);

    if(count($history) == 0)
    {
      return $history;
    }

    $results  = array();

    $lastDay  = array_slice($history, -1, 1);
    $emotions = $lastDay[key($lastDay)];
    foreach($emotions as $emotion => $languages)
    {
      if(count($languages) > 1)
      {
        $languagesLabel = array_keys($languages);
        $results[$emotion]['first'] = $languagesLabel[count($languagesLabel) - 1];
        $results[$emotion]['last']  = $languagesLabel[0];
      }
    }

    return $results;
  }
}
