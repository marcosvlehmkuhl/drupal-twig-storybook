<?php

/**
 * @file
 * Contains story.page.inc.
 *
 * Page callback for Story entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Story templates.
 *
 * Default template: story.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_story(array &$variables) {
  // Fetch Story Entity Object.
  $story = $variables['elements']['#story'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
