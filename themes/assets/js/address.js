$(function(){


	$('body').on('submit', 'form', function(e){

		e.preventDefault();

		var data = $(this).serializeArray();
		var url = $('[data-action]').data()['action'];

		console.log(data, url);

		$.post(url, data, function(address){
			console.log(address);

			if(address){
				window.location.href = address;
			}

		}, 'json');

	});

	$.post("<?=$router->route('address.showaddress');?>", function(data){
		console.log(data);
	}, 'json');

})
