<?php
/*
Plugin Name: Woocommerce Second Description
Plugin URI: https://bhavyasaggi.github.io/plugins/woocommerce-second-description
Description: Add Second Description to Product Category & Tags
Version: 0.1.0
Author: Bhavya Saggi
Author URI: https://bhavyasaggi.github.io/
License: MIT

------------------------------------------------------------------------

Copyright

*/

// ---------------
// 1. Display field on "Add new product category" admin page
function wcc_details_editor_add()
{
  ?>
    <div class="form-field">
        <label for="cat_details">Detail</label>

      <?php
      wp_editor(
        '',
        'cat_details',
        array(
          'textarea_name' => 'cat_details',
          'quicktags' => array('buttons' => 'em,strong,link'),
          'tinymce' => array(
            'theme_advanced_buttons1' => 'bold,italic,strikethrough,separator,bullist,numlist,separator,blockquote,separator,justifyleft,justifycenter,justifyright,separator,link,unlink,separator,undo,redo,separator',
            'theme_advanced_buttons2' => '',
          ),
          'editor_css' => '<style>#wp-details-editor-container .wp-editor-area{height:175px; width:100%;}</style>',
        )
      );
      ?>
    </div>
    <?php
}

// ---------------
// 2. Display field on "Edit product category" admin page
function wcc_details_editor_edit($term)
{
  $cat_details = htmlspecialchars_decode(get_woocommerce_term_meta($term->term_id, 'cat_details', true));
  ?>
    <tr class="form-field">
      <th scope="row" valign="top"><label for="cat_details">Detail</label></th>
      <td>
        <?php
        wp_editor(
          $cat_details,
          'cat_details',
          array(
            'textarea_name' => 'cat_details',
            'quicktags' => array('buttons' => 'em,strong,link'),
            'tinymce' => array(
              'theme_advanced_buttons1' => 'bold,italic,strikethrough,separator,bullist,numlist,separator,blockquote,separator,justifyleft,justifycenter,justifyright,separator,link,unlink,separator,undo,redo,separator',
              'theme_advanced_buttons2' => '',
            ),
            'editor_css' => '<style>#wp-details-editor-container .wp-editor-area{height:175px; width:100%;}</style>',
          )
        );
        ?>
      </td>
    </tr>
    <?php
}

// ---------------
// 3. Save field @ admin page
function wcc_save_details_editor($term_id, $tt_id = '', $taxonomy = '')
{
  if ('product_cat' === $taxonomy || 'product_tag' === $taxonomy) {
    if (isset($_POST['cat_details'])) {
      update_woocommerce_term_meta($term_id, 'cat_details', esc_attr($_POST['cat_details']));
    }
  }
}

// ---------------
// 4. Display field under products @ Product Category pages 
function wcc_display_details_editor_content()
{
  if (is_product_taxonomy()) {
    $term = get_queried_object();
    if ($term && !empty(get_woocommerce_term_meta($term->term_id, 'cat_details', true))) {
      echo '<div class="wc-second-description">' .
        wc_format_content(
          htmlspecialchars_decode(
            get_woocommerce_term_meta($term->term_id, 'cat_details', true)
          )
        ) .
        '</div>';
    }
  }
}

function wcc_second_descripton()
{
  add_action('product_cat_add_form_fields', 'wcc_details_editor_add', 10, 2);
  add_action('product_cat_edit_form_fields', 'wcc_details_editor_edit', 10, 2);
  add_action('product_tag_add_form_fields', 'wcc_details_editor_add', 10, 2);
  add_action('product_tag_edit_form_fields', 'wcc_details_editor_edit', 10, 2);
  add_action('edit_term', 'wcc_save_details_editor', 10, 3);
  add_action('created_term', 'wcc_save_details_editor', 10, 3);
  add_action('woocommerce_after_shop_loop', 'wcc_display_details_editor_content', 15);
}

add_action('init', 'wcc_second_descripton');
