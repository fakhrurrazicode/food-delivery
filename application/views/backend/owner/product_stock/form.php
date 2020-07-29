

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Product Stock</h1>
    </div>
    <!-- Content Row -->
    <div class="row justify-content-center">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    <?php if(isset($product_stock)): ?>
                        <h1 class="h5 mb-0 text-gray-800">Edit Product Stock</h1>
                    <?php else: ?>
                        <h1 class="h5 mb-0 text-gray-800">Add New Product Stock</h1>
                    <?php endif ?>
                </div>
                <div class="card-body">

                    <?php if(isset($product_stock)): ?>
                        <form method="POST" action="<?php echo base_url('backend/owner/productstock/update/'. $product_stock->id) ?>" enctype="multipart/form-data">
                    <?php else: ?>
                        <form method="POST" action="<?php echo base_url('backend/owner/productstock/store') ?>" enctype="multipart/form-data">
                    <?php endif ?>


                        

                        <div class="form-group">
                            <label for="product_id">Product</label>
                            <select class="form-control <?php echo form_error('product_id') ? 'is-invalid' : '' ?>" id="product_id" name="product_id" value="">
                                <?php 
                                $store_id = $this->session->logged_in_owner->store_id;
                                $query = $this->db->query("SELECT products.*, stores.name as store_name FROM products LEFT JOIN stores ON products.store_id = stores.id WHERE stores.id = ". $store_id);
                                $products = $query->result();
                                ?>

                                <option value=""></option>
                                <?php foreach($products as $product): ?>
                                    <option <?php echo set_value('product_id', isset($product_stock) ? $product_stock->product_id : null) == $product->id ? 'selected=""' : '' ?> value="<?php echo $product->id ?>"><?php echo $product->name ?></option>
                                <?php endforeach ?>
                            </select>
                            <?php echo form_error('product_id', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="qty">Qty</label>
                                    <input type="text" class="form-control <?php echo form_error('qty') ? 'is-invalid' : '' ?>" id="qty" name="qty" value="<?php echo set_value('qty', isset($product_stock) ? $product_stock->qty : null) ?>">
                                    <?php echo form_error('qty', '<div class="invalid-feedback">', '</div>'); ?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="price">Price</label>
                                    <input type="text" class="form-control <?php echo form_error('price') ? 'is-invalid' : '' ?>" id="price" name="price" value="<?php echo set_value('price', isset($product_stock) ? $product_stock->price : null) ?>">
                                    <?php echo form_error('price', '<div class="invalid-feedback">', '</div>'); ?>
                                </div>
                            </div>
                        </div>

                        

                        <hr>

                        <div class="text-right">
                            <a href="<?php echo base_url('backend/owner/productstock') ?>" class="btn btn-secondary"><i class="fa fa-times"></i> Cancel</a>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                        </div>
                        

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>