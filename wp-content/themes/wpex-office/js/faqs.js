jQuery(function($){
	$(document).ready(function(){
		$(".faq-title").click(function(){
			$(this).toggleClass("active").next('.faq-content').stop(true, true).slideToggle("normal");
			return false;
		});		
	});
});