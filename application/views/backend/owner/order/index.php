

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Order</h1>
    </div>
    <!-- Content Row -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="h5 mb-0 text-gray-800">List Order</h2>
                </div>
                <div class="card-body">
                    <div id="table-data-toolbar">
                        <a href="<?php echo base_url('backend/owner/order/create') ?>" class="btn btn-primary"><i class="fa fa-plus"></i> Add</a>
                    </div>
                    <table id="table-data"></table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function(){
        $('#table-data').bootstrapTable({
            toolbar: "#table-data-toolbar",
            classes: 'table table-striped table-no-bordered',
            search: true,
            showRefresh: true,
            iconsPrefix: 'fa',
            sortOrder: 'DESC',
            // showToggle: true,
            // showColumns: true,
            // showExport: true,
            // showPaginationSwitch: true,
            pagination: true,
            pageList: [10, 25, 50, 100, 'ALL'],
            // showFooter: false,
            sidePagination: 'server',
            url: '<?php echo base_url('backend/owner/order/data') ?>',
            columns: [
                {
                    field: 'id',
                    title: 'Action',
                    class: 'text-nowrap',
                    formatter: function(value, row, index){
                        
                        var html = `
                            <a class="btn btn-sm btn-info" href="${base_url + 'backend/owner/order/edit/' + value}"><i class="fa fa-edit"></i> Edit</a>
                            <a class="btn btn-sm btn-info" href="${base_url + 'backend/owner/orderdetail/index/' + value}"><i class="fa fa-list"></i> Detail</a>
                        `;

                        if(row.status == 'pending'){
                            html += `<a class="btn btn-sm btn-danger" href="${base_url + 'backend/owner/order/delete/' + value}" onclick="return confirm('Are you sure you want to delete data ${row.invoice_number}?')"><i class="fa fa-trash"></i> Delete</a> `;
                        }

                        switch (row.status) {

                            case 'pending':
                                html += `<a class="btn btn-sm btn-success" href="${base_url + 'backend/owner/order/paid/' + value}" ><i class="fa fa-check"></i> Set as Paid</a>`;
                                break;

                            case 'paid':
                                html += `<a class="btn btn-sm btn-success" href="${base_url + 'backend/owner/order/shipping/' + value}" ><i class="fa fa-box"></i> Process Shipping</a>`;
                                break;

                            case 'shipping':
                                html += `<a class="btn btn-sm btn-success" href="${base_url + 'backend/owner/order/delivered/' + value}" ><i class="fa fa-check"></i> Set as Delivered</a>`;
                                break;
                        
                            default:
                                break;
                        }

                        return html;
                    }
                },
                {
                    field: 'invoice_number',
                    title: 'Invoice Number'
                },

                {
                    field: 'status',
                    title: 'Status'
                },
                {
                    field: 'user_name',
                    title: 'User Name'
                },
                {
                    field: 'user_email',
                    title: 'User Email'
                },
                {
                    field: 'address',
                    title: 'Address'
                },
                {
                    field: 'lat',
                    title: 'Lat'
                },
                {
                    field: 'lng',
                    title: 'Lng'
                },

                
                {
                    field: 'sub_total',
                    title: 'Sub Total'
                },
                {
                    field: 'shipping_cost',
                    title: 'Shipping Cost'
                },
                {
                    field: 'grand_total',
                    title: 'Grand Total'
                },
                
                {
                    field: 'created_at',
                    title: 'Created at',
                },
                {
                    field: 'updated_at',
                    title: 'Updated at',
                },
            ]
        });
    });
</script>