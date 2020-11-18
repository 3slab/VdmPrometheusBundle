<?php

namespace Vdm\Bundle\PrometheusBundle\Controller;

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
     * PrometheusController constructor.
     *
     * @param string|null $secret
     */
    public function __construct(?string $secret)
    {
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
        return new Response("", 204);
    }
}
