$(function(){


	$('body').on('submit', 'form', function(e){

		e.preventDefault();

		var data = $(this).serializeArray();
		var url = $('[data-action]').data()['action'];

		console.log(data, url);

		$.post(url, data, function(address){
			console.log(address);

			if(address.error){
				console.log(address.error);
			}

			if(address.url){
				window.location.href = address.url;
			}

		}, 'json');

	});

	$.post("<?=$router->route('address.showaddress');?>", function(data){
		console.log(data);
	}, 'json');

})
