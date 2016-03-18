<?php

/**
 * @file
 * Contains \Drupal\views\Plugin\views\filter\NumericFilter.
 */

namespace Drupal\views\Plugin\views\filter;

use Drupal\Core\Form\FormStateInterface;

/**
 * Simple filter to handle greater than/less than filters
 *
 * @ingroup views_filter_handlers
 *
 * @ViewsFilter("numeric")
 */
class NumericFilter extends FilterPluginBase {

  protected $alwaysMultiple = TRUE;

  protected function defineOptions() {
    $options = parent::defineOptions();

    $options['value'] = array(
      'contains' => array(
        'min' => array('default' => ''),
        'max' => array('default' => ''),
        'value' => array('default' => ''),
      ),
    );

    return $options;
  }

  function operators() {
    $operators = array(
      '<' => array(
        'title' => $this->t('Is less than'),
        'method' => 'opSimple',
        'short' => $this->t('<'),
        'values' => 1,
      ),
      '<=' => array(
        'title' => $this->t('Is less than or equal to'),
        'method' => 'opSimple',
        'short' => $this->t('<='),
        'values' => 1,
      ),
      '=' => array(
        'title' => $this->t('Is equal to'),
        'method' => 'opSimple',
        'short' => $this->t('='),
        'values' => 1,
      ),
      '!=' => array(
        'title' => $this->t('Is not equal to'),
        'method' => 'opSimple',
        'short' => $this->t('!='),
        'values' => 1,
      ),
      '>=' => array(
        'title' => $this->t('Is greater than or equal to'),
        'method' => 'opSimple',
        'short' => $this->t('>='),
        'values' => 1,
      ),
      '>' => array(
        'title' => $this->t('Is greater than'),
        'method' => 'opSimple',
        'short' => $this->t('>'),
        'values' => 1,
      ),
      'between' => array(
        'title' => $this->t('Is between'),
        'method' => 'opBetween',
        'short' => $this->t('between'),
        'values' => 2,
      ),
      'not between' => array(
        'title' => $this->t('Is not between'),
        'method' => 'opBetween',
        'short' => $this->t('not between'),
        'values' => 2,
      ),
      'regular_expression' => array(
        'title' => $this->t('Regular expression'),
        'short' => $this->t('regex'),
        'method' => 'op_regex',
        'values' => 1,
      ),
    );

    // if the definition allows for the empty operator, add it.
    if (!empty($this->definition['allow empty'])) {
      $operators += array(
        'empty' => array(
          'title' => $this->t('Is empty (NULL)'),
          'method' => 'opEmpty',
          'short' => $this->t('empty'),
          'values' => 0,
        ),
        'not empty' => array(
          'title' => $this->t('Is not empty (NOT NULL)'),
          'method' => 'opEmpty',
          'short' => $this->t('not empty'),
          'values' => 0,
        ),
      );
    }

    return $operators;
  }

  /**
   * Provide a list of all the numeric operators
   */
  public function operatorOptions($which = 'title') {
    $options = array();
    foreach ($this->operators() as $id => $info) {
      $options[$id] = $info[$which];
    }

    return $options;
  }

  protected function operatorValues($values = 1) {
    $options = array();
    foreach ($this->operators() as $id => $info) {
      if ($info['values'] == $values) {
        $options[] = $id;
      }
    }

    return $options;
  }
  /**
   * Provide a simple textfield for equality
   */
  protected function valueForm(&$form, FormStateInterface $form_state) {
    $form['value']['#tree'] = TRUE;

    // Set a filter wrapper.
    $exposed = !empty($this->options['expose']);
    $wrapped = ($exposed && in_array($this->operator, $this->operatorValues(2))) || !empty($this->options['expose']['use_operator']);
    if ($wrapped) {
      $form['value']['#type'] = 'fieldset';
      if (!empty($this->exposedInfo()['label'])) {
        $form['value']['#title'] = $this->exposedInfo()['label'];
      }
      if (!empty($this->exposedInfo()['description'])) {
        $form['value']['#description'] = $this->exposedInfo()['description'];
      }
      if ($exposed) {
        $form['value']['operator'] = $form[$this->options['expose']['operator_id']];
        unset($form[$this->options['expose']['operator_id']]);
      }
    }

    // We have to make some choices when creating this as an exposed
    // filter form. For example, if the operator is locked and thus
    // not rendered, we can't render dependencies; instead we only
    // render the form items we need.
    $which = 'all';
    if ($exposed) {
      $identifier = $this->options['expose']['identifier'];

      if (empty($this->options['expose']['use_operator']) || empty($this->options['expose']['operator_id'])) {
        // exposed and locked.
        $which = in_array($this->operator, $this->operatorValues(2)) ? 'minmax' : 'value';
      }
      else {
        $source = ':input[name="' . $this->options['expose']['identifier'] . '[operator]"]';
      }
    }
    // Need to adjust the states source for the filter admin form.
    if (!empty($form['operator'])) {
      $source = ':input[name="options[operator]"]';
    }

    $user_input = $form_state->getUserInput();
    if ($which == 'all') {
      $value['value'] = array(
        '#type' => 'textfield',
        '#title' => !$exposed ? $this->t('Value') : '',
        '#size' => 30,
        '#default_value' => $this->value['value'],
      );
      // Setup #states for all operators with one value.
      foreach ($this->operatorValues(1) as $operator) {
        $value['value']['#states']['visible'][] = array(
          $source => array('value' => $operator),
        );
      }
      if ($exposed && !isset($user_input[$identifier]['value'])) {
        $user_input[$identifier]['value'] = $this->value['value'];
        $form_state->setUserInput($user_input);
      }
    }
    elseif ($which == 'value') {
      // When exposed we drop the value-value and just do value if
      // the operator is locked.
      $value = array(
        '#type' => 'textfield',
        '#title' => !$exposed ? $this->t('Value') : '',
        '#size' => 30,
        '#default_value' => $this->value['value'],
      );
      if ($exposed && !isset($user_input[$identifier])) {
        $user_input[$identifier] = $this->value['value'];
        $form_state->setUserInput($user_input);
      }
    }

    if ($which == 'all' || $which == 'minmax') {
      $value['min'] = array(
        '#type' => 'textfield',
        '#title' => $exposed ? $this->t('Min') : '',
        '#size' => 30,
        '#default_value' => $this->value['min'],
      );
      $value['max'] = array(
        '#type' => 'textfield',
        '#title' => $exposed ? $this->t('And max') : $this->t('And'),
        '#size' => 30,
        '#default_value' => $this->value['max'],
      );
      if ($which == 'all') {
        $states = array();
        // Setup #states for all operators with two values.
        foreach ($this->operatorValues(2) as $operator) {
          $states['#states']['visible'][] = array(
            $source => array('value' => $operator),
          );
        }
        $value['min'] += $states;
        $value['max'] += $states;
      }

      if ($exposed && !isset($user_input[$identifier]['min'])) {
        $user_input[$identifier]['min'] = $this->value['min'];
      }
      if ($exposed && !isset($user_input[$identifier]['max'])) {
        $user_input[$identifier]['max'] = $this->value['max'];
      }

      if (!isset($value)) {
        // Ensure there is something in the 'value'.
        $value = array(
          '#type' => 'value',
          '#value' => NULL
        );
      }
    }
    $form['value'] += $value;
  }

  public function query() {
    $this->ensureMyTable();
    $field = "$this->tableAlias.$this->realField";

    $info = $this->operators();
    if (!empty($info[$this->operator]['method'])) {
      $this->{$info[$this->operator]['method']}($field);
    }
  }

  protected function opBetween($field) {
    if ($this->operator == 'between') {
      $this->query->addWhere($this->options['group'], $field, array($this->value['min'], $this->value['max']), 'BETWEEN');
    }
    else {
      $this->query->addWhere($this->options['group'], db_or()->condition($field, $this->value['min'], '<=')->condition($field, $this->value['max'], '>='));
    }
  }

  protected function opSimple($field) {
    $this->query->addWhere($this->options['group'], $field, $this->value['value'], $this->operator);
  }

  protected function opEmpty($field) {
    if ($this->operator == 'empty') {
      $operator = "IS NULL";
    }
    else {
      $operator = "IS NOT NULL";
    }

    $this->query->addWhere($this->options['group'], $field, NULL, $operator);
  }

  /**
   * Filters by a regular expression.
   *
   * @param string $field
   *   The expression pointing to the queries field, for example "foo.bar".
   */
  protected function opRegex($field) {
    $this->query->addWhere($this->options['group'], $field, $this->value, 'REGEXP');
  }

  public function adminSummary() {
    if ($this->isAGroup()) {
      return $this->t('grouped');
    }
    if (!empty($this->options['exposed'])) {
      return $this->t('exposed');
    }

    $options = $this->operatorOptions('short');
    $output = $options[$this->operator];
    if (in_array($this->operator, $this->operatorValues(2))) {
      $output .= ' ' . $this->t('@min and @max', array('@min' => $this->value['min'], '@max' => $this->value['max']));
    }
    elseif (in_array($this->operator, $this->operatorValues(1))) {
      $output .= ' ' . $this->value['value'];
    }
    return $output;
  }

  /**
   * Do some minor translation of the exposed input
   */
  public function acceptExposedInput($input) {
    if (empty($this->options['exposed'])) {
      return TRUE;
    }

    // rewrite the input value so that it's in the correct format so that
    // the parent gets the right data.
    if (!empty($this->options['expose']['identifier'])) {
      $value = &$input[$this->options['expose']['identifier']];
      if (!is_array($value)) {
        $value = array(
          'value' => $value,
        );
      }
    }

    $rc = parent::acceptExposedInput($input);

    if (empty($this->options['expose']['required'])) {
      // We have to do some of our own checking for non-required filters.
      $info = $this->operators();
      if (!empty($info[$this->operator]['values'])) {
        switch ($info[$this->operator]['values']) {
          case 1:
            if ($value['value'] === '') {
              return FALSE;
            }
            break;
          case 2:
            if ($value['min'] === '' && $value['max'] === '') {
              return FALSE;
            }
            break;
        }
      }
    }

    return $rc;
  }

}
