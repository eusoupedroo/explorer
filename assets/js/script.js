var time;

$(document).ready(function() {


	$(".result").on("click", function() {
		
		var id = $(this).attr("data-linkId");
		var url = $(this).attr("href");

		if(!id) {
			alert("data-linkId attribute not found");
		}

		increaseLinkClicks(id, url);

		return false;
	});


	var grid = $(".imageResults");

	grid.on("layoutComplete", function(){ // layoutComplete is a function of masonry library, when the calculate is done, we can call this function and he will make the css comand on gridItem images
		$(".gridItem img").css("visibility", "visible");
	});

	grid.masonry({
		itemSelector: ".gridItem",
		columnWidth: 200,
		gutter: 5,
		isInitLayout: false
	});

	$("[data-fancybox]").fancybox();

});

function loadImage(src, className){
	var image = $("<img>"); // creating a image object in jquery

	image.on("load", function() {
		$("." + className + " a").append(image);

		clearTimeout(time); // cancel the global variable 'time'

		timer = setTimeout(function(){ // in this, we define the time that the images will be loaded, in this case, the images will be loaded in half second
			$(".imageResults").masonry();
		}, 500);
		
	});

	image.on("error", function() {
		$("." + className).remove();
		$.post("ajax/brokenImage.php", {src: src})
	});

	image.attr("src", src); // passing the src of the image in our object image
}	

function increaseLinkClicks(linkId, url) {

	$.post("ajax/updateLinkCount.php", {linkId: linkId})
	.done(function(result) {
		if(result != "") {
			alert(result);
			return;
		}

		window.location.href = url;
	});

}