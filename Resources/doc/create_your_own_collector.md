# Create your own collector

A collector is executed in 2 steps :

* On symfony `kernel.response` event, it collects metrics
* On symfony `kernel.terminate` event, it persists metrics

To create your own collector, you will need to extend the 
[AbstractController](../../Monitoring/Collector/AbstractCollector.php) class.

Each collector needs to implement 3 methos :

```php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract public function collect(Request $request, Response $response);

abstract public function getCollectorName(): string;

abstract public function getCollectorDescription(): string;
```

* `collect` reads information from the request and response objects to collect or compute the metric it is in charge of
* `getCollectorName` returns the name of the metric (without the namespace prefix)
* `getCollectorDescription` returns the description of the metric (help text in Prometheus response)

The default type of this collector is a gauge.

To change the type, you need to override the `save` method :

```php
use Prometheus\RegistryInterface;

public function save(RegistryInterface $collector, array $standardLabels)
{
    $gauge = $collector->getOrRegisterGauge(
        $this->namespace,
        $this->getCollectorName(),
        $this->getCollectorDescription(),
        array_keys($standardLabels)
    );
    $gauge->set($this->getData(), array_values($standardLabels));
}
```

In this method :

* `$collector` is the [PromPHP Prometheus client](https://github.com/PromPHP/prometheus_client_php/blob/master/src/Prometheus/CollectorRegistry.php)
* `$standardLabels` is a hashmap of the labels provided by this bundle and their values (per default app and route)
* `$this->getData()` should return the value collected in the previous `collect` method

To use other metric types, please refer to [PromPHP Prometheus client documentation](https://github.com/PromPHP/prometheus_client_php/)
