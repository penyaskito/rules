<?php

/**
 * @file
 * Contains \Drupal\rules\Plugin\Action\DataCalculateDateISO8601Value.
 */

namespace Drupal\rules\Plugin\Action;

use Drupal\rules\Core\RulesActionBase;

/**
 * Provides a 'date calculation' action for ISO8601 dates.
 *
 * @Action(
 *   id = "rules_data_calculate_date_iso8601_value",
 *   label = @Translation("Calculates a date value"),
 *   category = @Translation("Data"),
 *   context = {
 *     "datetime" = @ContextDefinition("datetime_iso8601",
 *       label = @Translation("Input value 1"),
 *       description = @Translation("The datetime value for the calculation.")
 *     ),
 *     "operator" = @ContextDefinition("string",
 *       label = @Translation("Operator"),
 *       description = @Translation("The calculation operator.")
 *     ),
 *     "duration" = @ContextDefinition("duration_iso8601",
 *       label = @Translation("Input value 2"),
 *       description = @Translation("The duration value for the calculation.")
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
class DataCalculateDateISO8601Value extends RulesActionBase {

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
    $datetime = $this->getContextValue('datetime');
    $op = $this->getContextValue('operator');
    $duration = $this->getContextValue('duration');

    switch ($op) {
      case '+':
        $result = $this->applyOffset($datetime, $duration, TRUE);
        break;

      case '-':
        $result = $this->applyOffset($datetime, $duration, FALSE);
        break;
    }

    if (isset($result)) {
      $this->setProvidedValue('result', $result);
    }
  }

  protected function applyOffset($datetime, $duration, $positive) {
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
