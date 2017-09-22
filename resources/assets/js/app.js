require('./bootstrap');

/**
 *  Format server side errors and display them to the user
 * @param errors
 */
function formatAndDisplayErrors(errors) {
    let AlertTemplate = $('#alert_template');
    let AlertClone = AlertTemplate.clone()
        .removeClass('hidden')
        .removeAttr('id')
        .addClass('alert-danger');

    let errorsDom = '';

    //Loop through errors and display
    _.each(errors, function (e) {
        errorsDom += '<li>';
        errorsDom += e;
        errorsDom += '</li>';
    });

    AlertClone.insertBefore('#contactsFormIndex > input[name="_token"]');
    AlertClone.find('ul').html(errorsDom)
}

function handleModalFormSubmission() {
    $('#contactsFormIndex').on('submit', function (e) {
        let self = $(this);
        e.preventDefault();

        // Add a request interceptor
        axios.interceptors.request.use(function (config) {
            self.find('button[type=submit] > i.fa-spinner').removeClass('hidden');
            return config;
        });

        let $requestOptions = {
            method: self.attr('method'),
            url: self.attr('action'),
            data: self.serialize()
        };

        axios($requestOptions)
            .then(function (response) {

                //@TODO: we may need to check the response.data.status
                if (response.status === 200) {
                    //Display notification
                    window.toastr["success"](response.data.message);
                    //Hide model
                    $('#contactModal').modal('hide');
                    //Hide spinner
                    self.find('button[type=submit] > i.fa-spinner').addClass('hidden');
                }

            })
            .catch(function (error) {

                if (error.response) {

                    //Handle server side errors
                    if (error.response.status === 422) {
                        formatAndDisplayErrors(error.response.data);
                    }

                } else if (error.request) {
                    //console.log(error.request);
                } else {
                    //console.log('Error', error.message);
                }

            });
    });
}

function handleFormCustomFields(customFieldsIndex) {
    $('#newCustomField').on('click', function (e) {
        e.preventDefault();

        let customFieldsList = $('.customFields .line');
        let customFieldsListLength = customFieldsList.length;

        //Limit the number of fields to 5
        if (customFieldsListLength >= 5) {
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

        customFieldsIndex++;
    });
}

function handleContactEdit() {
    $('#app').on('click', '#edit-contact', function (e) {
        e.preventDefault();

        let $link = $(this).data('url');

        axios.get($link).then(function (response) {

            let $data = response.data;
            let $modal = $('#contactModal');

            $modal
                .find('input#name').val($data.name).end()
                .find('input#surname').val($data.surname).end()
                .find('input#email').val($data.email).end()
                .find('input#phone').val($data.phone).end();


            //Loop through custom fields and display them
            let $customFieldTemplate = $('#customFieldTemplate');
            _.each(response.data.custom_fields, function (value, key, list) {

                let fieldIndex = _.indexOf(_.toArray(list), value);

                let $customField = $customFieldTemplate.clone()
                    .removeClass('hide')
                    .removeAttr('id');

                $customField
                    .find('input[name=fieldName]').attr('name', 'customFields[' + fieldIndex + '][fieldName]').val(key).end()
                    .find('input[name=fieldValue]').attr('name', 'customFields[' + fieldIndex + '][fieldValue]').val(value).end();

                $customField.insertAfter($modal.find('.customFieldsLabel'));

            });

            //Update modal title
            $modal.find('#contactModalLabel').text('Edit contact: ' + $data.name + ' ' + $data.surname);

            //Update form action to include contact ID
            let $form = $modal.find('form');
            $('<input type="hidden" name="_method" value="PATCH">').insertBefore($form.find('input[name="_token"]'));
            $form.attr('action', '/contacts/' + $data.id);

            //Show Modal
            $modal.modal('show');

            //Remove the custom fields after the modal was hidden
            $modal.on('hidden.bs.modal', function (e) {
                $modal.find('.line').remove();
            })

        }).catch(function () {

        })
    });
}
jQuery(document).ready(function ($) {

    //Handel Custom fields
    let customFieldsIndex = 0;
    handleFormCustomFields(customFieldsIndex);

    //Delete custom field
    $('.customFields').on('click', '.removeLine', function (e) {
        e.preventDefault();
        console.log(e);
        $(this).parents('.line').remove();
    });

    // Add or edit  contact
    // @TODO: Client side validation
    handleModalFormSubmission();

    // Handle contact dit
    handleContactEdit();

});
