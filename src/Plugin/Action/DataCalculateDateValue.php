<?php

/**
 * @file
 * Contains \Drupal\rules\Plugin\Action\DataCalculateDateValue.
 */

namespace Drupal\rules\Plugin\Action;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\TypedData\Plugin\DataType\DurationIso8601;
use Drupal\rules\Core\RulesActionBase;

/**
 * Provides a 'date calculation' action.
 *
 * @Action(
 *   id = "rules_data_calculate_date_value",
 *   label = @Translation("Calculates a date value"),
 *   category = @Translation("Data"),
 *   context = {
 *     "input_1" = @ContextDefinition("datetime_iso8601",
 *       label = @Translation("Input value 1"),
 *       description = @Translation("The first input value for the calculation.")
 *     ),
 *     "operator" = @ContextDefinition("string",
 *       label = @Translation("Operator"),
 *       description = @Translation("The calculation operator.")
 *     ),
 *     "input_2" = @ContextDefinition("duration_iso8601",
 *       label = @Translation("Input value 2"),
 *       description = @Translation("The second input value for the calculation.")
 *     )
 *   },
 *  provides = {
 *     "result" = @ContextDefinition("datetime_iso8601",
 *       label = @Translation("Calculated result")
 *     )
 *   }
 * )
 *
 * @todo: Add access callback information from Drupal 7.
 * @todo: Add defined operation options from Drupal 7.
 */
class DataCalculateDateValue extends RulesActionBase {

  /**
   * {@inheritdoc}
   */
  public function summary() {
    return $this->t('Calculate a date value');
  }

  /**
   * {@inheritdoc}
   */
  public function execute() {
    $datetime = $this->getContextValue('input_1');
    $op = $this->getContextValue('operator');
    $duration = $this->getContextValue('input_2');

    switch ($op) {
      case '+':
        $result = $this->applyOffset($datetime, $duration, TRUE);
        break;

      case '-':
        $result = $this->applyOffset($datetime, $duration, FALSE);
        break;

      case 'min':
        $result = min($datetime, $duration);
        break;

      case 'max':
        $result = max($datetime, $duration);
        break;
    }

    if (isset($result)) {
      $this->setProvidedValue('result', $result);
    }
  }

  protected function applyOffset($datetime, $duration, $positive) {
    // @todo: Watch for performance penalty of DrupalDateTime.
    $datetime = \DateTime::createFromFormat(\DateTime::ISO8601, $datetime);
    $interval = new \DateInterval($duration);
    if ($positive) {
      $datetime->add($interval);
    } else {
      $datetime->sub($interval);
    }
    return $datetime->format(\DateTime::ISO8601);
  }

}
