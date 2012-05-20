<?php

/**
 * home actions.
 *
 * @package    stickdown
 * @subpackage board
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class boardActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->stats = sfConfig::get('app_stat_item');

    $entities = EntityQuery::create()
      ->find()
    ;

    $this->info = array();
    $order = array();
    foreach($entities as $entity)
    {
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

  public function executeStat(sfWebRequest $request)
  {
    $this->stats = sfConfig::get('app_stat_item');
    $this->currentStat = $request->getParameter('stat');
    $this->forward404Unless(array_key_exists($this->currentStat, $this->stats));

    $entity = EntityQuery::create()
      ->filterByName($this->currentStat)
      ->findOne()
    ;
    $this->forward404Unless($entity);

    $params = $this->stats[$this->currentStat];
    $params['languages'] = $request->getParameter('languages', $params['languages']);

    $completeHistory = json_decode($entity->getHistory(), true);
    if($params['languages'])
    {
      $pattern = $this->generatePattern($completeHistory, $params['languages']);
      $history = $this->sortHistory($completeHistory, $pattern);
    }
    else
    {
      $pattern = null;
      $history = $this->computeHistoryTotal($completeHistory);
    }

    $this->entity = array(
      'params' => $params,
      'pattern' => $pattern,
      'values' => $history
    );

    $this->average = round(($entity->getNbDay()) ? $entity->getValue() / $entity->getNbDay() : 0, 0);
  }

  public function generatePattern($history, $maxLanguages)
  {
    if(count($history) == 0)
    {
      return $history;
    }

    $lastDay = array_slice($history, -1, 1);
    $lastDayLanguage = $lastDay[key($lastDay)]['l'];
    array_multisort(array_values($lastDayLanguage), SORT_DESC, array_keys($lastDayLanguage), SORT_ASC, $lastDayLanguage);
    $lastDayLanguage = array_slice($lastDayLanguage, 0, $maxLanguages);
    $lastDayLanguage['Others'] = 0;

    return $lastDayLanguage;
  }

  public function sortHistory($completeHistory, $pattern)
  {
    $result = array();

    foreach($completeHistory as $date => $info)
    {
      foreach($pattern as $language => $count)
      {
        $result[$date][$language] = (isset($info['l'][$language])) ? $info['l'][$language] : '';
      }

      foreach($info['l'] as $language => $count)
      {
        if(!array_key_exists($language, $pattern))
        {
          $result[$date]['Others'] = (isset($result[$date]['Others'])) ? $result[$date]['Others'] + $count : $count;
        }
      }
    }

    return $result;
  }

  public function computeHistoryTotal($completeHistory)
  {
    $result = array();

    foreach($completeHistory as $date => $info)
    {
      $result[$date] = $info['c'];
    }

    return $result;
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
}
