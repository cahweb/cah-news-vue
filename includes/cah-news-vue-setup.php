<?php
declare(strict_types = 1);

namespace CAH\News;

final class CAHNewsVueSetup
{
    private static $handle = "cah-news";

    public static function setup()
    {
        add_action('wp_enqueue_scripts', [__CLASS__, 'registerScripts'], 5, 0);
        add_action('wp_enqueue_scripts', [__CLASS__, 'maybeLoadScripts'], 10, 0);

        add_shortcode(static::$handle, [__CLASS__, 'shortcode']);
    }

    public static function registerScripts()
    {
        $uri = CAH_NEWS__PLUGIN_URI . "/dist";
        $path = CAH_NEWS__PLUGIN_DIR . "/dist";

        wp_register_script(
            static::$handle . "-vue-chunk",
            "$uri/js/" . static::$handle . "-vue-chunk-vendors.js",
            [],
            filemtime("$path/js/" . static::$handle . "-vue-chunk-vendors.js"),
            true
        );

        wp_register_script(
            static::$handle . "-vue-script",
            "$uri/js/" . static::$handle . "-vue-app.js",
            [static::$handle . "-vue-chunk"],
            filemtime("$path/js/" . static::$handle . "-vue-app.js"),
            true
        );

        wp_register_style(
            static::$handle . "-vue-style",
            "$uri/css/" . static::$handle . "-vue.css",
            [],
            filemtime("$path/css/" . static::$handle . "-vue.css"),
            'all'
        );
    }

    public static function maybeLoadScripts()
    {
        global $post;
        if (!isset($post) || !is_object($post)) {
            return;
        }

        if (stripos($post->post_content, "[" . static::$handle) !== false) {
            wp_enqueue_script(static::$handle . "-vue-script");
            wp_localize_script(
                static::$handle . "-vue-script",
                'wpVars',
                [
                    'restUri' => CAH_NEWS__BASE_URL . "wp-json/wp/v2",
                    'wpNonce' => wp_create_nonce('cah-news'),
                ]
            );
            wp_enqueue_style(static::$handle . "-vue-style");
        }
    }

    public static function shortcode($atts = [])
    {
        $atts = shortcode_atts([
            'dept' => defined('DEPT') ? \DEPT : 11,
            'limit' => -1,
            'per_page' => 20,
            'view' => 'full',
            'cat'  => [],
            'exclude' => [],
            'section_title' => "In the News",
            'section_title_classes' => '',
            'button_text' => "More News",
            'button_classes' => '',
            'button_href' => 'https://news.cah.ucf.edu/newsroom',
            'new_tab' => false,
            'tags' => '',
        ], $atts);

        if (is_string($atts['dept']) && stripos($atts['dept'], ',') !== false) {
            $atts['dept'] = explode(',', $atts['dept']);
        }

        foreach(array_keys($atts) as $key) {
            if ($atts[$key] == "true" || $atts[$key] == "false") {
                $atts[$key] = $atts[$key] == "true" ? true : false;
            }
            if (in_array($key, ['dept', 'limit', 'per_page'])) {
                if (!is_array($atts[$key])) {
                    $atts[$key] = intval($atts[$key]);
                } else {
                    $atts[$key] = array_map('intval', $atts[$key]);
                }
            }
        }

        ob_start();
        ?>
        <input type="hidden" id="cah-news-vue-options" value="<?= htmlentities(json_encode($atts)) ?>">
        <div id="cah-news-app"></div>
        <?php
        return ob_get_clean();
    }

    public static function ajax()
    {
        if (!isset($_POST['wpNonce'])
            || empty($_POST['wpNonce'])
            || !check_ajax_referer('cah-news', 'wpNonce')
        ) {
            die("Nonce failed to verify");
        }
    }
}
