<?php require 'layout/header.php' ?>
<main id="maincontent" class="page-main">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="/" target="_self">Giỏ hàng</a></li>
                    <li><span>/</span></li>
                    <li class="active"><span>Thông tin giao hàng</span></li>
                </ol>
            </div>
        </div>
        <div class="row">
            <aside class="col-md-6 cart-checkout">
                <?php foreach ($cart->getItems() as $item): ?>
                    <div class="row">
                        <div class="col-xs-2">
                            <img class="img-responsive" src="../upload/<?= $item['img'] ?>" alt="<?= $item['name'] ?>">
                        </div>
                        <div class="col-xs-7">
                            <a class="product-name"
                                href="?c=product&=detail&id=<?= $item['product_id'] ?>"><?= $item['name'] ?></a>
                            <br>
                            <span><?= $item['qty'] ?></span> x <span><?= formatMoney($item['unit_price']) ?>₫</span>
                        </div>
                        <div class="col-xs-3 text-right">
                            <span><?= formatMoney($item['total_price']) ?>₫</span>
                        </div>
                    </div>
                    <hr>
                <?php endforeach; ?>
                <div class="row">
                    <div class="col-xs-6">
                        Tạm tính
                    </div>
                    <div class="col-xs-6 text-right">
                        <?= formatMoney($cart->getTotalPrice()) ?>₫
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        Phí vận chuyển
                    </div>
                    <div class="col-xs-6 text-right">
                        <?php $shipping_fee = 50000 //later 
                        ?>
                        <span class="shipping-fee" data=""><?= formatMoney($shipping_fee) ?>₫</span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-xs-6">
                        Tổng cộng
                    </div>
                    <div class="col-xs-6 text-right">
                        <span class="payment-total"
                            data="<?= $cart->getTotalPrice() ?>"><?= formatMoney($cart->getTotalPrice() + $shipping_fee) ?>₫</span>
                    </div>
                </div>
            </aside>
            <div class="ship-checkout col-md-6">
                <h4>Thông tin giao hàng</h4>

                <form action="?c=payment&a=order" method="POST" class="form-checkout">
                    <?php require 'layout/address.php' ?>
                    <h4>Phương thức thanh toán</h4>
                    
                    <!-- Payment Methods Container -->
                    <div class="payment-methods-container" style="margin-top: 15px;">
                        <!-- COD Option -->
                        <div class="payment-option" style="background: white; border: 2px solid #e0e0e0; border-radius: 8px; padding: 15px; margin-bottom: 12px; cursor: pointer; transition: all 0.3s ease;" onclick="selectPaymentMethod('cod', this)">
                            <label style="display: flex; align-items: center; margin: 0; cursor: pointer; width: 100%;">
                                <input type="radio" name="payment_method" value="0" checked style="margin-right: 12px; width: 18px; height: 18px; cursor: pointer;">
                                <div style="flex: 1;">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <i class="fas fa-money-bill-wave" style="color: #22c55e; font-size: 20px;"></i>
                                        <span style="font-weight: 600; font-size: 15px; color: #2c3e50;">Thanh toán khi giao hàng (COD)</span>
                                    </div>
                                    <small style="color: #6c757d; margin-left: 30px; display: block; margin-top: 4px;">Thanh toán bằng tiền mặt khi nhận hàng</small>
                                </div>
                            </label>
                        </div>

                        <!-- Bank Transfer Option -->
                        <div class="payment-option" style="background: white; border: 2px solid #e0e0e0; border-radius: 8px; padding: 15px; cursor: pointer; transition: all 0.3s ease;" onclick="selectPaymentMethod('bank', this)">
                            <label style="display: flex; align-items: center; margin: 0; cursor: pointer; width: 100%;">
                                <input type="radio" name="payment_method" value="1" style="margin-right: 12px; width: 18px; height: 18px; cursor: pointer;">
                                <div style="flex: 1;">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <i class="fas fa-university" style="color: #ee4d2d; font-size: 20px;"></i>
                                        <span style="font-weight: 600; font-size: 15px; color: #2c3e50;">Chuyển khoản ngân hàng</span>
                                    </div>
                                    <small style="color: #6c757d; margin-left: 30px; display: block; margin-top: 4px;">Quét mã QR để thanh toán nhanh chóng</small>
                                </div>
                            </label>
                            
                            <!-- QR Button - Hidden by default -->
                            <div id="qr-button-container" style="display: none; margin-top: 12px; padding-top: 12px; border-top: 1px solid #f0f0f0;">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#qrPaymentModal"
                                    style="width: 100%; background: linear-gradient(135deg, #ee4d2d 0%, #d73211 100%); border: none; border-radius: 6px; padding: 10px; font-weight: 600;">
                                    <i class="fas fa-qrcode"></i> Xem mã QR thanh toán
                                </button>
                            </div>
                        </div>
                    </div>

                    <script>
                    function selectPaymentMethod(method, element) {
                        // Remove active state from all options
                        document.querySelectorAll('.payment-option').forEach(opt => {
                            opt.style.border = '2px solid #e0e0e0';
                            opt.style.background = 'white';
                        });
                        
                        // Add active state to selected option
                        element.style.border = '2px solid #ee4d2d';
                        element.style.background = '#fff5f5';
                        
                        // Check the radio button
                        element.querySelector('input[type="radio"]').checked = true;
                        
                        // Show/hide QR button based on selection
                        const qrContainer = document.getElementById('qr-button-container');
                        if (method === 'bank') {
                            qrContainer.style.display = 'block';
                        } else {
                            qrContainer.style.display = 'none';
                        }
                    }
                    
                    // Initialize on page load
                    document.addEventListener('DOMContentLoaded', function() {
                        const checkedRadio = document.querySelector('input[name="payment_method"]:checked');
                        if (checkedRadio) {
                            const paymentOption = checkedRadio.closest('.payment-option');
                            if (checkedRadio.value === '0') {
                                selectPaymentMethod('cod', paymentOption);
                            } else {
                                selectPaymentMethod('bank', paymentOption);
                            }
                        }
                    });
                    </script>
                    
                    <!-- QR Payment Modal -->
                    <div class="modal fade" id="qrPaymentModal" tabindex="-1" role="dialog" 
                        aria-labelledby="qrPaymentModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 450px;">
                            <div class="modal-content" style="border-radius: 10px; overflow: hidden;">
                                <!-- Header -->
                                <div class="modal-header" style="background: linear-gradient(135deg, #ee4d2d 0%, #d73211 100%); color: white; border: none; padding: 15px 20px;">
                                    <h5 class="modal-title" id="qrPaymentModalLabel" style="font-weight: 700; margin: 0; font-size: 16px;">
                                        <i class="fas fa-qrcode"></i> Quét mã QR thanh toán
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 0.9;">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                
                                <!-- Body -->
                                <div class="modal-body" style="padding: 20px; text-align: center; background: #f8f9fa;">
                                    <!-- QR Code -->
                                    <div style="background: white; padding: 15px; border-radius: 8px; display: inline-block; box-shadow: 0 3px 10px rgba(0,0,0,0.1); margin-bottom: 15px;">
                                        <img src="https://img.vietqr.io/image/vpbank-0965337849-compact2.jpg?amount=<?= $cart->getTotalPrice() + $shipping_fee ?>&addInfo=TheBloomStudio-DonHang&accountName=NGUYEN%20DUC%20THANH%20LONG"
                                            alt="QR Code" style="width: 220px; height: 220px; display: block; border-radius: 6px;">
                                    </div>
                                    
                                    <!-- Amount -->
                                    <div style="background: linear-gradient(135deg, #fff5f5 0%, #ffe8e8 100%); padding: 12px 15px; border-radius: 6px; margin-bottom: 15px; border-left: 3px solid #ee4d2d;">
                                        <div style="font-size: 12px; color: #666; margin-bottom: 3px;">Số tiền thanh toán</div>
                                        <div style="font-size: 20px; font-weight: 700; color: #ee4d2d;"><?= formatMoney($cart->getTotalPrice() + $shipping_fee) ?>₫</div>
                                    </div>
                                    
                                    <!-- Bank Info - Compact -->
                                    <div style="background: white; padding: 15px; border-radius: 6px; text-align: left; font-size: 13px;">
                                        <div style="font-weight: 700; margin-bottom: 10px; color: #2c3e50;">
                                            <i class="fas fa-university" style="color: #ee4d2d;"></i> Thông tin chuyển khoản
                                        </div>
                                        <div style="margin-bottom: 6px;">
                                            <span style="color: #666;">NH:</span>
                                            <span style="font-weight: 600; color: #2c3e50; margin-left: 5px;">VPBank - 0965337849</span>
                                        </div>
                                        <div style="margin-bottom: 6px;">
                                            <span style="color: #666;">Chủ TK:</span>
                                            <span style="font-weight: 600; color: #2c3e50; margin-left: 5px;">NGUYEN DUC THANH LONG</span>
                                        </div>
                                        <div>
                                            <span style="color: #666;">Nội dung:</span>
                                            <span style="font-weight: 600; color: #ee4d2d; background: #fff5f5; padding: 3px 8px; border-radius: 3px; margin-left: 5px; font-size: 12px;">TheBloomStudio DonHang</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Footer -->
                                <div class="modal-footer" style="border-top: 1px solid #dee2e6; padding: 12px 20px;">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 5px; font-size: 13px; padding: 8px 16px;">
                                        <i class="fas fa-times"></i> Đóng
                                    </button>
                                    <button type="button" class="btn btn-success" style="background: #22c55e; border: none; border-radius: 5px; font-size: 13px; padding: 8px 16px;">
                                        <i class="fas fa-check-circle"></i> Đã chuyển khoản
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>



            </div>
            <div>
                <button type="submit" class="btn btn-sm btn-primary pull-right">Hoàn tất đơn hàng</button>
            </div>
            </form>
        </div>
    </div>
    </div>
</main>
<?php require 'layout/footer.php' ?>