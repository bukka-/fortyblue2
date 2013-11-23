myApp.directive('contentItem', function ($compile) {
	var imageTemplate = '<div class="entry-image"><div class="entry-img">{{content.data}}, {{content.title}}, {{content.description}}</div>';
	var videoTemplate = '<div class="entry-video"><div class="entry-vid">{{content.data}}, {{content.title}}, {{content.description}}</div>';
	var noteTemplate = '<div class="entry-note"><div class="entry-note">{{content.data}}, {{content.title}}, {{content.description}}</div>';

	var getTemplate = function(contentType) {
		var template = '';

		switch(contentType) {
			case 'image':
				template = imageTemplate;
				break;
			case 'video':
				template = videoTemplate;
				break;
			case 'notes':
				template = noteTemplate;
				break;
		}

		return template;
	}

	var linker = function(scope, element, attrs) {
		scope.rootDirectory = 'images/';

		element.html(getTemplate(scope.content.content_type)).show();

		$compile(element.contents())(scope);
	}

	return {
		restrict: "E",
		replace: true,
		link: linker,
		scope: {
			content:'='
		}
	};
});