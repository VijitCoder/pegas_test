/**
 * Script for posting a jokes by ajax request
 */

$(function() {
    /**
     * Submit the form
     */
    $('form#joker').on('submit', function(e) {
        e.preventDefault();

        let $form = $(this),
            $notifiers = {
                success: $('#success-notifier'),
                error: $('#error-notifier'),
            },
            $submitButton = $form.find('button[type=submit]'),
            $indicator = $('#indicator');

        $notifiers.success.html('');
        $notifiers.error.html('');

        $submitButton.toggle();
        $indicator.toggle();

        $.ajax({
            method: $form.attr('method'),
            url: $form.attr('action'),
            data: $form.serialize()
        }).done(function(data) {
           $notifiers.success.html('<p>' + data.message + '</p>');
           $notifiers.success.append('<p>Joke #' + data.jokeId + ': ' + data.joke + '</p>');
        }).fail(function(data) {
            $notifiers.error.html(data.responseJSON.message);
        }).always(function() {
            $indicator.toggle();
            $submitButton.toggle();
        });
    });
});
