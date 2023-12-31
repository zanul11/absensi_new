<!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
<script src="{{asset('assets/js/libs/jquery-3.5.1.min.js')}}"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
<script src="{{asset('bootstrap/js/popper.min.js')}}"></script>
<script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>

<script src="{{asset('plugins/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
<script src="{{asset('assets/js/app.js')}}"></script>
<script>
  $(document).ready(function() {
    App.init();
    $("form").on('submit', function(e) {
      $(":submit").attr("disabled", true).text("Menyimpan..");
    });
  });
</script>
<script src="{{asset('assets/js/scrollspyNav.js')}}"></script>
<script src="{{asset('plugins/highlight/highlight.pack.js')}}"></script>
<script src="{{asset('assets/js/custom.js')}}"></script>

<script src="{{asset('plugins/font-icons/feather/feather.min.js')}}"></script>
<script type="text/javascript">
  feather.replace();
</script>
@stack("scripts")
<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS -->