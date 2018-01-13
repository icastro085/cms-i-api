<?php
namespace API;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class Middleware {
  /**
   * Middleware invokable class
   *
   * @param  \Psr\Http\Message\ServerRequestInterface $request
   * @param  \Psr\Http\Message\ResponseInterface $response
   * @param  callable $next
   *
   * @return \Psr\Http\Message\ResponseInterface
   */
  public function __invoke(
    ServerRequestInterface $request,
    ResponseInterface $response,
    callable $next
  ) {
    $response = $next($request, $response);
    return $response;
  }
}