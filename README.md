# Requires
- PHP 7.3+ with SMTP
- PHP Composer

# Usage

- Install PHP7.3 with SMTP
- Install Nginx
- Install PHP Composer

In your website dir:

```shell
$ git clone https://github.com/kmvan/mail-forward.git
$ cd ./mail-forward
$ composer install
$ composer dumpautoload -o
$ chown [YOUR PHP/NGINX USER GROUP] ./ -R
```

Enjoy it!
