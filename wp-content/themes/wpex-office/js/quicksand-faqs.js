jQuery(function($){
	$(document).ready(function() {
			
		var $filterType = $('#faqs-cats a.active').attr('rel');
		var $holder = $('ul.faqs-content');
		var $data = $holder.clone();
		
		$('#faqs-cats li a').click(function(e) {
		$('#faqs-cats a').removeClass('active');
			var $filterType = $(this).attr('rel');
			$(this).addClass('active');
			
			if ($filterType == 'all') {
				var $filteredData = $data.find('li.faqs-container');
			}
			else {
				var $filteredData = $data.find('li.faqs-container[data-type~=' + $filterType + ']');
			}
			
			$holder.quicksand($filteredData, {
				duration: 400,
				adjustHeight: "dynamic",
				easing: 'easeInOutQuad'
	
				}, function() {				
				$(".faqs-content").css("height", "auto");
				$(".faq-title").click(function(){
					$(this).toggleClass("active").next('.faq-content').stop(true, true).slideToggle("normal");
					return false;
				});					
		  	});
		  
		  return false;
		});
	});
});