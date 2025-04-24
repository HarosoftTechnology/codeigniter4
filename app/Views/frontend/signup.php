<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token-name" content="<?= csrf_token() ?>">
    <meta name="csrf-token-hash" content="<?= csrf_hash() ?>">


    <title><?= esc("Job Test - $pageTitle") ?></title>
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
    <div class="flex items-center min-h-screen p-6 bg-gray-50 dark:bg-gray-900">
      <div class="flex-1 h-full max-w-4xl mx-auto overflow-hidden bg-white rounded-lg shadow-xl dark:bg-gray-800">
        <div class="flex flex-col overflow-y-auto md:flex-row">
          <div class="h-32 md:h-auto md:w-1/2">
            <img src="<?= base_url('public/images/create-account.jpeg') ?>" aria-hidden="true" class="object-cover w-full h-full dark:hidden" alt="Office"/>
          </div>

          <div class="flex items-center justify-center p-6 sm:p-12 md:w-1/2">
            <div class="w-full">
              <h1 class="mb-4 text-xl font-semibold text-gray-700 dark:text-gray-200">Create account </h1>
              <?= form_open("", array('id' => 'SignupForm')); ?>
                  <label class="block text-sm">
                      <span class="">First Name</span>
                      <input type="text" class="form-input" value="Ola" name="firstname" placeholder="Ola" />
                  </label>
                  <label class="block text-sm">
                      <span class="">Last Name</span>
                      <input type="text" class="form-input" value="Faith" name="lastname" placeholder="Olanusi" />
                  </label>
                  <label class="block text-sm">
                      <span class="">Email</span>
                      <input type="email" class="form-input" value="ola@testing.com" name="email" placeholder="dele@testing.com" />
                  </label>
                  <label class="block text-sm">
                    <span>Password</span>
                    <div class="input-group">
                      <input type="password" value="123456" name="password" class="form-input" placeholder="***************" />
                      <span class="input-group-text password-visibility cursor-pointer"><i class="fa fa-eye"></i></span>
                    </div>
                  </label>
                  <label class="block text-sm">
                    <span>Confirm Password</span>
                    <div class="input-group">
                      <input type="password" value="123456" name="cpassword" class="form-input" placeholder="***************" />
                      <span class="input-group-text password-visibility cursor-pointer"><i class="fa fa-eye"></i></span>
                    </div>
                  </label>
                  
                  <button class="mt-6 w-full py-2 hover:bg-indigo-700 flex items-center justify-center hover:bg-purple-700 focus:shadow-outline focus:outline-none spin" data-send="false">
                  <i class="fa fa-spinner fa-spin"></i> <span class="ml-3">Create Account </span>
                  </button>
                <?= form_close() ?>

              <div class="text-center mt-4 text-sm font-medium text-purple-600">
                  <span> Already have an account? </span>
                  <a href="<?= url_to_pager("login") ?>"><span>Login</span></a>
              </div>

              <!-- <p id="message"></p> -->
              <?php // echo flashdata() ?>
              <?php echo (session()->has('validation')) ? session('validation')->listErrors() : null; ?>



              <?php // echo (session()->getFlashdata('validation')) ? "" : null ?>

            </div>
          </div>
        </div>
      </div>
    </div>
    </body>
  <script src="<?= base_url('public/js/jquery.js') ?>"></script>
  <script src="<?= base_url('public/js/toastr.js') ?>"></script>
  <script src="<?= base_url('public/js/ui-toasts.js') ?>"></script>
  <script src="<?= base_url('public/js/scripts.js') ?>"></script>

  <script type="text/javascript">
      let baseUrl = '<?= base_url() ?>';
      let requestToken = '<?= csrf_hash() ?>';
      let csrf_token = '<?= csrf_hash() ?>';
  </script>
</html>