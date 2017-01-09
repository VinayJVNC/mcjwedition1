require([
	'jquery',
	],function($){
		$(document).ready(function(){
			$('.page-header,.header.content').wrapAll("<div class='staticFixedHeader'></div>");
			$("<div class='GutterSpace'></div>").insertAfter(".staticFixedHeader");
			
			/*Search transition*/

			$('input#search').click(function(){
				$('input#search').css('width','100%');
			});
			$('input#search').blur(function(){
				if($('input#search').val().length>=1){
					$('input#search').css('width','100%');
				}
				else{
					$('input#search').css('width','46px');
				}
			});

			/*End for search transition*/
		});
	});