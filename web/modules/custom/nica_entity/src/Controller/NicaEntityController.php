<?php

namespace Drupal\nica_entity\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\nica_entity\Entity\NicaEntityInterface;

/**
 * Class NicaEntityController.
 *
 *  Returns responses for Nica entity routes.
 *
 * @package Drupal\nica_entity\Controller
 */
class NicaEntityController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Nica entity  revision.
   *
   * @param int $nica_entity_revision
   *   The Nica entity  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($nica_entity_revision) {
    $nica_entity = $this->entityManager()->getStorage('nica_entity')->loadRevision($nica_entity_revision);
    $view_builder = $this->entityManager()->getViewBuilder('nica_entity');

    return $view_builder->view($nica_entity);
  }

  /**
   * Page title callback for a Nica entity  revision.
   *
   * @param int $nica_entity_revision
   *   The Nica entity  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($nica_entity_revision) {
    $nica_entity = $this->entityManager()->getStorage('nica_entity')->loadRevision($nica_entity_revision);
    return $this->t('Revision of %title from %date', ['%title' => $nica_entity->label(), '%date' => format_date($nica_entity->getRevisionCreationTime())]);
  }

  /**
   * Generates an overview table of older revisions of a Nica entity .
   *
   * @param \Drupal\nica_entity\Entity\NicaEntityInterface $nica_entity
   *   A Nica entity  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(NicaEntityInterface $nica_entity) {
    $account = $this->currentUser();
    $langcode = $nica_entity->language()->getId();
    $langname = $nica_entity->language()->getName();
    $languages = $nica_entity->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $nica_entity_storage = $this->entityManager()->getStorage('nica_entity');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $nica_entity->label()]) : $this->t('Revisions for %title', ['%title' => $nica_entity->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all nica entity revisions") || $account->hasPermission('administer nica entity entities')));
    $delete_permission = (($account->hasPermission("delete all nica entity revisions") || $account->hasPermission('administer nica entity entities')));

    $rows = [];

    $vids = $nica_entity_storage->revisionIds($nica_entity);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\nica_entity\NicaEntityInterface $revision */
      $revision = $nica_entity_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $nica_entity->getRevisionId()) {
          $link = $this->l($date, new Url('entity.nica_entity.revision', ['nica_entity' => $nica_entity->id(), 'nica_entity_revision' => $vid]));
        }
        else {
          $link = $nica_entity->link($date);
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
              Url::fromRoute('entity.nica_entity.translation_revert', ['nica_entity' => $nica_entity->id(), 'nica_entity_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.nica_entity.revision_revert', ['nica_entity' => $nica_entity->id(), 'nica_entity_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.nica_entity.revision_delete', ['nica_entity' => $nica_entity->id(), 'nica_entity_revision' => $vid]),
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

    $build['nica_entity_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
