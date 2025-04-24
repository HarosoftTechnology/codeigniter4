      </div>
    </div>
  </body>
  <script src="<?= base_url('public/js/jquery.js') ?>"></script>
  <script src="<?= base_url('public/js/toastr.js') ?>"></script>
  <script src="<?= base_url('public/js/ui-toasts.js') ?>"></script>
  <script src="<?= base_url('public/js/alpine.min.js') ?>"></script>
  <script src="<?= base_url('public/js/init-alpine.js') ?>"></script>
  <script src="<?= base_url('public/js/scripts.js') ?>"></script>

  <script type="text/javascript">
      let baseUrl = '<?= base_url() ?>';
      let requestToken = '<?= csrf_hash() ?>';
      let csrf_token = '<?= csrf_hash() ?>';
  </script>

  

</html>