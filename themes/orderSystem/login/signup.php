<?php
$v->layout('orderSystem/_template', ['title' => 'Sign Up']);
$next = filter_input(INPUT_GET, 'next', FILTER_DEFAULT);
header('Location: ' . $goToUrl);
?>

<style>
	h1, p, label{
		color: #fff;
	}
</style>

<h1>SignUp</h1>
<!-- comprador: c29430053830363582212@sandbox.pagseguro.com.br -->

<form method="post" name="form" data-action="<?=$router->route('identification.signup');?>">

	<input type="text" name="action" value="signup">
	<input type="text" name="next" value="<?=$next;?>">
	<label>E-mail: <input id="email" type="email" name="email" value="" required></label><br>
	<label>Senha: <input id="pass" type="password" name="pass" value="" required></label><br>
	<label>CPF: <input id="cpf" type="text" name="cpf" value="" required></label><br>
	<label>Nome e Sobrenome: <input id="name" type="text" name="name" value="" required></label><br>
	<label>Data de nascimento: <input id="birthDate" type="date" name="birthDate" value="" required></label><br>
	<label>Sexo: M<input id="m" type="radio" name="genre" value="m">F<input id="f" type="radio" name="genre" value="f" required></label><br>
	<label>Telefone: <input id="phone" type="tel" name="phone" value="" required></label><br>
	<label>Desejo receber notificações via WhatsApp: <input id="whatsApp" type="checkbox" name="whatsApp" value="1"></label><br>
	<label>Receber ofertas pelo e-mail: <input id="newsletter" type="checkbox" name="newsletter" value="1"></label><br>
	<button>Fazer Cadastro</button>

</form>

<p>Já tem um cadastro? <a href="<?=$router->route('order.login') . (!empty($next) ? '&next=' . $next : '');?>" title="Entrar">Entrar</a></p>

<?php $v->start('scripts'); ?>

<script src="<?=asset('js/identification.js')?>"></script>

<?php $v->end(); ?>