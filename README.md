Viva IT - Settings Bundle
============

The aim of this bundle is to allow you to pass dynamic settings to services.
These settings can be currently be stored in Doctrine, Redis, or Symfony's config
itself. Single or multiple drivers can be specified for each setting and/or
service, which will search each driver until a setting has been found.

A controller has been provided to easily allow your users to configure settings
via a UI - although we recommend you customise this to match your own application.

Installation
------------
**Using composer**
``` bash
$ composer require viviat/settings-bundle
```

**Enabling bundle**
``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Vivait\SettingsBundle\VivaitSettingsBundle()
    );
}
```

**Add routing rules**
``` yml
# app/config/routing.yml
tpg_extjs:
  resource: "@VivaitSettingsBundle/Resources/config/routing.yml"
  prefix:   /settings
```

Usage
-----------
Settings are accessed via a driver. The simplest way to retrieve a setting is to
do it directly via the vivait_settings.registry class. This will then check all
of the drivers available until it can find the setting:

```
  $this->get('vivait_settings.registry').get('configname');
```

You can also check for settings via a driver collection. A driver collection is
just a stack of drivers, and can be created via the vivait_settings.registry class.

```
  $this->get('vivait_settings.registry').drivers('doctrine', 'yaml').get('configname');
```
In the example above, the settings registry would try each driver referenced in
the driver collection and stop when it found the appropriate setting.

You can also specify a default value in the 'get' method which will be returned
should the settings driver fail to find the appropriate setting:

```
  $this->get('vivait_settings.registry').get('configname', 'default value');
```

**Passing settings directly to services**
Most likely, you're going to want to pass your settings directly to your services.
You can do this in your services config file using expressions:

```
services:
    my_service:
        class:        "Me\MyBundle\Services\MyService"
        arguments:    [ "@=service('vivait_settings.registry').get('myservice.configname')" ]
```

Notice in the example above how we've used a '.' to categorise a setting. The
reason for this will become apparent when create our settings form, but groups
are optional and can be nested.

Testing Run
-----------
Unit Test are written with PHPSpec.
