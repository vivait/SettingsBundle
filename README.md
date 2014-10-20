Viva IT - Settings Bundle
============

The aim of this bundle is to allow you to retrieve and pass dynamic settings to  services and other classes used in Symfony. These settings can be currently be  stored in Doctrine, Redis, or Symfony's config itself. Single or multiple drivers can be specified for each setting and/or service, which will search each driver until a setting has been found.

A controller has been provided to easily allow your users to configure settings via a UI - although we recommend you customise this to match your own application.

Installation
------------
**Using composer**
``` bash
$ composer require vivait/settings-bundle
```

**Enabling bundle**

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Vivait\\SettingsBundle\\VivaitSettingsBundle()
    );
}
```

**Add routing rules (optional)**
``` yml
# app/config/routing.yml
vivait_settings:
  resource: "@VivaitSettingsBundle/Resources/config/routing.yml"
  prefix:   /settings
```

Usage
-----------
Settings are accessed via drivers. The bundle comes with several drivers to get you started, but you must define them in 
your `config.yml` file:

```yaml
vivait_settings:
    drivers:
        yaml: vivait_settings.driver.yaml
        doctrine: vivait_settings.driver.doctrine
```

The simplest way to retrieve a setting is to
do it directly via the ```vivait_settings.registry``` class. This will then check all
of the drivers available until it can find the setting:

```php
  $this->get('vivait_settings.registry')->get('settingname');
```

You can also check for settings via a driver collection. A driver collection is
just a stack of drivers, and can be created via the ```vivait_settings.registry``` class.

```php
  $this->get('vivait_settings.registry')->drivers(['doctrine', 'yaml'])->get('settingname');
```
In the example above, the settings registry would try each driver referenced in the driver collection and stop when it found the appropriate setting.

You can also specify a default value for if a setting value isn't found in any driver:

```
  $this->get('vivait_settings.registry')->get('settingname', 'default value');
```

###Passing settings directly to services
Most likely, you're going to want to pass your settings directly to your services in your services config file. You can do this via expressions:

```yaml
services:
    my_service:
        class:        "Me\\MyBundle\\Services\\MyService"
        arguments:    [ "@=service('vivait_settings.registry').get('myservice.settingname')" ]
```

You can still specify the drivers in the expression:
```yaml
services:
    my_service:
        class:        "Me\\MyBundle\\Services\\MyService"
        arguments:    [ "@=service('vivait_settings.registry').drivers(['yaml', 'doctrine']).get('myservice.settingname')" ]
```

__Notice in the examples above how we've used a '.' to categorise a setting. The
reason for this will become apparent when create our settings form, but groups
are optional and can be nested.__

Adding custom drivers
-----------
Adding custom drivers is easy, and is encouraged. For example, as part of our Auth Bundle, we allow per-user settings. This is provided via a custom driver.

All drivers must implement the ```\\Vivait\\SettingsBundle\\Driver\\ParametersStorageInterface``` interface. To register a driver add your driver to the service container:

```yaml
  me.mybundle.mydriver:
    class: Me\\MyBundle\\Driver\\MyDriver
```

Next, add the service id to your `config.yml` file as described above.

Providing a UI to customise settings
-----------
For maximum flexibility, a new form type should be created for each group of settings you'd like to configure. These can then be collated in to a single form if required, or provided on different pages.

The bundle provides a SettingsType which will handle the binding of the form submission to updating a driver's storage. An instance of SettingsType can only be binded to one driver, and in most cases this should be sufficient. The Yaml driver cannot be used with the SettingsType because it is not persistent, this driver is meant as a fallback driver. An example of how to build your form is below:

```php
$form         = new MyServiceType(); // Change this with your form type
$driver       = $this->get('vivait_settings.driver.doctrine');
$settingsType = new SettingsType($driver, $form);

$form = $this->createForm($settingsType);
```

In this example, the settings bundle will act as a middleman, binding the settings to ```MyServiceType``` and updating the driver when the form is successfully submitted.

Grouped settings
-----------
When dealing with settings from multiple components, it is recommended to group the settings. As mentioned before, the bundle provides some helper utilities for grouping settings. This allows you to link to bundle in to the settings bundle in a modular way.

### Registering a form type to be used as a settings form
To register a settings form for a group of settings (e.g. myservice), you must tag it with ```vivait_settings.register.form```, provide it with an alias that matches the group name of your settings, and provide it with a title:

```yaml
  me.mybundle.myservice.form_type:
    class: Me\\MyBundle\\Form\\Type\\Settings\\MyServiceType
    tags:
      - { name: vivait_settings.register.form, for: myservice, title: 'My Service Settings' }
```

All settings in this group will accessible via myservice.settingname.

Testing Run
-----------
Unit Test are written with PHPSpec.
