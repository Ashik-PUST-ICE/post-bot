(function ($) {
    "use strict";

    // ─── Config from DOM data attributes (no Blade in static JS) ──────────────
    var $modal       = $('#queueSettingsModal');
    var statusRoute  = $('#queueStatusRoute').val();
    var saveRoute    = $('#queueSaveRoute').val();
    var retryRoute   = $('#queueRetryRoute').val();
    var flushRoute   = $('#queueFlushRoute').val();
    var autoOpen     = $('#queueAutoOpen').val() === '1';
    var csrfToken    = $('meta[name="csrf-token"]').attr('content');

    if (!$modal.length) return; // guard: not on admin layout

    // ─── Auto-open on first session visit ─────────────────────────────────────
    $(document).ready(function () {
        if (autoOpen) {
            setTimeout(function () {
                new bootstrap.Modal($modal[0]).show();
                // Tell server to mark session so modal won't re-open
                $.post(saveRoute, { _token: csrfToken, _mark_seen: 1 });
            }, 1200);
        }

        // Load stats every time the modal is opened
        $modal.on('show.bs.modal', loadStats);
    });

    // ─── Load Live Stats via AJAX ──────────────────────────────────────────────
    function loadStats() {
        $.get(statusRoute, function (res) {
            if (!res.status) return;

            // Counters
            $('.queue-stat-pending').text(res.pending);
            $('.queue-stat-failed').text(res.failed);
            $('.queue-stat-driver').text((res.driver || 'sync').toUpperCase());
            $('.queue-failed-badge').text(res.failed);

            // Worker command in terminal box
            if (res.worker_cmd) {
                $('.queue-worker-cmd').text(res.worker_cmd);
            }

            // Failed jobs tab content
            if (parseInt(res.failed) > 0) {
                $('#failedJobsEmpty').addClass('d-none');
                $('#failedJobsActions').removeClass('d-none');
            } else {
                $('#failedJobsEmpty').removeClass('d-none');
                $('#failedJobsActions').addClass('d-none');
            }
        });
    }

    // ─── Live command preview as user adjusts inputs ───────────────────────────
    $(document).on('input', '#queueTries, #queueTimeout, #queueMemory', function () {
        var tries   = $('#queueTries').val()   || 3;
        var timeout = $('#queueTimeout').val() || 60;
        var memory  = $('#queueMemory').val()  || 128;

        // Update hint code block
        $('.queue-hint-tries').text(tries);
        $('.queue-hint-timeout').text(timeout);
        $('.queue-hint-memory').text(memory);

        // Update terminal command box
        $('.queue-worker-cmd').text(
            'php artisan queue:work --tries=' + tries +
            ' --timeout=' + timeout +
            ' --memory=' + memory
        );
    });

    // ─── Save Queue Settings ───────────────────────────────────────────────────
    $(document).on('submit', '#queueSettingsForm', function (e) {
        e.preventDefault();

        var $btn  = $('#queueSaveBtn');
        var orig  = $btn.html();
        $btn.html('<i class="fa-solid fa-spinner fa-spin me-6"></i>Saving...').prop('disabled', true);

        $.ajax({
            type: 'POST',
            url: saveRoute,
            data: $(this).serialize(),
            dataType: 'json',
            success: function (res) {
                if (res.status) {
                    toastr.success(res.message || 'Saved!');
                    if (res.cmd) { $('.queue-worker-cmd').text(res.cmd); }
                } else {
                    toastr.error(res.message || 'Failed to save.');
                }
                $btn.html(orig).prop('disabled', false);
            },
            error: function () {
                toastr.error('Server error. Please try again.');
                $btn.html(orig).prop('disabled', false);
            }
        });
    });

    // ─── Copy Worker Command to Clipboard ─────────────────────────────────────
    $(document).on('click', '.copy-worker-cmd', function () {
        var cmd = $('.queue-worker-cmd').text().trim();
        if (navigator.clipboard) {
            navigator.clipboard.writeText(cmd).then(function () {
                toastr.success('Command copied!');
            });
        } else {
            // Fallback for older browsers
            var $tmp = $('<textarea>').val(cmd).appendTo('body').select();
            document.execCommand('copy');
            $tmp.remove();
            toastr.success('Command copied!');
        }
    });

    // ─── Retry All Failed Jobs ─────────────────────────────────────────────────
    $(document).on('click', '#retryFailedBtn', function () {
        var $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-6"></i>Retrying...');

        commonAjax('POST', retryRoute,
            function (res) {
                if (res.status) {
                    toastr.success(res.message);
                    loadStats();
                } else {
                    toastr.error(res.message);
                }
                $btn.prop('disabled', false)
                    .html('<i class="fa-solid fa-rotate-right me-6"></i>Retry All Failed');
            },
            function () {
                toastr.error('Failed.');
                $btn.prop('disabled', false)
                    .html('<i class="fa-solid fa-rotate-right me-6"></i>Retry All Failed');
            },
            { _token: csrfToken }
        );
    });

    // ─── Flush (Delete) All Failed Jobs ───────────────────────────────────────
    $(document).on('click', '#flushFailedBtn', function () {
        Swal.fire({
            title:              'Clear all failed jobs?',
            text:               'This cannot be undone.',
            icon:               'warning',
            showCancelButton:   true,
            confirmButtonColor: '#ef4444',
            confirmButtonText:  'Yes, clear all',
        }).then(function (result) {
            if (!result.isConfirmed) return;

            commonAjax('POST', flushRoute,
                function (res) {
                    if (res.status) {
                        toastr.success(res.message);
                        loadStats();
                    } else {
                        toastr.error(res.message);
                    }
                },
                null,
                { _token: csrfToken }
            );
        });
    });

})(jQuery);
