# Symfony PHAR demo

Example of how to run a Symfony command in a phar file.

## Installation

```bash
git clone git@github.com:tacman/symfony-phar-demo.git && cd symfony-phar-demo
composer install
bin/console about
```

## install humbug/box globally

```bash
composer global require humbug/box:dev-main
```

## Create the phar using humbug/box

```bash
~/.config/composer/vendor/bin/box compile
```

## ERROR :-(

```bash
./app.phar
```

```
PHP Fatal error:  Cannot redeclare interface Symfony\Component\Runtime\RunnerInterface (previously declared in phar:///home/tac/trash/symfony-phar-demo/app.phar/vendor/symfony/runtime/RunnerInterface.php:17) in /home/tac/trash/symfony-phar-demo/vendor/symfony/runtime/RunnerInterface.php on line 17
Symfony\Component\ErrorHandler\Error\FatalError^ {#72
  #message: "Compile Error: Cannot redeclare interface Symfony\Component\Runtime\RunnerInterface (previously declared in phar:///home/tac/trash/symfony-phar-demo/app.phar/vendor/symfony/runtime/RunnerInterface.php:17)"
  #code: 0
  #file: "./vendor/symfony/runtime/RunnerInterface.php"
  #line: 17
  -error: array:4 [
    "type" => 64
    "message" => "Cannot redeclare interface Symfony\Component\Runtime\RunnerInterface (previously declared in phar:///home/tac/trash/symfony-phar-demo/app.phar/vendor/symfony/runtime/RunnerInterface.php:17)"
    "file" => "/home/tac/trash/symfony-phar-demo/vendor/symfony/runtime/RunnerInterface.php"
    "line" => 17
```



