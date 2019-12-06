<script>
$(function(){

	/**
	* Cria sessão do pagseguro.
	* Create pagseguro's session
	**/
	$.post("<?=$router->route('payment.createsession');?>", function(data){

		if(data){
			$('#session').val(data);
			SESSION = $('#session').val();
			pagseguroInit();
		}


		if($('#payment').val() === 'creditCard'){
			pagSeguroCreditCard();
		}

	}, 'json');

	/**
	* Submite os dados de pagamento preenchidos no formulário.
	* Submit the completed payment details on the form.
	**/
	$('body').on('submit', 'form', function(e){
		e.preventDefault();

		var data = $(this).serializeArray();
		var url = $('[data-action]').data()['action'];

		$.post(url, data, function(payment){

			if(payment.error){
				console.log(payment.error);	
			}

			if(payment.url){
				window.location.href = payment.url;
			}

		}, 'json');


	});

	/**
	* Método responsável pelos dados necessários para inicialização do Pagseguro.
	* Method responsible for the data required for Pagseguro startup.
	**/
	function pagseguroInit(){

		PagSeguroDirectPayment.setSessionId(SESSION); // Seta Sessão

		// Recupera Métodos de Pagamentos Disponíveis
		PagSeguroDirectPayment.getPaymentMethods({
			amount: <?=floatval($_SESSION['cart']['total']) + floatval($_SESSION['shipping']['value']);?>,
			success: function(response) {
			    // Retorna os meios de pagamento disponíveis.
			    //console.log(response);
			},
			error: function(response) {
			    // Callback para chamadas que falharam.
			    // console.log(response);
			},
			complete: function(response) {
			    // Callback para todas chamadas.
			    // console.log(response);
			}
		});

		// Identificador com os dados do comprador
		$('body').on('mousemove', function(){

			var fieldHash = $('#hash');

			if(fieldHash.val() !== ''){
				return;
			}

			PagSeguroDirectPayment.onSenderHashReady(function(response){
			    if(response.status == 'error') {
			        // console.log(response.message);
			        return false;
			    }
			    var hash = response.senderHash; //Hash estará disponível nesta variável.
			    fieldHash.val(hash);
			});

		});

	}

	/**
	* Método responsável por gerar os dados necessários para o pagamento via Cartão de Crédito
	* (bandeira e número de parcelas do catão).
	* Method responsible for generating the necessary data for payment by Credit Card.
	* (flag and number of card installments).
	**/
	function pagSeguroCreditCard(){

		BRAND = '';
		var formater = Intl.NumberFormat("pt-BR", {
			style: "currency",
			currency: "BRL"
		});

		$('body').on('keyup', 'form #cardNumber', function(){

			var card = $(this).val();
			// var installments = $(this).val();
			var installmentsNoInterest = $('form').find('#noInterestInstallmentQuantity').val();
			var totalCart = $('form').find('#totalCart').val();
			var shippingCost = $('form').find('#shippingCost').val();
			var amount = parseFloat(totalCart) + parseFloat(shippingCost);

			// Recupera bandeira do cartão que está sendo editada
			PagSeguroDirectPayment.getBrand({
			    cardBin: card,
			    success: function(response) {
			      //bandeira encontrada
			      // console.log(response);
			      BRAND = response['brand']['name'];

			    },
			    error: function(response) {
			      //tratamento do erro
			      // console.log(response);
			    },
			    complete: function(response) {
			      //tratamento comum para todas chamadas
			     // console.log(response);
			    }
			});


			PagSeguroDirectPayment.getInstallments({
	        amount: amount,
	        maxInstallmentNoInterest: <?=NO_INTEREST_INSTALLMENTS;?>,
	        brand: BRAND,
	        success: function(response){

	        	// console.log(response['installments'][BRAND]);

	        	$('form #installmentQuantity').html('<option selected disabled value="">Parcelas</option>');

	        	$.each(response['installments'][BRAND], function(index, value){

				$('form #installmentQuantity').
				append('<option data-installment="'+value['installmentAmount']+'" data-totalamount="'+value['totalAmount']+'" value="'+value['quantity']+'">'+ value['quantity'] + 'x de '
					+ formater.format(value['installmentAmount']) + '</option>');
	        	});

	       },
	        error: function(response) {
	       	    // callback para chamadas que falharam.
	       	    // console.log(response);
	       },
	        complete: function(response){
	            // Callback para todas chamadas.
	            // console.log(response);
	       }
		});

	});

		// Exibe opções de parcelamentos disponíveis ao comprador

		$('body').on('change','form #installmentQuantity', function(){

			var totalAmount = $(this).find('option:selected').attr('data-totalamount');
			var installmentValue = $(this).find('option:selected').attr('data-installment');

			$('form').find('#installmentValue').val(installmentValue);
			$('form').find('#totalCart').val(totalAmount);

		});

		// Utiliza o dados do cartão de crédito para gerar um token
		$('body').on('keyup', '.j_credit_card', function(){

			var card = $('#cardNumber').val();
			var cvv = $('#cvv').val();
			var expirationMonth = $('#expirationMonth').val();
			var expirationYear = $('#expirationYear').val();

			// console.log(card, cvv, expirationMonth, expirationYear);

			PagSeguroDirectPayment.createCardToken({
			   cardNumber: card, // Número do cartão de crédito
			   brand: BRAND, // Bandeira do cartão
			   cvv: cvv, // CVV do cartão
			   expirationMonth: expirationMonth, // Mês da expiração do cartão
			   expirationYear: expirationYear, // Ano da expiração do cartão, é necessário os 4 dígitos.
			   success: function(response) {
			        // Retorna o cartão tokenizado.
			        //console.log(response);
			        $('form').find('#tokenCard').val(response.card.token);
			   },
			   error: function(response) {
					    // Callback para chamadas que falharam.
					    // console.log(response);
			   },
			   complete: function(response) {
			        // Callback para todas chamadas.
			        //console.log(response);
			   }
			});

		});


	}
});
</script>