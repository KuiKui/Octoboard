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
      'name'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'value'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'average_value'  => new sfWidgetFormFilterInput(),
      'average_count'  => new sfWidgetFormFilterInput(),
      'gap_value'      => new sfWidgetFormFilterInput(),
      'gap_percentage' => new sfWidgetFormFilterInput(),
      'history'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'created_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'updated_at'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'name'           => new sfValidatorPass(array('required' => false)),
      'value'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'average_value'  => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'average_count'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'gap_value'      => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'gap_percentage' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'history'        => new sfValidatorPass(array('required' => false)),
      'created_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated_at'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
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
      'id'             => 'Number',
      'name'           => 'Text',
      'value'          => 'Number',
      'average_value'  => 'Number',
      'average_count'  => 'Number',
      'gap_value'      => 'Number',
      'gap_percentage' => 'Number',
      'history'        => 'Text',
      'created_at'     => 'Date',
      'updated_at'     => 'Date',
    );
  }
}
