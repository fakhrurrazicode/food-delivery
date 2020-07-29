

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Order Detail</h1>
        
    </div>
    <!-- Content Row -->
    <div class="row">
        <div class="col-sm-12">

            <p><a href="<?php echo base_url('backend/admin/order') ?>" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Kembali</a></p>

            <div class="card mb-3">

                <div class="card-header">
                    <h2 class="h5 mb-0 text-gray-800">Order <?php echo $order->invoice_number ?></h2>
                </div>

                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3 text-md-right">Invoice Number</dt>
                        <dd class="col-sm-9"><?php echo $order->invoice_number ?></dd>

                        <dt class="col-sm-3 text-md-right">Customer</dt>
                        <dd class="col-sm-9"><?php echo $order->user_name .' ('. $order->user_email .')' ?></dd>

                        <dt class="col-sm-3 text-md-right">Address</dt>
                        <dd class="col-sm-9"><?php echo $order->address ?></dd>

                        <dt class="col-sm-3 text-md-right">Lat</dt>
                        <dd class="col-sm-9"><?php echo $order->lat ?></dd>

                        <dt class="col-sm-3 text-md-right">Shipping Cost</dt>
                        <dd class="col-sm-9">Rp. <?php echo number_format($order->shipping_cost, 0, ',', '.') ?></dd>
                    </dl>

                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2 class="h5 mb-0 text-gray-800">List Order Detail For Order <?php echo $order->invoice_number ?></h2>
                </div>
                <div class="card-body">
                    

                    
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th></th>
                                <th>No</th>
                                <th>Product Name</th>
                                <th>Qty</th>
                                <th>Price</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if($order_details): ?>
                                <?php $no = 1; foreach($order_details as $order_detail): ?>
                                    <tr>
                                        <td></td>
                                        <td><?php echo $no++ ?></td>
                                        <td><?php echo $order_detail->product_name ?></td>
                                        <td><?php echo $order_detail->qty ?></td>
                                        <td><?php echo $order_detail->price ?></td>
                                    </tr>
                                <?php endforeach ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">Tidak Ada Order Detail</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
