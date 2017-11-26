<?php

namespace Drupal\agile_issue\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class IssueTypeForm.
 */
class IssueTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $issue_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $issue_type->label(),
      '#description' => $this->t("Label for the Issue type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $issue_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\agile_issue\Entity\IssueType::load',
      ],
      '#disabled' => !$issue_type->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $issue_type = $this->entity;
    $status = $issue_type->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Issue type.', [
          '%label' => $issue_type->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Issue type.', [
          '%label' => $issue_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($issue_type->toUrl('collection'));
  }

}
