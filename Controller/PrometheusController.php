<?php

namespace Vdm\Bundle\PrometheusBundle\Controller;

use Prometheus\CollectorRegistry;
use Prometheus\RenderTextFormat;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * PrometheusController
 */
class PrometheusController
{
    public const HEADER_SECRET = 'VDM-Prometheus-Secret';

    /**
     * @var string|null
     */
    protected $secret;

    /**
     * @var CollectorRegistry
     */
    protected $registry;

    /**
     * PrometheusController constructor.
     *
     * @param CollectorRegistry $registry
     * @param string|null $secret
     */
    public function __construct(CollectorRegistry $registry, ?string $secret)
    {
        $this->registry = $registry;
        $this->secret = $secret;
    }

    /**
     * metrics route
     *
     * @param Request $request
     * @return Response
     */
    public function metrics(Request $request): Response
    {
        $secret = $request->get('secret', null) ?? $request->headers->get(static::HEADER_SECRET, null) ?? null;

        $content = '';
        if (($this->secret === null) || ($secret === $this->secret)) {
            $renderer = new RenderTextFormat();
            $content = $renderer->render($this->registry->getMetricFamilySamples());
        }

        return new Response($content, 200, ['Content-Type' => 'text/plain']);
    }
}
