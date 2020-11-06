## Overview

Easily add tooltips to your wordpress site. Tooltips will show when target element is hovered over.
You can easily pick your tooltip color settings in `Settings > Easy Tooltips`.

## Requirements
- PHP >= 7.4
- Composer - [Install](https://getcomposer.org/doc/00-intro.md)


## Installation

Clone the repository to your local machine into `my_project/wp-content/plugins`.
Next you need install all dependencies, in this case you can to use the `composer`.
```bash
$ composer update --no-dev
```

## Activate

From WP admin > `Plugins` > `Easy Tooltips`.

Click the `Activate` Plugin link

Or to use the wp-cli:

```bash
$ wp plugin activate easy-tooltips

Plugin 'easy-tooltips' activated.
Success: Activated 1 of 1 plugins.
```

## Using

You can add the tooltip manually if you use the shortcode like this:

>[easy_tooltip title="Hello world"]
>
>[easy_tooltip title="Hello world"][any shortcode][/easy_tooltip]

Or create the html element, for example:

```html
<a class="easy__tooltips" title="Hello world" href="#">Some page</a>
```

##Options

`Shortcode`:
- title
- content
- position
- opacity
- max_width

`Html element`
- data-ztt_title
- data-ztt_content
- data-ztt_position
- data-ztt_opacity
- data-ztt_max_width


## Deactivate and remove
From WP admin > `Plugins` > `Easy Tooltips`.

Click the `Deactivate` plugin link and the `Delete` link for removing.
