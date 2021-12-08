Modx 1.2.x injection to use modx API in your console application

# Пример использования:

```php
use \mmaurice\modx\Core;
use \mmaurice\modx\Search;

// Если нам необходимо ядро Modx
$injector = new Core;
$modx = $injector->modx();

// Если необходим только ядро для работы с DB
$modxDb = $injector->db();

// Если необходим слой для работы с DB
$search = new Search;
$result = $search->query(... some sql ...);

// В слое есть следующие удобные для работы методы:
$search->getList([... params array ...]);
$search->getItem([... params array ...]);
$search->getRawSql([... params array ...]);
```

В качестве параметров фильтра принимаются следующие:
- `select`
- `alias`
- `from`
- `join`
- `where`
- `group`
- `having`
- `order`
- `limit`
- `offset`
