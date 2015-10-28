Author:
-------

Daniel Tomé Fernández <daniel.tome@estudiants.urv.cat>


Introduction:
-------------

This plugin provide us a changelog of our application.

Instalation:
------------
To install this module you must follow this steps:

1- In your composer.json you must write:

```php
"require": {
        "sred/changelog": "dev-master",
    },
```

2- Then you must run the following command line `composer update`.


Configuration:
-------------

1- We provide you a bash script in script folder, to place it wherever and only changing the path it will execute
the `Generator.php` file.

1- We also provide you different constants in our `Generator.php` file to adapt this plugin to your project.