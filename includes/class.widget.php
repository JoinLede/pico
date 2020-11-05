<?php

class Pico_Widget {

    private static $publisher_id;

    public static function init() {
        $pub_id = Pico_Setup::get_publisher_id(false);
        if ($pub_id !== null) {
            add_action('wp', array('Pico_Widget', 'do_widget'), 11);
            self::$publisher_id = $pub_id;
        }
    }

    public static function verify_tags_are_where_they_should_be() {
        if (Pico_Setup::get_publisher_id(false) !== null) {

            if (!did_action(array('Pico_Widget', 'load_empty_widget_container'))) {
                Pico_Widget::load_empty_widget_container();
            }

            if (!did_action(array('Pico_Widget', 'load_info_for_widget'))) {
                Pico_Widget::load_info_for_widget();
            }

            if (!is_singular()) {
                // If not, just add the <pico> tag at the end so we can load the widget without cutting off content
                if (!did_action(array('Pico_Widget', 'load_empty_pico_tag'))) {
                    Pico_Widget::load_empty_pico_tag();
                }
            }
        }
    }

    public static function do_widget() {
        add_action('wp_footer', array('Pico_Widget', 'load_empty_widget_container'));
        add_action('wp_enqueue_scripts', array('Pico_Widget', 'load_info_for_widget'));

        // If this is a post, page, or any other kind of custom content type, run it through the filter to cut off content
        if (is_singular()) {
            add_filter('the_content', array('Pico_Widget', 'filter_content'));
        } else {
            // If not, just add the <pico> tag at the end so we can load the widget without cutting off content
                add_action('wp_footer', array('Pico_Widget', 'load_empty_pico_tag'));
        }
    }

    public static function load_empty_pico_tag() {
        echo '<div id="pico"></div>';
    }

    public static function load_empty_widget_container() {
        echo "<div id='pico-widget-container'></div>";
    }

    public static function load_gadget_script() {
        echo '<script data-pico-id="' . self::$publisher_id . '" src="' . Pico_Setup::get_gadget_endpoint() . '/load/build.js"></script>';
    }

    public static function filter_content($content) {
        return '<div id="pico">' . $content . '</div>';
    }

    public static function get_list_of_taxonomies() {
        $taxonomies         = get_taxonomies();
        $list_of_taxonomies = [];
        foreach ($taxonomies as $key) {
            array_push($list_of_taxonomies, $key);
        }
        return $list_of_taxonomies;
    }

    public static function load_info_for_widget() {
        $widget_endpoint    = Pico_Setup::get_widget_endpoint();
        $publisher_id       = Pico_Setup::get_publisher_id(false);
        $widget_version     = Pico_Setup::get_widget_version_info();
        $pico_context       = Pico_Setup::get_pico_context();
        wp_register_script('read-more.js', plugin_dir_url(__FILE__) . 'js/read-more.js', [], $widget_version);
        wp_localize_script('read-more.js', 'pp_vars',
            array_merge(
                array(
                    'publisher_id'    => $publisher_id,
                    'widget_endpoint' => $widget_endpoint,
                    'plugin_version'  => PICO_VERSION,
                    'widget_version'  => str_replace('"', "", $widget_version),
                    'pico_context'    => $pico_context,
                ), self::get_current_view_info()
            )
        );
        wp_enqueue_script('read-more.js');
    }

    /**
     * Handles all of the post info that needs to be
     * returned for the loader to use
     * @return [array]
     */
    public static function get_current_view_info() {
        global $wp;
        $queried_object            = get_queried_object();
        $post_info                 = array();
        $list_of_taxonomies        = self::get_list_of_taxonomies();

        if (is_home() || is_front_page()) {
            // this should prevent us from evaluating the post type
            // to be a page when a static page is set as a home page
            // because it is evaluated before is_singular()
            // more info: https://codex.wordpress.org/Conditional_Tags#The_Blog_Page
            $post_info['post_type']             = 'home';
            $post_info['post_id']               = null;
            $post_info['show_read_more_button'] = false;
        } elseif (is_singular()) {
            // is singular can refer to either a post, page, or custom content type
            $post_info['post_id']                       = $queried_object->ID;
            $post_info['post_type']                     = $queried_object->post_type;
            $post_info['post_title']                    = $queried_object->post_title;
            $post_info['taxonomies_for_this_post_type'] = get_object_taxonomies($queried_object->post_type);
            $post_info['list_of_taxonomies']            = $list_of_taxonomies;
            $post_info['show_read_more_button']         = true;
            // this probably could be removed if edible is using a taxonomy
            if (function_exists('tribe_is_event_query') && tribe_is_event_query()) {
                $post_info['show_read_more_button']         = false;
            } else {
                $post_info['show_read_more_button']         = true;
            }

            // this is assuming their more tag is unmodified
            if (check_to_see_if_more_tag_is_set($queried_object)) {
                $post_info['break_selector'] = 'more-' . $queried_object->ID;
            }

            // tags and categories
            $categories_array = [];
            $tags_array       = [];
            $taxonomies       = [];
            foreach ($post_info['taxonomies_for_this_post_type'] as $tax) {
                $tax_terms = get_the_terms($queried_object->ID, $tax);
                if ($tax_terms) {
                    $taxonomies[$tax] = [];
                    foreach ( $tax_terms as $term ) {
                        array_push($taxonomies[$tax], strtolower($term->name));
                    }
                }
            }
            $post_info['taxonomies'] = $taxonomies;
        } elseif (is_author()) {
            $post_info['post_type']             = 'author';
            $post_info['post_id']               = $queried_object->ID;
            $post_info['show_read_more_button'] = false;
        } elseif (is_category()) {
            $post_info['post_type']             = 'category';
            $post_info['post_id']               = $queried_object->term_id;
            $post_info['show_read_more_button'] = false;
        } elseif (is_tag()) {
            $post_info['post_type']             = 'tag';
            $post_info['post_id']               = $queried_object->term_id;
            $post_info['show_read_more_button'] = false;
        } elseif (is_tax()) {
            $post_info['post_type']             = 'tax';
            $post_info['post_id']               = $queried_object->term_id;
            $post_info['show_read_more_button'] = false;
        } elseif (is_search()) {
            $post_info['post_type']             = 'search';
            $post_info['post_id']               = null;
            $post_info['show_read_more_button'] = false;
        } elseif (is_date()) {
            $post_info['post_type']             = 'date';
            $post_info['post_id']               = null;
            $post_info['show_read_more_button'] = false;
        } elseif (is_404()) {
            $post_info['post_type']             = '404';
            $post_info['post_id']               = null;
            $post_info['show_read_more_button'] = false;
        } else {
            // hopefully I've accounted for every scenario
            $post_info['post_type']             = 'unknown';
            $post_info['post_id']               = null;
            $post_info['show_read_more_button'] = false;
        }

        $post_info['resource_ref'] = $wp->query_string;

        return $post_info;
    }
}
