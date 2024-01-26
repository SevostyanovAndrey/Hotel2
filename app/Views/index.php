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
   <input type="hidden" class="txt_csrfname" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" />

   <!-- Table -->
   <table id='userTable' class='display dataTable'>

     <thead>
       <tr>
         <th>ID</th>
         <th>Name</th>
         <th>Email</th>
         <th>City</th>
       </tr>
     </thead>
   </table>

   <div style="width: 100%">
       <div class="inputGroup">
           <input type="text" required="" minlength="5" maxlength="50" autocomplete="off" id="gmail" name="gmail">
           <label for="name">Email</label>
       </div>
       <div>
           <label>Комментарий:</label>
           <textarea class="inputGroup" style="min-width: 480px; min-height: 50px" maxlength="100" required=""
                     autocomplete="off" id="comment" name="comment"></textarea>
       </div>
       <div class="inputGroup">
           <input type="date" required="" autocomplete="off" id="date" name="date">

       </div>
       <button type="submit" class="btnSubmitCustom mb-5">
           Отправить!
       </button>
   </div>


   <!-- Script -->
   <script type="text/javascript">
   $(document).ready(function(){
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
            'url':"<?=site_url('users/getUsers')?>",
            'data': function(data){
               // CSRF Hash
               var csrfName = $('.txt_csrfname').attr('name'); // CSRF Token name
               var csrfHash = $('.txt_csrfname').val(); // CSRF hash

               return {
                  data: data,
                  [csrfName]: csrfHash // CSRF Token
               };
            },
            dataSrc: function(data){

              // Update token hash
              $('.txt_csrfname').val(data.token);

              // Datatable data
              return data.aaData;
            }
         },
         'columns': [
            { data: 'id' },
            { data: 'name' },
            { data: 'email' },
            { data: 'city' },
         ]
      });
   });
   </script>
</body>
</html>