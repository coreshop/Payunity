<div class="container shop checkout checkout-step-5">
    <?=$this->partial("coreshop/helper/order-steps.php", array("step" => 5));?>

    <p>Ihre Bestellung wurde bezahlt. Die Bestellnummer lautet <?=$this->order->getOrderNumber()?></p>
</div>