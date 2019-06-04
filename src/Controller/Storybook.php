<?php

namespace Drupal\twig_storybook\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Controller to render stories
 */
class Storybook extends ControllerBase {

  public function build() {
    return [
      '#theme' => 'storybook_template',
      '#variables' => []
    ];
  }
}
