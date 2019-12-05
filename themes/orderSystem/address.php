<?php
$v->layout('orderSystem/_template', ['title' => 'Delivery Address']);
header('Location: ' . $goToUrl);
?>

<style>
	h1, p, label{
		color: #fff;
	}
</style>

<h1>ADDRESS FILE</h1>;

<form method="post" name="form" data-action="<?=$router->route('address.add');?>">

	<label>Nome: <input id="name" type="text" name="name" value="" placeholder="Ex. Minha Casa" required></label><br>
	<label>CEP: <input id="cep" type="text" name="cep" value="" required></label><br>
	<label>Logradouro: <input id="logradouro" type="text" name="logradouro" value="" placeholder="Nome da Rua:" required></label><br>
	<label>Número: <input id="number" type="text" name="number" value="" required></label><br>
	<label>Complemento: <input id="complement" type="text" name="complement" value="" required></label><br>
	<label>Bairro: <input id="bairro" type="text" name="bairro" value="" required></label><br>
	<label>Cidade: <input id="city" type="text" name="city" value="" required></label><br>
	<label>Estado: <input id="uf" type="text" name="uf" value="" required></label><br>

	<button>Continuar</button>

</form>

<?php $v->start('scripts'); ?>

<script src="<?=asset('js/address.js')?>"></script>

<?php $v->end(); ?>