<?php

namespace Drupal\agile_issue\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Issue entities.
 *
 * @ingroup agile_issue
 */
interface IssueInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Issue name.
   *
   * @return string
   *   Name of the Issue.
   */
  public function getName();

  /**
   * Sets the Issue name.
   *
   * @param string $name
   *   The Issue name.
   *
   * @return \Drupal\agile_issue\Entity\IssueInterface
   *   The called Issue entity.
   */
  public function setName($name);

  /**
   * Gets the Issue creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Issue.
   */
  public function getCreatedTime();

  /**
   * Sets the Issue creation timestamp.
   *
   * @param int $timestamp
   *   The Issue creation timestamp.
   *
   * @return \Drupal\agile_issue\Entity\IssueInterface
   *   The called Issue entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Issue published status indicator.
   *
   * Unpublished Issue are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Issue is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Issue.
   *
   * @param bool $published
   *   TRUE to set this Issue to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\agile_issue\Entity\IssueInterface
   *   The called Issue entity.
   */
  public function setPublished($published);

  /**
   * Gets the Issue revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Issue revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\agile_issue\Entity\IssueInterface
   *   The called Issue entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Issue revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Issue revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\agile_issue\Entity\IssueInterface
   *   The called Issue entity.
   */
  public function setRevisionUserId($uid);

}
