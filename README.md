# SLIME Utilities

## A set of abstraction functions to promote a fast and easy development workflow with Slim.


These aren't particularly specific to slim and can be use in other contexts or on their own (except for the 'render' ones), but we've bundled them together to be included with the [slime] metaframework.




## INSTALLATION
```
composer require hxgf/slime-utilities:0.1.0@dev
```

```php

use Slime\render;
use Slime\db;
use Slime\http;
use Slime\cookie;
use Slime\x;

require __DIR__ . '/vendor/autoload.php';

```

## API

### render - Render Content
- render::json()
- render::twig()
- render::hbs()

### db - Data Handlers (mysql w/ PDO)
- db::init()
- db::insert()
- db::find()
- db::update()
- db::delete()
- db::where_placeholders()

### http - HTTP Request Handlers
- http::request()
- http::get()
- http::post()
- http::json()

### cookie - Cookie Handlers
- cookie::set()
- cookie::get()
- cookie::delete()

### x - Misc Helpers
- x::client_ip()
- x::email_send()
- x::url_slug()
- x::url_strip()
- x::url_validate()
- x::br2nl()
- x::array_encode()
- x::array_decode()


### db::where_placeholders($criteria)