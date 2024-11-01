<?php
/**
 * Plugin Name: TCP Referrer Shortcode
 * Plugin URI:
 * Description: Replace shortcode with referrer by using [tcp_referer]
 * Version: 1.1.0
 * Stable tag: 1.1.0
 * Requires PHP: 5.6
 * Requires at least: 5.5
 * Tested up to: 5.8
 * Author: TCP Team
 * Author URI: https://www.thecartpress.com
 * WC tested up to: 5.9.0
 */
defined('ABSPATH') or exit;

class TCP_referrer_shortcode {

	const TCP_REFERER_COOKIE_KEY = 'tcp_referer_domain';
	const TCP_REFERER_SHORTCODE = 'tcp_referer';

	function __construct() {
		$tcp_f = __DIR__ . '/tcp.php';
		if (file_exists($tcp_f)) {
			require_once $tcp_f;
		}
		if (is_admin() && class_exists('TCP_Menu')) {
			if (method_exists('TCP_Menu', 'add_submenu')) {
				TCP_Menu::add_submenu([
					'plugin_id' => 'tcp-referrer-shortcode',
					'page_title' => 'TCP Referrer Shortcode',
					'menu_title' => 'Referrer Shortcode',
					'menu_slug' => 'tcp_referrer_shortcode',
					'function' => [$this, 'create_admin_page'],
				]);
			} else {
				add_action('admin_menu', [$this, 'admin_menu'], 20);
			}
		}
		add_action('template_redirect', array($this, 'tcp_referer_shortcode_init'));
		add_shortcode(self::TCP_REFERER_SHORTCODE, array($this, 'tcp_show_referer'));
		add_filter('strip_shortcodes_tagnames', array($this, 'tcp_rd_filter_strip_shortcodes_tagnames'), 10, 2);
	}

	// define the strip_shortcodes_tagnames callback
	function tcp_rd_filter_strip_shortcodes_tagnames($tags_to_remove, $content) {
		$allowed_shortcodes = array(self::TCP_REFERER_SHORTCODE);
		foreach ($allowed_shortcodes as $tag) {
			if (($key = array_search($tag, $tags_to_remove)) !== false) {
				unset($tags_to_remove[$key]);
			}
		}
		return $tags_to_remove;
	}

	function tcp_show_referer($atts = [], $content = null, $tag = '') {
		$atts = array_change_key_case((array) $atts, CASE_LOWER);
		// override default attributes with user attributes
		$tcpreferershortcode_atts = shortcode_atts(
			array(
				'default' => '',
				'linkify' => 0
			), $atts, $tag
		);

		$default_shortcode = esc_html__($tcpreferershortcode_atts['default'], 'tcpreferershortcode');
		$convert_to_link = $tcpreferershortcode_atts['linkify'];
		$value = $this->tcp_referer_get_referrer();
		$final_result = '';
		// check if referer is same with cookie referer
		if (isset($_COOKIE[self::TCP_REFERER_COOKIE_KEY]) && empty($value)) {
			// getting domain key
			$final_result = sanitize_text_field($_COOKIE[self::TCP_REFERER_COOKIE_KEY]);
		} else if (!empty($value)) {
			$final_result = $value;
		} else if (!empty($default_shortcode)) {
			$final_result = $default_shortcode;
		}

		if ($convert_to_link) {
			return $this->tcp_referer_convert_to_link($final_result);
		}

		return esc_html__($final_result, 'tcpreferershortcode');
	}

	function tcp_referer_convert_to_link($host) {
		return '<a href="https://' . $host . '">' . $host . '</a>';
	}

	// get referer host or utm_source and set into cookie
	function tcp_referer_shortcode_init() {
		// Exit function if doing an AJAX request
		if (defined('DOING_AJAX') && DOING_AJAX) {
			return;
		}
		if (is_admin()) {
			return;
		}

		$expiry = strtotime('+12 month');
		$value = $this->tcp_referer_get_referrer();
		if (!empty($value)) {
			setcookie(self::TCP_REFERER_COOKIE_KEY, $value, $expiry, '/', '.' . $this->tcp_referer_strip_subdomain($_SERVER['HTTP_HOST']));
		}
	}

	function tcp_referer_strip_subdomain($host) {
		return preg_replace("/.*?([^\.]+)(\.((com?\.\w+)|\w+))$/i", '\1\2', $host);
	}

	function tcp_referer_get_referrer() {
		$referer_parse = parse_url($_SERVER['HTTP_REFERER']);
		if (isset($_GET['utm_source'])) {
			$utm_source = sanitize_text_field($_GET['utm_source']);
		}
		$value = '';
		// check if referer is not from current site
		if (!empty($utm_source)) {
			$value = $utm_source;
		} else if (!empty($referer_parse["host"]) && $this->tcp_referer_strip_subdomain($referer_parse["host"]) != $this->tcp_referer_strip_subdomain($_SERVER['HTTP_HOST'])) {
			$value = $referer_parse["host"];
		}

		return $value;
	}

	function admin_menu() {
		add_submenu_page(
			'thecartpress',
			'TCP Referrer Shortcode',
			'Referrer Shortcode',
			'manage_options',
			'tcp_referrer_shortcode',
			[$this, 'create_admin_page']
		);
	}

	function create_admin_page() {
		if (!function_exists('get_plugin_data')) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$plugin = get_plugin_data(__FILE__);
		?>
		<div class="wrap">
			<h2><?php echo $plugin['Name']; ?></h2>
			<p>Version <?php echo $plugin['Version']; ?></p>
			<p><?php echo $plugin['Description']; ?></p>
		</div><?php
	}

}

new TCP_referrer_shortcode();

