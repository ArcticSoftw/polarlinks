[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/support-ukraine.svg?t=1" />](https://supportukrainenow.org)

<p align="center"><img src="https://i.imgur.com/liyp3D7.png" /></p>

# Simple hyperlink content CRUD for Laravel

PolarLinks provides a unified place to administer site wide hyperlinks for content management on your laravel application. This package does not deal with any frontend components of Laravel, but serves basic CRUD API for easy implementation into your administration panel and frontend library of choice. 

## Requirements

* Laravel 8.*
* PHP 8.*

## Installation

You can install this package through composer:
``` bash
composer require arcticsoftware/polarlinks
```
Once you have installed the package, and before you proceed with your database migration you might want to customize and define what table names the package is going to use. There are two database tables defined and needed by the package. One for link collections, and one for the links themselves. By default, these are:

* polarsections
* polarlinks

Respectively. To publish the configuration file, run the command after installation in the root folder of your laravel installation:
``` bash
php artisan vendor:publish --provider="ArcticSoftware\PolarLinks\PolarLinksServiceProvider" --tag="config"
```
From here on you can edit the published configuration file in *\[laravel_root\]/config/polarlinks.php*

Once you are satisfied with your choice(s), publish the migrations by running the following command in the root folder of your laravel installation:
``` bash
php artisan vendor:publish --provider="ArcticSoftware\PolarLinks\PolarLinksServiceProvider" --tag="migrations"
```
Then run *php artisan migrate* to run the database migrations. The package is now ready for use.

## Usage

Accessing the underlying models and data are done through an API available with a couple of defined fascades.

### PolarLink

The PolarLink class provides a series of static functions to manipulate link data. Every call on this class relies on a name identifier, so no matter what operation you want to run, you will always have to provide the name function:

```php
PolarLink::name('a_name')
```

The name identifier has to be unique for the table and only contain alphanumeric characters pluss underscores. From here on you can define optional properties on the model to populate the link record:

```php
PolarLink::name('a_name')
    ->title('A link title') // String field
    ->weight(3)             // Int field
    ->url('/site/link')     // String field that accepts valid URLs and paths
    ->description('A description') // Text field
    ->published(false)      // A boolean field
    ->create();             // Creates the link with provided data
```

The last callable function on the class is always an operation. The available function operations for the PolarLink::class are:

```php
function get() : Link
// Returns a single eloquent link model, or *null* if the link was not found in the database table, using the provided name.

function create() : Link
// Inserts a new link with the provided name as an identifier and any optional data. Returns a single eloquent link model after creation.

function update() : Link
// Updates a link with the provided name as an identifier and any optional data. Returns a single eloquent link model after updating or a *null* value if no model was found in the database with the provided name.

function delete() : void
// Deletes a link with the provided name as an identifier.

function newName(string $newName) : Link
// Renames the link with a new identifier, returns Link eloquent model if successful or *null* if no link where found
```

PolarLink::class also has the following static utility functions (do not rely on using the static name function):

```php
function checkifExists(string $linkName) : bool
// Check if the link with the provided name exists in the database. Returns true or false depending on the result

function testUrl(string $url) : bool
// Check if the string passed validates as a URL or relative internal site path. Returns true or false depending on the result

function testName(string $name) : bool
// Check if the string passed validates as a Link identifier. Returns true or false depending on the result
```
### PolarSection

The PolarSection class provides means to categorize and sort your links and is defined as a one-to-many relationship between the LinkSection-Link eloquent models. Besides the usual CRUD routines, it also lets you retrieve link collections as either Elequent\Collection, PHP array, or JSON string.

```php
function get() : LinkSection
```
Returns a single eloquent linksection model, or *null* if the linksection was not found in the database table, using the provided name.

If the linksection has one or more child link models, you can retrieve this as either Elequent\Collection, array or json by using the *load()* function and passing the options as an array. For example:

```php
LinkSection::name('mainmenu_links')
    ->load([
        'format' => 'json'
    ])
    ->get();
```

This will return all the links associated (and their data) with the section as a json array string. The format parameter accepts 3 options:

    * 'collection' - Returns the links as an Eloquent\Collection
    * 'array' - Returns the links as a PHP array
    * 'json' - Returns the links as a json array string

```php
function create() : LinkSection
// Inserts a new linksection with the provided name as an identifier. Returns a single eloquent linksection model after creation.

function delete(bool $purgeLinks = false) : void
// Deletes a linksection with the provided name as an identifier. If the passed *$purgeLinks* parameter is true, it will also delete any associated links. If the parameter is false (default), it will only dissassociate the model relationship and leave the links associated with the section intact in the database.

function attach(Link $polarLink) : void
// Attaches a Link model to the Section, with the provided name.

function attachMore(Collection $polarLinkEloquentCollection) : void
// Attaches an Eloquent collection of Link models with the provided Section name.

function empty() : void
// Dissassociates all links in the section

function purge() : void
// Deletes all links in the section

function newName(string $newNameIdentifier) : void
// Renames the linksection with a new identifier, given the old name. 
```

PolarSection::class also has the following static utility functions (do not rely on using the static name function):

```php
checkifExists(string $linkSectionName) : bool
// Check if the linksection with the provided parameter name exists in the database. Returns true or false depending on the result
```

## Testing
``` bash
composer test
```
## Changelog

Please see the [CHANGELOG](CHANGELOG.md) log for details about changes between the versions

## Contributing

Please [contact us](https://arcticsoftware.no/kontakt) if you would like to make some contributions to our code, and we will set up the needed branches and permissions for you to submit your changes. We will be sure to credit you on this page (and on our [company page](https://arcticsoftware.no/) if your contributions are significant).

### Security

If you discover any security vulnerabilities in the code, please [contact us](https://arcticsoftware.no/kontakt) directly instead of using the issue tracker

## License

This software is licensed under the terms of the MIT license. Please review the accompanied [license.md](LICENSE.md) file for details.