<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?= render_meta_tags() ?>
    <meta name="csrf-token-name" content="<?= csrf_token() ?>">
    <meta name="csrf-token-hash" content="<?= csrf_hash() ?>">

    <title><?= html_entity_decode($title, ENT_QUOTES) ?></title>
    
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
  <body>
