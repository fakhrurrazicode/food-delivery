

<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">User</h1>
    </div>
    <!-- Content Row -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="h5 mb-0 text-gray-800">List User</h2>
                </div>
                <div class="card-body">
                    <div id="table-data-toolbar">
                        <a href="<?php echo base_url('backend/admin/user/create') ?>" class="btn btn-primary"><i class="fa fa-plus"></i> Add</a>
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
            url: '<?php echo base_url('backend/admin/user/data') ?>',
            columns: [
                {
                    field: 'id',
                    title: 'Action',
                    class: 'text-nowrap',
                    formatter: function(value, row, index){
                        
                        return `
                            <a class="btn btn-sm btn-info" href="${base_url + 'backend/admin/user/edit/' + value}"><i class="fa fa-edit"></i> Edit</a>
                            <a class="btn btn-sm btn-danger" href="${base_url + 'backend/admin/user/delete/' + value}" onclick="return confirm('Are you sure you want to delete data ${row.name}?')"><i class="fa fa-trash"></i> Delete</a>
                        `;
                    }
                },
                {
                    field: 'name',
                    title: 'Name'
                },
                {
                    field: 'email',
                    title: 'Email'
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