<?php

namespace Drupal\nica_entity\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class NicaEntityTypeForm.
 *
 * @package Drupal\nica_entity\Form
 */
class NicaEntityTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $nica_entity_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $nica_entity_type->label(),
      '#description' => $this->t("Label for the Nica entity type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $nica_entity_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\nica_entity\Entity\NicaEntityType::load',
      ],
      '#disabled' => !$nica_entity_type->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $nica_entity_type = $this->entity;
    $status = $nica_entity_type->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Nica entity type.', [
          '%label' => $nica_entity_type->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Nica entity type.', [
          '%label' => $nica_entity_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($nica_entity_type->toUrl('collection'));
  }

}
