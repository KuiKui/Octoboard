<?php

/**
 * choice actions.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage choice
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 16987 2009-04-04 14:16:46Z fabien $
 */
class choiceActions extends sfActions
{
  public function executeArticle($request)
  {
    $this->form = new ArticleForm();

    if ($request->getParameter('impossible_validator'))
    {
      $criteria = new Criteria();
      $criteria->add(CategoryPeer::ID, null, Criteria::ISNULL);

      $this->form->getValidator('category_id')->setOption('criteria', $criteria);
    }

    if ($request->getParameter('impossible_validator_many'))
    {
      $criteria = new Criteria();
      $criteria->add(AuthorPeer::ID, null, Criteria::ISNULL);

      $this->form->getValidator('author_article_list')->setOption('criteria', $criteria);
    }

    if ($request->isMethod(sfRequest::POST))
    {
      $this->form->bind($request->getParameter('article'));

      if ($this->form->isValid())
      {
        $this->form->save();

        $this->redirect('choice/ok');
      }
    }
  }

  public function executeOk()
  {
    return $this->renderText('ok');
  }
}
