<?php

/**
 * Clears log files.
 *
 * @package    symfony
 * @subpackage task
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfLogClearTask.class.php 23922 2009-11-14 14:58:38Z fabien $
 */
class importClearTask extends sfBaseTask
{
  /**
   * @see sfTask
   */
  protected function configure()
  {
    $this->namespace = 'deuteron';
    $this->name = 'clear-import';
    $this->aliases = array('dci');
    $this->briefDescription = 'Complete removal of import files (.gitignore file is not erased)';
  }

  /**
   * @see sfTask
   */
  protected function execute($arguments = array(), $options = array())
  {
    if (!sfConfig::get('sf_data_dir') || !is_dir(sfConfig::get('sf_data_dir').DIRECTORY_SEPARATOR.'import'))
    {
      throw new sfException(sprintf('Import directory "%s" does not exist.', sfConfig::get('sf_data_dir').DIRECTORY_SEPARATOR.'import'));
    }
    
    try
    {
      $this->getFilesystem()->remove(sfFinder::type('any')
        ->discard('.gitignore')
        ->in(sfConfig::get('sf_data_dir').DIRECTORY_SEPARATOR.'import')
      );
    }
    catch(Exception $e)
    {
      return 1;
    }

    return 0;
  }
}
