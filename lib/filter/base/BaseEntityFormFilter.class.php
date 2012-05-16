<?php

/**
 * Entity filter form base class.
 *
 * @package    octoboard
 * @subpackage filter
 * @author     Denis Roussel
 */
abstract class BaseEntityFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'value'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'nb_day'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'history'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'updated_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'name'       => new sfValidatorPass(array('required' => false)),
      'value'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'nb_day'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'history'    => new sfValidatorPass(array('required' => false)),
      'created_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('entity_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Entity';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'name'       => 'Text',
      'value'      => 'Number',
      'nb_day'     => 'Number',
      'history'    => 'Text',
      'created_at' => 'Date',
      'updated_at' => 'Date',
    );
  }
}
