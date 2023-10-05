![ANSTECH Limited](docs/assets/img/square-logo-256px.png?raw=true "ANSTECH Limited")


## FuelPHP - REST Package

> A basic extension of the default FuelPHP REST Controller, with cross site (XS) and CORS preflight support.

### Basic Usage
Extend the `\REST\Controller_RestXS` class, instead of standard `\Controller_Rest` class; for example:
```php
class Controller_API extends \REST\Controller_RestXS;
```