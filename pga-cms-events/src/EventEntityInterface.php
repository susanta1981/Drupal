<?php

namespace Drupal\events;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Event entity entities.
 *
 * @ingroup events
 */
interface EventEntityInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Event entity event_title.
   *
   * @return string
   *   Name of the Event entity.
   */
  public function getName();

  /**
   * Sets the Event entity event_title.
   *
   * @param string $event_title
   *   The Event entity event_title.
   *
   * @return \Drupal\events\EventEntityInterface
   *   The called Event entity entity.
   */
  public function setName($event_title);

  /**
   * Gets the Event entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Event entity.
   */
  public function getCreatedTime();

  /**
   * Sets the Event entity creation timestamp.
   *
   * @param int $timestamp
   *   The Event entity creation timestamp.
   *
   * @return \Drupal\events\EventEntityInterface
   *   The called Event entity entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Event entity published status indicator.
   *
   * Unpublished Event entity are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Event entity is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Event entity.
   *
   * @param bool $published
   *   TRUE to set this Event entity to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\events\EventEntityInterface
   *   The called Event entity entity.
   */
  public function setPublished($published);

}
