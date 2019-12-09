<?php
$v->layout('orderSystem/_template', ['title' => 'Recover']);
$next = filter_input(INPUT_GET, 'next', FILTER_DEFAULT);
header('Location: ' . $goToUrl);
?>

<h1>Recover</h1>
<!-- comprador: c29430053830363582212@sandbox.pagseguro.com.br -->
<form method="post" name="form" data-action="<?=$router->route('identification.recover');?>">
	<label>E-mail: <input id="email" type="email" name="email" value="" required></label><br>
	<button>Continuar</button>
</form>

<p>Lembrou da Senha? <a href="<?=$router->route('order.login');?>" title="Entrar">Entre</a></p>

<?php $v->start('scripts');?>
<script src="<?=asset('js/identification.js');?>"></script>
<?php $v->end();?>