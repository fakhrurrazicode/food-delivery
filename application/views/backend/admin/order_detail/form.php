

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Order</h1>
    </div>
    <!-- Content Row -->
    <div class="row justify-content-center">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    <?php if(isset($order)): ?>
                        <h1 class="h5 mb-0 text-gray-800">Edit Order</h1>
                    <?php else: ?>
                        <h1 class="h5 mb-0 text-gray-800">Add New Order</h1>
                    <?php endif ?>
                </div>
                <div class="card-body">

                    <?php if(isset($order)): ?>
                        <form method="POST" action="<?php echo base_url('backend/admin/order/update/'. $order->id) ?>" enctype="multipart/form-data">
                    <?php else: ?>
                        <form method="POST" action="<?php echo base_url('backend/admin/order/store') ?>" enctype="multipart/form-data">
                    <?php endif ?>

                        <div class="form-group">
                            <label for="invoice_number">Invoice Number</label>
                            <input type="text" class="form-control <?php echo form_error('invoice_number') ? 'is-invalid' : '' ?>" id="invoice_number" name="invoice_number" value="<?php echo set_value('invoice_number', isset($order) ? $order->invoice_number : null) ?>">
                            <?php echo form_error('invoice_number', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>

                        <div class="form-group">
                            <label for="user_id">User Customer</label>
                            <select class="form-control <?php echo form_error('user_id') ? 'is-invalid' : '' ?>" id="user_id" name="user_id" value="">
                                <?php 
                                $query = $this->db->query("SELECT * FROM users ");
                                $users = $query->result();
                                ?>

                                <option value=""></option>
                                <?php foreach($users as $user): ?>
                                    <option <?php echo set_value('user_id', isset($store) ? $store->user_id : null) == $user->id ? 'selected=""' : '' ?> value="<?php echo $user->id ?>"><?php echo $user->name ?></option>
                                <?php endforeach ?>
                            </select>
                            <?php echo form_error('user_id', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>

                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea rows="5" class="form-control <?php echo form_error('address') ? 'is-invalid' : '' ?>" id="address" name="address"><?php echo set_value('address', isset($order) ? $order->address : null) ?></textarea>
                            <?php echo form_error('address', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="lat">Lat</label>
                                    <input type="text" class="form-control <?php echo form_error('lat') ? 'is-invalid' : '' ?>" id="lat" name="lat" value="<?php echo set_value('lat', isset($order) ? $order->lat : null) ?>">
                                    <?php echo form_error('lat', '<div class="invalid-feedback">', '</div>'); ?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="lng">Lng</label>
                                    <input type="text" class="form-control <?php echo form_error('lng') ? 'is-invalid' : '' ?>" id="lng" name="lng" value="<?php echo set_value('lng', isset($order) ? $order->lng : null) ?>">
                                    <?php echo form_error('lng', '<div class="invalid-feedback">', '</div>'); ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="shipping_cost">Shipping Cost</label>
                            <input type="number" class="form-control <?php echo form_error('shipping_cost') ? 'is-invalid' : '' ?>" id="shipping_cost" name="shipping_cost" value="<?php echo set_value('shipping_cost', isset($order) ? $order->shipping_cost : null) ?>">
                            <?php echo form_error('shipping_cost', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>

                        <hr>

                        <div class="text-right">
                            <a href="<?php echo base_url('backend/admin/order') ?>" class="btn btn-secondary"><i class="fa fa-times"></i> Cancel</a>
                            <button type="submit" class="btn btn-primary">Next <i class="fa fa-arrow-right"></i> </button>
                        </div>
                        

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>