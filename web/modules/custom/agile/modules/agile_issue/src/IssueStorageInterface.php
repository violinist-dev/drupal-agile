<?php

namespace Drupal\agile_issue;

use Drupal\Core\Entity\ContentEntityStorageInterface;
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
interface IssueStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Issue revision IDs for a specific Issue.
   *
   * @param \Drupal\agile_issue\Entity\IssueInterface $entity
   *   The Issue entity.
   *
   * @return int[]
   *   Issue revision IDs (in ascending order).
   */
  public function revisionIds(IssueInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Issue author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Issue revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\agile_issue\Entity\IssueInterface $entity
   *   The Issue entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(IssueInterface $entity);

  /**
   * Unsets the language for all Issue with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
