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
 *     "list_builder" = "\Drupal\nica_entity\Entity\Listing\NicaEntityBaseListBuilder",
 *     "view_builder" = "\Drupal\Core\Entity\EntityViewBuilder",
 *     "views_data"   = "\Drupal\content_entity_base\Entity\Views\EntityBaseViewsData",
 *     "permission_provider" = "\Drupal\content_entity_base\Entity\Access\EntityPermissionProvider",
 *     "route_provider" = {
 *       "html" = "\Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *       "crud" = "\Drupal\content_entity_base\Entity\Routing\CrudUiRouteProvider",
 *       "revision" = "\Drupal\content_entity_base\Entity\Routing\RevisionHtmlRouteProvider"
 *     },
 *     "form" = {
 *       "add"             = "\Drupal\content_entity_base\Entity\Form\EntityBaseForm",
 *       "edit"            = "\Drupal\content_entity_base\Entity\Form\EntityBaseForm",
 *       "default"         = "\Drupal\content_entity_base\Entity\Form\EntityBaseForm",
 *       "delete"          = "\Drupal\Core\Entity\ContentEntityDeleteForm",
 *       "delete-multiple" = "\Drupal\entity\Routing\DeleteMultipleRouteProvider",
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
 *     "add-form"    = "/nica/add/{nica_entity_type}",
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
    $fields = parent::baseFieldDefinitions($entity_type);

    // There is no title or name for this custom entity.
    unset($fields['name']);

    // Make user reference configurable in view modes.
    $fields['uid']->setDisplayConfigurable('view', TRUE);

    // Expose the bundle in view modes.
    $fields['type']
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
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
        if ($entity_type->id() == 'profile') {
          $label = $this->get('field_first_name')->value . ' ' . $this->get('field_last_name')->value;
        }
        else {
          $label = $entity_type->label() . ' ' . $this->id();
        }
      }
    }

    return $label;
  }

}
