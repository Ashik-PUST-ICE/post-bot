(function ($) {
    "use strict";

    // ── Cached route references (set via hidden inputs in the blade) ───────────
    var replyRoute       = $('#replyRoute').val();
    var getMessagesRoute = $('#getMessagesRoute').val();
    var forInboxRoute    = $('#forInboxRoute').val();
    var noTplText        = $('#noTemplatesText').val() || 'No templates found.';

    // ── Scroll thread to bottom ────────────────────────────────────────────────
    function scrollToBottom() {
        var thread = document.getElementById('messageThread');
        if (thread) thread.scrollTop = thread.scrollHeight;
    }
    scrollToBottom();

    // ── Load fresh messages from server and inject into thread ─────────────────
    function refreshMessages() {
        $.getJSON(getMessagesRoute, function (res) {
            if (res.html !== undefined) {
                $('#messageThread').html(res.html);
                scrollToBottom();
            }
            if (res.count !== undefined) {
                $('#totalMsgCount').text(res.count);
            }
        });
    }

    // ── Reply form — fully custom AJAX submit ─────────────────────────────────
    $('#replyForm').on('submit', function (e) {
        e.preventDefault();

        var body = $.trim($('#replyBody').val());
        if (!body) {
            toastr.warning('Please type a message before sending.');
            return;
        }

        var $btn = $(this).find('[type=submit]');
        $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i>');

        $.ajax({
            url    : replyRoute,
            method : 'POST',
            data   : {
                _token : $('meta[name="csrf-token"]').attr('content'),
                body   : body,
            },
            success: function (res) {
                if (res.status) {
                    toastr.success(res.message || 'Reply sent.');
                    $('#replyBody').val('');
                    hidePicker();
                    refreshMessages();
                } else {
                    toastr.error(res.message || 'Could not send reply.');
                }
            },
            error: function (xhr) {
                var msg = xhr.responseJSON && xhr.responseJSON.message
                    ? xhr.responseJSON.message
                    : 'Something went wrong. Please try again.';
                toastr.error(msg);
            },
            complete: function () {
                $btn.prop('disabled', false)
                    .html('<i class="fa-solid fa-paper-plane"></i>');
            },
        });
    });

    // ── Conversation status change ─────────────────────────────────────────────
    $('#conversationStatusSelect').on('change', function () {
        $.post($(this).data('route'), {
            _token: $('meta[name="csrf-token"]').attr('content'),
            status: $(this).val()
        }, function (res) {
            if (res.status) toastr.success(res.message);
            else toastr.error(res.message);
        });
    });

    // ── Quick Reply Template Picker ────────────────────────────────────────────
    var templates     = [];
    var pickerVisible = false;

    function loadTemplates() {
        $.getJSON(forInboxRoute, function (res) {
            templates = res.data || [];
            renderTemplates(templates);
        });
    }

    function renderTemplates(list) {
        var $ul = $('#templateList').empty();
        if (!list.length) {
            $ul.append('<li class="px-14 py-8 fs-13 text-para-text">' + noTplText + '</li>');
            return;
        }
        list.forEach(function (t) {
            var $li = $('<li>')
                .addClass('px-14 py-10 border-bottom tpl-item')
                .css('cursor', 'pointer')
                .attr('data-content', t.content)
                .html(
                    '<span class="d-block fs-13 fw-600 text-textBlack">'
                        + $('<div>').text(t.title).html()
                    + '</span>'
                    + '<span class="d-block fs-11 text-para-text mt-2">'
                        + $('<div>').text(t.content.substring(0, 70)).html() + '…'
                    + '</span>'
                );
            $ul.append($li);
        });
    }

    function showPicker() {
        if (!templates.length) loadTemplates();
        $('#templateDropdown').show();
        pickerVisible = true;
        $('#templateSearch').val('').focus();
    }

    function hidePicker() {
        $('#templateDropdown').hide();
        pickerVisible = false;
    }

    $('#btnPickTemplate2').on('click', function (e) {
        e.stopPropagation();
        if (pickerVisible) hidePicker(); else showPicker();
    });

    $(document).on('click', '.tpl-item', function () {
        var content = $(this).data('content');
        var $ta     = $('#replyBody');
        var cur     = $ta.val();
        // If textarea has text, insert at cursor position; otherwise replace
        if (cur.trim().length === 0) {
            $ta.val(content);
        } else {
            var pos   = $ta[0].selectionStart;
            var after = cur.substring(0, pos) + content + cur.substring(pos);
            $ta.val(after);
        }
        hidePicker();
        $ta.focus();
    });

    $('#templateSearch').on('input', function () {
        var q = $(this).val().toLowerCase();
        renderTemplates(templates.filter(function (t) {
            return t.title.toLowerCase().indexOf(q) !== -1
                || t.content.toLowerCase().indexOf(q) !== -1;
        }));
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest('#btnPickTemplate2, #templateDropdown').length) {
            hidePicker();
        }
    });

})(jQuery);
