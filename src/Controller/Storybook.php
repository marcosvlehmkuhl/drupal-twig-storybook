<?php

namespace Drupal\twig_storybook\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\Yaml\Yaml;
use Drupal\Core\Field\FieldStorageDefinitionInterface;

/**
 * Controller to render stories.
 */
class Storybook extends ControllerBase {

  /**
   * Generate content for each story.
   */
  protected function generateContent($component) {
    $moduleHanlder = \Drupal::service('module_handler');
    $entityTypeManager = \Drupal::service('entity_type.manager');
    $fieldTypePluginManager = \Drupal::service('plugin.manager.field.field_type');

    $entity_type = $entityTypeManager->getStorage('story');

    foreach($fieldTypePluginManager->getUiDefinitions() as $definition) {
      $field_storage_values = [
        'field_name' => "field_story_${definition['id']}",
        'entity_type' => 'story',
        'type' => $definition['id'],
      ];

      $field_values = [
        'field_name' => "field_story_${definition['id']}",
        'entity_type' => 'story',
        'bundle' => 'story',
        'label' => $definition['id'],
      ];

      // @TODO instead of deleting make a continue on future
      $field = $entityTypeManager->getStorage('field_storage_config')->load("story.field_story_${definition['id']}");
      // if (!empty($field)) $field->delete();
      if (!empty($field)) {
        continue;
      };

      try {
        $entityTypeManager->getStorage('field_storage_config')->create($field_storage_values)->save();
        $field = $entityTypeManager->getStorage('field_config')->create($field_values);
        $field->save();
      }
      catch (\Exception $e) {
        dump($e);
      }
    }


    $storyManager = \Drupal::service('entity_type.manager')
      ->getStorage('story');
    $fieldManager = \Drupal::service('entity_field.manager');

    $fields = $fieldManager->getFieldStorageDefinitions('story');
    $story = $storyManager->create([]);

    foreach ($fields as $field) {
      $max = $cardinality = $field->getCardinality();
      if ($cardinality == FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED) {
        // Just an arbitrary number for 'unlimited'.
        $max = rand(1, 3);
      }
      $fieldName = $field->getName();
      if (!empty($story->fieldName)) $story->$fieldName->generateSampleItems($max);
    }

    foreach ($component['data'] as $key => $data) {
      $fieldName = "field_story_${data['value']}";
      dump($story->get($fieldName)->view());
      die();
      if (in_array($fieldName, array_keys($fields))) {
        $component['data'][$key] = $story->get($fieldName)->view('default');
      }
    };

    dump($component);
    die();

    // return $component;
  }

  /**
   * Builds the storybook page.
   */
  public function build() {
    $themeManager = \Drupal::service('theme.manager');
    $activeTheme = $themeManager->getActiveTheme();
    $activeThemePath = $activeTheme->getPath();
    $activeThemeName = $activeTheme->getName();
    $fileName = $activeThemeName . '.stories.yml';
    $filePath = DRUPAL_ROOT . '/' . $activeThemePath . '/' . $fileName;

    $file_contents = file_get_contents($filePath);
    $components = Yaml::parse($file_contents);

    foreach ($components as $name => $component) {
      $components[$name] = $this->generateContent($component);
    }

    return [
      '#theme' => 'storybook_template',
      '#variables' => [
        'activeTheme' => $activeThemeName,
        'components' => $components,
      ],
    ];
  }

}
