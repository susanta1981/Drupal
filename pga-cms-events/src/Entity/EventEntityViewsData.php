<?php

namespace Drupal\events\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for Event entity entities.
 */
class EventEntityViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['event_entity']['table']['base'] = array(
      'field' => 'id',
      'title' => $this->t('Event entity'),
      'help' => $this->t('The Event entity ID.'),
    );

    return $data;
  }

}
