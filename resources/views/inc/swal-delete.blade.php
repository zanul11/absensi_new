<script src="{{asset('plugins/sweetalerts/sweetalert2.min.js')}}"></script>
<script>
    function fn_deleteData(url) {
        swal({
            title: 'Anda yakin?',
            text: "Untuk menghapus data ini!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            padding: '2em'
        }).then(function(result) {
            if (result.value) {
                token = '{{csrf_token()}}';
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    dataType: "JSON",
                    data: {
                        "_method": 'DELETE',
                        "_token": token,
                    },
                    error: function(xhr, err) {
                        console.log(xhr);
                    },

                });

                setTimeout(function() {
                    window.location.reload();
                }, 100)

            }
        })
        // swal({
        //     title: "Are you sure?",
        //     text: "You will not be able to recover this data !",
        //     icon: "info",
        //     buttons: {
        //         cancel: {
        //             text: "Cancel",
        //             value: null,
        //             visible: !0,
        //             className: "btn btn-danger",
        //             closeModal: !0
        //         },
        //         confirm: {
        //             text: "Yes Delete it !",
        //             value: !0,
        //             visible: !0,
        //             className: "btn btn-primary",
        //             closeModal: !0
        //         }
        //     }
        // }).then(function(isConfirm) {
        //     if (isConfirm) {
        //         token = '{{csrf_token()}}';
        //         $.ajax({
        //             url: url,
        //             type: 'DELETE',
        //             dataType: "JSON",
        //             data: {
        //                 "_method": 'DELETE',
        //                 "_token": token,
        //             },
        //             error: function(xhr, err) {
        //                 console.log(xhr);
        //             }
        //         });

        //         setTimeout(function() {
        //             window.location.reload();
        //         }, 100)
        //     }
        // });
    }
</script>