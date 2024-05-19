<!DOCTYPE html>
<html>
<head>
    <title>Project Types</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container mt-4">
        <h1>Project Types</h1>
        <button id="addProjectType" class="btn btn-primary mb-3">Add Project Type</button>
        <table id="projectTypesTable" class="display table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Type Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>

    <!-- Project Type Modal -->
    <div class="modal fade" id="projectTypeModal" tabindex="-1" role="dialog" aria-labelledby="projectTypeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="projectTypeModalLabel">Project Type Form</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="projectTypeForm">
                        <input type="hidden" id="id" name="id">
                        <div class="form-group">
                            <label for="type_name">Type Name:</label>
                            <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                            <input type="text" class="form-control" id="type_name" name="type_name">
                            <div class="invalid-feedback"></div>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var table = $('#projectTypesTable').DataTable({
                "processing": true,
                "serverSide": true,
                "searching": true,
                "paging": true,
                "ajax": {
                    "url": "/get-propertytype-details",
                    "type": "POST",
                    "dataSrc": "data"
                },
                "columns": [
                    { "data": "id" },
                    { "data": "property_type_name" },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return '<button class="btn btn-info edit">Edit</button> <button class="btn btn-danger delete">Delete</button>';
                        }
                    }
                ]
            });

            $('#addProjectType').on('click', function() {
                $('#projectTypeModalLabel').text('Add Project Type');
                $('#projectTypeModal').modal('show');
                $('#projectTypeForm')[0].reset();
                $('#id').val('');
                $('.invalid-feedback').text('');
            });

            $('#projectTypesTable tbody').on('click', '.edit', function() {
                var data = table.row($(this).parents('tr')).data();
                $('#projectTypeModalLabel').text('Edit Project Type');
                $('#projectTypeModal').modal('show');
                $('#id').val(data.id);
                $('#type_name').val(data.property_type_name);
                $('.invalid-feedback').text('');
            });

            $('#projectTypesTable tbody').on('click', '.delete', function() {
                var data = table.row($(this).parents('tr')).data();
                if (confirm('Are you sure you want to delete this project type?')) {
                    $.ajax({
                        url: '/delete-project-type/' + data.id,
                        type: 'GET',
                        success: function(response) {
                            table.ajax.reload();
                        }
                    });
                }
            });

            $('#projectTypeForm').on('submit', function(e) {
                e.preventDefault();
                var id = $('#id').val();
                var type_name = $('#type_name').val();
                var url = id ? '/update-project-type' : '/add-project-type';
                var method = id ? 'PUT' : 'POST';

                // Client-side validation
                if (type_name === '') {
                    $('#type_name').addClass('is-invalid');
                    $('#type_name').next('.invalid-feedback').text('Type name is required.');
                    return;
                } else {
                    $('#type_name').removeClass('is-invalid');
                    $('#type_name').next('.invalid-feedback').text('');
                }

                $.ajax({
                    url: url,
                    type: method,
                    data: $(this).serialize(),
                    success: function(response) {
                        if(response.success) {
                            $('#projectTypeModal').modal('hide');
                            table.ajax.reload();
                        } else {
                            // Handle error
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
