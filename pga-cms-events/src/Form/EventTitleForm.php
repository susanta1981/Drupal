<?php

namespace Drupal\events\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class EventTitleForm.
 *
 * @package Drupal\events\Form
 */
class EventTitleForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'event_title_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $help_msg = "<p>Select a unique combination of <em>Year</em> and <em>Name</em>.</p>";
    $form['event_year'] = array(
      '#type' => 'select',
      '#title' => t('Year'),
      '#multiple' => FALSE,
      '#options' => $this->getTermOptions("event_year"),
      '#prefix' => $help_msg.'<div class="form--inline clearfix">'
    );

    $form['event_name'] = array(
      '#type' => 'select',
      '#title' => t('Name'),
      '#multiple' => FALSE,
      '#options' => $this->getTermOptions("event_name"),
      '#suffix' => '</div><br>'
    );

    $form['actions']['submit']['#type'] = 'submit';
    $form['actions']['submit']['#value'] = $this->t('Continue');
    $form['actions']['submit']['#button_type'] = 'primary';
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $tempstore = \Drupal::service('user.private_tempstore')->get('events');
    $tempstore->set('event_year', $form_state->getValue('event_year'));
    $tempstore->set('event_name', $form_state->getValue('event_name'));
    $form_state->setRedirect('entity.event_entity.add_form');
    //$form_state->setRedirect('entity.event_entity.add_form', ['event_year' => $form_state->getValue('event_year'),'event_name' => $form_state->getValue('event_name')]);
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    $verified_title = \Drupal::entityQuery('event_entity')
    ->condition('event_title', $form_state->getValue('event_year')."-".$form_state->getValue('event_name'))
    ->count()->execute();
    if ($verified_title > 0) {
      $form_state->setErrorByName('event_year', $this->t('Oops. An event already exists with this combination.'));
      $form_state->setErrorByName('event_name');
    }
  }

  private function getTermOptions($vid){
    $query = \Drupal::entityQuery('taxonomy_term');
        $query->condition('vid', $vid);
        $tids = $query->execute();
        $terms = \Drupal\taxonomy\Entity\Term::loadMultiple($tids);
    $options = array();
    foreach($terms as $term){
      $options[$term->name->value] = $term->name->value;
    }
    return $options;
  }

}
