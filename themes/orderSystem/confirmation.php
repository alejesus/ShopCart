<?php
$v->layout('orderSystem/_template', ['title' => 'Confirmation']);
header('Location: ' . $goToUrl);
?>

	<h1>CONFIRMATION FILE</h1>

	<div>
		<h1>Parabéns! Sua compra foi realizada com sucesso!</h1>
		<p>Você receberá um e-mail com todas as informações da sua compra.</p>
		<?=(!empty($session->payment_link) ? '<a title="" target="_blank" href="' . $session->payment_link . '">Imprimir Boleto</a>' : '');?>
	</div>

<?php $v->start('scripts');?>
<?php require path('js/confirmation.php');?>
<?php $v->end();?>