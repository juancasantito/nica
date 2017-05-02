<?php

namespace Drupal\nica_entity\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Nica entity type entity.
 *
 * @ConfigEntityType(
 *   id = "nica_entity_type",
 *   label = @Translation("Nica entity type"),
 *   handlers = {
 *     "list_builder" = "Drupal\nica_entity\NicaEntityTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\nica_entity\Form\NicaEntityTypeForm",
 *       "edit" = "Drupal\nica_entity\Form\NicaEntityTypeForm",
 *       "delete" = "Drupal\nica_entity\Form\NicaEntityTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\nica_entity\NicaEntityTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "nica_entity_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "nica_entity",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/nica_entity_type/{nica_entity_type}",
 *     "add-form" = "/admin/structure/nica_entity_type/add",
 *     "edit-form" = "/admin/structure/nica_entity_type/{nica_entity_type}/edit",
 *     "delete-form" = "/admin/structure/nica_entity_type/{nica_entity_type}/delete",
 *     "collection" = "/admin/structure/nica_entity_type"
 *   }
 * )
 */
class NicaEntityType extends ConfigEntityBundleBase implements NicaEntityTypeInterface {

  /**
   * The Nica entity type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Nica entity type label.
   *
   * @var string
   */
  protected $label;

}
