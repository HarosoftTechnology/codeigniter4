<?php

use App\Controllers\BaseController;
use CodeIgniter\Config\Services;
use App\Libraries\Menu;
use Config\Services\Session;

if (!defined('APPPATH'))
  exit('No direct script access allowed');
  $session = \Config\Services::session();
$CI_INSTANCE = [];  // It keeps a reference to global CI instance

function register_ci_instance(\App\Controllers\BaseController &$_ci)
{
  global $CI_INSTANCE;
  $CI_INSTANCE[0] = &$_ci;
}

function &get_instance(): \App\Controllers\BaseController
{
  global $CI_INSTANCE;
  return $CI_INSTANCE[0];
}

if (! function_exists('route_url')) {
  /**
   * Generate the full browser URL from a route alias.
   *
   * @param string $alias  The alias name of the route.
   * @param array  $params Optional array of parameters for routes with placeholders.
   *
   * @return string|null Full URL (e.g., https://example.com/about-us) or null if the alias does not exist.
   */
  function url_to_pager(string $alias, array $params = []): ?string
  {
      // Get the routes service
      $routes = service('routes');

      // Generate the relative URI based on the alias and parameters
      $relativeUri = $routes->reverseRoute($alias, $params);

      // Return null if no route exists for the given alias
      if (!$relativeUri) {
          return null;
      }

      return base_url($relativeUri);
  }
}

/**
 * Function retrieve a menu item from a location by its id or alias.
 * @param string $location Location of the menu (eg, header, footer)
 * @param string $id The menu uri alias
 */
function get_menu($location, $id = null) {
	return Menu::getMenu($location, $id);
}

/**
 * Function to get the menus for a location
 * @param string $location
 * @return array
 */
function get_menus($location) {
	return Menu::getAllMenus($location);
}

function getCurrentPageAlias()
{
    $router = Services::router();
    return $router->getMatchedRouteOptions()['as'] ?? null;
}

/**
 * Function to set a flashdata message
 * @param string $message The message
 * @param array $flash The expected array keys: ['type', 'dismiss', 'closebutton', 'alert', 'class', 'id']
 * @return void
 */
function setFlashdata($message, $flash = [])
{
    if ($message) {
        $id = $flash['id'] ?? "flasher-message";
        session()->setFlashdata($id, [
            'message'     => $message,
            'type'        => $flash['type'] ?? 'info',
            'dismiss'     => $flash['dismiss'] ?? true,
            'closebutton' => $flash['closebutton'] ?? false,
            'alert'       => $flash['alert'] ?? true,
            'class'       => $flash['class'] ?? '',
        ]);
    }
}

/**
 * Function to retrieve flashdata message and render it as an HTML string.
 * @param string $id Identifier for the flashdata (default is "flasher-message")
 * @return string|null Returns the flash message HTML, or null if there is no message.
 */
function flashdata($id = "flasher-message")
{
    // Retrieve flashdata using CodeIgniter's session service.
    $flash = session()->getFlashdata($id);

    if (empty($flash) || empty($flash['message'])) {
        return null;
    }

    $message = $flash['message'];
    $type = $flash['type'] ?? 'info';
    $type = ($type === 'error') ? 'danger' : $type;

    // Determine the alert CSS class.
    if (isset($flash['alert'])) {
        if (is_bool($flash['alert'])) {
            // If alert is boolean, true becomes "alert", false becomes an empty string.
            $alert = $flash['alert'] ? 'alert' : '';
        } elseif (is_string($flash['alert'])) {
            $alert = $flash['alert'];
        } else {
            $alert = '';
        }
    } else {
        $alert = '';
    }

    // Process additional CSS classes.
    if (isset($flash['class'])) {
        $className = is_array($flash['class']) ? implode(' ', $flash['class']) : $flash['class'];
    } else {
        $className = '';
    }

    // Determine dismiss class if necessary.
    $dismissClass = (isset($flash['dismiss']) && $flash['dismiss'] === true)
        ? 'alert-dismissible'
        : '';

    // Build the final HTML output.
    // Example (good for Bootstrap-styled alerts):
    // The final element might have classes like: "alert alert-success alert-success-dismissible text-red-500"
    $html = "<div class='text-center mb-1 {$className} {$alert} {$alert}-{$type} {$dismissClass}'>";

    // Optionally add a close button if enabled.
    if (isset($flash['closebutton']) && $flash['closebutton'] === true) {
        $html .= "<button type='button' class='close' data-dismiss='alert' aria-label='Close'>";
        $html .= "<span aria-hidden='true'>&times;</span>";
        $html .= "</button>";
    }

    // Escape the message content.
    $html .= htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
    $html .= "</div>";

    return $html;
}

function add_flash($flash = array()) {
	if($flash and isset($flash['message'])) {
		$id = (isset($flash['id'])) ? $flash['id'] : "flash-message";
		$message = serialize($flash['message']);
		session_put($id, $message);
    // session_put("{$id}-message", true);
      if(isset($flash['type'])) session_put($id.'-type', $flash['type']);

      session_put($id.'-dismiss', true);
      if(isset($flash['dismiss'])) session_put($id.'-dismiss', $flash['dismiss']);

		session_put($id.'-closebutton', true);
        if(isset($flash['closebutton'])) session_put($id.'-closebutton', $flash['closebutton']);

		session_put($id.'-position', "top-right");
        if(isset($flash['position'])) session_put($id.'-position', $flash['position']);
	}
}

/**
 * Function to check flash data
 * @param string $id
 */
function has_flash($id) {
	$data = session_get($id);
	if($data) return true;
	return false;
}

/**
 * Function to flash data
 * @param string $id
 */
function get_flash($id) {
	$data = array(
        'message' => session_get($id),
        'type' => session_get($id.'-type'),
        'dismiss' => session_get($id.'-dismiss'),
        'closebutton' => session_get($id.'-closebutton'),
        'position' => session_get($id.'-position'),
		'alert' => session_get($id.'-alert'),
		'class' => session_get($id.'-class')
    );
	//if($data) $data = unserialize($data);
	session_forget($id);
	session_forget($id.'-type');
	session_forget($id.'-dismiss');
	session_forget($id.'-closebutton');
	session_forget($id.'-alert');
	session_forget($id.'-position');
	session_forget($id.'-class');
	return $data;
}

/**
 * Function to put data into the session
 * @param string $name
 * @param string $value
 * @return boolean
 */
function session_put($name, $value = "") {
	$_SESSION[$name] = $value;
	return true;
}

/**
 * Function to get value from a session
 * @param string $name
 * @param string $default
 * @return string
 */
function session_get($name, $default = false) {
	if(!isset($_SESSION[$name])) return $default;
	return $_SESSION[$name];
}

/**
 * Function to remove data from the session
 * @param string $name
 * @return boolean
 */
function session_forget($name) {
	if(isset($_SESSION[$name])) unset($_SESSION[$name]);
	return true;
}

//redirect to a pager
function redirect_to_pager($id, $param = array(), $flash = array()) {
	$url = url_to_pager($id, $param);
	add_flash($flash);
	return redirect()->to($url);
}

/**
 * Function to redirect by link
 * @param string $url The url you want to redirect to
 * @param array $flash array('id' => 'flash-message-id', 'message' => '')
 * @return mixed
 */
function redirect_to($url, $flash = array()) {
	add_flash($flash);
	@session_write_close();
	//@session_regenerate_id(true);
	header("Location:".$url);
	exit;
}

/**
 * @param array $flash
 */
function redirect_back($flash = array()) {
	$back = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : '';
	add_flash($flash);
	if(empty($back) and !preg_match("#".config("base_url")."#", $back)) redirect(base_url());
	redirect_to($back);
}

if (! function_exists('set_meta_tags')) {
    /**
     * Set (i.e., register) custom meta tags.
     *
     * @param array $customMetaTags
     * @return array The merged meta tags.
     */
    function set_meta_tags(array $customMetaTags): array {
        return \App\Libraries\MetaTags::getInstance()->setMetaTags($customMetaTags);
    }
}

if (! function_exists('get_meta_tags_array')) {
    /**
     * Get the current meta tags.
     *
     * @return array
     */
    function get_meta_tags_array(): array {
        return \App\Libraries\MetaTags::getInstance()->getMetaTags();
    }
}

if (! function_exists('render_meta_tags')) {
    /**
     * Render the meta tags as HTML.
     *
     * @return string
     */
    function render_meta_tags(): string {
        return \App\Libraries\MetaTags::getInstance()->renderMetaTags();
    }
}



