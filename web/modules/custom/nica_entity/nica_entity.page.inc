<?php

/**
 * @file
 * Contains nica_entity.page.inc.
 *
 * Page callback for Nica entity entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Nica entity templates.
 *
 * Default template: nica_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_nica_entity(array &$variables) {
  // Fetch NicaEntity Entity Object.
  $nica_entity = $variables['elements']['#nica_entity'];
  $variables['nica_entity'] = $variables['elements']['#nica_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
