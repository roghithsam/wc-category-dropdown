# WooCommerce Category Dropdown Shortcode

This WordPress plugin provides a shortcode `[wc_category_dropdown]` to display WooCommerce product categories in a dropdown-style menu with category images and subcategories.

## Features
- Displays product categories in a dropdown menu.
- Supports showing/hiding empty categories.
- Option to display only parent categories.
- Displays category images (or a default icon if no image is available).
- Includes expandable subcategories.
- Uses jQuery for toggle interactions.
- Fully customizable via CSS.

## Installation
1. **Download the Plugin**  
   Clone the repository or download the ZIP file.
   ``` https://github.com/YOUR_USERNAME/wc-category-dropdown.```

2. **Upload to WordPress**

- Go to Plugins > Add New in the WordPress dashboard.
- Click Upload Plugin and upload the plugin folder.
- Activate the plugin.

3. **Use the Shortcode**
- Add the following shortcode to any page or post:

```
[wc_category_dropdown hide_empty="true" parent_only="false" show_image="true"]
```

## Shortcode Attributes

| Attribute      | Default Value | Description |
|---------------|--------------|-------------|
| `hide_empty`  | `true`       | Hide categories with no products (`true` or `false`). |
| `parent_only` | `false`      | Show only parent categories (`true` or `false`). |
| `orderby`     | `name`       | Order categories by `name`, `id`, or `count`. |
| `order`       | `ASC`        | Sort order (`ASC` or `DESC`). |
| `show_image`  | `true`       | Show category images (`true` or `false`). |
| `default_icon` | `fa-folder` | Default FontAwesome icon if no image is found. |


## License
 - This plugin is licensed under the MIT License.

## Contributions & Issues
- Feel free to submit issues or contribute to the development via pull requests. 🎉
