<?php

namespace Drupal\agile_issue\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Issue type entity.
 *
 * @ConfigEntityType(
 *   id = "issue_type",
 *   label = @Translation("Issue type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\agile_issue\IssueTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\agile_issue\Form\IssueTypeForm",
 *       "edit" = "Drupal\agile_issue\Form\IssueTypeForm",
 *       "delete" = "Drupal\agile_issue\Form\IssueTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\agile_issue\IssueTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "issue_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "issue",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/issue_type/{issue_type}",
 *     "add-form" = "/admin/structure/issue_type/add",
 *     "edit-form" = "/admin/structure/issue_type/{issue_type}/edit",
 *     "delete-form" = "/admin/structure/issue_type/{issue_type}/delete",
 *     "collection" = "/admin/structure/issue_type"
 *   }
 * )
 */
class IssueType extends ConfigEntityBundleBase implements IssueTypeInterface {

  /**
   * The Issue type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Issue type label.
   *
   * @var string
   */
  protected $label;

}
