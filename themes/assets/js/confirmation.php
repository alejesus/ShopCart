<script>

$(function(){
	/**
	* Exibe a sessão como os dados do pedido até o momento.
	* Displays the session as order data so far.
	**/
	$.post("<?=$router->route('identification.showidentification');?>", function(data){

		console.log(data);

	}, 'json');


	/**
	* Exibe a sessão como os dados do pedido até o momento.
	* Displays the session as order data so far.
	**/
	$.post("<?=$router->route('confirmation.clear');?>", function(data){

		console.log(data);

	}, 'json');
});

</script>