<?php
$v->layout('orderSystem/_template', ['title' => 'Login']);
$next = filter_input(INPUT_GET, 'next', FILTER_DEFAULT);
header('Location: ' . $goToUrl);
?>

<h1>Login</h1>
<!-- comprador: c29430053830363582212@sandbox.pagseguro.com.br -->
<form method="post" name="form" data-action="<?=$router->route('identification.signin');?>">
	<input type="text" name="action" value="signin">
	<input type="text" name="next" value="<?=$next;?>">
	<label>E-mail: <input id="email" type="email" name="email" value="" required></label><br>
	<a href="<?=$router->route('order.login/recover') . (!empty($next) ? '&next=' . $next : '');?>" title="Recuperar Senha">Esqueceu?</a>
	<label>Senha: <input id="pass" type="password" name="pass" value="" required></label><br>
	<button>Continuar</button>
</form>

<p>Não tem cadastro? <a href="<?=$router->route('order.login/register') . (!empty($next) ? '&next=' . $next : '');?>" title="Cadastre-se">Cadastre-se</a></p>

<?php $v->start('scripts');?>
<script src="<?=asset('js/identification.js');?>"></script>
<?php $v->end();?>