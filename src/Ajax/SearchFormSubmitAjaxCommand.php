<?php

namespace Drupal\search_beverage\Ajax;

use Drupal\Core\Ajax\CommandInterface;

/**
 * Defines search form ajax command.
 *
 * This command is implemented in
 * Drupal.AjaxCommands.prototype.searchFormSubmit.
 */
class SearchFormSubmitAjaxCommand implements CommandInterface {

  /**
   * Ajax command options.
   *
   * @var array
   */
  protected $options;

  /**
   * Constructor.
   *
   * @param array $options
   *   Command options.
   */
  public function __construct(array $options) {
    $this->options = $options;
  }

  /**
   * {@inheritdoc}
   */
  public function render(): array {
    return [
      'command' => 'searchFormSubmit',
      'options' => $this->options,
    ];
  }

}
