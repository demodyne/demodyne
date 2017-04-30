function registrationHandlers() {


    $('#user-registration-form')
        .formValidation({
            locale: 'fr_FR'
        })
        .on('success.form.fv', function(e) {
            // Prevent form submission
            e.preventDefault();

            var $form = $(e.target);

            // Use Ajax to submit form data
            $('#user-registration-submit').prop('disabled', 'disabled').find('i').removeClass("fa-sign-in").addClass('fa-spinner').addClass('fa-pulse');

            $.ajax({
                data: $form.serialize(),
                type: $form.attr('method'),
                url: $form.attr('action'),
                success: function(response) {
                    $('#register').parent().html(response);
                }

            });

        });


    $("#usrPostalcode").on('keyup change paste', function() {
        updateCityList();
    });

    function updateCityList() {
        var citySelectId = $('#city');
        if ($('#usrPostalcode').val().length==5) {
            $('#cityLoad').show();
            $.post($('#register').data('getcities'),
                {'country': $('#country').val(), 'postalcode': $('#usrPostalcode').val()},
                function (cities) {
                    $(citySelectId).empty();
                    if (!jQuery.isEmptyObject(cities)) {
                        $.each(cities, function (index) {
                            var option = $('<option></option>');
                            option.val($(this)[0].id);
                            option.text($(this)[0].name);
                            $(citySelectId).append(option);
                        });
                        $(citySelectId).val($(citySelectId).children('option:first').val());
                    }
                    else {
                        var option = $('<option value>Code postal invalide</option>');
                        $(citySelectId).append(option);
                    }
                    $('#cityLoad').hide();
                    $('#user-registration-form').formValidation('revalidateField', 'city');
                }, 'json');
        }
    }
}


function userRegistrationUserNirHandlers() {


    $('#user-nir-form')
        .formValidation({
            locale: 'fr_FR'
        })
        .on('success.form.fv', function(e) {
        // Prevent form submission
        e.preventDefault();

        var $form = $(e.target);

        // Use Ajax to submit form data
        $('#user-name-submit').prop('disabled', 'disabled').find('i').removeClass("fa-save").addClass('fa-spinner').addClass('fa-pulse');

        $.ajax({
            data: $form.serialize(),
            type: $form.attr('method'),
            url: $form.attr('action'),
            success: function(response) {
                if (typeof response ==  'object') {
                    $('.modal').modal('hide');
                }
                else {
                    $('.modal-body').html(response);
                }
            }

        });

    });

}