<?php

namespace Tests\Chaman\EventListener;

use PHPUnit\Framework\TestCase;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

use Chaman\EventListener\OwaspHeaderListener;

// php vendor/bin/phpunit tests/EventListener/OwaspHeaderListenerTest.php
class OwaspHeaderListenerTest extends TestCase
{
    /**
     * @var null|EventDispatcher
     */
    private ?EventDispatcher $dispatcher;

    /**
     * @var null|HttpKernelInterface
     */
    private ?HttpKernelInterface $kernel;

    public function testHeadersAreInserted()
    {
        $response = new Response();
        $event = new ResponseEvent(
            $this->kernel,
            new Request(),
            // note this is a master request:
            HttpKernelInterface::MAIN_REQUEST,
            $response
        );

        $this->dispatcher->dispatch($event, KernelEvents::RESPONSE);
        $headers = $event->getResponse()->headers;

        $this->assertInstanceOf(ResponseHeaderBag::class, $headers);
        $this->assertTrue($headers->has('X-Content-Type-Option'));
        $this->assertTrue($headers->has('Content-Type'));
        $this->assertSame($headers->get('X-Content-Type-Option'), 'nosniff');
    }

    public function testHeadersAreNotInserted()
    {
        $response = new Response();
        $event = new ResponseEvent(
            $this->kernel,
            new Request(),
            // note this is a "sub" request:
            HttpKernelInterface::SUB_REQUEST,
            $response
        );

        $this->dispatcher->dispatch($event, KernelEvents::RESPONSE);
        $headers = $event->getResponse()->headers;
        $this->assertInstanceOf(ResponseHeaderBag::class, $headers);

        $this->assertIsArray($headers->all());
        $this->assertFalse($headers->has('X-Content-Type-Option'));
        $this->assertFalse($headers->has('Content-Type'));
    }

    protected function setUp(): void
    {
        $addedHeaders = [
            'X-Content-Type-Option' => 'nosniff',
            'Content-Type' => 'text/html; charset=utf-8',
        ];
        // the System Under Test:
        $listener = new OwaspHeaderListener($addedHeaders);

        $this->dispatcher = new EventDispatcher();
        $this->dispatcher->addListener(KernelEvents::RESPONSE, [$listener, 'onKernelResponse']);
        $this->kernel = $this->createMock(HttpKernelInterface::class);
    }

    protected function tearDown(): void
    {
        $this->dispatcher = null;
        $this->kernel = null;
    }

}
