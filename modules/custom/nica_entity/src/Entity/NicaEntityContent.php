<?php

/**
 * @file
 *   Contains Drupal\nica_entity_entity\Entity\NicaEntityContent.
 */

namespace Drupal\nica_entity\Entity;

use Drupal\content_entity_base\Entity\EntityBase;
use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Defines a custom entity class.
 *
 * @ContentEntityType(
 *   id                      = "nica_entity",
 *   label                   = @Translation("Nica content"),
 *   bundle_label            = @Translation("Nica content type"),
 *   base_table              = "nica_entity",
 *   revision_table          = "nica_entity_revision",
 *   data_table              = "nica_entity_field_data",
 *   revision_data_table     = "nica_entity_field_revision",
 *   translatable            = TRUE,
 *   admin_permission        = "administer nica_entity",
 *   bundle_entity_type      = "nica_entity_type",
 *   field_ui_base_route     = "entity.nica_entity_type.edit_form",
 *   common_reference_target = TRUE,
 *   permission_granularity  = "bundle",
 *   render_cache            = TRUE,
 *   handlers = {
 *     "storage"      = "\Drupal\content_entity_base\Entity\Storage\ContentEntityBaseStorage",
 *     "access"       = "\Drupal\content_entity_base\Entity\Access\EntityBaseAccessControlHandler",
 *     "translation"  = "\Drupal\content_translation\ContentTranslationHandler",
 *     "list_builder" = "\Drupal\content_entity_base\Entity\Listing\EntityBaseListBuilder",
 *     "view_builder" = "\Drupal\Core\Entity\EntityViewBuilder",
 *     "views_data"   = "\Drupal\content_entity_base\Entity\Views\EntityBaseViewsData",
 *     "route_provider" = {
 *       "html" = "\Drupal\nica_entity\Entity\Routing\HtmlRouteProvider",
 *       "crud" = "\Drupal\content_entity_base\Entity\Routing\CrudUiRouteProvider",
 *       "revision" = "\Drupal\nica_entity\Entity\Routing\RevisionHtmlRouteProvider"
 *     },
 *     "form" = {
 *       "add"        = "\Drupal\content_entity_base\Entity\Form\EntityBaseForm",
 *       "edit"       = "\Drupal\content_entity_base\Entity\Form\EntityBaseForm",
 *       "default"    = "\Drupal\content_entity_base\Entity\Form\EntityBaseForm",
 *       "delete"     = "\Drupal\Core\Entity\ContentEntityDeleteForm",
 *     },
 *   },
 *   entity_keys = {
 *     "id"           = "id",
 *     "bundle"       = "type",
 *     "langcode"     = "langcode",
 *     "uuid"         = "uuid",
 *     "revision"     = "revision_id",
 *   },
 *   links = {
 *     "collection"   = "/admin/content/nica",
 *     "canonical"    = "/nica/{nica_entity}",
 *     "add-page"    = "/nica/add",
 *     "add-form"    = "/nica/add/{type}",
 *     "delete-form"  = "/nica/{nica_entity}/delete",
 *     "edit-form"    = "/nica/{nica_entity}/edit",
 *     "version-history" = "/nica/{nica_entity}/revisions",
 *     "revision" = "/nica/{nica_entity}/revisions/{nica_entity_revision}/view",
 *     "revision-revert" = "/nica/{nica_entity}/revisions/{nica_entity_revision}/revert",
 *     "revision-delete" = "/nica/{nica_entity}/revisions/{nica_entity_revision}/delete",
 *   },
 * )
 */
class NicaEntityContent extends EntityBase {

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $definitions = parent::baseFieldDefinitions($entity_type);

    // There is no title or name for this custom entity.
    unset($definitions['name']);

    return $definitions;
  }


  /**
   * {@inheritdoc}
   *
   * Insert a dynamic label fabricated from bundle and ID.
   */
  public function label() {
    $label = '';

    $class = $this->entityTypeManager()->getDefinition($this->getEntityType()->getBundleEntityType())->getClass();
    if (is_callable([$class, 'load'])) {
      $entity_type = $class::load($this->bundle());
      if ($entity_type) {
        $label = $entity_type->label() . ' ';
      }
    }
    $label .= $this->id();

    return $label;
  }

}
