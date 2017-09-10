require('./bootstrap');


//Handel Custom fields
let customFieldsIndex = 0;
$('#newCustomField').on('click', function (e) {
    e.preventDefault();

    let customFieldsList = $('.customFields .line');
    let customFieldsListLength = customFieldsList.length;

    if (customFieldsListLength >= 5 ) {
        alert('You\'ve already added 5 custom fields ! ');
        return false;
    }

    let template = $('#customFieldTemplate');
    let $clone = template.clone()
        .removeClass('hide')
        .removeAttr('id')
        .attr('data-field-index', customFieldsIndex);

    if (customFieldsListLength) {
        $clone.insertAfter($('.customFields .line:last'))
    } else {
        $clone.insertAfter($('.customFieldsLabel'))
    }

    //Update last inserted fields names
    $clone
        .find('[name="fieldName"]').attr('name', 'customFields[' + customFieldsListLength + '][fieldName]').end()
        .find('[name="fieldValue"]').attr('name', 'customFields[' + customFieldsListLength + '][fieldValue]').end();

    customFieldsIndex ++;

});

//Delete custom field
$('.customFields').on('click', '.removeLine', function (e) {
    e.preventDefault();
    $(this).parents('.line').remove();
});

//Save contact
$('#contactsFormIndex').on('submit', function (e) {
    e.preventDefault();

    var data = $(this).serialize();

    axios.post('/contacts', data)
        .then(function (response) {

            console.log(response.data);

            if (response.status === 200) {
                //Check the server response
            }

        })
        .catch(function (error) {
            console.log(error.response.status);

            if (error.response) {

                //Handle server side errors
                if (error.response.status === 422) {

                    let AlertTemplate = $('#alert_template');
                    let AlertClone = AlertTemplate.clone()
                        .removeClass('hidden')
                        .removeAttr('id')
                        .addClass('alert-danger');

                    let errors = '';

                    //Loop through errors and display
                    _.each(error.response.data, function (e) {
                        errors += '<li>';
                        errors += e;
                        errors += '</li>';
                    });

                    AlertClone.insertBefore('#contactsFormIndex > input[name="_token"]');
                    AlertClone.find('ul').html(errors)
                }


            } else if (error.request) {
                //console.log(error.request);
            } else {
                //console.log('Error', error.message);
            }

        });


});