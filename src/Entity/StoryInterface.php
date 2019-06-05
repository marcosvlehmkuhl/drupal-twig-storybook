<?php

namespace Drupal\twig_storybook\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Story entities.
 *
 * @ingroup twig_storybook
 */
interface StoryInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Story name.
   *
   * @return string
   *   Name of the Story.
   */
  public function getName();

  /**
   * Sets the Story name.
   *
   * @param string $name
   *   The Story name.
   *
   * @return \Drupal\twig_storybook\Entity\StoryInterface
   *   The called Story entity.
   */
  public function setName($name);

  /**
   * Gets the Story creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Story.
   */
  public function getCreatedTime();

  /**
   * Sets the Story creation timestamp.
   *
   * @param int $timestamp
   *   The Story creation timestamp.
   *
   * @return \Drupal\twig_storybook\Entity\StoryInterface
   *   The called Story entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Story published status indicator.
   *
   * Unpublished Story are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Story is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Story.
   *
   * @param bool $published
   *   TRUE to set this Story to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\twig_storybook\Entity\StoryInterface
   *   The called Story entity.
   */
  public function setPublished($published);

}
