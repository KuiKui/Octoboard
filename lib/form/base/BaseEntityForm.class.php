<?php

/**
 * Entity form base class.
 *
 * @method Entity getObject() Returns the current form's model object
 *
 * @package    octoboard
 * @subpackage form
 * @author     Denis Roussel
 */
abstract class BaseEntityForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'name'       => new sfWidgetFormInputText(),
      'value'      => new sfWidgetFormInputText(),
      'nb_day'     => new sfWidgetFormInputText(),
      'history'    => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'name'       => new sfValidatorString(array('max_length' => 255)),
      'value'      => new sfValidatorInteger(array('min' => -2147483648, 'max' => 2147483647)),
      'nb_day'     => new sfValidatorInteger(array('min' => -2147483648, 'max' => 2147483647)),
      'history'    => new sfValidatorString(array('max_length' => 8191)),
      'created_at' => new sfValidatorDateTime(array('required' => false)),
      'updated_at' => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('entity[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Entity';
  }


}
