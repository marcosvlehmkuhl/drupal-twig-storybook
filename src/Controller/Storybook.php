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
      $story->$fieldName->generateSampleItems($max);
    }

    foreach ($component['data'] as $key => $data) {
      if (in_array($data['value'], array_keys($fields))) {
        $component['data'][$key] = $story->get($data['value'])->view('default');
      }
    };

    return $component;
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
