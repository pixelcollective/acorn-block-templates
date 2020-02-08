## Acorn Block Templates

Easily override block markup with Blade templates in Sage 10.

## Requirements

- [Sage](https://github.com/roots/sage) >= 10.0
- [PHP](https://secure.php.net/manual/en/install.php) >= 7.2
- [Composer](https://getcomposer.org)

## Installation

Install via Composer:

```bash
$ composer require tiny-pixel/acorn-block-templates
```

## Usage

Create a `blocks` directory in `resources/assets/views`. Templates should be named after the block they target and should be placed within a directory with a name matching the block's namespace.

**Example**
```sh
├── blocks
    └── core
        ├── gallery.blade.php     # core/gallery
        ├── image.blade.php       # core/image
        └── paragraph.blade.php   # core/paragraph
```

The template is passed two variables: `$attr` and `$content`.
- `$attr` is an array of the block's attributes.
- `$content` is a string of the block's output.

In this way you can override the template for _any_ block, regardless of if it is registered by `core` or a plugin. You can use Composers, `extends`, or any other feature afforded to you by Sage, Illuminate or other packages.

## Contributing

Contributing, whether it be through PRs, reporting an issue, or suggesting an idea is encouraged and appreciated.

All contributors absolutely must strictly adhere to our [Code of Conduct](https://github.com/pixelcollective/acorn-instagram/blob/master/LICENSE.md).

## License

This project is provided under the [MIT License](https://github.com/pixelcollective/acorn-block-templates/blob/master/LICENSE.md).