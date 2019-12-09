<?php
$v->layout('orderSystem/_template', ['title' => 'Payment']);
header('Location: ' . $goToUrl);
?>

	<h1>Payment FILE<h1>

	<!-- <form method="post" data-action="<?=$router->route('payment.billet');?>">

		<input id="session" type="text" name="session" value=""><br>
		<input type="text" name="paymentMode" value="default"><br>
		<input type="text" name="paymentMethod" value="boleto"><br>
		<input type="text" name="receiverEmail" value="<?=EMAIL_PAGSEGURO;?>"><br>
		<input type="text" name="currency" value="BRL"><br>
		<input type="text" name="extraAmount" value="<?=(!empty($carts['discount']) ? number_format(-($carts['subtotal'] * $carts['discount']), 2, '.', '') : number_format(0, 2, '.', ''));?>"><br>

		<?php $i        = 0;foreach ($carts['items'] as $cart): $i++;
    $priceDiscounted = (!empty($cart['discount']) ? $cart['price'] - ($cart['price'] * $cart['discount']) : $cart['price']);?>
						<input type="text" name="itemId<?=$i;?>" value="<?=$cart['id'];?>"><br>
						<input type="text" name="itemDescription<?=$i;?>" value="<?=$cart['product'];?>"><br>
						<input id="itemAmout" type="text" name="itemAmount<?=$i;?>" value="<?=number_format(str_replace(',', '.', $priceDiscounted), 2, '.', '');?>"><br>
						<input type="text" name="itemQuantity<?=$i;?>" value="<?=$cart['amount'];?>"><br>
					<?php endforeach;?>

		<input type="text" name="reference" value="<?=rand();?>"><br>

		<input type="text" name="senderName" value="<?=$user->name;?>"><br>
		<input type="text" name="senderCPF" value="<?=$user->cpf;?>"><br>
		<input type="text" name="senderAreaCode" value="<?=substr($user->phone, 0, 2);?>"><br>
		<input type="text" name="senderPhone" value="<?=substr($user->phone, 2);?>"><br>
		<input type="text" name="senderEmail" value="<?=$user->email;?>"><br>

		<input id="hash" type="text" name="senderHash" value=""><br>

		<input type="text" name="shippingAddressStreet" value="<?=$address->logradouro;?>"><br>
		<input type="text" name="shippingAddressNumber" value="<?=$address->number;?>"><br>
		<input type="text" name="shippingAddressComplement" value="<?=$address->complement;?>"><br>
		<input type="text" name="shippingAddressDistrict" value="<?=$address->bairro;?>"><br>
		<input type="text" name="shippingAddressPostalCode" value="<?=$address->cep;?>"><br>
		<input type="text" name="shippingAddressCity" value="<?=$address->city;?>"><br>
		<input type="text" name="shippingAddressState" value="<?=$address->uf;?>"><br>
		<input type="text" name="shippingAddressCountry" value="BRA"><br>
		<input type="text" name="shippingType" value="<?=SHIPMENT_TYPE[$shipping['type']];?>"><br>

		<input id="shippingCost" type="text" name="shippingCost" value="<?=number_format(str_replace(',', '.', $shipping['value']), 2, '.', '');?>"><br>

		<button>Pagar</button>

	 </form>
 -->

	<!-- <form method="post" data-action="<?=$router->route('payment.onlinedebit');?>">

		<input id="session" type="text" name="session" value=""><br>
		<input type="text" name="paymentMode" value="default"><br>
		<input type="text" name="paymentMethod" value="eft"><br>
		<input type="text" name="extraAmount" value="<?=(!empty($carts['discount']) ? number_format(-($carts['subtotal'] * $carts['discount']), 2, '.', '') : number_format(0, 2, '.', ''));?>"><br>

		Itaú: <input id="itaubank" type="radio" name="bankName" value="itau"><br>
		Bradesco: <input id="bradescobank" type="radio" name="bankName" value="bradesco"><br>

		<input type="text" name="receiverEmail" value="<?=EMAIL_PAGSEGURO;?>"><br>
		<input type="text" name="currency" value="BRL"><br>

		<?php $i        = 0;foreach ($carts['items'] as $cart): $i++;
    $priceDiscounted = (!empty($cart['discount']) ? $cart['price'] - ($cart['price'] * $cart['discount']) : $cart['price']);?>
						<input type="text" name="itemId<?=$i;?>" value="<?=$cart['id'];?>"><br>
						<input type="text" name="itemDescription<?=$i;?>" value="<?=$cart['product'];?>"><br>
						<input id="itemAmout" type="text" name="itemAmount<?=$i;?>" value="<?=number_format(str_replace(',', '.', $priceDiscounted), 2, '.', '');?>"><br>
						<input type="text" name="itemQuantity<?=$i;?>" value="<?=$cart['amount'];?>"><br>
					<?php endforeach;?>

		<input type="text" name="reference" value="<?=rand();?>"><br>

		<input type="text" name="senderName" value="<?=$user->name;?>"><br>
		<input type="text" name="senderCPF" value="<?=$user->cpf;?>"><br>
		<input type="text" name="senderAreaCode" value="<?=substr($user->phone, 0, 2);?>"><br>
		<input type="text" name="senderPhone" value="<?=substr($user->phone, 2);?>"><br>
		<input type="text" name="senderEmail" value="<?=$user->email;?>"><br>

		<input id="hash" type="text" name="senderHash" value=""><br>

		<input type="text" name="shippingAddressStreet" value="<?=$address->logradouro;?>"><br>
		<input type="text" name="shippingAddressNumber" value="<?=$address->number;?>"><br>
		<input type="text" name="shippingAddressComplement" value="<?=$address->complement;?>"><br>
		<input type="text" name="shippingAddressDistrict" value="<?=$address->bairro;?>"><br>
		<input type="text" name="shippingAddressPostalCode" value="<?=$address->cep;?>"><br>
		<input type="text" name="shippingAddressCity" value="<?=$address->city;?>"><br>
		<input type="text" name="shippingAddressState" value="<?=$address->uf;?>"><br>
		<input type="text" name="shippingAddressCountry" value="BRA"><br>
		<input type="text" name="shippingType" value="<?=SHIPMENT_TYPE[$shipping['type']];?>"><br>

		<input id="shippingCost" type="text" name="shippingCost" value="<?=number_format(str_replace(',', '.', $shipping['value']), 2, '.', '');?>"><br>

		<button>Pagar</button>

	</form>
 -->

<form method="post" data-action="<?=$router->route('payment.creditcard');?>">
		<input id="session" type="text" name="session" value=""><br>
		<input type="text" name="paymentMode" value="default"><br>
		<input id="payment" type="text" name="paymentMethod" value="creditCard"><br>
		<input type="text" name="receiverEmail" value="lfelipelopesti@gmail.com"><br>
		<input type="text" name="currency" value="BRL"><br>
		<input type="text" name="extraAmount" value="<?=(!empty($carts['discount']) ? number_format(-($carts['subtotal'] * $carts['discount']), 2, '.', '') : number_format(0, 2, '.', ''));?>"><br>

		<?php $i        = 0;foreach ($carts['items'] as $cart): $i++;
    $priceDiscounted = (!empty($cart['discount']) ? $cart['price'] - ($cart['price'] * $cart['discount']) : $cart['price']);?>
						<input type="text" name="itemId<?=$i;?>" value="<?=$cart['id'];?>"><br>
						<input type="text" name="itemDescription<?=$i;?>" value="<?=$cart['product'];?>"><br>
						<input id="itemAmout" type="text" name="itemAmount<?=$i;?>" value="<?=number_format(str_replace(',', '.', $priceDiscounted), 2, '.', '');?>"><br>
						<input type="text" name="itemQuantity<?=$i;?>" value="<?=$cart['amount'];?>"><br>
					<?php endforeach;?>

		<input type="text" name="reference" value="<?=rand();?>"><br>

		<input type="text" name="senderName" value="<?=$user->name;?>"><br>
		<input type="text" name="senderCPF" value="<?=$user->cpf;?>"><br>
		<input type="text" name="senderAreaCode" value="<?=substr($user->phone, 0, 2);?>"><br>
		<input type="text" name="senderPhone" value="<?=substr($user->phone, 2);?>"><br>
		<input type="text" name="senderEmail" value="<?=$user->email;?>"><br>

		<input id="hash" type="text" name="senderHash" value=""><br>

		<input type="text" name="shippingAddressStreet" value="<?=$address->logradouro;?>"><br>
		<input type="text" name="shippingAddressNumber" value="<?=$address->number;?>"><br>
		<input type="text" name="shippingAddressComplement" value="<?=$address->complement;?>"><br>
		<input type="text" name="shippingAddressDistrict" value="<?=$address->bairro;?>"><br>
		<input type="text" name="shippingAddressPostalCode" value="<?=$address->cep;?>"><br>
		<input type="text" name="shippingAddressCity" value="<?=$address->city;?>"><br>
		<input type="text" name="shippingAddressState" value="<?=$address->uf;?>"><br>
		<input type="text" name="shippingAddressCountry" value="BRA"><br>
		<input type="text" name="shippingType" value="<?=SHIPMENT_TYPE[$shipping['type']];?>"><br>

		<input id="shippingCost" type="text" name="shippingCost" value="<?=number_format(str_replace(',', '.', $shipping['value']), 2, '.', '');?>"><br>

		<input id="tokenCard" type="text" name="creditCardToken" value=""><br>
		<input class="j_credit_card" id="cardNumber" type="text" name="cardNumber" value="4111111111111111"><br>
		<input class="j_credit_card" id="cvv" type="text" name="cvv" value="013"><br>
		<input class="j_credit_card" id="expirationMonth" type="text" name="expirationMonth" value="12"><br>
		<input class="j_credit_card" id="expirationYear" type="text" name="expirationYear" value="2026"><br>

		<label>installmentQuantity:
			<select id="installmentQuantity" name="installmentQuantity">
				<option selected disabled value="">Parcelas</option>
			</select>
		</label><br>

		<label>installmentValue: <input id="installmentValue" type="text" name="installmentValue" value=""></label><br>
		<label>noInterestInstallmentQuantity: <input id="noInterestInstallmentQuantity" type="text" name="noInterestInstallmentQuantity" value="<?=NO_INTEREST_INSTALLMENTS;?>"></label><br>
		<label>totalAmount: <input id="totalCart" type="text" name="totalCart" value="<?=number_format(str_replace(',', '.', $carts['total']), 2, '.', '');?>"></label><br>

		<input type="text" name="creditCardHolderName" value="Jose Comprador"><br>
		<input type="text" name="creditCardHolderCPF" value="22111944785"><br>
		<input type="text" name="creditCardHolderBirthDate" value="27/10/1987"><br>
		<input type="text" name="creditCardHolderAreaCode" value="11"><br>
		<input type="text" name="creditCardHolderPhone" value="56273440"><br>

		<input type="text" name="billingAddressStreet" value="<?=$address->logradouro;?>"><br>
		<input type="text" name="billingAddressNumber" value="<?=$address->number;?>"><br>
		<input type="text" name="billingAddressComplement" value="<?=$address->complement;?>"><br>
		<input type="text" name="billingAddressDistrict" value="<?=$address->bairro;?>"><br>
		<input type="text" name="billingAddressPostalCode" value="<?=$address->cep;?>"><br>
		<input type="text" name="billingAddressCity" value="<?=$address->city;?>"><br>
		<input type="text" name="billingAddressState" value="<?=$address->uf;?>"><br>
		<input type="text" name="billingAddressCountry" value="BRA"><br>
		<button>Pagar</button>
	</form>

<?php $v->start('scripts');?>
<script type="text/javascript" src="<?=URL_DIRECTPAYMENT_PAGSEGURO;?>"></script>
<?php require path('js/payment.php');?>
<?php $v->end();?>