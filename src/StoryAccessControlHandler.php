<?php

namespace Drupal\twig_storybook;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Story entity.
 *
 * @see \Drupal\twig_storybook\Entity\Story.
 */
class StoryAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\twig_storybook\Entity\StoryInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished story entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published story entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit story entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete story entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add story entities');
  }

}
