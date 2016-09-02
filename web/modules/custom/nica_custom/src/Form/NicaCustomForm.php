<?php

/**
 * @file
 * Contains \Drupal\nica_custom\Form\NicaCustomForm.
 */

namespace Drupal\nica_custom\Form;


use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
class NicaCustomForm extends ConfigFormBase{

	public function getFormId() {
		return 'nica_custom_settings';
	}

	public function buildForm(array $form, FormStateInterface $form_state) {
		$nica_config = $this->config('nica_custom.settings');

		$form['nica_cv']['city_project']  = array(
			'#type' => 'textfield',
			'#title' => $this->t('City Project'),
			'#placeholder' => $this->t('City Project'),
			'#default_value' => $nica_config->get('nica_city_project'),
		);
		$form['nica_cv']['departament_project']  = array(
			'#type' => 'textfield',
			'#title' => $this->t('Departament Project'),
			'#placeholder' => $this->t('Departament Project'),
			'#default_value' => $nica_config->get('nica_departament_project'),
		);
		$form['nica_cv']['name_execute'] = array(
			'#type' => 'textfield',
			'#title' => $this->t('Name Organization Excecutor'),
			'#placeholder' => $this->t('Name Organization Excecutor'),
			'#default_value' => $nica_config->get('nica_name_executor'),
		);
		return parent::buildForm($form, $form_state);
	}

	public function submitForm(array &$form, FormStateInterface $form_state) {
		$this->config('nica_custom.settings')
			->set('nica_city_project', $form_state->getValue('city_project'))
			->set('nica_departament_project', $form_state->getValue('departament_project'))
			->set('nica_name_executor', $form_state->getValue('name_execute'))
			->save();
		parent::submitForm($form, $form_state);
	}

	protected function getEditableConfigNames() {
		return ['nica_custom.settings'];
	}
}