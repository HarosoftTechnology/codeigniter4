<?php
use App\Libraries\Menu;

// Main menus
Menu::addMenuItem("main-menu", [
    "id"    =>  "home",
    "title" =>  "Home",
    "link"  =>  base_url(),
    "icon"  =>  "fa fa-home"
]);

Menu::addMenuItem("main-menu", [
    "id"    => "about",
    "title" => "About Us",
    "link"  => url_to_pager('about-us')
]);

Menu::addMenuItem("main-menu", [
    "id"    => "services",
    "title" => "Our Services",
    "link"  => url_to_pager('services')
]);

Menu::addMenuItem("main-menu", [
    "id"    => "pricing",
    "title" => "Pricing",
    "link"  => base_url('pricing')
]);

// Add customization parent and children
Menu::addMenuItem("main-menu", [
    "id"    => "customization",
    "title" => "Customization",
    "link"  => "#"  
]);

Menu::addMenuItem("main-menu", [
    "id"    => "contact",
    "title" => "Contact Us",
    "link"  => url_to_pager('contact-page')
]);

Menu::addMenuItem("admin-menu", [
    "id"    =>  "dashboard",
    "title" =>  "Home",
    "link"  =>  url_to_pager('dashboard'),
    "icon"  =>  "fa fa-home"
]);

Menu::addMenuItem("admin-menu", [
    "id"    => "add-task-page",
    "title" => "Add Task",
    "link"  => url_to_pager('create-task'),
    "icon"  => "fa fa-add"
]);

Menu::addMenuItem("admin-menu", [
    "id"    => "task-categories",
    "title" => "Categories",
    "link"  => url_to_pager('task-categories'),
    "icon"  => "fa fa-clone"
]);

Menu::addMenuItem("admin-menu", [
    "id"    => "logout",
    "title" => "Logout",
    "link"  => url_to_pager('logout'),
    "icon"  => "fa fa-sign-out"
]);

/** Example of how to get added menu with it children **/
// get_menu("admin-menu", "task-categories")->addMenu('New Ticket', base_url('admincp/tickets/new'), 'new');
// get_menu("admin-menu", "task-categories")->addMenu('Old Ticket', base_url('admincp/tickets/old'), 'old');

// get_menu("admin-menu", "task-categories")->addMenu("Ban Filters", "#", "ban-filters");
// get_menu("admin-menu", "task-categories")->findMenu("ban-filters")->addMenu("Telephone", url_to_pager("admin-ban-filters", array("type" => "telephone")), "telephone");
// get_menu("admin-menu", "task-categories")->findMenu("ban-filters")->addMenu("IP Address", url_to_pager("admin-ban-filters", array("type" => "ip")), "ip");

// Define other menus like footer, account, etc.
Menu::addLocation('main-menu', 'Main Menu');
Menu::addLocation('admin-menu', 'Main Menu');
