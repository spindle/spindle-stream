spindle/stream
========================

Iterator builder like Java8' Stream API

```php
<?php
use Spindle\Stream;

Stream::_(range(0, 9))
    ->filter('$_ > 5')
    ->distinct()
    ->map('$_ * 5')
    ->export($stream);

foreach ($stream as $v) {
    echo $v, PHP_EOL;
}
```

Install
-----------------------

```
$ composer require 'spindle/stream:*'
```

License
-----------------------

Creative Commons Zero 1.0 Universal
(Public Domain)
