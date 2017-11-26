<?php

namespace Drupal\agile_issue\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\agile_issue\Entity\IssueInterface;

/**
 * Class IssueController.
 *
 *  Returns responses for Issue routes.
 */
class IssueController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Issue  revision.
   *
   * @param int $issue_revision
   *   The Issue  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($issue_revision) {
    $issue = $this->entityManager()->getStorage('issue')->loadRevision($issue_revision);
    $view_builder = $this->entityManager()->getViewBuilder('issue');

    return $view_builder->view($issue);
  }

  /**
   * Page title callback for a Issue  revision.
   *
   * @param int $issue_revision
   *   The Issue  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($issue_revision) {
    $issue = $this->entityManager()->getStorage('issue')->loadRevision($issue_revision);
    return $this->t('Revision of %title from %date', ['%title' => $issue->label(), '%date' => format_date($issue->getRevisionCreationTime())]);
  }

  /**
   * Generates an overview table of older revisions of a Issue .
   *
   * @param \Drupal\agile_issue\Entity\IssueInterface $issue
   *   A Issue  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(IssueInterface $issue) {
    $account = $this->currentUser();
    $langcode = $issue->language()->getId();
    $langname = $issue->language()->getName();
    $languages = $issue->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $issue_storage = $this->entityManager()->getStorage('issue');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $issue->label()]) : $this->t('Revisions for %title', ['%title' => $issue->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all issue revisions") || $account->hasPermission('administer issue entities')));
    $delete_permission = (($account->hasPermission("delete all issue revisions") || $account->hasPermission('administer issue entities')));

    $rows = [];

    $vids = $issue_storage->revisionIds($issue);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\agile_issue\IssueInterface $revision */
      $revision = $issue_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $issue->getRevisionId()) {
          $link = $this->l($date, new Url('entity.issue.revision', ['issue' => $issue->id(), 'issue_revision' => $vid]));
        }
        else {
          $link = $issue->link($date);
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
              Url::fromRoute('entity.issue.translation_revert', ['issue' => $issue->id(), 'issue_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.issue.revision_revert', ['issue' => $issue->id(), 'issue_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.issue.revision_delete', ['issue' => $issue->id(), 'issue_revision' => $vid]),
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

    $build['issue_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
