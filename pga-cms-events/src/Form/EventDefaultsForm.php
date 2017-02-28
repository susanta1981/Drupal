<?php

namespace Drupal\events\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Cache\CacheBackendInterface;

/**
 * Configure Event Defaults settings for this site.
 */
class EventDefaultsForm extends FormBase {

  /**
   * @var CacheBackendInterface
   */
  protected $cacheBackend;

  /**
   * Class constructor.
   */
  public function __construct(CacheBackendInterface $cacheBackend) {
    $this->cacheBackend = $cacheBackend;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('cache.default')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'event_defaults_form';
  }


  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // echo"Cache:<br>";
    // $cacheBackend = \Drupal::cache();
    // echo"dumping cacheBackend:<br>";
    // var_dump($cacheBackend);
    // if ($cacheBackend = \Drupal::cache()->get('cache.default',TRUE)) {
    //   $data = $cacheBackend->data;
    //   echo"dumping DATA:<br>";
    //   var_dump($data);
    // }
    // echo"<br><hr><br>";
    // Get Taxonomy Events
    $name_terms = $this->getTermOptions("event_name");
    $year_terms = $this->getTermOptions("event_year");

    // Get DB Defaults Events
    $query = \Drupal::database()->select('event_defaults', 'ed');
    $query->fields('ed',['eid','event_name','default_year','url_slug']);
    $db = $query->execute()->fetchAll();
    $terms_to_add = $name_terms;

    $form['event_defaults'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Event Default Settings'),
      '#tree' => TRUE,
    ];

    if(!empty($db)){
      /* Events existing in DB */
      $form['event_defaults']['event_defaults_update'] = [
        '#type' => 'details',
        '#title' => $this->t('Defaults'),
        '#tree' => TRUE,
        '#open' => TRUE,
      ];
      foreach($db as $event){
        if(in_array($event->event_name,$name_terms)){
          $form['event_defaults']['event_defaults_update'][$event->eid]['event_name'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Event Name'),
            '#default_value' => $event->event_name,
            '#disabled' => TRUE,
            '#required' => TRUE,
          ];
          $form['event_defaults']['event_defaults_update'][$event->eid]['default_year'] = [
            '#type' => 'select',
            '#title' => $this->t('Event Default Year'),
            '#default_value' => intval($event->default_year),
            '#options' => $year_terms,
            '#required' => TRUE,
            '#multiple' => FALSE,
            '#prefix' => '<div class="form--inline clearfix">'
          ];
          $form['event_defaults']['event_defaults_update'][$event->eid]['url_slug'] = [
            '#type' => 'textfield',
            '#title' => $this->t('URL Slug'),
            '#default_value' => $event->url_slug,
            '#required' => TRUE,
          ];
          if ($event === end($db)){
            $form['event_defaults']['event_defaults_update'][$event->eid]['url_slug']['#suffix'] = '</div>';
          } else {
            $form['event_defaults']['event_defaults_update'][$event->eid]['url_slug']['#suffix'] = '</div><br><hr><br>';
          }
          /* Build list of pending terms to add to DB */
          unset($terms_to_add[$event->event_name]);
        } else {
          /* The record in DB does not exist in terms anymore so clean DB. */
          $query = \Drupal::database()->delete('event_defaults');
          $query->condition('event_name', $event->event_name);
          $query->execute();
        }
      }
    }

    if(!empty($terms_to_add)){
      /* Events needing to be added to DB */
      $form['event_defaults']['event_defaults_add'] = [
        '#type' => 'details',
        '#title' => $this->t('Pending Defaults'),
        '#tree' => TRUE,
        '#open' => TRUE,
      ];
      foreach($terms_to_add as $k => $event_name){
        $default_slug = strtolower(preg_replace("/(\W)+/", "", $event_name));
        $form['event_defaults']['event_defaults_add'][$k]['event_name'] = [
          '#type' => 'textfield',
          '#title' => $this->t('Event Name'),
          '#default_value' => $event_name,
          '#disabled' => TRUE,
          '#required' => TRUE,
        ];

        $form['event_defaults']['event_defaults_add'][$k]['default_year'] = [
          '#type' => 'select',
          '#title' => $this->t('Event Default Year'),
          '#default_value' => date("Y"),
          '#options' => $year_terms,
          '#required' => TRUE,
          '#multiple' => FALSE,
          '#prefix' => '<div class="form--inline clearfix">'
        ];

        $form['event_defaults']['event_defaults_add'][$k]['url_slug'] = [
          '#type' => 'textfield',
          '#title' => $this->t('URL Slug'),
          '#default_value' => $default_slug,
          '#required' => TRUE,
        ];
        if ($event_name === end($terms_to_add)){
          $form['event_defaults']['event_defaults_add'][$k]['url_slug']['#suffix'] = '</div>';
        } else {
          $form['event_defaults']['event_defaults_add'][$k]['url_slug']['#suffix'] = '</div><br><hr><br>';
        }
      }
    }

    $form['actions']['submit']['#type'] = 'submit';
    $form['actions']['submit']['#value'] = $this->t('Save Defaults');
    $form['actions']['submit']['#button_type'] = 'primary';


    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();

    // Add new Defaults
    if(isset($values['event_defaults']['event_defaults_add'])){
      $events_to_add = $values['event_defaults']['event_defaults_add'];
      foreach($events_to_add as $add_event){
        $add_query = \Drupal::database()->insert('event_defaults');
        $add_query->fields(array(
          'event_name' => $add_event['event_name'],
          'default_year' => intval($add_event['default_year']),
          'url_slug' => $add_event['url_slug'],
        ))
        ->execute();
      }
    }

    // Update Stored Defaults
    if(isset($values['event_defaults']['event_defaults_update'])){
      $events_to_update = $values['event_defaults']['event_defaults_update'];
      foreach($events_to_update as $eid => $update_event){
        $update_query = \Drupal::database()->update('event_defaults');
        $update_query->condition('eid', $eid)
        ->fields(array(
          'default_year' => intval($update_event['default_year']),
          'url_slug' => $update_event['url_slug'],
        ))
        ->execute();
      }
    }


    // Get DB Defaults Events
    $defaults_query = \Drupal::database()->select('event_defaults', 'ed');
    $defaults_query->fields('ed',['eid','event_name','default_year','url_slug']);
    $defaults_data = $defaults_query->execute()->fetchAll();
    $defaults_cache = \Drupal::cache('events_cache');
    $defaults_cache->set('defaults_cache', $defaults_data, CacheBackendInterface::CACHE_PERMANENT, array('defaults_cache'));
    drupal_set_message('Event Default Settings have been saved.', 'status');
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

  /**
   * Clears the slugs from the cache.
   */
  function clearDefaultsCache() {
    if ($cache = \Drupal::cache()->get('defaults_cache')) {
      \Drupal::cache()->delete('defaults_cache');
      drupal_set_message('Event Default Settings have been removed from cache.', 'status');
    }
    else {
      drupal_set_message('No Event Default Settings in cache.', 'error');
    }
  }

}
