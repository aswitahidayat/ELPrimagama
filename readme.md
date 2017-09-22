<p align="center">
    <img src="https://laravel.com/assets/img/components/logo-laravel.svg">
    <img width="70" src="https://vuejs.org/images/logo.png">
</p>


# Laravel 5.4 + CoreUI Admin Bootstrap Template VueJS


## What is this?

A clean install of Laravel 5.4 with the [CoreUI Full Bootstrap Admin Template VueJS Version](https://github.com/mrholek/CoreUI-Free-Bootstrap-Admin-Template/tree/master/Vue_Full_Project) added in. This implementation is nowhere near to perfect but it's a working start. Pull Requests are welcome!


## What's it look like?

[Here's a demo.](http://coreui.io/demo/Vue_Demo/)


## Tips

If your here, you most likely know what Laravel is and hopefully how to get up and running with it. If not, [click here to check the docs on getting up and running](https://laravel.com/docs/5.4/). Also [Laracasts](https://laracasts.com) is a fantastic resource for getting up to speed with everything Laravel from pretty much any version.

## Getting Started
```
composer install
```

```
php artisan serve
```

```
npm install
```

```
npm run watch
```

```
php artisan make:controller Apps/<Controller Name> --resource
```

```
php artisan make:model SmartEbook --migration
```

```
php artisan migrate
```

## Frequent Trouble & Soulution

[bash: $'\302\226php': command not found]

```
composer global require "laravel/installer"
```

SQL Server driver for php
[https://github.com/Microsoft/msphpsql/releases]