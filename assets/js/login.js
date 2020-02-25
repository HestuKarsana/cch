
// ------------------------------------------------------- //
    // Material Inputs
    // ------------------------------------------------------ //

    var materialInputs = $('input.input-material');

    // activate labels for prefilled values
    materialInputs.filter(function () {
        return $(this).val() !== "";
    }).siblings('.label-material').addClass('active');

    // move label on focus
    materialInputs.on('focus', function () {
        $(this).siblings('.label-material').addClass('active');
    });

    // remove/keep label on blur
    materialInputs.on('blur', function () {
        $(this).siblings('.label-material').removeClass('active');

        if ($(this).val() !== '') {
            $(this).siblings('.label-material').addClass('active');
        } else {
            $(this).siblings('.label-material').removeClass('active');
        }
    });
// Login Page 
if($('.login-page').length > 0){
		
    $('#form-login').validate({
        errorElement: "div",
        errorClass: 'is-invalid',
        validClass: 'is-valid',
        ignore: ':hidden:not(.summernote),.note-editable.card-block',
        errorPlacement: function (error, element) {
            // Add the `invalid-feedback` class to the error element
            error.addClass("invalid-feedback");
            //console.log(element);
            if (element.prop("type") === "checkbox") {
                error.insertAfter(element.siblings("label"));
            } 
            else {
                error.insertAfter(element);
            }
        },
        submitHandler: function(form) {
            $.post( site_url + 'do_login', $('#form-login').serialize(), function(res){
                if(res.status){
                    swal({
                        title: "Berhasil!",
                        text: "Silahkan tunggu...",
                        content:"success",
                        timer: 2000,
                        buttons : false,
                        closeOnEsc: false,
                        closeOnClickOutside: false
                    }).then((res) =>{
                        location.href = site_url;
                    });
                }else{
                    swal("Sorry",res.message, "error").then((value) => {
                        location.reload();
                    });
                }
            },"json");
        }
    });
    
}