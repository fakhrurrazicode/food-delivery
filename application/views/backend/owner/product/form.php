

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Product</h1>
    </div>
    <!-- Content Row -->
    <div class="row justify-content-center">
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    <?php if(isset($product)): ?>
                        <h1 class="h5 mb-0 text-gray-800">Edit Product</h1>
                    <?php else: ?>
                        <h1 class="h5 mb-0 text-gray-800">Add New Product</h1>
                    <?php endif ?>
                </div>
                <div class="card-body">

                    <?php if(isset($product)): ?>
                        <form method="POST" action="<?php echo base_url('backend/owner/product/update/'. $product->id) ?>" enctype="multipart/form-data">
                    <?php else: ?>
                        <form method="POST" action="<?php echo base_url('backend/owner/product/store') ?>" enctype="multipart/form-data">
                    <?php endif ?>


                        <div class="form-group" style="display: none;">
                            <label for="store_id">Store</label>
                            <select class="form-control <?php echo form_error('store_id') ? 'is-invalid' : '' ?>" id="store_id" name="store_id" value="">
                                <?php 

                                $user_id = $this->session->logged_in_owner->id;
                                $query = $this->db->query("SELECT * FROM stores WHERE user_id = ? ", [$user_id]);
                                $stores = $query->result();
                                ?>

                                <option value=""></option>
                                <?php foreach($stores as $store): ?>
                                    <option <?php echo set_value('store_id', isset($product) ? $product->store_id : null) == $store->id ? 'selected=""' : '' ?> value="<?php echo $store->id ?>"><?php echo $store->name ?></option>
                                <?php endforeach ?>
                            </select>
                            <?php echo form_error('store_id', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control <?php echo form_error('name') ? 'is-invalid' : '' ?>" id="name" name="name" value="<?php echo set_value('name', isset($product) ? $product->name : null) ?>">
                            <?php echo form_error('name', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>



                        <div class="row">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label for="photo">Photo</label>
                                    <input type="file" class="form-control <?php echo form_error('photo') ? 'is-invalid' : '' ?>" id="photo" name="photo">
                                    <?php echo form_error('photo', '<div class="invalid-feedback">', '</div>'); ?>
                                    <?php if(isset($product)): ?>
                                        <p class="my-2">Abaikan jika tidak ingin mengganti gambar.</p>
                                    <?php endif ?>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <?php if(isset($product) && $product->photo): ?>
                                    <img src="<?php echo base_url($product->photo) ?>" style="width: 100%;" alt="">
                                <?php endif ?>
                            </div>
                        </div>
                        

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea rows="5" class="form-control <?php echo form_error('description') ? 'is-invalid' : '' ?>" id="description" name="description"><?php echo set_value('description', isset($product) ? $product->description : null) ?></textarea>
                            <?php echo form_error('description', '<div class="invalid-feedback">', '</div>'); ?>
                        </div>

                        <hr>

                        <div class="text-right">
                            <a href="<?php echo base_url('backend/owner/product') ?>" class="btn btn-secondary"><i class="fa fa-times"></i> Cancel</a>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                        </div>
                        

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>