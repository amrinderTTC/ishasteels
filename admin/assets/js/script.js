$(document).ready(function(){
	let date = new Date();
	let currentyear = date.getFullYear();
	$('.currentyear').html(currentyear);
	(function(){ 
		let windowwidth = $(window).width();
		if(windowwidth < 1024){
			$('body').removeClass('sidebar-verticle');
		}
	})();
	$('.form-elem-checkbox-btn').each(function(){
		$(this).on('click', function(){
			$(this).siblings('input').trigger('click').toggleClass('active');
			$(this).toggleClass('active');
		});
	});

	$('.has-submenu').each(function(){
		$(this).children('a').click(function(e){
			e.preventDefault();
			let that = $(this);
			if(!($('body').hasClass('sidebar-verticle'))){
				if(!(that.closest('li').hasClass('active'))){
					that.closest('li').siblings('li.active').each(function(){
						$(this).removeClass('active');
						$(this).children('.submenu').slideUp(300);
					});
					that.closest('li').addClass('active');
					that.siblings('.submenu').slideDown(300);
				}else{
					that.closest('li').removeClass('active');
					that.siblings('.submenu').slideUp(300);
				}
			}
		});
		$(this).hover(
			function(){
				if($('body').hasClass('sidebar-verticle')){
					$(this).addClass('active');
					$(this).children('.submenu').slideDown(300);
				}
			},
			function(){
				if($('body').hasClass('sidebar-verticle')){
					$(this).removeClass('active');
					$(this).children('.submenu').slideUp(300);
				}
			}
		);
	});

	$('.menubar-toggler').click(function(){
		let windowwidth = $(window).width();
		if(windowwidth >= 1024){
			$('body').toggleClass('sidebar-verticle');
		}else{
			$('body').toggleClass('sidebar-open');
			$('.has-submenu').each(function(){
				$(this).children('.submenu').slideUp(10);
				$(this).removeClass('active');
			})
		}
	});

	$('#header-user').click(function(e){
		e.preventDefault();
		$('.userdrop').fadeIn(300);
		$('body').addClass('userdrop-active');
		return false;
	});
	
	$(document).on('click', '.userdrop-active', function(e){
		if(!(e.target.closest('.userdrop'))){
			e.preventDefault();
			$('.userdrop').fadeOut(300);
			$('body').removeClass('userdrop-active');
			return false
		}
	});
	$('#fullscreen').click(function() {
	  	toggleFullscreen();
	});
	document.addEventListener("fullscreenchange", function() {
		if(!document.fullscreenElement){
			$('#fullscreen').children('.bi').removeClass('bi-fullscreen-exit').addClass('bi-fullscreen');
		}else{
			$('#fullscreen').children('.bi').addClass('bi-fullscreen-exit').removeClass('bi-fullscreen');
		}
	});
	
	document.addEventListener("webkitfullscreenchange", function() {
		if(!document.mozFullScreenElement){
			$('#fullscreen').children('.bi').removeClass('bi-fullscreen-exit').addClass('bi-fullscreen');
		}else{
			$('#fullscreen').children('.bi').addClass('bi-fullscreen-exit').removeClass('bi-fullscreen');
		}
	});
	
	document.addEventListener("mozfullscreenchange", function() {
		if(!document.webkitFullscreenElement){
			$('#fullscreen').children('.bi').removeClass('bi-fullscreen-exit').addClass('bi-fullscreen');
		}else{
			$('#fullscreen').children('.bi').addClass('bi-fullscreen-exit').removeClass('bi-fullscreen');
		}
	});

	document.addEventListener("msfullscreenchange", function() {
		if(!document.msFullscreenElement){
			$('#fullscreen').children('.bi').removeClass('bi-fullscreen-exit').addClass('bi-fullscreen');
		}else{
			$('#fullscreen').children('.bi').addClass('bi-fullscreen-exit').removeClass('bi-fullscreen');
		}
	});

	$('.expand').each(function(){
		$(this).click(function(){
			$(this).children('i').toggleClass('fa-plus fa-minus');
			$(this).closest('tr').next('.collapsible-row').children('.has-table').slideToggle(400)
		});
	});
});

function toggleFullscreen() {
	elem = document.documentElement;
	if (!document.fullscreenElement && !document.mozFullScreenElement &&
	  !document.webkitFullscreenElement && !document.msFullscreenElement) {
		if (elem.requestFullscreen) {
			elem.requestFullscreen();
		} else if (elem.msRequestFullscreen) {
			elem.msRequestFullscreen();
		} else if (elem.mozRequestFullScreen) {
			elem.mozRequestFullScreen();
		} else if (elem.webkitRequestFullscreen) {
			elem.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
		}
	} else {
		if (document.exitFullscreen) {
			document.exitFullscreen();
		} else if (document.msExitFullscreen) {
			document.msExitFullscreen();
		} else if (document.mozCancelFullScreen) {
			document.mozCancelFullScreen();
		} else if (document.webkitExitFullscreen) {
			document.webkitExitFullscreen();
		}
	}
  }
  
//   document.getElementById('exampleImage').addEventListener('click', function() {
// 	toggleFullscreen(this);
//   });
