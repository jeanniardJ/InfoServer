# News Server Linux

[![Latest Stable Version](https://img.shields.io/packagist/v/jjeanniard/infoserver.svg)](https://packagist.org/packages/jjeanniard/infoserver)
[![Total Downloads](https://img.shields.io/packagist/dt/illuminatech/db-role.svg)](https://packagist.org/packages/jjeanniard/infoserver)

## Description

Récupère les informations de base d'un serveur linux (Ubuntu 16.04 & 20.04)
et les retournes en tableaux, pour pouvoir ensuite les utilisées de différentes manières.

```text
* getSystem()
* getCpu()
* getRam()
* getSwap()
* getLoadAverage()
* getReseau()
* getDisk()
* getUptime()
```

### Installation

```composer require jjeanniard/infoserver```

#### Warning

Require: ```phpseclib/phpseclib```

## Usage

Exemple :

```php
$ssh = new SSH2('localhost', 'port');

if (!$ssh->login('username', 'password')) {
    echo('Login Failed');
}

$dataServ = new InfoServer($ssh);
$dataServ->getCpu();
```

### Licence

[MIT](https://github.com/JJeanniard/InfoServer/blob/master/LICENSE)
