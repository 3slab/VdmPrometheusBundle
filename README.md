# VdmPrometheusBundle

[![Build Status](https://travis-ci.com/3slab/VdmPrometheusBundle.svg?branch=master)](https://travis-ci.com/3slab/VdmPrometheusBundle)

This bundle provides a metric endpoint to be pulled by Prometheus. It uses the [prometheus client from PromPHP](https://github.com/PromPHP/prometheus_client_php/)

It collects the following metrics :

* Memory in byte per route
* Number of call to the API per response code
* Response size in bytes
* Request execution time in seconds

Response example :

```text
# HELP php_info Information about the PHP environment.
# TYPE php_info gauge
php_info{version="7.3.11-0ubuntu0.19.10.6"} 1
# HELP vdm_sf_app_call_total Number of call to the app
# TYPE vdm_sf_app_call_total counter
vdm_sf_app_call_total{app="app",route=""} 1
vdm_sf_app_call_total{app="app",route="error_route"} 1
vdm_sf_app_call_total{app="app",route="success_route"} 2
# HELP vdm_sf_app_memory_usage Memory in byte per route
# TYPE vdm_sf_app_memory_usage gauge
vdm_sf_app_memory_usage{app="app",route=""} 25165824
vdm_sf_app_memory_usage{app="app",route="error_route"} 25165824
vdm_sf_app_memory_usage{app="app",route="success_route"} 25165824
# HELP vdm_sf_app_response_code_total Number of call to the API per response code
# TYPE vdm_sf_app_response_code_total counter
vdm_sf_app_response_code_total{app="app",route="",http_code="404"} 1
vdm_sf_app_response_code_total{app="app",route="error_route",http_code="500"} 1
vdm_sf_app_response_code_total{app="app",route="success_route",http_code="200"} 2
# HELP vdm_sf_app_response_size Response size in bytes
# TYPE vdm_sf_app_response_size gauge
vdm_sf_app_response_size{app="app",route=""} 305784
vdm_sf_app_response_size{app="app",route="error_route"} 5
vdm_sf_app_response_size{app="app",route="success_route"} 7
# HELP vdm_sf_app_response_time Request execution time in seconds
# TYPE vdm_sf_app_response_time gauge
vdm_sf_app_response_time{app="app",route=""} 81.976890563965
vdm_sf_app_response_time{app="app",route="error_route"} 0.10800361633301
vdm_sf_app_response_time{app="app",route="success_route"} 0.09608268737793
```

## Installation

```shell script
composer require 3slab/vdm-prometheus-bundle
```

And load the routes in `routing.yml` :

```yaml
vdm_prometheus:
  resource: "@VdmPrometheusBundle/Resources/config/routing.yml"
  prefix:   /
```

## Configuration

Put your configuration in `config/packages/vdm_prometheus.yaml` file. This is the default :

```yaml
vdm_prometheus:
  app: app
  namespace: vdm
  register_default_metrics: true
  secret: ~
  metrics_path: /metrics
  storage:
    type: default
```

Parameter | Default | Description
--- | --- | ---
`vdm_prometheus.app` | `app` | set the value of the app tag on all metrics
`vdm_prometheus.namespace` | `vdm` | prefix all the metrics' label
`vdm_prometheus.register_default_metrics` | `true` | app PromPHP Prometheus Client default metric
`vdm_prometheus.secret` | `null` | if set, you need to provide the secret as a GET parameter `secret` or in the 
header `VDM-Prometheus-Secret` to get the detailed result of the metrics in the response body.
`vdm_prometheus.metrics_path` | `/metrics` | Change the path of the metric endpoint.
`vdm_prometheus.storage` | see below | Configure the storage to store metrics between requests

## Metrics storage

To persist metrics between requests, you have to store them in persistent storage.
 
The following storage are supported :

* **In Memory** *(the default)*

```
vdm_prometheus:
  storage:
    type: memory
```

* **APCu**

```
vdm_prometheus:
  storage:
    type: apcu
```

You need to have the php module `ext-apc` installed.

* **Redis**

```
vdm_prometheus:
  storage:
    type: redis
    settings:
      host: '127.0.0.1'
      port: 6379
      timeout: 0.1
      read_timeout: 10
      persistent_connections: false
      password: ~
```

You need to have the php module `ext-redis` installed.

* **Custom**

```
vdm_prometheus:
  storage:
    type: custom
    service: my_service_id
```

With custom storage, you need to provide a Symfony service which implements the 
[PromPHP Storage Adapter Interface](https://github.com/PromPHP/prometheus_client_php/blob/master/src/Prometheus/Storage/Adapter.php)

## Custom collectors

You can [create your own collector](./Resources/doc/create_your_own_collector.md) if you want to track other 
information.

## Grafana

This bundle provides a [grafana dashboard](./Resources/grafana) setup to work with default configuration for settings `vdm_prometheus.app` and `vdm_prometheus.namespace`.