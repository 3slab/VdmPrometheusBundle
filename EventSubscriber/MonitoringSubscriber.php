<?php

namespace Vdm\Bundle\PrometheusBundle\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Vdm\Bundle\PrometheusBundle\Monitoring\CollectorRegistry;

/**
 * Class MonitoringSubscriber
 *
 * Subscribe on kernel events to collect and save monitoring metrics
 *
 * @package Suez\Bundle\PrometheusMonitoringBundle\EventSubscriber
 */
class MonitoringSubscriber implements EventSubscriberInterface
{
    /**
     * The registry of data collectors in the API
     *
     * @var CollectorRegistry
     */
    protected $dataRegistry;

    /**
     * The logger
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * MonitoringSubscriber constructor.
     *
     * @param CollectorRegistry $dataRegistry
     * @param LoggerInterface $vdmLogger
     */
    public function __construct(CollectorRegistry $dataRegistry, LoggerInterface $vdmLogger)
    {
        $this->dataRegistry = $dataRegistry;
        $this->logger = $vdmLogger;
    }

    /**
     * Handles the onKernelResponse event.
     *
     * Collect and save the metrics on this event
     *
     * @param ResponseEvent $event FilterResponseEvent
     */
    public function onKernelResponse(ResponseEvent $event)
    {
        $master = $event->isMasterRequest();
        if (!$master) {
            return;
        }

        $request = $event->getRequest();
        $response = $event->getResponse();

        try {
            $this->dataRegistry->setCurrentRoute($request->get('_route'));
            $this->dataRegistry->collect($request, $response);
        } catch (\Exception $e) {
            $this->logger->error('Save prometheus metrics error', ['exception' => $e]);
            $this->logger->error((string) $e);
        }
    }

    /**
     * Handles the onKernelTerminate event.
     *
     * Save the collected metrics to the backend
     *
     * @param TerminateEvent $event
     */
    public function onKernelTerminate(TerminateEvent $event)
    {
        try {
            $this->dataRegistry->save();
        } catch (\Exception $e) {
            $this->logger->error('Save prometheus metrics error', ['exception' => $e]);
            $this->logger->error((string) $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::RESPONSE => array('onKernelResponse', -100),
            KernelEvents::TERMINATE => array('onKernelTerminate', -1024),
        );
    }
}
