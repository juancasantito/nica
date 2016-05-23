<?php

/**
 * @file
 * Contains \Drupal\nica_entity\Entity\NicaEntityViewBuilder.
 */

namespace Drupal\nica_entity\Entity;

use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityViewBuilder;
use Drupal\nica_entity\Entity\NicaEntityContent;

/**
 * Render controller for nica entities.
 */
class NicaEntityViewBuilder extends EntityViewBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildComponents(array &$build, array $entities, array $displays, $view_mode) {
    /** @var \Drupal\content_entity_base\Entity\EntityBaseInterface[] $entities */
    if (empty($entities)) {
      return;
    }

    parent::buildComponents($build, $entities, $displays, $view_mode);

    foreach ($entities as $id => $entity) {
      $bundle = $entity->bundle();
      $display = $displays[$bundle];

      if ($display->getComponent('links')) {
        $build[$id]['links'] = array(
          '#lazy_builder' => [get_called_class() . '::renderLinks', [
            $entity->id(),
            $view_mode,
            $entity->language()->getId(),
          ]],
        );
      }
    }
  }

  /**
   * #lazy_builder callback; builds a nica entities's links.
   *
   * @param string $entity_id
   *   The entity ID.
   * @param string $view_mode
   *   The view mode in which the entity is being viewed.
   * @param string $langcode
   *   The language in which the entity is being viewed.
   *
   * @return array
   *   A renderable array representing the node links.
   */
  public static function renderLinks($entity_id, $view_mode, $langcode) {
    $links = array(
      '#theme' => 'links',
      '#pre_render' => array('drupal_pre_render_links'),
      '#attributes' => array('class' => array('links', 'inline')),
    );

    $entity = NicaEntityContent::load($entity_id)->getTranslation($langcode);
    $links['entity'] = static::buildLinks($entity, $view_mode);

    // Allow other modules to alter the entity links.
    $hook_context = array(
      'view_mode' => $view_mode,
      'langcode' => $langcode,
    );
    \Drupal::moduleHandler()->alter('nica_entity_links', $links, $entity, $hook_context);

    return $links;
  }

  /**
   * Build the default links (Read more) for a node.
   *
   * @param \Drupal\nica_entity\Entity\NicaEntityContent
   *   The entity object.
   * @param string $view_mode
   *   A view mode identifier.
   *
   * @return array
   *   An array that can be processed by drupal_pre_render_links().
   */
  protected static function buildLinks(NicaEntityContent $entity, $view_mode) {
    $links = array();

    // Always display a read more link on teasers because we have no way
    // to know when a teaser view is different than a full view.
    if ($view_mode == 'teaser') {
      $title_stripped = strip_tags($entity->label());
      $links['nica-entity-readmore'] = array(
        'title' => t('Read more<span class="visually-hidden"> about @title</span>', array(
          '@title' => $title_stripped,
        )),
        'url' => $entity->urlInfo(),
        'language' => $entity->language(),
        'attributes' => array(
          'rel' => 'tag',
          'title' => $title_stripped,
        ),
      );
    }

    return array(
      '#theme' => 'links',
      '#links' => $links,
      '#attributes' => array('class' => array('links', 'inline')),
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function alterBuild(array &$build, EntityInterface $entity, EntityViewDisplayInterface $display, $view_mode) {
    /** @var \Drupal\content_entity_base\Entity\EntityBaseInterface $entity */
    parent::alterBuild($build, $entity, $display, $view_mode);
    if ($entity->id()) {
      $build['#contextual_links']['nica_entity'] = array(
        'route_parameters' =>array('nica_entity' => $entity->id()),
        'metadata' => array('changed' => $entity->getChangedTime()),
      );
    }
  }

}
