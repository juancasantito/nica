<?php

namespace Drupal\nica_theme\Plugin\Preprocess;

use Drupal\bootstrap\Plugin\Preprocess\PreprocessBase;
use Drupal\bootstrap\Plugin\Preprocess\PreprocessInterface;
use Drupal\bootstrap\Utility\Variables;
use Drupal\Component\Datetime\DateTimePlus;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\datetime\Plugin\views\filter\Date;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Datetime\DateFormatterInterface;

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

	/** @var \Drupal\Core\Datetime\DateFormatterInterface  */
	protected $dateFormatter;

	/**
	 * {@inheritdoc}
	 */
	public function __construct(array $configuration, $pluginId, $pluginDefinition, ConfigFactoryInterface $configFactory, DateFormatterInterface $dateFormatter) {
		parent::__construct($configuration, $pluginId, $pluginDefinition);

		$this->configFactory = $configFactory;
		$this->dateFormatter = $dateFormatter;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function create(ContainerInterface $container, array $configuration, $pluginId, $pluginDefinition) {
		return new static(
			$configuration,
			$pluginId,
			$pluginDefinition,
			$container->get('config.factory'),
			$container->get('date.formatter'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function preprocessVariables(Variables $variables) {
		$nicaConfig = $this->configFactory->get('nica_custom.settings');
		$variables->community = $nicaConfig->get('nica_community_project');
    $variables->municipality = $nicaConfig->get('nica_municipality_project');
		$variables->departament = $nicaConfig->get('nica_departament_project');
		$variables->organization = $nicaConfig->get('nica_name_executor');
		$variables->date =  $this->dateFormatter->format(REQUEST_TIME, 'nica_cv');
	}
}
