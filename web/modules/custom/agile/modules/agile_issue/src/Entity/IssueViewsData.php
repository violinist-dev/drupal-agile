<?php

namespace Drupal\agile_issue\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Issue entities.
 */
class IssueViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.

    return $data;
  }

}
