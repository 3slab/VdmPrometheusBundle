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
     * @throws \Exception
     */
    public function error()
    {
        throw new \Exception('myexception');
    }
}
