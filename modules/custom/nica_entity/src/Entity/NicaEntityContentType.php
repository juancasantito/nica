<?php

/**
 * @file
 *   Contains Drupal\nica_entity\Entity\NicaEntityContentType.
 */

namespace Drupal\nica_entity\Entity;

use Drupal\content_entity_base\Entity\EntityTypeBase;
use Drupal\entity\Entity\EntityDescriptionInterface;

/**
 * Defines the nica_entity type configuration entity.
 *
 * @ConfigEntityType(
 *   id               = "nica_entity_type",
 *   label            = @Translation("Nica content type"),
 *   admin_permission = "administer nica_entity",
 *   config_prefix    = "content_type",
 *   bundle_of        = "nica_entity",
 *   handlers = {
 *     "form" = {
 *       "default" = "Drupal\content_entity_base\Entity\Form\EntityTypeBaseForm",
 *       "add"     = "Drupal\content_entity_base\Entity\Form\EntityTypeBaseForm",
 *       "edit"    = "Drupal\content_entity_base\Entity\Form\EntityTypeBaseForm",
 *       "delete"  = "Drupal\content_entity_base\Entity\Form\EntityTypeBaseDeleteForm",
 *     },
 *     "list_builder" = "Drupal\content_entity_base\Entity\Listing\EntityTypeBaseListBuilder",
 *     "route_provider" = {
 *       "html" = "\Drupal\Core\Entity\Routing\DefaultHtmlRouteProvider",
 *       "crud" = "\Drupal\content_entity_base\Entity\Routing\CrudUiRouteProvider",
 *     },
 *   },
 *   entity_keys = {
 *     "id"           = "id",
 *     "label"        = "label",
 *   },
 *   links = {
 *     "collection"   = "/admin/structure/nica_entity",
 *     "add-form"     = "/admin/structure/nica_entity/manage/add",
 *     "edit-form"    = "/admin/structure/nica_entity/manage/{nica_entity_type}",
 *     "delete-form"  = "/admin/structure/nica_entity/manage/{nica_entity_type}/delete",
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "revision",
 *     "description",
 *   }
 * )
 */
class NicaEntityContentType extends EntityTypeBase implements EntityDescriptionInterface {
  /**
   * {@inheritdoc}
   */
  public function setDescription($description) {
    $this->description = $description;
    return $this;
  }
}
