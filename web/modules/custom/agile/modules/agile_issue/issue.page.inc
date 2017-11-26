<?php

/**
 * @file
 * Contains issue.page.inc.
 *
 * Page callback for Issue entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Issue templates.
 *
 * Default template: issue.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_issue(array &$variables) {
  // Fetch Issue Entity Object.
  $issue = $variables['elements']['#issue'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
