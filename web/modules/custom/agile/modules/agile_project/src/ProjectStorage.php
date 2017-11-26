<?php

namespace Drupal\agile_project;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\agile_project\Entity\ProjectInterface;

/**
 * Defines the storage handler class for Project entities.
 *
 * This extends the base storage class, adding required special handling for
 * Project entities.
 *
 * @ingroup agile_project
 */
class ProjectStorage extends SqlContentEntityStorage implements ProjectStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(ProjectInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {project_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {project_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(ProjectInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {project_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('project_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
