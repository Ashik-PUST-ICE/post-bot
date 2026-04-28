{{--
    Queue Settings Modal
    ─────────────────────────────────────────────────────────────────────────────
    Included in admin layout. Auto-opens on first visit per session.
    Shows: pending jobs, failed jobs, worker command, settings form.
--}}

<input type="hidden" id="queueStatusRoute"      value="{{ route('admin.queue.status') }}">
<input type="hidden" id="queueSaveRoute"         value="{{ route('admin.queue.save') }}">
<input type="hidden" id="queueRetryRoute"        value="{{ route('admin.queue.retry.failed') }}">
<input type="hidden" id="queueFlushRoute"        value="{{ route('admin.queue.flush.failed') }}">
<input type="hidden" id="queueAutoOpen"
    value="{{ !session('queue_modal_seen') ? '1' : '0' }}">

<div class="modal fade" id="queueSettingsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:620px;">
        <div class="modal-content border-0 bd-ra-10" style="overflow:hidden;">

            {{-- Header --}}
            <div class="d-flex align-items-center justify-content-between p-20 pb-0">
                <div class="d-flex align-items-center cg-12">
                    <div class="wh-42 bd-ra-8 d-flex align-items-center justify-content-center"
                        style="background:#6366f11a;">
                        <i class="fa-solid fa-layer-group fs-18" style="color:#6366f1;"></i>
                    </div>
                    <div>
                        <h5 class="fs-17 fw-700 text-textBlack">{{ __('Queue Worker Settings') }}</h5>
                        <p class="fs-12 text-para-text">{{ __('Configure background job processing') }}</p>
                    </div>
                </div>
                <button type="button" class="border-0 bg-transparent text-para-text p-5"
                    data-bs-dismiss="modal">
                    <i class="fa-solid fa-times fs-16"></i>
                </button>
            </div>

            {{-- Live Stats Bar --}}
            <div class="d-flex align-items-stretch cg-0 mx-20 mt-20 bd-one bd-c-stroke bd-ra-8 overflow-hidden">
                <div class="flex-fill p-16 text-center bd-r-one bd-c-stroke" id="statPending">
                    <p class="fs-22 fw-700 text-textBlack queue-stat-pending">—</p>
                    <p class="fs-12 text-para-text mt-2">{{ __('Pending Jobs') }}</p>
                </div>
                <div class="flex-fill p-16 text-center bd-r-one bd-c-stroke" id="statFailed">
                    <p class="fs-22 fw-700 queue-stat-failed" style="color:#ef4444;">—</p>
                    <p class="fs-12 text-para-text mt-2">{{ __('Failed Jobs') }}</p>
                </div>
                <div class="flex-fill p-16 text-center">
                    <p class="fs-13 fw-600 queue-stat-driver text-textBlack">—</p>
                    <p class="fs-12 text-para-text mt-2">{{ __('Queue Driver') }}</p>
                </div>
            </div>

            {{-- Worker Command Box --}}
            <div class="mx-20 mt-15 bd-one bd-c-stroke bd-ra-8 p-14"
                style="background:#0f172a;">
                <p class="fs-11 fw-500 mb-8" style="color:#94a3b8;">
                    {{ __('Run this command in your server terminal:') }}
                </p>
                <div class="d-flex align-items-center justify-content-between">
                    <code class="fs-12 queue-worker-cmd" style="color:#6ee7b7; font-family:monospace;">
                        php artisan queue:work --tries=3 --timeout=60 --memory=128
                    </code>
                    <button type="button" class="border-0 bg-transparent ms-10 copy-worker-cmd flex-shrink-0"
                        title="{{ __('Copy') }}">
                        <i class="fa-solid fa-copy fs-13" style="color:#94a3b8;"></i>
                    </button>
                </div>
            </div>

            {{-- Tabs --}}
            <div class="px-20 pt-20">
                <ul class="nav bd-one bd-c-stroke bd-ra-8 overflow-hidden" id="queueTabs">
                    <li class="nav-item flex-fill">
                        <a class="nav-link active text-center fs-13 fw-500 py-10 bd-ra-0" href="#"
                            data-bs-toggle="tab" data-bs-target="#tabSettings">
                            <i class="fa-solid fa-sliders me-5"></i>{{ __('Settings') }}
                        </a>
                    </li>
                    <li class="nav-item flex-fill bd-l-one bd-c-stroke">
                        <a class="nav-link text-center fs-13 fw-500 py-10 bd-ra-0" href="#"
                            data-bs-toggle="tab" data-bs-target="#tabFailed">
                            <i class="fa-solid fa-triangle-exclamation me-5"></i>{{ __('Failed Jobs') }}
                            <span class="py-2 px-7 bd-ra-50 fs-10 fw-700 ms-3 queue-failed-badge"
                                style="background:#ef44441a;color:#ef4444;">0</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="tab-content px-20 pb-20 mt-15">

                {{-- ── Tab 1: Settings ──────────────────────────────────────── --}}
                <div class="tab-pane fade show active" id="tabSettings">
                    <form id="queueSettingsForm">
                        @csrf
                        <div class="row rg-15">
                            <div class="col-md-6">
                                <label class="zForm-label">{{ __('Queue Connection') }}</label>
                                <select name="queue_connection" id="queueConnection"
                                    class="form-control zForm-control">
                                    @php $cur = env('QUEUE_CONNECTION', 'sync'); @endphp
                                    <option value="sync"     {{ $cur === 'sync'     ? 'selected' : '' }}>
                                        Sync {{ __('(immediate — no worker needed)') }}
                                    </option>
                                    <option value="database" {{ $cur === 'database' ? 'selected' : '' }}>
                                        Database {{ __('(recommended)') }}
                                    </option>
                                    <option value="redis"    {{ $cur === 'redis'    ? 'selected' : '' }}>
                                        Redis {{ __('(high-performance)') }}
                                    </option>
                                </select>
                                <p class="fs-11 text-para-text mt-5">
                                    {{ __('Sync runs jobs immediately inside the webhook request.') }}
                                </p>
                            </div>
                            <div class="col-md-3">
                                <label class="zForm-label">
                                    {{ __('Tries') }}
                                    <span class="text-para-text fs-11">{{ __('(max retries)') }}</span>
                                </label>
                                <input type="number" name="queue_tries" id="queueTries"
                                    class="form-control zForm-control"
                                    min="1" max="10" value="{{ getOption('queue_tries', 3) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="zForm-label">
                                    {{ __('Timeout') }}
                                    <span class="text-para-text fs-11">(s)</span>
                                </label>
                                <input type="number" name="queue_timeout" id="queueTimeout"
                                    class="form-control zForm-control"
                                    min="30" max="600" value="{{ getOption('queue_timeout', 60) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="zForm-label">
                                    {{ __('Memory') }}
                                    <span class="text-para-text fs-11">(MB)</span>
                                </label>
                                <input type="number" name="queue_memory" id="queueMemory"
                                    class="form-control zForm-control"
                                    min="64" max="512" value="{{ getOption('queue_memory', 128) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="zForm-label">
                                    {{ __('Delay') }}
                                    <span class="text-para-text fs-11">(s)</span>
                                </label>
                                <input type="number" name="queue_delay" id="queueDelay"
                                    class="form-control zForm-control"
                                    min="0" max="60" value="{{ getOption('queue_delay', 0) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="zForm-label">{{ __('Supervisor / PM2 Hint') }}</label>
                                <div class="bd-one bd-c-stroke bd-ra-6 p-10"
                                    style="background:#f8fafc;font-family:monospace;font-size:11px;color:#475569;line-height:1.8;">
                                    php artisan queue:work<br>
                                    &nbsp;&nbsp;--tries=<span class="queue-hint-tries">3</span><br>
                                    &nbsp;&nbsp;--timeout=<span class="queue-hint-timeout">60</span><br>
                                    &nbsp;&nbsp;--memory=<span class="queue-hint-memory">128</span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mt-20">
                            <button type="button" class="py-10 px-16 bd-one bd-ra-4 bd-c-stroke bg-white fs-13 fw-500 text-para-text"
                                data-bs-dismiss="modal">
                                {{ __('Close') }}
                            </button>
                            <button type="submit" id="queueSaveBtn"
                                class="py-10 px-20 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-13 fw-600">
                                <i class="fa-solid fa-save me-6"></i>{{ __('Save & Update Command') }}
                            </button>
                        </div>
                    </form>
                </div>

                {{-- ── Tab 2: Failed Jobs ────────────────────────────────────── --}}
                <div class="tab-pane fade" id="tabFailed">
                    <div class="text-center py-20" id="failedJobsEmpty">
                        <i class="fa-solid fa-circle-check fs-36 mb-10" style="color:#10b981;"></i>
                        <p class="fs-14 fw-600 text-textBlack">{{ __('No failed jobs!') }}</p>
                        <p class="fs-12 text-para-text mt-5">{{ __('All jobs processed successfully.') }}</p>
                    </div>
                    <div id="failedJobsActions" class="d-none">
                        <div class="bd-one bd-c-stroke bd-ra-8 p-16 mb-15"
                            style="background:#fef2f2;">
                            <div class="d-flex align-items-center cg-10">
                                <i class="fa-solid fa-triangle-exclamation fs-20" style="color:#ef4444;"></i>
                                <div>
                                    <p class="fs-14 fw-600 text-textBlack">
                                        <span class="queue-stat-failed">0</span> {{ __('failed jobs found') }}
                                    </p>
                                    <p class="fs-12 text-para-text">{{ __('These jobs failed after maximum retries.') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex cg-10">
                            <button type="button" id="retryFailedBtn"
                                class="flex-fill py-11 px-16 bd-one bd-ra-4 fs-13 fw-600 d-flex align-items-center justify-content-center cg-8"
                                style="border-color:#6366f1;background:#6366f11a;color:#6366f1;">
                                <i class="fa-solid fa-rotate-right"></i>{{ __('Retry All Failed') }}
                            </button>
                            <button type="button" id="flushFailedBtn"
                                class="flex-fill py-11 px-16 bd-one bd-ra-4 fs-13 fw-600 d-flex align-items-center justify-content-center cg-8"
                                style="border-color:#ef4444;background:#ef44441a;color:#ef4444;">
                                <i class="fa-solid fa-trash"></i>{{ __('Clear All Failed') }}
                            </button>
                        </div>
                    </div>
                    <div class="mt-15 d-flex justify-content-end">
                        <button type="button" class="py-10 px-16 bd-one bd-ra-4 bd-c-stroke bg-white fs-13 fw-500 text-para-text"
                            data-bs-dismiss="modal">
                            {{ __('Close') }}
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
