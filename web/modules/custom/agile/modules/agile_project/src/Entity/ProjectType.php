<?php

namespace Drupal\agile_project\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Project type entity.
 *
 * @ConfigEntityType(
 *   id = "project_type",
 *   label = @Translation("Project type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\agile_project\ProjectTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\agile_project\Form\ProjectTypeForm",
 *       "edit" = "Drupal\agile_project\Form\ProjectTypeForm",
 *       "delete" = "Drupal\agile_project\Form\ProjectTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\agile_project\ProjectTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "project_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "project",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/project_type/{project_type}",
 *     "add-form" = "/admin/structure/project_type/add",
 *     "edit-form" = "/admin/structure/project_type/{project_type}/edit",
 *     "delete-form" = "/admin/structure/project_type/{project_type}/delete",
 *     "collection" = "/admin/structure/project_type"
 *   }
 * )
 */
class ProjectType extends ConfigEntityBundleBase implements ProjectTypeInterface {

  /**
   * The Project type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Project type label.
   *
   * @var string
   */
  protected $label;

}
