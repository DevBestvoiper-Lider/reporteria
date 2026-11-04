<?php
if (!isset($tipoTablaSel)) {
    $tipoTablaSel = 'gatewaymapping';
}
$tiposReporte = array(
    'todas' => 'Todo',
    'gatewaymapping' => 'Gateway Mapping',
    'gatewayrouting' => 'Gateway Routing',
    'phone' => 'Phone Fee',
    'customerclearinglocation' => 'Customer Clearing Location Fee',
);
?>
<div class="bv-type-pills">
    <?php foreach ($tiposReporte as $val => $label) { ?>
        <label>
            <input type="radio" name="tipoTabla" value="<?php echo htmlspecialchars($val, ENT_QUOTES, 'UTF-8'); ?>" <?php echo ($tipoTablaSel == $val) ? 'checked' : ''; ?>>
            <span><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></span>
        </label>
    <?php } ?>
</div>
