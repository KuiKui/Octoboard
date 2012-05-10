<?php

class cacheClearTask extends sfBaseTask
{
  /**
   * @return void
   */
  protected function configure()
  {
    $this->namespace = 'deuteron';
    $this->name = 'clear-cache';
    $this->aliases = array('dcc');
    $this->briefDescription = 'Complete removal of cache (.gitignore file is not erased)';
  }

  /**
   * @param array $arguments
   * @param array $options
   * @return int
   */
  protected function execute($arguments = array(), $options = array())
  {
    if (!sfConfig::get('sf_cache_dir') || !is_dir(sfConfig::get('sf_cache_dir')))
    {
      throw new sfException(sprintf('Cache directory "%s" does not exist.', sfConfig::get('sf_cache_dir')));
    }
    
    try
    {
      $this->getFilesystem()->remove(sfFinder::type('any')
        ->discard('.sf')
        ->discard('.gitignore')
        ->in(sfConfig::get('sf_cache_dir'))
      );

      $this->logSection('less', 'Compilation');
      $lessTask = new lessPluginTask($this->dispatcher, $this->formatter);
      $lessTask->run(array(), array());
    }
    catch(Exception $e)
    {
      return 1;
    }

    return 0;
  }
}
