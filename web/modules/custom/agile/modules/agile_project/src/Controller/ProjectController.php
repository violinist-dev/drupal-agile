<?php

namespace Drupal\agile_project\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\agile_project\Entity\ProjectInterface;

/**
 * Class ProjectController.
 *
 *  Returns responses for Project routes.
 */
class ProjectController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Project  revision.
   *
   * @param int $project_revision
   *   The Project  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($project_revision) {
    $project = $this->entityManager()->getStorage('project')->loadRevision($project_revision);
    $view_builder = $this->entityManager()->getViewBuilder('project');

    return $view_builder->view($project);
  }

  /**
   * Page title callback for a Project  revision.
   *
   * @param int $project_revision
   *   The Project  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($project_revision) {
    $project = $this->entityManager()->getStorage('project')->loadRevision($project_revision);
    return $this->t('Revision of %title from %date', ['%title' => $project->label(), '%date' => format_date($project->getRevisionCreationTime())]);
  }

  /**
   * Generates an overview table of older revisions of a Project .
   *
   * @param \Drupal\agile_project\Entity\ProjectInterface $project
   *   A Project  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(ProjectInterface $project) {
    $account = $this->currentUser();
    $langcode = $project->language()->getId();
    $langname = $project->language()->getName();
    $languages = $project->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $project_storage = $this->entityManager()->getStorage('project');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $project->label()]) : $this->t('Revisions for %title', ['%title' => $project->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all project revisions") || $account->hasPermission('administer project entities')));
    $delete_permission = (($account->hasPermission("delete all project revisions") || $account->hasPermission('administer project entities')));

    $rows = [];

    $vids = $project_storage->revisionIds($project);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\agile_project\ProjectInterface $revision */
      $revision = $project_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $project->getRevisionId()) {
          $link = $this->l($date, new Url('entity.project.revision', ['project' => $project->id(), 'project_revision' => $vid]));
        }
        else {
          $link = $project->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => \Drupal::service('renderer')->renderPlain($username),
              'message' => ['#markup' => $revision->getRevisionLogMessage(), '#allowed_tags' => Xss::getHtmlTagList()],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.project.translation_revert', ['project' => $project->id(), 'project_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.project.revision_revert', ['project' => $project->id(), 'project_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.project.revision_delete', ['project' => $project->id(), 'project_revision' => $vid]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['project_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
