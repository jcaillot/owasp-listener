<?php

declare(strict_types=1);

namespace Chaman\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class OwaspHeaderListener
{
    /**
     * @var non-empty-array<string, string>
     */
    protected array $addedHeaders;

    /**
     * @param non-empty-array<string, string> $addedHeaders
     */
    public function __construct(array $addedHeaders)
    {
        $this->addedHeaders = $addedHeaders;
    }

    /**
     * @param ResponseEvent $event
     */
    public function onKernelResponse(ResponseEvent $event)
    {
        if (!$event->isMainRequest()) {
            return;
        }

        /** @var Request $request */
        $request = $event->getRequest();
        if ($request->isXmlHttpRequest()) {
            return;
        }

        /** @var ResponseHeaderBag $responseHeaders */
        $responseHeaders = $event->getResponse()->headers;

        foreach ($this->addedHeaders as $key => $value) {
            $responseHeaders->set($key, $value, true);
        }
    }
}
