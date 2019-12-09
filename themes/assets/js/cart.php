<script>
$(function(){
		/**
		* Executa a ação determinada pela rota em cada botão clicado no carrinho.
		* Performs the action determined by the route on each button clicked on the cart.
		**/
		$('[data-action]').click(function(e){
			e.preventDefault();

			var data = $(this).data();

			$.post(data.action, function(cart){

				ajaxCart(cart);

			}, 'json');

		});

		/**
		* Exibe a sessão de carrinho existente.
		* Displays the existing cart session.
		**/
		$.post("<?=$router->route('cart');?>", function(cart){
			ajaxCart(cart);
		}, 'json');

		/**
		* Função responsáveis pelas ações que serão executadas no carrinho.
		* Role responsible for the actions that will be performed on the cart.
		**/
		function ajaxCart(cart){
			console.log(cart);
			var cart_message = $('.cart_message');
			var cart_amount = $('.cart_amount');
			var cart_total = $('.cart_total');
			var formater = Intl.NumberFormat("pt-BR", {
				style: "currency",
				currency: "BRL"
			});


			if(cart.url){

				window.location.href = cart.url;
			}

			if(cart.message){

				cart_message.fadeOut(200, function(){
					$(this).html(cart.message).fadeIn(200);
				});

			}else{
				cart_message.fadeOut(200);
			}

			$('span[class^="item_"]').html("0");
			if(cart.items){

				$.each(cart.items, function(index, item){
					$('.item_' + item.id).html(item.amount);
				});

			}

			if(cart.amount){
				cart_amount.html(cart.amount);
			}else{
				cart_amount.html("0");
			}

			if(cart.total){
				cart_total.html(formater.format(cart.total));
			}else{
				cart_total.html("0,00");
			}

		}
	});
</script>