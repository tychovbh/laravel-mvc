# laravel-mvc

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Laravel MVC is created by, and is maintained by Tycho, and is a Laravel/Lumen package to manage all your data via a Repositoru. Feel free to check out the [change log](CHANGELOG.md), [releases](https://github.com/tychovbh/laravel-mvc/releases), [license](LICENSE.md), and [contribution guidelines](CONTRIBUTING.md)

## Install

Via Composer

``` bash
$ composer require tychovbh/laravel-mvc
```

## Usage

Use The UserRepository in controller, but you can use it anywhere else too.
``` php
artisan make:repository UserRepository

class UserController extends controller
{
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository
    }
    
    public function index()
    {
        $users = $this->repository->all();
        return response()->json($users)
    }
}
```

Make sure you have a Model that your repository can use. If you want to use save/update methods add $filled to your model.
```php
class User extends model
{
    protected $filled = ['name']
}
```

Available methods"
```php
// Get all
$this->repository->all();

// Search all resources with name: Jan
$this->repository->all(['name' => 'Jan']);

// Search resource with ID: 1
$this->repository->find(1);

// Store resource in the database. This uses laravel fill make sure you add protected $filled = ['name'] to your User model.
$user = $this->repository->save(['name' => 'jan']);

// Update resource.
$user = $this->repository->update(['name' => 'piet'], 1);

// Destroy resource(s).
$this->repository->destroy([1]);
```

If you wish to override on of the methods above just add it to you repository
```php
class UserRepository extends AbstractRepository implements Repository
{
    public function find(int $id)
    {
        // add your own implementation of find
        return $user;
    }
    
    public function save($data)
    {
        // Add some logic and then call parent save
        $data['password'] = Hash:make($data['password']);
        return parent::save($data);
    }
}

```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email info@bespokeweb.nl instead of using the issue tracker.

## Credits

- [Tycho][link-author]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/tychovbh/laravel-mvc.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/tychovbh/laravel-mvc/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/tychovbh/laravel-mvc.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/tychovbh/laravel-mvc.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/tychovbh/laravel-mvc.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/tychovbh/laravel-mvc
[link-travis]: https://travis-ci.org/tychovbh/laravel-mvc
[link-scrutinizer]: https://scrutinizer-ci.com/g/tychovbh/laravel-mvc/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/tychovbh/laravel-mvc
[link-downloads]: https://packagist.org/packages/tychovbh/laravel-mvc
[link-author]: https://github.com/tychovbh
[link-contributors]: ../../contributors
