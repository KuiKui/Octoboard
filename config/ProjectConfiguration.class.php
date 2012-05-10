<?php

require_once dirname(__FILE__).'/../lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    $this->enablePlugins(array(
      'lessPlugin',
      'sfPropelORMPlugin'
    ));

    // set default form decorator
    sfWidgetFormSchema::setDefaultFormFormatterName('custom');
  }
}
