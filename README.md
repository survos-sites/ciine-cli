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


