<?php

namespace Drupal\twig_storybook\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\Yaml\Yaml;

/**
 * Controller to render stories
 */
class Storybook extends ControllerBase {
  public function build() {
    $themeManager = \Drupal::service('theme.manager');
    $activeTheme = $themeManager->getActiveTheme();
    $activeThemePath = $activeTheme->getPath();
    $activeThemeName = $activeTheme->getName();
    $fileName = $activeThemeName . '.stories.yml';
    $filePath = DRUPAL_ROOT . '/' . $activeThemePath . '/' . $fileName;

    $file_contents = file_get_contents($filePath);
    $components = Yaml::parse($file_contents);

    return [
      '#theme' => 'storybook_template',
      '#variables' => [
        'activeTheme' => $activeThemeName,
        'components' => $components
      ]
    ];
  }
}
