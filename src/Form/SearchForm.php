<?php

namespace Drupal\search_beverage\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\FormStateInterface;
use Drupal\search_beverage\Ajax\SearchFormSubmitAjaxCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Url;

/**
 * Defines the search beverage form.
 */
class SearchForm extends FormBase {

  /**
   * Seach form config name.
   *
   * @var string
   */
  const CONFIG_NAME = 'search_beverage.settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'search_beverage_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['search_form'] = [
      '#type' => 'container',
    ];
    $form['search_form']['search_term'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Search term'),
      '#description' => $this->t('Here you can search our fine beverages.'),
    ];
    $form['search_form']['actions']['#type'] = 'actions';
    $form['search_form']['actions']['submit'] = [
      '#type' => 'submit',
      '#button_type' => 'primary',
      '#value' => $this->t('Search'),
      '#ajax' => ['callback' => [$this, 'searchCallback']],
    ];
    $form['results'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['search-results-container'],
      ],
    ];

    $this->appendClientSettings($form);

    return $form;
  }

  /**
   * Form submit AJAX callback.
   *
   * @param array $form
   *   Form build array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Form state.
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   *   AJAX response.
   */
  public function searchCallback(array &$form, FormStateInterface $form_state): AjaxResponse {
    $response = new AjaxResponse();

    if (!$form_state->hasAnyErrors()) {
      $response->addCommand(new SearchFormSubmitAjaxCommand([
        'search_term' => $form_state->getValue('search_term'),
      ]));
    }
    else {
      $content = [
        '#theme' => 'item_list',
        '#title' => $this->t('Search form has the following errors'),
      ];

      $errors = $form_state->getErrors();
      foreach ($errors as $message) {
        $content['#items'][] = $message;
      }
      $response->addCommand(new HtmlCommand('.search-results-container', $content));
    }

    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $search_term = $form_state->getValue('search_term');
    if (empty($search_term)) {
      $form_state->setError($form['search_form'], $this->t('Field "Search term" cannot be empty'));
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * Append client libarary settings to the form.
   *
   * @param array $form
   *   Form build array.
   */
  protected function appendClientSettings(array &$form): void {
    $config = $this->configFactory()->get(static::CONFIG_NAME);
    $endpoint = $config->get('search_api_endpoint');
    $items_per_page = $config->get('items_per_page');
    $search_results_path = Url::fromRoute('search_beverage.results')->toString();

    $settings = [
      'endpoint' => $endpoint,
      'items_per_page' => $items_per_page,
      'search_results_path' => $search_results_path,
    ];

    $form['#attached']['drupalSettings']['search_beverage'] = $settings;
    $form['#attached']['library'][] = 'search_beverage/search-form';
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {}

}
