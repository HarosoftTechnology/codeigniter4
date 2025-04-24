<!DOCTYPE html>
<html :class="{ 'theme-dark': dark }" x-data="data()" lang="en">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token-name" content="<?= csrf_token() ?>">
    <meta name="csrf-token-hash" content="<?= csrf_hash() ?>">
    <title><?= html_entity_decode($title, ENT_QUOTES) ?></title>
    <?= render_meta_tags() ?>
    <link rel="stylesheet" href="<?= base_url('public/css/font-awesome/css/font-awesome.min.css') ?>"/>
    <link rel="stylesheet" href="<?= base_url('public/css/tailwind.output.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('public/css/tailwind.min.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('public/css/toastr.css') ?>">
    <link rel="stylesheet" href="<?= base_url('public/css/style.css') ?>">

    <?php $flash = get_flash("flash-message")?>
    <?php $flashMessage = unserialize($flash['message']) ?>
    <?php $flash_type = $flash['type']; $flash_dismiss = $flash['dismiss']; $flash_position = $flash['position']; $flash_closebutton = $flash['closebutton'] ?>
    <?php if($flashMessage):?>
        <div style="display: none;" class="flash-message" data-type="<?= $flash_type ?>" data-dismiss="<?= $flash_dismiss ?>" data-position="<?= $flash_position ?>" data-closebutton="<?= $flash_closebutton ?>"><?= $flashMessage ?></div>
    <?php endif ?>
  </head>
  <body class="dashboard">
    <div class="flex h-screen bg-gray-50 dark:bg-gray-900" :class="{ 'overflow-hidden': isSideMenuOpen }">

      <?= view("layouts/menu") ?>

      <div class="flex flex-col flex-1 w-full">
        <header class="w-full z-10 py-4 bg-white shadow-md dark:bg-gray-800">
          <div class="container flex items-center justify-end h-full px-6 mx-auto text-purple-600 dark:text-purple-300">
            <ul class="flex items-center flex-shrink-0 space-x-6">
              <li class="relative">
                <button class="align-middle rounded-full focus:shadow-outline-purple focus:outline-none" @click="toggleProfileMenu" @keydown.escape="closeProfileMenu">
                  <img class="object-cover w-8 h-8 rounded-full" src="<?= base_url('images/avatar.png') ?>" alt="" aria-hidden="true" />
                </button>
                <template x-if="isProfileMenuOpen">
                  <ul class="absolute right-0 w-56 p-2 mt-2 space-y-2 text-gray-600 bg-white border border-gray-100 rounded-md shadow-md dark:border-gray-700 dark:text-gray-300 dark:bg-gray-700">
                    <li class="flex">
                      <a href="<?= base_url('logout') ?>" class="inline-flex text-red-500 items-center w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200">
                        <i class="fa fa-sign-out mr-2"></i> <span>Log out</span>
                      </a>
                    </li>
                  </ul>
                </template>
              </li>
            </ul>
            
            <button class="p-1 ml-4 w-10 rounded-md md:hidden focus:outline-none focus:shadow-outline-purple" @click="toggleSideMenu" aria-label="Menu">
              <i class="fas fa-navicon"></i>
            </button>

          </div>
        </header>