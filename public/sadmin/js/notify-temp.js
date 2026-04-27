(function ($) {
    "use strict";

    $(document).on('click', '.edit', function () {
        var id = $(this).data('id')
        commonAjax('GET', $('#notifyTemplateConfigRoute').val(), emailTemplateConfigRes, emailTemplateConfigRes, { 'id': id })
    });

    function emailTemplateConfigRes(response) {
        if (response['status'] == true) {
            var selector = $('#notifyConfigureModal');
            var fields = '';

            Object.entries(response.data.fields).forEach((field) => {
                fields += '<span class="singleField" data-field="' + field[0] + '">' + field[0] + '</span>';
            });

            selector.find('.templateFields').html(fields);
            selector.find('input[name=id]').val(response.data.template.id);
            selector.find('input[name=title]').val(response.data.template.title);
            selector.find('textarea[name=body]').summernote('code', response.data.template.body);
            selector.modal('show');
        } else {
            commonHandler(response)
        }
    }


})(jQuery)
