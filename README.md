# VdmPrometheusBundle

[![Build Status](https://travis-ci.org/3slab/VdmPrometheusBundle.svg?branch=master)](https://travis-ci.org/3slab/VdmPrometheusBundle)

This bundle provides a metric endpoint to be pulled by Prometheus.

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
