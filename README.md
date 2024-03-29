# SLIME Utilities

## Abstraction functions to promote a fast and easy development workflow with Slim.

Most of these functions aren't specific to Slim and can be used in other contexts or on their own (except for the 'render' functions). We've bundled them together for convenience to be included with the [Slime boilerplate and metaframework](https://github.com/jyoungblood/slime).

These functions are also available as separate packages:
- [slime-render](https://github.com/jyoungblood/slime-render)
- [dbkit](https://github.com/jyoungblood/dbkit)
- [http-request](https://github.com/jyoungblood/http-request)
- [cookie](https://github.com/jyoungblood/cookie)
- [x-utilities](https://github.com/jyoungblood/x-utilities)


## Installation
```
composer require jyoungblood/slime-utilities
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

Refer to the source packages for usage examples.

### [render - Render Content as PSR-7](https://github.com/jyoungblood/slime-render)
- render::hbs($request, $response, $parameters)
- render::redirect($request, $response, $parameters)
- render::json($request, $response, $parameters)
- render::lightncandy_html($parameters)($data)
- render::initialize_handlebars_helpers()
- render::twig($request, $response, $parameters)


### [db - Data Handlers (mysql w/ PDO)](https://github.com/jyoungblood/dbkit)
- db::init($settings)
- db::insert($table, $input)
- db::find($table, $criteria, $options)
- db::update($table, $input, $criteria)
- db::delete($table, $criteria)
- db::create_placeholders($criteria)



### [http - HTTP Request Handlers](https://github.com/jyoungblood/http-request)
- http::request($url, $parameters)
- http::get($url, $parameters)
- http::post($url, $parameters)
- http::json($url, $parameters)

### [cookie - Cookie Handlers](https://github.com/jyoungblood/cookie)
- cookie::set($key, $value, $parameters)
- cookie::get($key)
- cookie::delete($key)

### [x - Misc Utilities](https://github.com/jyoungblood/x-utilities)
- x::email_send($parameters)
- x::client_ip()
- x::url_slug($string)
- x::url_strip($url)
- x::url_validate($url)
- x::br2nl($string)
- x::array_encode($array)
- x::array_decode($string)
- x::console_log($input, $parameters)
- x::dd($input, $parameters)
- x::file_write($input, $target_filename, $parameters)
- x::error_log($input, $parameters)