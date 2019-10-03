# 19h47/set-glance-items

Set glance items to the __WordPress__ admin dashboard.

`SetGlanceItems` is class that takes two arguments: an array of array taxonomies and an array of arrays of posts.    
Each of this array takes two keys:
- `name`: the name of the item
- `code`: the dashicon code

## Usage

```php
if ( class_exists( 'SetGlanceItems' ) ) {
    $custom_taxonomies = array(
        array(
            'name' => 'city',
            'code' => '\f11d',
        ),
    );

    $custom_post_types = array(
        array(
            'name' => 'discovery',
            'code' => '\f230',
        ),
    );

    new SetGlanceItems( $custom_taxonomies, $custom_post_types );
}
```

## composer

To use it directly inside your theme add this lines to your `composer.json` file:

> Assuming your architecture is `wp-content/themes` and your plugin folder is in `wp-content/plugins`

```json
{
    "require": {
        "19h47/set-glance-items": "dev-master"
    },
    "repositories": [
        {
            "type": "git",
            "url": "https://github.com/19h47/set-glance-items.git"
        }
    ],
    "extra": {
        "installer-paths": {
            "../../plugins/{$name}/": [
                "type:wordpress-plugin"
            ]
        }
    }
}
```
