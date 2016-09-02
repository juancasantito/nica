<?php

namespace Drupal\nica_theme\Plugin\Preprocess;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\bootstrap\Plugin\Preprocess\PreprocessInterface;

/**
 * Pre-processes variables for the "constancy" theme hook.
 *
 * @ingroup plugins_preprocess
 *
 * @BootstrapPreprocess("constancy")
 */

class NicaConstancy extends PreprocessBase implements PreprocessInterface, ContainerInjectionInterface{

	/** @var \Drupal\Core\Config\ConfigFactoryInterface $configFactory*/
	protected $configFactory;

	public function __construct(ConfigFactoryInterface $configFactory) {
		$this->configFactory = $configFactory;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function create(ContainerInterface $container) {
		return new static($container->get('config.factory'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function preprocess(array &$variables, $hook, array $info) {
		$nicaConfig = $this->configFactory->get('nica_custom.settings');
	}
}
