<?php
declare(strict_types = 1);

namespace CAH\News;

final class CAHNewsVueSetup
{
    private static $handle = "cah-news-vue";

    public static function setup()
    {
        add_action('wp_enqueue_scripts', [__CLASS__, 'registerScripts'], 5, 0);
        add_action('wp_enqueue_scripts', [__CLASS__, 'maybeLoadScripts'], 10, 0);

        add_shortcode(static::$handle, [__CLASS__, 'shortcode']);

        add_action('wp_ajax_cah-news', [__CLASS__, 'ajax'], 10, 0);
        add_action('wp_ajax_nopriv_cah-news', [__CLASS__, 'ajax'], 10, 0);
    }

    public static function registerScripts()
    {
        $uri = CAH_NEWS__PLUGIN_URI . "/dist";
        $path = CAH_NEWS__PLUGIN_DIR . "/dist";

        wp_register_script(
            static::$handle . "-chunk",
            "$uri/js/" . static::$handle . "-chunk-vendors.js",
            [],
            filemtime("$path/js/" . static::$handle . "-chunk-vendors.js"),
            true
        );

        wp_register_script(
            static::$handle . "-script",
            "$uri/js/" . static::$handle . "-app.js",
            [static::$handle . "-chunk"],
            filemtime("$path/js/" . static::$handle . "-app.js"),
            true
        );

        wp_register_style(
            static::$handle . "-style",
            "$uri/css/" . static::$handle . ".css",
            [],
            filemtime("$path/css/" . static::$handle . ".css"),
            'all'
        );
    }

    public static function maybeLoadScripts()
    {
        $postContent = '';

        global $wp_query;
        $postObj = $wp_query->get_queried_object();

        if (isset($postObj)) {
            $postContent = $postObj->post_content;
        }


        if (stripos($postContent, "[" . static::$handle) !== false) {
            static::loadScripts();
        } elseif (stripos($postContent, "[ucf-section") !== false
            && preg_match_all('/\[ucf-section[^\]]+slug=[\'"]([\w_-]+)[\'"].*\]/', $postContent, $matches, PREG_PATTERN_ORDER)
        ) {
            if (isset($matches[1]) && !empty($matches[1])) {
                foreach ($matches[1] as $match) {
                    if (stripos($match, 'news') !== false) {
                        static::loadScripts();
                        break;
                    }
                }
            }
        }
    }

    public static function shortcode($atts = [])
    {
        $atts = shortcode_atts([
            'dept' => get_option('cah_news_display_dept2', []),
            'limit' => -1,
            'per_page' => 20,
            'view' => 'full',
            'cat'  => [],
            'exclude' => [],
            'section_title' => "In the News",
            'section_title_classes' => '',
            'button_text' => "More News",
            'button_classes' => '',
            'button_href' => 'https://cah.ucf.edu/news//newsroom',
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
            || !check_ajax_referer('cah-news', 'wpNonce', false)
        ) {
            die("Nonce failed to verify");
        }

        $restRequest = html_entity_decode($_POST['restRequest']);

        $restResponse = wp_remote_get($restRequest);

        if (is_wp_error($restResponse)) {
            error_log($restResponse->get_error_message());
            die("Error with REST request");
        }

        $news = json_decode(wp_remote_retrieve_body($restResponse));

        $data = [];
        foreach ($news as $newsItem) {
            $data[$newsItem->id] = $newsItem;
        }

        echo json_encode($data);
        die;
    }

    private static function loadScripts()
    {
        wp_enqueue_script(static::$handle . "-script");
        wp_localize_script(
            static::$handle . "-script",
            'wpVars',
            [
                'restUri' => CAH_NEWS__BASE_URL . "wp-json/wp/v2",
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'pluginUri' => CAH_NEWS__PLUGIN_URI,
                'wpNonce' => wp_create_nonce('cah-news'),
            ]
        );
        wp_enqueue_style(static::$handle . "-style");
    }
}
