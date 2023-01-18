<?php

declare(strict_types=1);

namespace Indgy\Profiler;

use Psr\Http\Message\{ResponseInterface, ServerRequestInterface};
use Psr\Http\Server\{MiddlewareInterface, RequestHandlerInterface};


/**
 * This middleware adds the tracked timers to the Response
 *
 * @package Profiler
 * @author github.com/indgy
 */
class TimingMiddleware implements MiddlewareInterface
{
    /**
     * @var Timer - The timer instance
     */
    private $timer;


    /**
     * @param Timer $timer
     */
    public function __construct()
    {
        $this->timer = new Timer;
    }
    /**
     * Process the middleware
     *
     * @param ServerRequestInterface $request - The current request
     * @param RequestHandlerInterface $handler - The next handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request->withAttribute('timer', $this->timer));

        return $response->withAddedHeader('Server-Timing', $this->getHeaderValue());
    }
    /**
     * Returns the Server-Timing header value
     *
     * @return String
     */
    private function getHeaderValue() : String
    {
        $parts = [];

        foreach($this->timer->getTimers() as $name=>$timer)
        {
            $out = (null === $timer['stop'])
                ? sprintf('%s;dur=NS', $name)
                : sprintf('%s;dur=%.1f', $name, ($timer['stop'] - $timer['start']) * 1000);

            $out.= (null === $timer['desc']) 
                ? sprintf(';desc="%s"', addslashes($timer['desc']))
                : null;

            $parts[] = $out;
        }

        return implode(', ', $parts);
    }
}
