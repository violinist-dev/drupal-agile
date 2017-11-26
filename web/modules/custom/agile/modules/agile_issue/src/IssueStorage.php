<?php

namespace Drupal\agile_issue;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\agile_issue\Entity\IssueInterface;

/**
 * Defines the storage handler class for Issue entities.
 *
 * This extends the base storage class, adding required special handling for
 * Issue entities.
 *
 * @ingroup agile_issue
 */
class IssueStorage extends SqlContentEntityStorage implements IssueStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(IssueInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {issue_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {issue_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(IssueInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {issue_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('issue_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
