<?php

namespace Drupal\search_beverage\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Render\RendererInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Defines base controller for rendering AJAX content.
 */
abstract class AjaxRenderBaseController extends ControllerBase {

  /**
   * The renderer service.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *   The  renderer service.
   */
  public function __construct(RendererInterface $renderer) {
    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('renderer')
    );
  }

  /**
   * Get response object.
   *
   * @param array $build
   *   Render array.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *   HTTP response object with rendered content.
   */
  protected function response(array $build): Response {
    $response = new Response();
    $response->setContent($this->renderer->renderRoot($build));
    return $response;
  }

}
