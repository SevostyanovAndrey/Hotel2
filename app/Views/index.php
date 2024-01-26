<!DOCTYPE html>
<html>
<head>
    <title>DataTables AJAX Pagination with Search and Sort in CodeIgniter 4</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Datatable CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.24/datatables.min.css"/>

    <!-- jQuery Library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Datatable JS -->
    <script src="https://cdn.datatables.net/v/dt/dt-1.10.24/datatables.min.js"></script>

</head>
<body>

<!-- CSRF token -->
<input type="hidden" class="txt_csrfname" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>"/>

<!-- Table -->
<table id='userTable' class='display dataTable'>
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>City</th>
        <th>Actions</th>
        </th>
    </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<div style="width: 100%">
    <div class="inputGroup">
        <input type="text" required="" minlength="5" maxlength="50" autocomplete="off" id="name" name="name">
        <label for="name">Name</label>
    </div>
    <div class="inputGroup">
        <input type="text" required="" minlength="5" maxlength="50" autocomplete="off" id="email" name="email">
        <label for="name">Email</label>
    </div>
    <div class="inputGroup">
        <input type="text" required="" minlength="5" maxlength="50" autocomplete="off" id="city" name="city">
        <label for="name">city</label>
    </div>
    <button type="submit" class="btnSubmitCustom mb-5">
        Отправить!
    </button>
</div>

<script>
    $(document).ready(function () {


        $('#userTable').DataTable({
            "lengthMenu": [3, 6, 12, 24],
            "language": {
                url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Russian.json'
            },
            "bFilter": false,
            'processing': true,
            'serverSide': true,
            'serverMethod': 'post',
            'ajax': {
                'url': "<?=site_url('users/getUsers')?>",
                'data': function (data) {
                    // CSRF Hash
                    var csrfName = $('.txt_csrfname').attr('name');
                    var csrfHash = $('.txt_csrfname').val();

                    return {
                        data: data,
                        [csrfName]: csrfHash
                    };
                },
                dataSrc: function (data) {
                    $('.txt_csrfname').val(data.token);

                    return data.aaData;
                }
            },
            'columns': [
                {data: 'id'},
                {data: 'name'},
                {data: 'email'},
                {data: 'city'},
                { data: 'actions', orderable: false, searchable: false }
            ]
        });

        $('.btnSubmitCustom').click(function () {
            var formData = {
                name: $('#name').val(),
                email: $('#email').val(),
                city: $('#city').val()
            };

            $.ajax({
                url: "<?= site_url('users/addUser') ?>",
                type: "POST",
                data: formData,
                dataType: "json",
                success: function (response) {
                    if (response.status === 'success') {
                        // Обновите таблицу DataTables
                        $('#userTable').DataTable().ajax.reload();
                        // Покажите сообщение об успехе
                        alert(response.message);
                    } else {
                        // Обработайте ошибку
                        console.error(response.message);
                    }
                }
            });
            return false; // Предотвратите стандартную отправку формы
        });

        $(document).on('click', '.btnDelete', function() {
            var buttonValue = $(this).val();

            var url = "<?= site_url('users/delete/') ?>" + buttonValue;

            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function (response) {
                    if (response.status === 'success') {

                        $('#userTable').DataTable().ajax.reload();
                        alert(response.message);
                    } else {
                        console.error(response.message);
                    }
                }
            });
        });

    });

</script>

</body>
</html>