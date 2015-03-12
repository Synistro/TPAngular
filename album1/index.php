<!doctype html>
<html ng-app="app">
<head>
<meta charset="utf-8"/>
<title>Album 1</title>
<link rel="stylesheet" type="text/css" href="app.css"/>
<script type="text/javascript" src="../album_rsc/angular.min.js"></script>
<script type="text/javascript" src="app.js"></script>
</head>
<body>
<div id="global" ng-controller="AlbumCtr as album">
	<h1>album 1</h1>
	<ul>
		<li ng-repeat="photo in album.photos">
			<img ng-src="{{photo.urlV}}"/>
		</li>
	</ul>
</div>
</body>
</html>
