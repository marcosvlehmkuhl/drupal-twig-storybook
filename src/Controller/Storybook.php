<?php

namespace Drupal\twig_storybook\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\Yaml\Yaml;
use Drupal\Component\Utility\Random;
use Drupal\Core\Field\FieldStorageDefinitionInterface;

/**
 * Controller to render stories
 */
class Storybook extends ControllerBase {

  protected function generateContent($component) {
    $entityManagerStorage = \Drupal::service('entity_type.manager')
      ->getStorage($component['type']);

    $entity = $entityManagerStorage->create([
      'nid' => NULL,
      'type' => $component['bundle'],

      // make it configurable
      'title' => Random::name(),
      'status' => TRUE,
    ]);

    $instances = entity_load_multiple_by_properties('field_config',[
      'entity_type' => $entity->getEntityType()->id(),
      'bundle' => $entity->bundle()
    ]);

    foreach ($instances as $instance) {
      $field_storage = $instance->getFieldStorageDefinition();
      $max = $cardinality = $field_storage->getCardinality();
      if ($cardinality == FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED) {
        // Just an arbitrary number for 'unlimited'
        $max = rand(1, 3);
      }
      $field_name = $field_storage->getName();
      $entity->$field_name->generateSampleItems($max);
    }

    foreach($component['data'] as $key => $value) {
      if (!$entity->hasField($value)) {
        throw new \Exception('Entity has no field');
      }
      $component['data'][$key] = $entity->get($value)->view();
    }

    return $component;
  }

  public function build() {
    $themeManager = \Drupal::service('theme.manager');
    $activeTheme = $themeManager->getActiveTheme();
    $activeThemePath = $activeTheme->getPath();
    $activeThemeName = $activeTheme->getName();
    $fileName = $activeThemeName . '.stories.yml';
    $filePath = DRUPAL_ROOT . '/' . $activeThemePath . '/' . $fileName;

    $file_contents = file_get_contents($filePath);
    $components = Yaml::parse($file_contents);

    foreach($components as $name => $component) {
      if(!empty($component['type'])) {
        $components[$name] = $this->generateContent($component);
      }
    }

    return [
      '#theme' => 'storybook_template',
      '#variables' => [
        'activeTheme' => $activeThemeName,
        'components' => $components
      ]
    ];
  }
}
