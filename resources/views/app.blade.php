<!DOCTYPE html>
<html lang="en"  ng-app="main">
<head>
	@include('layout-partials/head')
</head>
<body>

<div class="page-container-wrapper">
	<div class="content">
		<div class="u-fillHeight u-fillWidth">
			@yield('content')
		</div>
	</div>
</div>

@include('layout-partials/scripts')

</body>
</html>
