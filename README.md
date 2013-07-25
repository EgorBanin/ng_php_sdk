PHP-библиотека для добавления приложений и игр [nextgame](http://nextgame.ru) на сайт.

У многих разработчиков возникают трудности с установкой приложений на сайт, эта библиотека должна им помочь.

```php

<?php

const NG_SITE_ID = 123;
const NG_SECRET_KEY = '0123456789ABCDEF';

require_once 'NG.php';

$ng = new NG(NG_SITE_ID, NG_SECRET_KEY);

// код каталога приложений и игр
echo $ng->code();

// или отдельная игра
echo $ng->code()->setAppId('166');

// или со сквозной авторизацией
$user = $ng->user('1', 'admin');
echo $ng->code()->setAppId('166')->setUser($user);

// список приложений установленных пользователем
var_dump($user->getApps());

// удаление приложения пользователя
$user->uninstallApp('166');

?>

```