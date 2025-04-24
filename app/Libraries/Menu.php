<?php
namespace App\Libraries;

namespace App\Libraries;

class Menu {
    /*-------------------------------------------------
     * Instance Properties (per menu item)
     *-------------------------------------------------*/
    public $id;
    public $title;
    public $link;
    public $icon = '';
    public $badgecount = '';
    public $badge_bgcolor = '';
    public $ajax = true;  // Determines if AJAX navigation is enabled.
    public $tab = false;  // Determines if the link should open in a new tab.
    public $children = []; // Child menus.
    public $active = false; // True if this item or any of its children is active.

    /*-------------------------------------------------
     * Static Properties (global menu management)
     *-------------------------------------------------*/
    // Menus stored by location (e.g., "main-menu", "footer-menu")
    protected static $menus = [];
    // List of available menu locations with descriptions.
    protected static $menuLocations = [];
    // Available menus registration.
    protected static $availableMenus = [];

    /**
     * Constructor.
     *
     * @param string      $title         The display title for the menu item.
     * @param string|null $link          The URL (defaults to '#' if null).
     * @param string      $id            Optional unique identifier.
     * @param string      $icon          Optional icon CSS class.
     * @param string      $badgecount    Optional badge count.
     * @param string      $badge_bgcolor Optional badge background color.
     * @param bool        $ajaxify       Optional flag for AJAX navigation.
     * @param bool        $open_new_tab  Optional flag to open the link in a new tab.
     */
    public function __construct(
        string $title, 
        ?string $link = "#", 
        string $id = "", 
        string $icon = "", 
        string $badgecount = "", 
        string $badge_bgcolor = "", 
        bool $ajaxify = true, 
        bool $open_new_tab = false
    ) {
        $this->title         = $title;
        $this->link          = $link ?? "#"; // Guarantee a valid string.
        $this->id            = $id;
        $this->icon          = $icon;
        $this->badgecount    = $badgecount;
        $this->badge_bgcolor = $badge_bgcolor;
        $this->ajax          = $ajaxify;
        $this->tab           = $open_new_tab;
    }

    /*=================================================
     * Instance (Non-static) Methods:
     * Methods for handling individual menu item operations
     *=================================================*/

    /**
     * Add a submenu (child item) to this menu item.
     *
     * @param string      $title      The submenu title.
     * @param string|null $link       The submenu link.
     * @param string|null $alias      Optional alias/identifier.
     * @param array       $attributes Array with extra attributes: 'icon', 'badgecount', 'badge_bgcolor', 'ajax', 'tab'.
     * @return Menu The newly created submenu item.
     */
    public function addMenu(string $title, ?string $link, ?string $alias = null, array $attributes = []): Menu
    {
        // Guarantee $link is a valid string.
        $link = $link ?? "#";

        // Extract extra attributes with defaults.
        $icon         = $attributes['icon'] ?? '';
        $badgecount   = $attributes['badgecount'] ?? '';
        $badge_bgcolor= $attributes['badge_bgcolor'] ?? '';
        $ajaxify      = $attributes['ajax'] ?? true;
        $open_new_tab = $attributes['tab'] ?? false;

        $child = new Menu(
            $title,
            $link,
            $alias ?? uniqid(),
            $icon,
            $badgecount,
            $badge_bgcolor,
            $ajaxify,
            $open_new_tab
        );

        // Use the alias (or a newly generated unique id) as the key.
        $key = $alias ?? uniqid();
        $this->children[$key] = $child;
        return $child;
    }

    /**
     * Recursively check if this menu item or any of its children is active.
     *
     * @param string|null $currentUrl Current URL string to compare (if null, fetched automatically).
     * @return bool True if active.
     */
    public function isActive(?string $currentUrl = null): bool
    {
        // If not provided, get the current URL (assumes helper function current_url() exists).
        if ($currentUrl === null) {
            $currentUrl = current_url();
        }

        // Check if the item’s link matches the current URL.
        if ($this->link === $currentUrl) {
            $this->active = true;
        }

        // Recursively check child menu items.
        foreach ($this->children as $child) {
            if ($child->isActive($currentUrl)) {
                $this->active = true;
            }
        }
        return $this->active;
    }


    /**
     * Set this menu item as active.
     *
     * @param bool $value
     * @return Menu
     */
    public function setActive(bool $value = true): Menu {
        $this->active = $value;
        return $this;
    }

    /**
     * Recursively search for a menu item with the given ID among this item's children.
     *
     * @param string $id The ID or alias to search for.
     * @return Menu Returns the found Menu item or a new (empty) Menu if not found.
     */
    public function findMenu(string $id): Menu {
        foreach ($this->children as $child) {
            if ($child->id === $id) {
                return $child;
            }
            $found = $child->findMenu($id);
            if ($found->title !== '') {  // Using title to check if the item is non-empty.
                return $found;
            }
        }
        return new Menu('');
    }

    /**
     * Check if this menu item has any child menus.
     *
     * @return bool
     */
    public function hasChildren(): bool {
        return !empty($this->children);
    }

    /**
     * Recursively render this menu item (and its children) as HTML.
     *
     * @return string HTML <li> element for the menu.
     */
    public function render(): string {
        $activeClass = $this->active ? 'active' : '';
        $target      = $this->tab ? ' target="_blank"' : '';
        $html  = "<li class='{$activeClass}'>";
        $html .= "<a href='{$this->link}'{$target}>";
        if ($this->icon) {
            $html .= "<i class='{$this->icon}'></i> ";
        }
        $html .= "{$this->title}";
        if ($this->badgecount !== '') {
            $html .= " <span class='badge' style='background-color: {$this->badge_bgcolor};'>{$this->badgecount}</span>";
        }
        $html .= "</a>";

        if ($this->hasChildren()){
            $html .= "<ul>";
            foreach ($this->children as $child) {
                $html .= $child->render();
            }
            $html .= "</ul>";
        }
        $html .= "</li>";
        return $html;
    }


    /*=================================================
     * Static Methods:
     * Methods for global menu management
     *=================================================*/

    /**
     * Add a menu item to a specific location.
     *
     * Expects an associative array with keys: 'id', 'title', 'link', 'icon', 'badgecount',
     * 'badge_bgcolor', 'ajax', and 'tab'.
     *
     * @param string $location The menu location (e.g., 'main-menu').
     * @param array  $data     The menu data.
     * @param string|null $alias Optional key/alias.
     */
    public static function addMenuItem(string $location, array $data, ?string $alias = null): void
    {
        if (!isset(self::$menus[$location])) {
            self::$menus[$location] = [];
        }
        $key = $alias ?? ($data['id'] ?? uniqid());
        $menuItem = new Menu(
            $data['title']         ?? '',
            $data['link']          ?? '#',
            $data['id']            ?? $key,
            $data['icon']          ?? '',
            $data['badgecount']    ?? '',
            $data['badge_bgcolor'] ?? '',
            $data['ajax']          ?? true,
            $data['tab']           ?? false
        );
        self::$menus[$location][$key] = $menuItem;
    }

    /**
     * Retrieve a menu item from a location by its ID or alias.
     * If ID is null, it defaults to a value from getCurrentPageAlias().
     *
     * @param string      $location
     * @param string|null $id
     * @return Menu
     */
    public static function getMenu(string $location, ?string $id = null): Menu
    {
        if (isset(self::$menus[$location])) {
            $id = $id ?: getCurrentPageAlias();
            if (isset(self::$menus[$location][$id])) {
                return self::$menus[$location][$id];
            }
        }
        return new Menu('');
    }

    /**
     * Define a menu location.
     *
     * @param string $location    The internal key.
     * @param string $description A human-friendly description.
     */
    public static function addLocation(string $location, string $description): void
    {
        self::$menuLocations[$location] = $description;
    }

    /**
     * Add an available menu registration.
     *
     * @param string $id    The identifier.
     * @param string $link  Typically a URL or shortcut.
     * @param mixed  $other Optional extra information.
     */
    public static function addAvailableMenu(string $id, string $link, $other = null): void
    {
        self::$availableMenus[$id] = [
            'link'  => $link,
            'other' => $other,
        ];
    }

    /**
     * Retrieve all menu items for a given location.
     *
     * @param string $location
     * @return array
     */
    public static function getAllMenus(string $location): array
    {
        return self::$menus[$location] ?? [];
    }

    /**
     * Render the menus from a given location as an HTML list.
     * Marks active items by comparing each menu's link with the current URL.
     *
     * @param string $location
     * @return string
     */
    public static function renderMenu(string $location): string
    {
        $html = '';
        $menuItems = self::getAllMenus($location);
        if (!empty($menuItems)) {
            $currentUrl = current_url();
            foreach ($menuItems as $menuItem) {
                $menuItem->isActive($currentUrl);
            }
            $html .= "<ul class='menu {$location}'>";
            foreach ($menuItems as $item) {
                $html .= $item->render();
            }
            $html .= "</ul>";
        }
        return $html;
    }

    /**
     * Retrieve all menus for debugging or further processing.
     *
     * @return array
     */
    public static function getMenus() {
        return self::$menus;
    }
}
