<script>
	var BACKEND_CFG = {!! App\Services\ViewHelpers::getBackendCfg() !!};
</script>

<!-- inject:vendor:js-->

<script src="/bower_components/jquery/dist/jquery.min.js"></script>

<script src="/bower_components/underscore/underscore-min.js"></script>

<script src="/bower_components/modernizr/modernizr.js"></script>

<script src="/bower_components/angular/angular.js"></script>

<script src="/bower_components/angular-ui-router/release/angular-ui-router.min.js"></script>

<script src="/bower_components/angular-sanitize/angular-sanitize.min.js"></script>

<script src="/bower_components/ngDialog/js/ngDialog.min.js"></script>

<script src="/bower_components/angular-animate/angular-animate.min.js"></script>

<script src="/bower_components/angular-file-upload/angular-file-upload.min.js"></script>

<script src="/bower_components/angular-loading-bar/build/loading-bar.min.js"></script>

<script src="/bower_components/slick-carousel/slick/slick.js"></script>

<script src="/bower_components/angular-slick/dist/slick.min.js"></script>

<script src="/bower_components/angular-notification/angular-notification.min.js"></script>

<script src="/bower_components/angular-ui-notification/dist/angular-ui-notification.min.js"></script>

<!-- endinject-->

<!-- inject:app:js-->

<script src="/app/components/utils.js"></script>

<script src="/app/components/notify.js"></script>

<script src="/app/services/services.js"></script>

<script src="/app/filters/common.js"></script>

<script src="/app/modules/main/auth/auth.js"></script>

<script src="/app/directives/form/form.js"></script>

<script src="/app/directives/main/main.js"></script>

<script src="/app/public.js"></script>

<!-- endinject-->



@if(Request::path() == '/')
	<script src="/js/parallax.js"></script>
@endif
