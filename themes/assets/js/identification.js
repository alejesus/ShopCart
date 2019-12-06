$(function(){

		/**
		* Submete o formulário com os dados de cadastro ou login do usuário.
		* Submit the form with user registration or login data.
		**/
		$('body').on('submit', 'form', function(e){
			e.preventDefault();

			var data = $(this).serializeArray();
			var url = $('[data-action]').data()['action'];

			$.post(url, data, function(identification){

				ajaxIdentification(identification);

			}, 'json');

		});

		/**
		* Função responsável pelas ações a serem executadas após determinada ação na página.
		* Role responsible for actions to be performed after a given action on the page.
		**/
		function ajaxIdentification(identification){

			if(identification.error){
				console.log(identification.error);
			}	

			if(identification.url){

				window.location.href = identification.url;

			}

			console.log(identification);
		}

	});