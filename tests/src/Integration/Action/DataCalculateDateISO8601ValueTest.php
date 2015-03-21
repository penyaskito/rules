<?php

/**
 * @file
 * Contains \Drupal\rules\Tests\Action\DataCalculateDateISO8601ValueTest.
 */

namespace Drupal\Tests\rules\Integration\Action;

use Drupal\Tests\rules\Integration\RulesIntegrationTestBase;

/**
 * @coversDefaultClass \Drupal\rules\Plugin\Action\DataCalculateDateISO8601Value
 * @group rules_actions
 */
class DataCalculateDateISO8601ValueTest extends RulesIntegrationTestBase {

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

    $this->action = $this->actionManager->createInstance('rules_data_calculate_date_iso8601_value');

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
    $input_1 = '2014-01-01T20:00:00+0000';
    $input_2 = 'P1Y';
    $this->action->setContextValue('datetime', $this->getTypedData('datetime_iso8601', $input_1))
      ->setContextValue('operator', $this->getTypedData('string', '+'))
      ->setContextValue('duration', $this->getTypedData('duration_iso8601', $input_2));
    $this->action->execute();
    $result = $this->action->getProvided('result')->getContextValue();
    $this->assertEquals('2015-01-01T20:00:00+0000', $result, "Addition calculation correct");
  }

  /**
   * Tests the subtraction of one numeric value from another.
   *
   * @covers ::execute
   */
  public function testSubtractionAction() {
    $input_1 = '2014-01-01T20:00:00+0000';
    $input_2 = 'P1Y';
    $this->action->setContextValue('datetime', $this->getTypedData('datetime_iso8601', $input_1))
      ->setContextValue('operator', $this->getTypedData('string', '-'))
      ->setContextValue('duration', $this->getTypedData('duration_iso8601', $input_2));
    $this->action->execute();
    $result = $this->action->getProvided('result')->getContextValue();
    $this->assertEquals('2013-01-01T20:00:00+0000', $result, "Subtraction calculation correct");
  }

}
