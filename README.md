Oauth2
===============
Oauth2 Library for Safan Framework

REQUIREMENTS
------------
PHP >= 7.1,
gap-db/orm = 1.*,
gap-db/orm-cache = 1.*,
symfony/http-foundation = 4.*,

INSTALLATION
------------
If you're using [Composer](http://getcomposer.org/) for your project's dependencies, add the following to your "composer.json":
```
"require": {
    "safan/oauth2": "1.*"
}
```

Update Modules Config List - safan-framework-standard/application/Settings/modules.config.php
```
<?php
return [
    // Safan Framework default modules route
    'Safan'         => 'vendor/safan-lab/safan/Safan',
    'SafanResponse' => 'vendor/safan-lab/safan/SafanResponse',
    // Write created or installed modules route here ... e.g. 'FirstModule' => 'application/Modules/FirstModule'
    'Oauth2'        => 'vendor/safan-lab/oauth2/Oauth2',
    'GapOrm'        => 'vendor/gap-db/orm/GapOrm',
    'GapOrmCache'   => 'vendor/gap-db/orm-cache/GapOrmCache'
];
```


