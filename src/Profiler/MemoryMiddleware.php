<?php

declare(strict_types=1);

namespace Indgy\Profiler;

use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\{MiddlewareInterface, RequestHandlerInterface};


/**
 * This middleware adds a peak memory usage header to the Response
 *
 * @package Profiler
 * @author github.com/indgy
 */
class MemoryMiddleware implements MiddlewareInterface
{
    /**
     * Process the middleware
     *
     * @param ServerRequestInterface $request - The current request
     * @param RequestHandlerInterface $handler - The next handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        return $response->withAddedHeader('Peak-Memory', $this->processMemory(memory_get_peak_usage()));
    }
    /**
     * Returns a formatted string representing the memory usage
     *
     * @param String $size The value to form
     * @return String
     */
    private function processMemory($size) : String
    {
        $unit = array('B','KB','MB','GB','TB','PB');

        return @round($size/pow(1024,($i=floor(log($size,1024)))),2).$unit[$i];
    }
}
