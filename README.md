# News Server Linux

Récupère les informations de base d'un serveur linux (Ubuntu 16.04 & 20.04) 
et les retourne en tableau, pour pouvoir ensuite les utilisées de différentes manières.
```
* getSystem()
* getCpu()
* getRam()
* getSwap()
* getLoadAverage()
* getReseau()
* getDisk()
```
### Installation

```composer require jjeanniard/infoserver```

##### Warning

Require: ```phpseclib/phpseclib```

## Usage

Exemple :
```php
$dataServ = new InfoServer($ssh);
$dataServ->getCpu();
```

## Credits

#### Licence

[MIT](https://github.com/JJeanniard/InfoServer/blob/master/LICENSE)