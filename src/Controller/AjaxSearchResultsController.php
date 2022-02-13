<?php

namespace Drupal\search_beverage\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Defines controller for rendering content.
 */
class AjaxSearchResultsController extends AjaxRenderBaseController {

  /**
   * Return search results content.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *   HTTP response.
   */
  public function content(Request $request): Response {
    $build = [
      '#theme' => 'search_form_results',
      '#items' => $request->get('items', []),
      '#page' => $request->get('page'),
      '#items_per_page' => $request->get('items_per_page'),
    ];
    return $this->response($build);
  }

}
