<?php

namespace Vdm\Bundle\PrometheusBundle\Tests\Fixtures\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class AppController
{
    /**
     * @return Response
     */
    public function success(): Response
    {
        return new Response('success');
    }

    /**
     * @return Response
     */
    public function error(): Response
    {
        return new Response('error', 500);
    }
}
