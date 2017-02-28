<?php

namespace Drupal\events;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of Event entity entities.
 *
 * @ingroup events
 */
class EventEntityListBuilder extends EntityListBuilder {

  use LinkGeneratorTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Event ID');
    $header['event_title'] = $this->t('Event Title');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\events\Entity\EventEntity */
    $row['id'] = $entity->id();
    $row['event_title'] = $this->l(
      $entity->label(),
      new Url(
        'entity.event_entity.edit_form', array(
          'event_entity' => $entity->id(),
        )
      )
    );
    return $row + parent::buildRow($entity);
  }

}
