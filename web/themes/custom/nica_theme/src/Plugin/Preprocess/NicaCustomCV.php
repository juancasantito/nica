<?php

namespace Drupal\nica_theme\Plugin\Preprocess;

use Drupal\bootstrap\Plugin\Preprocess\PreprocessBase;
use Drupal\bootstrap\Plugin\Preprocess\PreprocessInterface;
use Drupal\bootstrap\Utility\Variables;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\datetime\Plugin\views\filter\Date;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Pre-processes variables for the "nica_custom_cv" theme hook.
 *
 * @ingroup plugins_preprocess
 *
 * @BootstrapPreprocess("nica_custom_cv")
 */

class NicaCustomCV extends PreprocessBase implements PreprocessInterface, ContainerFactoryPluginInterface{

	/** @var \Drupal\Core\Config\ConfigFactoryInterface $configFactory*/
	protected $configFactory;

	/**
	 * {@inheritdoc}
	 */
	public function __construct(array $configuration, $pluginId, $pluginDefinition, ConfigFactoryInterface $configFactory) {
		parent::__construct($configuration, $pluginId, $pluginDefinition);

		$this->configFactory = $configFactory;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function create(ContainerInterface $container, array $configuration, $pluginId, $pluginDefinition) {
		return new static($configuration, $pluginId, $pluginDefinition,$container->get('config.factory'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function preprocessVariables(Variables $variables) {
		$nicaConfig = $this->configFactory->get('nica_custom.settings');
		$variables->city = $nicaConfig->get('nica_city_project');
		$variables->organization = $nicaConfig->get('nica_name_executor');
		$variables->date = '2 de septiembre 2016';
	}
}
