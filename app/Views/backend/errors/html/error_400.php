<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= lang('Errors.badRequest') ?></title>
</head>
<body>
    <style type="text/css">
        .wrap{
            margin:0 auto;
            width:100%;
            text-align:center;
            padding: 5% 0;
            font-family: 'Love Ya Like A Sister', cursive;
        }
        .code{
            font-size:10em;
        }
        .code span {
            color:#93BA09
        }
    </style>
    <div class="container-xxl flex-grow-1 container-p-y page-content">
        <div class="wrap">
            <div style="color:#272727; font-size:40px; ">Page Not Found<?php // echo nl2br(esc($message)) ?></div>
            <div class="code"><span>4</span>0<span>0</span></div>
            <div>
                <?php if (ENVIRONMENT !== 'production') : ?>
                    <?= nl2br(esc($message)) ?>
                <?php else : ?>
                    <?= lang('Errors.sorryBadRequest') ?>
                <?php endif ?>
            </div>
        </div>
    </div>
</body>
</html>


