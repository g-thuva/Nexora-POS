<?php
    // This is the overlay version for merging with PDF letterhead
    $letterheadConfig = $letterheadConfig ?? [];
    $positions = $letterheadConfig['positions'] ?? [];
    $elementToggles = $letterheadConfig['element_toggles'] ?? [];
    $tableWidth = $letterheadConfig['table_width'] ?? 480;

    // Build position map for easier template access
    $positionMap = [];
    foreach ($positions as $pos) {
        $positionMap[$pos['field']] = $pos;
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice - <?php echo e($order->invoice_no); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #000;
        }

        .container {
            width: 210mm;
            height: 297mm;
            position: relative;
        }

        .field {
            position: absolute;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            background-color: transparent;
            border-bottom: 1pt solid #333;
            padding: 4px 2px;
            text-align: left;
            font-weight: bold;
            font-size: 9pt;
        }

        table td {
            border-bottom: 0.5pt solid #ccc;
            padding: 3px 2px;
            font-size: 9pt;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Invoice Number -->
        <?php if(isset($positionMap['invoice_no'])): ?>
        <div class="field" style="position: absolute; left: <?php echo e($positionMap['invoice_no']['x']); ?>pt; top: <?php echo e($positionMap['invoice_no']['y']); ?>pt; font-size: <?php echo e($positionMap['invoice_no']['font_size'] ?? 12); ?>pt; font-weight: <?php echo e($positionMap['invoice_no']['font_weight'] ?? 'bold'); ?>;">
            <?php echo e($order->invoice_no); ?>

        </div>
        <?php endif; ?>

        <!-- Invoice Date -->
        <?php if(isset($positionMap['invoice_date'])): ?>
        <div class="field" style="position: absolute; left: <?php echo e($positionMap['invoice_date']['x']); ?>pt; top: <?php echo e($positionMap['invoice_date']['y']); ?>pt; font-size: <?php echo e($positionMap['invoice_date']['font_size'] ?? 11); ?>pt; font-weight: <?php echo e($positionMap['invoice_date']['font_weight'] ?? 'normal'); ?>;">
            <?php echo e(\Carbon\Carbon::parse($order->order_date)->format('d/m/Y')); ?>

        </div>
        <?php endif; ?>

        <!-- Customer Name -->
        <?php if(isset($positionMap['customer_name']) && (!isset($elementToggles['customer_name']) || $elementToggles['customer_name'])): ?>
        <div class="field" style="position: absolute; left: <?php echo e($positionMap['customer_name']['x']); ?>pt; top: <?php echo e($positionMap['customer_name']['y']); ?>pt; font-size: <?php echo e($positionMap['customer_name']['font_size'] ?? 12); ?>pt; font-weight: <?php echo e($positionMap['customer_name']['font_weight'] ?? 'bold'); ?>;">
            <?php echo e($order->customer->name); ?>

        </div>
        <?php endif; ?>

        <!-- Customer Phone -->
        <?php if(isset($positionMap['customer_phone']) && (!isset($elementToggles['customer_phone']) || $elementToggles['customer_phone'])): ?>
        <div class="field" style="position: absolute; left: <?php echo e($positionMap['customer_phone']['x']); ?>pt; top: <?php echo e($positionMap['customer_phone']['y']); ?>pt; font-size: <?php echo e($positionMap['customer_phone']['font_size'] ?? 11); ?>pt; font-weight: <?php echo e($positionMap['customer_phone']['font_weight'] ?? 'normal'); ?>;">
            <?php echo e($order->customer->phone ?? ''); ?>

        </div>
        <?php endif; ?>

        <!-- Customer Address -->
        <?php if(isset($positionMap['customer_address']) && (!isset($elementToggles['customer_address']) || $elementToggles['customer_address'])): ?>
        <div class="field" style="position: absolute; left: <?php echo e($positionMap['customer_address']['x']); ?>pt; top: <?php echo e($positionMap['customer_address']['y']); ?>pt; font-size: <?php echo e($positionMap['customer_address']['font_size'] ?? 10); ?>pt; font-weight: <?php echo e($positionMap['customer_address']['font_weight'] ?? 'normal'); ?>;">
            <?php echo e($order->customer->address ?? ''); ?>

        </div>
        <?php endif; ?>

        <!-- Products Table with Payment Details -->
        <?php if(isset($positionMap['product_name'])): ?>
        <div class="field" style="position: absolute; left: <?php echo e($positionMap['product_name']['x']); ?>pt; top: <?php echo e($positionMap['product_name']['y']); ?>pt; width: <?php echo e($tableWidth); ?>pt;">
            <table>
                <thead>
                    <tr>
                        <th style="width: 50%;">Product</th>
                        <th class="text-center" style="width: 15%;">Qty</th>
                        <th class="text-right" style="width: 17.5%;">Price</th>
                        <th class="text-right" style="width: 17.5%;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $order->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td style="border-bottom: none;"><?php echo e($detail->product->name); ?></td>
                        <td class="text-center" style="border-bottom: none;"><?php echo e($detail->quantity); ?></td>
                        <td class="text-right" style="border-bottom: none;"><?php echo e(number_format($detail->unitcost, 2)); ?></td>
                        <td class="text-right" style="border-bottom: none;"><?php echo e(number_format($detail->total, 2)); ?></td>
                    </tr>
                    <?php if($detail->serial_number || $detail->warranty_name || ($detail->product && $detail->product->warranty_years)): ?>
                    <tr>
                        <td colspan="4" style="font-size: 8pt; color: #888; padding: 2px 2px 3px 2px; border-bottom: none;">
                            <?php if($detail->serial_number): ?>
                                <span style="margin-right: 12pt;">SN: <?php echo e($detail->serial_number); ?></span>
                            <?php endif; ?>
                            <?php if($detail->warranty_name): ?>
                                <span>Warranty: <?php echo e($detail->warranty_name); ?></span>
                            <?php elseif($detail->product && $detail->product->warranty_years): ?>
                                <span>Warranty: <?php echo e($detail->product->warranty_years); ?> <?php echo e($detail->product->warranty_years == 1 ? 'Year' : 'Years'); ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <!-- Payment Details as Table Rows -->
                    <tr>
                        <td colspan="3" class="text-right" style="font-weight: normal; border-bottom: none;">Subtotal:</td>
                        <td class="text-right" style="font-weight: bold; border-bottom: none;"><?php echo e(number_format($order->sub_total, 2)); ?></td>
                    </tr>

                    <?php if(($order->discount_amount ?? 0) > 0): ?>
                    <tr>
                        <td colspan="3" class="text-right" style="font-weight: normal; border-bottom: none;">Discount:</td>
                        <td class="text-right" style="font-weight: bold; border-bottom: none;">-<?php echo e(number_format($order->discount_amount, 2)); ?></td>
                    </tr>
                    <?php endif; ?>

                    <?php if(($order->service_charges ?? 0) > 0): ?>
                    <tr>
                        <td colspan="3" class="text-right" style="font-weight: normal; border-bottom: none;">Service Charges:</td>
                        <td class="text-right" style="font-weight: bold; border-bottom: none;"><?php echo e(number_format($order->service_charges, 2)); ?></td>
                    </tr>
                    <?php endif; ?>

                    <tr>
                        <td colspan="3" class="text-right" style="font-weight: bold; font-size: 10pt; border-bottom: none;">TOTAL:</td>
                        <td class="text-right" style="font-weight: bold; font-size: 10pt; border-bottom: none;"><?php echo e(number_format($order->total, 2)); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\New folder\NexoraLabs\resources\views/orders/pdf-bill-overlay.blade.php ENDPATH**/ ?>