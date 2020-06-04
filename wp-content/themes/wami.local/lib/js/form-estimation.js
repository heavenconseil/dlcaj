jQuery(function ($) {
  $('.page-template-tpl-estimation .intro__button').on('click', function (e) {
    e.preventDefault()
    $('.page-template-tpl-estimation .intro').hide()
    $('.page-template-tpl-estimation .formulaire_estimation').show()
  })
})

document.addEventListener( 'wpcf7mailsent', function( event ) {
	var this_url = $(".formulaire_estimation").data("url");	
    location = this_url+'merci/';
}, false );
