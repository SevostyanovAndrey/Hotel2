<!DOCTYPE html>
<html>
<head>
    <title>Commentaries</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.24/datatables.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/v/dt/dt-1.10.24/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
            crossorigin="anonymous"></script>
</head>
<body>

<!-- CSRF token -->
<input type="hidden" class="txt_csrfname" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>"/>

<!-- Table -->
<table id='userTable' class='table table-hover'>
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>text</th>
        <th>date</th>
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
        <input type="text" required="" minlength="5" maxlength="50" autocomplete="off" id="text" name="text">
        <label for="name">text</label>
    </div>
    <div class="inputGroup">
        <input type="text" required="" minlength="5" maxlength="50" autocomplete="off" id="date" name="date">
        <label for="name">date</label>
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
                {data: 'text'},
                {data: 'date'},
                { data: 'actions', orderable: false, searchable: false }
            ]
        });

        $('.btnSubmitCustom').click(function () {
            var formData = {
                name: $('#name').val(),
                text: $('#text').val(),
                date: $('#date').val()
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
<style>
    .btnDelete {
        position: relative;
        padding: 5px 10px;
        border-radius: 7px;
        border: 1px solid rgba(249, 255, 61, 0.16);
        font-size: 14px;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 2px;
        background: transparent;
        color: #000000;
        overflow: hidden;
        box-shadow: 0 0 0 0 transparent;
        -webkit-transition: all 0.2s ease-in;
        -moz-transition: all 0.2s ease-in;
        transition: all 0.2s ease-in;
    }

    .btnDelete:hover {
        background: rgba(255, 61, 61, 0.59);
        box-shadow: 0 0 30px 5px rgba(236, 28, 0, 0.81);
        -webkit-transition: all 0.2s ease-out;
        -moz-transition: all 0.2s ease-out;
        transition: all 0.2s ease-out;
    }
    .btnDelete::before {
        content: '';
        display: block;
        width: 0px;
        height: 86%;
        position: absolute;
        top: 7%;
        left: 0%;
        opacity: 0;
        background: #fff;
        box-shadow: 0 0 50px 30px #fff;
        -webkit-transform: skewX(-20deg);
        -moz-transform: skewX(-20deg);
        -ms-transform: skewX(-20deg);
        -o-transform: skewX(-20deg);
        transform: skewX(-20deg);
    }
</style>
</html>