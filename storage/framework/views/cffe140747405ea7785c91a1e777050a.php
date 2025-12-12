<div>
    <!--[if BLOCK]><![endif]--><?php if(!$paymentCompleted): ?>
        <!-- Payment Form -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Complete Payment</h3>
            </div>
            <div class="card-body">
                <!--[if BLOCK]><![endif]--><?php if($errors->any()): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </ul>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                <!-- Order Summary -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h4>Order Summary</h4>
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <td>Subtotal:</td>
                                    <td class="text-end">LKR <?php echo e(number_format($subTotal, 2)); ?></td>
                                </tr>

                                <tr class="fw-bold">
                                    <td>Total:</td>
                                    <td class="text-end">LKR <?php echo e(number_format($total, 2)); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <h4>Cart Items (<?php echo e(count($cartItems)); ?>)</h4>
                        <div class="list-group list-group-flush" style="max-height: 200px; overflow-y: auto;">
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center py-2">
                                    <div>
                                        <strong><?php echo e($item['name'] ?? 'Unknown Product'); ?></strong><br>
                                        <small class="text-muted"><?php echo e($item['quantity'] ?? 0); ?> × LKR <?php echo e(number_format($item['price'] ?? 0, 2)); ?></small>
                                    </div>
                                    <span class="badge" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); font-weight: 600; padding: 8px 12px; border-radius: 6px; box-shadow: 0 2px 6px rgba(59, 130, 246, 0.25);">LKR <?php echo e(number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 0), 2)); ?></span>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                </div>

                <!-- Payment Form -->
                <form wire:submit.prevent="processPayment">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Customer *</label>
                                <select class="form-select <?php $__errorArgs = ['customerId'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" wire:model="customerId" required>
                                    <option value="">Select Customer</option>
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($customer->id); ?>"><?php echo e($customer->name); ?> - <?php echo e($customer->phone); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </select>
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['customerId'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Payment Method *</label>
                                <select class="form-select <?php $__errorArgs = ['paymentType'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" wire:model="paymentType" required>
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $paymentTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"><?php echo e($label); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </select>
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['paymentType'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Payment Amount *</label>
                                <div class="input-group">
                                    <span class="input-group-text">LKR</span>
                                    <input type="number"
                                           class="form-control <?php $__errorArgs = ['paymentAmount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           wire:model.live="paymentAmount"
                                           step="0.01"
                                           min="0"
                                           required>
                                </div>
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['paymentAmount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <!--[if BLOCK]><![endif]--><?php if($paymentAmount > $total): ?>
                                    <small class="text-success" style="color: #059669 !important; font-weight: 600; font-size: 13px;">
                                        <i class="fas fa-check-circle me-1"></i>Change: LKR <?php echo e(number_format($paymentAmount - $total, 2)); ?>

                                    </small>
                                <?php elseif($paymentAmount < $total && $paymentAmount > 0): ?>
                                    <small class="text-danger" style="color: #dc2626 !important; font-weight: 600; font-size: 13px;">
                                        <i class="fas fa-exclamation-triangle me-1"></i>Insufficient amount. Required: LKR <?php echo e(number_format($total, 2)); ?>

                                    </small>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit"
                                class="btn btn-success btn-lg px-4"
                                style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; font-weight: 700; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.35); transition: all 0.3s ease; text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1); font-size: 18px;"
                                wire:loading.attr="disabled"
                                wire:target="processPayment"
                                <?php echo e($isProcessing ? 'disabled' : ''); ?>

                                onmouseover="this.style.background='linear-gradient(135deg, #059669 0%, #047857 100%)'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 25px rgba(16, 185, 129, 0.45)'"
                                onmouseout="this.style.background='linear-gradient(135deg, #10b981 0%, #059669 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(16, 185, 129, 0.35)'">
                            <div wire:loading.remove wire:target="processPayment">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M5 12l5 5l10 -10"/>
                                </svg>
                                Complete Payment
                            </div>
                            <div wire:loading wire:target="processPayment">
                                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                Processing...
                            </div>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php else: ?>
        <!-- Payment Success -->
        <div class="card border-success" style="box-shadow: 0 8px 25px rgba(16, 185, 129, 0.15); border-radius: 12px;">
            <div class="card-header text-white" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 12px 12px 0 0; padding: 20px; border: none;">
                <h3 class="card-title mb-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M5 12l5 5l10 -10"/>
                    </svg>
                    Payment Completed Successfully!
                </h3>
            </div>
            <div class="card-body">
                <!--[if BLOCK]><![endif]--><?php if($orderDetails): ?>
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Transaction Details</h4>
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td><strong>Invoice No:</strong></td>
                                        <td><?php echo e($orderDetails['invoice_no']); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Transaction ID:</strong></td>
                                        <td><?php echo e($orderDetails['transaction_id']); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Customer:</strong></td>
                                        <td><?php echo e($orderDetails['customer']->name ?? 'N/A'); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Payment Method:</strong></td>
                                        <td><?php echo e($paymentType); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Date & Time:</strong></td>
                                        <td><?php echo e($orderDetails['created_at']->format('Y-m-d H:i:s')); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h4>Payment Summary</h4>
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td><strong>Total Amount:</strong></td>
                                        <td>LKR <?php echo e(number_format($orderDetails['total_amount'], 2)); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Amount Paid:</strong></td>
                                        <td>LKR <?php echo e(number_format($orderDetails['payment_amount'], 2)); ?></td>
                                    </tr>
                                    <!--[if BLOCK]><![endif]--><?php if($orderDetails['change_amount'] > 0): ?>
                                        <tr class="text-success" style="color: #059669 !important;">
                                            <td><strong><i class="fas fa-hand-holding-usd me-2"></i>Change Due:</strong></td>
                                            <td><strong style="font-size: 1.1em; color: #047857 !important;">LKR <?php echo e(number_format($orderDetails['change_amount'], 2)); ?></strong></td>
                                        </tr>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <a href="<?php echo e(route('orders.show', $orderDetails['order_id'])); ?>" class="btn btn-primary me-2" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); border: none; font-weight: 600; padding: 12px 24px; border-radius: 8px; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); transition: all 0.3s ease;">
                            View Order Details
                        </a>
                        <button type="button" class="btn btn-outline-success" wire:click="resetPayment" style="border: 2px solid #10b981; color: #059669; font-weight: 600; padding: 12px 24px; border-radius: 8px; transition: all 0.3s ease; background: transparent;" onmouseover="this.style.background='#10b981'; this.style.color='white'; this.style.transform='translateY(-1px)'" onmouseout="this.style.background='transparent'; this.style.color='#059669'; this.style.transform='translateY(0)'">
                            Process New Payment
                        </button>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH C:\xampp\htdocs\New folder\NexoraLabs\resources\views/livewire/payment/payment-processor.blade.php ENDPATH**/ ?>