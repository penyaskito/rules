<?php

/**
 * @file
 * Contains \Drupal\rules\Tests\Action\DataCalculateDateValueTest.
 */

namespace Drupal\Tests\rules\Integration\Action;

use Drupal\Core\Language\Language;
use Drupal\Tests\rules\Integration\RulesIntegrationTestBase;

/**
 * @coversDefaultClass \Drupal\rules\Plugin\Action\DataCalculateDateValue
 * @group rules_actions
 */
class DataCalculateDateValueTest extends RulesIntegrationTestBase {

  /**
   * The mocked language manager.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface|\PHPUnit_Framework_MockObject_MockObject
   */
  protected $languageManager;

  /**
   * The action to be tested.
   *
   * @var \Drupal\rules\Core\RulesActionInterface
   */
  protected $action;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    $this->action = $this->actionManager->createInstance('rules_data_calculate_date_value');

  }

  /**
   * Tests the summary.
   *
   * @covers ::summary
   */
  public function testSummary() {
    $this->assertEquals('Calculate a date value', $this->action->summary());
  }

  /**
   * Tests the addition of two numeric values.
   *
   * @covers ::execute
   */
  public function testAdditionAction() {
    $input_1 = '2014-01-01T20:00:00+00:00';
    $input_2 = 'P1Y';
    $this->action->setContextValue('input_1', $this->getTypedData('datetime_iso8601', $input_1))
      ->setContextValue('operator', $this->getTypedData('string', '+'))
      ->setContextValue('input_2', $this->getTypedData('duration_iso8601', $input_2));
    $this->action->execute();
    $result = $this->action->getProvided('result')->getContextValue();
    $this->fail($result);
    $this->assertEquals('2015-01-01T20:00:00+00:00', $result, "Addition calculation correct");
  }

  /**
   * Tests the subtraction of one numeric value from another.
   *
   * @covers ::execute
   */
  public function testSubtractionAction() {
    $input_1 = '2014-01-01T20:00:00+00:00';
    $input_2 = 'P1Y';
    $this->action->setContextValue('input_1', $this->getTypedData('datetime_iso8601', $input_1))
      ->setContextValue('operator', $this->getTypedData('string', '-'))
      ->setContextValue('input_2', $this->getTypedData('duration_iso8601', $input_2));
    $this->action->execute();
    $result = $this->action->getProvided('result')->getContextValue();
    $this->fail($result);
    $this->assertEquals('2013-01-01T20:00:00+00:00', $result, "Subtraction calculation correct");
  }

  /**
   * Tests the use of php's min function for 2 input values
   *
   * @covers ::execute
   */
  public function testMinimumAction() {
    $input_1 = '2014-01-01T20:00:00+00:00';
    $input_2 = 'P1Y';
    $this->action->setContextValue('input_1', $this->getTypedData('datetime_iso8601', $input_1))
      ->setContextValue('operator', $this->getTypedData('string', 'min'))
      ->setContextValue('input_2', $this->getTypedData('duration_iso8601', $input_2));
    $this->action->execute();
    $result = $this->action->getProvided('result')->getContextValue();
    $this->assertEquals(min($input_1, $input_2), $result, "Min calculation correct");
  }

  /**
   * Tests the use of php's max function for 2 input values
   *
   * @covers ::execute
   */
  public function testMaximumAction() {
    $input_1 = '2014-01-01T20:00:00+00:00';
    $input_2 = 'P1Y';
    $this->action->setContextValue('input_1', $this->getTypedData('datetime_iso8601', $input_1))
      ->setContextValue('operator', $this->getTypedData('string', 'max'))
      ->setContextValue('input_2', $this->getTypedData('duration_iso8601', $input_2));
    $this->action->execute();
    $result = $this->action->getProvided('result')->getContextValue();
    $this->assertEquals(max($input_1, $input_2), $result, "Max calculation correct");
  }
}
