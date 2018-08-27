# php cidr lib

### install by composer

#### step 1
composer.json

```
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/colindev/php-cidr.git"
        }
    ],
    "require": {
        "colindev/cidr": "dev-master"
    }
}
```

#### step 2

```
$ composer install
```

#### step 3

[test/main.php](test/main.php)

```
$ php test/main.php 10.5.0.0/16 10.5.1.3
// print bood(true)
```
