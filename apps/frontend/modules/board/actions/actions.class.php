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
    $entities = EntityQuery::create()
      ->orderById()
      ->find()
    ;

    $this->entities = array();
    foreach($entities as $entity)
    {
      $this->entities[] = array(
        'name' => $entity->getName(),
        'average' => $entity->getAverageValue(),
        'values' => json_decode($entity->getHistory(), true)
      );
    }
  }
}
