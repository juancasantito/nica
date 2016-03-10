<?php

/**
 * @file
 * Contains \Drupal\views_ui\Tests\FilterNumericWebTest.
 */

namespace Drupal\views_ui\Tests;

use Drupal\config\Tests\SchemaCheckTestTrait;

/**
 * Tests the numeric filter UI.
 *
 * @group views_ui
 * @see \Drupal\views\Plugin\views\filter\NumericFilter
 */
class FilterNumericWebTest extends UITestBase {
  use SchemaCheckTestTrait;

  /**
   * Views used by this test.
   *
   * @var array
   */
  public static $testViews = array('test_view');

  /**
   * Tests the filter numeric UI.
   */
  public function testFilterNumericUI() {
    $this->drupalPostForm('admin/structure/views/nojs/add-handler/test_view/default/filter', array('name[views_test_data.age]' => TRUE), t('Add and configure @handler', array('@handler' => t('filter criteria'))));

    $this->drupalPostForm(NULL, array(), t('Expose filter'));
    $this->drupalPostForm(NULL, array(), t('Grouped filters'));

    $edit = array();
    $edit['options[group_info][group_items][1][title]'] = 'Old';
    $edit['options[group_info][group_items][1][operator]'] = '>';
    $edit['options[group_info][group_items][1][value][value]'] = 27;
    $edit['options[group_info][group_items][2][title]'] = 'Young';
    $edit['options[group_info][group_items][2][operator]'] = '<=';
    $edit['options[group_info][group_items][2][value][value]'] = 27;
    $edit['options[group_info][group_items][3][title]'] = 'From 26 to 28';
    $edit['options[group_info][group_items][3][operator]'] = 'between';
    $edit['options[group_info][group_items][3][value][min]'] = 26;
    $edit['options[group_info][group_items][3][value][max]'] = 28;

    $this->drupalPostForm(NULL, $edit, t('Apply'));

    $this->drupalGet('admin/structure/views/nojs/handler/test_view/default/filter/age');
    foreach ($edit as $name => $value) {
      $this->assertFieldByName($name, $value);
    }

    $this->drupalPostForm('admin/structure/views/view/test_view', array(), t('Save'));
    $this->assertConfigSchemaByName('views.view.test_view');

    // Test that the exposed filter works as expected.
    $this->drupalPostForm(NULL, array(), t('Update preview'));
    $this->assertText('John');
    $this->assertText('Paul');
    $this->assertText('Ringo');
    $this->assertText('George');
    $this->assertText('Meredith');
    $this->drupalPostForm(NULL, array('age' => '2'), t('Update preview'));
    $this->assertText('John');
    $this->assertText('Paul');
    $this->assertNoText('Ringo');
    $this->assertText('George');
    $this->assertNoText('Meredith');

    // Change the filter to a single filter to test the schema when the operator
    // is not exposed.
    $this->drupalPostForm('admin/structure/views/nojs/handler/test_view/default/filter/age', array(), t('Single filter'));
    $edit = array();
    $edit['options[value][value]'] = 25;
    $this->drupalPostForm(NULL, $edit, t('Apply'));
    $this->drupalPostForm('admin/structure/views/view/test_view', array(), t('Save'));
    $this->assertConfigSchemaByName('views.view.test_view');

    // Test that the filter works as expected.
    $this->drupalPostForm(NULL, array(), t('Update preview'));
    $this->assertText('John');
    $this->assertNoText('Paul');
    $this->assertNoText('Ringo');
    $this->assertNoText('George');
    $this->assertNoText('Meredith');
    $this->drupalPostForm(NULL, array('age' => '26'), t('Update preview'));
    $this->assertNoText('John');
    $this->assertText('Paul');
    $this->assertNoText('Ringo');
    $this->assertNoText('George');
    $this->assertNoText('Meredith');

    // Change the filter to a 'between' filter to test if the label and
    // description are set for the 'minimum' filter element.
    $this->drupalGet('admin/structure/views/nojs/handler/test_view/default/filter/age');
    $edit = array();
    $edit['options[expose][label]'] = 'Age between';
    $edit['options[expose][description]'] = 'Description of the exposed filter';
    $edit['options[operator]'] = 'between';
    $edit['options[value][min]'] = 26;
    $edit['options[value][max]'] = 28;
    $this->drupalPostForm(NULL, $edit, t('Apply'));
    $this->drupalPostForm('admin/structure/views/view/test_view', array(), t('Save'));
    $this->assertConfigSchemaByName('views.view.test_view');

    $this->drupalPostForm(NULL, array(), t('Update preview'));
    // Check the max field label.
    $this->assertRaw('<label for="edit-age-max">And</label>', 'Max field label found');
    $this->assertRaw('<label for="edit-age-min">Age between</label>', 'Min field label found');
    // Check that the description is shown in the right place.
    $this->assertEqual(trim($this->cssSelect('.form-item-age-min .description')[0]), 'Description of the exposed filter');

    // Test if label and description are still visible if you have more than one
    // exposed filter form element (select and one input, select and two inputs,
    // or two inputs.
    $label = 'Age filter';
    $label_not_found = 'Label not found on other form item.';
    $description = 'Age filter description';
    $description_not_found = 'Description not found on (other) form item';

    // Prepare view with numeric filter.
    $this->drupalGet('admin/structure/views/nojs/handler/test_view/default/filter/age');

    // Test the selector and value, min and max fields have the correct label
    // and no description when viewed in the settings UI.
    $this->assertEqual(trim($this->cssSelect('#edit-options-operator--wrapper .fieldset-legend')[0]), 'Operator');
    // When the isNull and notIsNull options are available the total number of
    // options > 10, so they get rendered as a select.
    //$this->assertEqual(trim($this->cssSelect('.form-item-options-operator label')[0]), 'Operator');
    $this->assertEqual(count($this->cssSelect('.form-item-options-operator description')), 0, $description_not_found);
    $this->assertEqual(trim($this->cssSelect('.form-item-options-value-value label')[0]), 'Value');
    $this->assertEqual(count($this->cssSelect('.form-item-options-value-value description')), 0, $description_not_found);
    $this->assertEqual(trim($this->cssSelect('.form-item-options-value-min label')[0]), 'Min');
    $this->assertEqual(count($this->cssSelect('.form-item-options-value-min description')), 0, $description_not_found);
    $this->assertEqual(trim($this->cssSelect('.form-item-options-value-max label')[0]), 'And max');
    $this->assertEqual(count($this->cssSelect('.form-item-options-value-max description')), 0, $description_not_found);

    // First try two inputs, no select.
    $edit = array();
    $edit['options[expose][label]'] = $label;
    $edit['options[expose][description]'] = $description;
    $edit['options[value][min]'] = 26;
    $edit['options[value][max]'] = 28;
    $edit['options[operator]'] = 'between';
    $this->drupalPostForm(NULL, $edit, t('Apply'));
    $this->drupalPostForm('admin/structure/views/view/test_view', array(), t('Save'));
    $this->assertConfigSchemaByName('views.view.test_view');
    $this->drupalPostForm(NULL, array(), t('Update preview'));
    // The first form item should have the label and description: the min field.
    $this->assertEqual(trim($this->cssSelect('.form-item-age-min label')[0]), $label);
    $this->assertEqual(trim($this->cssSelect('.form-item-age-min .description')[0]), $description);
    // The other form item should have no label or description: the max field.
    $this->assertEqual(count($this->cssSelect('.form-item-age-max label')), 0, $label_not_found);
    $this->assertEqual(count($this->cssSelect('.form-item-age-max .description')), 0, $description_not_found);

    // Next try two inputs and one select.
    $this->drupalGet('admin/structure/views/nojs/handler/test_view/default/filter/age');
    $edit = array();
    $edit['options[expose][use_operator]'] = 1;
    $this->drupalPostForm(NULL, $edit, t('Apply'));
    $this->drupalPostForm('admin/structure/views/view/test_view', array(), t('Save'));
    $this->assertConfigSchemaByName('views.view.test_view');
    $this->drupalPostForm(NULL, array(), t('Update preview'));
    // The first form item should have the label and description: the select.
    $this->assertEqual(trim($this->cssSelect('.form-item-age-op label')[0]), $label);
    $this->assertEqual(trim($this->cssSelect('.form-item-age-op .description')[0]), $description);
    // The other form items should have no label or description: the min and max field.
    $this->assertEqual(count($this->cssSelect('.form-item-age-min label')), 0, $label_not_found);
    $this->assertEqual(count($this->cssSelect('.form-item-age-min .description')), 0, $description_not_found);
    $this->assertEqual(count($this->cssSelect('.form-item-age-max label')), 0, $label_not_found);
    $this->assertEqual(count($this->cssSelect('.form-item-age-max .description')), 0, $description_not_found);

    // Last try one inputs and one select.
    $this->drupalGet('admin/structure/views/nojs/handler/test_view/default/filter/age');
    $edit = array();
    $edit['options[operator]'] = '<';
    $this->drupalPostForm(NULL, $edit, t('Apply'));
    $this->drupalPostForm('admin/structure/views/view/test_view', array(), t('Save'));
    $this->assertConfigSchemaByName('views.view.test_view');
    $this->drupalPostForm(NULL, array(), t('Update preview'));
    // The first form item should have the label and description: the select.
    $this->assertEqual(trim($this->cssSelect('.form-item-age-op label')[0]), $label);
    $this->assertEqual(trim($this->cssSelect('.form-item-age-op .description')[0]), $description);
    // The other form item should have no label or description: the value field.
    $this->assertEqual(count($this->cssSelect('.form-item-age-value label')), 0, $label_not_found);
    $this->assertEqual(count($this->cssSelect('.form-item-age-value .description')), 0, $description_not_found);
  }
}
