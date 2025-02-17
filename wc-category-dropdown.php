<?php

add_shortcode("wc_category_dropdown", "wc_category_dropdown");

function wc_category_dropdown($atts)
{
    $atts = shortcode_atts([
        "hide_empty" => "true",
        "parent_only" => "false",
        "orderby" => "name",
        "order" => "ASC",
        "show_image" => "true", // Show category image
        "default_icon" => "fa-folder", // Default icon if no image
    ], $atts, "wc_category_dropdown");

    $hide_empty = filter_var($atts["hide_empty"], FILTER_VALIDATE_BOOLEAN);
    $parent_only = filter_var($atts["parent_only"], FILTER_VALIDATE_BOOLEAN);
    $show_image = filter_var($atts["show_image"], FILTER_VALIDATE_BOOLEAN);
    $default_icon = sanitize_text_field($atts["default_icon"]);
    $orderby = sanitize_text_field($atts["orderby"]);
    $order = sanitize_text_field($atts["order"]);

    $args = [
        "taxonomy" => "product_cat",
        "hide_empty" => $hide_empty,
        "orderby" => $orderby,
        "order" => $order,
    ];

    if ($parent_only) {
        $args["parent"] = 0; // Show only top-level categories
    }

    $categories = get_terms($args);

    if (!empty($categories)) {
        echo '<div class="category-dropdown-container">';
        echo '<button class="category-label"><i class="fas fa-bars"></i> <span>All Categories <i class="fa-solid fa-caret-down"></i></span></button>';
        echo '<div class="category-list">';
        
        

        foreach ($categories as $category) {
             // Get Category Image
            $thumbnail_id = get_term_meta($category->term_id, "thumbnail_id", true);
            $image_url = $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : '';
            
            // Check for subcategories
            $subcategories = get_terms([
                "taxonomy" => "product_cat",
                "hide_empty" => $hide_empty,
                "parent" => $category->term_id,
            ]);
            
             $category_icon = $image_url ? '<img src="' . esc_url($image_url) . '" class="category-icon">' : '<i class="fa ' . esc_attr($default_icon) . '"></i>';

            if (!empty($subcategories)) {
                echo '<div class="category-item has-subcategories">';
                echo '<button class="subcategory-toggle">' . $category_icon . ' <span>' . esc_html($category->name) . '</span> <i class="toggle-arrow">▶</i></button>';
                echo '<div class="subcategory-list">';

                foreach ($subcategories as $subcategory) {
                     $sub_thumbnail_id = get_term_meta($subcategory->term_id, "thumbnail_id", true);
                    $sub_image_url = $sub_thumbnail_id ? wp_get_attachment_url($sub_thumbnail_id) : '';
                    $subcategory_icon = $sub_image_url ? '<img src="' . esc_url($sub_image_url) . '" class="category-icon">' : '<i class="fa ' . esc_attr($default_icon) . '"></i>';

                    echo '<a href="' . esc_url(get_term_link($subcategory)) . '" class="subcategory">' . $subcategory_icon . ' — ' . esc_html($subcategory->name) . '</a>';
                }

                echo '</div>';
                echo '</div>';
            } else {
                echo '<a href="' . esc_url(get_term_link($category)) . '" class="category-item">' . $category_icon . ' ' . esc_html($category->name) . '</a>';
            }
        }

        echo "</div>"; // Close .category-list
        echo "</div>"; // Close .category-dropdown-container
        ?>
        <style>
            .category-dropdown-container {
                width: 100%;
                position: relative;
                display: inline-block;
            }

            .category-label {
                width: 100%;
                background: #6c2243;
                color: #fff;
                padding: 8px 15px;
                border: none;
                cursor: pointer;
                font-size: 16px;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .category-list {
                position: absolute;
                top: 100%;
                left: 0;
                min-width: 200px;
                width: 100%;
                background: #fff;
                box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
                border-radius: 5px;
                display: none;
                z-index: 9999;
            }

            .category-item {
                display: block;
                padding: 10px;
                font-weight: 600;
                color: #333;
                text-decoration: none;
                cursor: pointer;
            }

            .category-item:hover {
                background: #f1f1f1;
            }

            .has-subcategories {
                padding: 0;
                border-bottom: 1px solid #eee;
            }

            .subcategory-toggle {
                width: 100%;
                background: none;
                border: none;
                padding: 10px;
                text-align: left;
                font-size: 16px;
                font-weight: 600;
                color: #333;
                cursor: pointer;
            }

            .subcategory-toggle:hover {
                background: #f1f1f1;
            }
            
            button.subcategory-toggle i.toggle-arrow {
                  float:right !important;
            }

            .subcategory-list {
                display: none;
                padding-left: 10px;
                background: #f9f9f9;
            }

            .subcategory-list a {
                display: block;
                padding: 8px 15px;
                font-size: 14px;
                color: #333;
                text-decoration: none;
            }

            .subcategory-list a:hover {
                background: #e0e0e0;
            }
            
            .category-icon {
                width: 24px;
                height: 24px;
                margin-right: 10px;
                border-radius: 50%;
                object-fit: cover;
            }

            .toggle-arrow {
                transition: transform 0.3s ease;
            }

            .open .toggle-arrow {
                transform: rotate(90deg);
            }
        </style>

        <script>
            jQuery(document).ready(function ($) {
                // Toggle main category dropdown
                $(".category-label").click(function (event) {
                    event.stopPropagation();
                    $(".category-list").not($(this).next(".category-list")).slideUp();
                    $(this).next(".category-list").slideToggle();
                });

                // Toggle subcategories
                $(".subcategory-toggle").click(function () {
                    $(this).next(".subcategory-list").slideToggle();
                    $(this).toggleClass("open");
                    $(this).find(".toggle-arrow").text($(this).hasClass("open") ? "▼" : "▶");
                });

                // Close dropdown when clicking outside
                $(document).click(function (event) {
                    if (!$(event.target).closest(".category-dropdown-container").length) {
                        $(".category-list").slideUp();
                        $(".subcategory-list").slideUp();
                        $(".subcategory-toggle").removeClass("open");
                        $(".toggle-arrow").text("▶");
                    }
                });
            });
        </script>
        <?php
    }
}
