<!-- Sidebar -->
<div class="zSidebar" data-background="{{ asset('assets/images/sidebar-bg.png') }}">
    <div class="zSidebar-overlay"></div>
    <!-- Logo -->
    <a href="{{ route('index') }}" class="d-block mx-26 mb-27 max-w-146 pt-23">
        <img class="max-h-35" src="{{ getSettingImage('app_logo') }}" alt="{{ getOption('app_name') }}"/>
    </a>
    <!-- Menu & Logout -->
    <div class="zSidebar-fixed">
        <ul class="zSidebar-menu" id="sidebarMenu">
            @if (auth()->user()->role == USER_ROLE_ADMIN)
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                       class="{{ $activeDashboard ?? '' }} d-flex align-items-center cg-10">
                        <div class="d-flex">
                            <svg width="25" height="24" viewBox="0 0 25 24" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M6.88979 10.3929C6.14657 10.3929 5.62851 10.3924 5.22349 10.3635C4.82565 10.3351 4.59466 10.2819 4.4186 10.2051C3.89833 9.97813 3.48308 9.56288 3.25609 9.04261C3.17928 8.86655 3.12616 8.63556 3.09774 8.23773C3.0688 7.83271 3.06836 7.31465 3.06836 6.57143C3.06836 5.82821 3.0688 5.31015 3.09774 4.90513C3.12616 4.50729 3.17928 4.2763 3.25609 4.10024C3.48308 3.57997 3.89833 3.16473 4.4186 2.93773C4.59466 2.86092 4.82565 2.80781 5.22349 2.77938C5.6285 2.75045 6.14657 2.75 6.88979 2.75C7.63301 2.75 8.15107 2.75045 8.55609 2.77938C8.95392 2.80781 9.18491 2.86092 9.36097 2.93773C9.88124 3.16473 10.2965 3.57997 10.5235 4.10024C10.6003 4.2763 10.6534 4.50729 10.6818 4.90513C10.7108 5.31015 10.7112 5.82821 10.7112 6.57143C10.7112 7.31465 10.7108 7.83271 10.6818 8.23773C10.6534 8.63556 10.6003 8.86655 10.5235 9.04261C10.2965 9.56288 9.88124 9.97813 9.36097 10.2051C9.18491 10.2819 8.95392 10.3351 8.55609 10.3635C8.15107 10.3924 7.63301 10.3929 6.88979 10.3929Z"
                                    stroke="white" stroke-opacity="0.7" stroke-width="1.5"/>
                                <path
                                    d="M6.88979 21.25C6.14657 21.25 5.62851 21.2496 5.22349 21.2207C4.82565 21.1922 4.59466 21.1391 4.4186 21.0623C3.89833 20.8353 3.48308 20.4201 3.25609 19.8998C3.17928 19.7237 3.12616 19.4927 3.09774 19.0949C3.0688 18.6899 3.06836 18.1718 3.06836 17.4286C3.06836 16.6854 3.0688 16.1673 3.09774 15.7623C3.12616 15.3645 3.17928 15.1335 3.25609 14.9574C3.48308 14.4372 3.89833 14.0219 4.4186 13.7949C4.59466 13.7181 4.82565 13.665 5.22349 13.6366C5.6285 13.6076 6.14657 13.6072 6.88979 13.6072C7.63301 13.6072 8.15107 13.6076 8.55609 13.6366C8.95392 13.665 9.18491 13.7181 9.36097 13.7949C9.88124 14.0219 10.2965 14.4372 10.5235 14.9574C10.6003 15.1335 10.6534 15.3645 10.6818 15.7623C10.7108 16.1673 10.7112 16.6854 10.7112 17.4286C10.7112 18.1718 10.7108 18.6899 10.6818 19.0949C10.6534 19.4927 10.6003 19.7237 10.5235 19.8998C10.2965 20.4201 9.88124 20.8353 9.36097 21.0623C9.18491 21.1391 8.95392 21.1922 8.55609 21.2207C8.15107 21.2496 7.63301 21.25 6.88979 21.25Z"
                                    stroke="white" stroke-opacity="0.7" stroke-width="1.5"/>
                                <path
                                    d="M17.7472 10.3929C17.004 10.3929 16.4859 10.3924 16.0809 10.3635C15.6831 10.3351 15.4521 10.2819 15.276 10.2051C14.7558 9.97813 14.3405 9.56288 14.1135 9.04261C14.0367 8.86655 13.9836 8.63556 13.9552 8.23773C13.9262 7.83271 13.9258 7.31465 13.9258 6.57143C13.9258 5.82821 13.9262 5.31015 13.9552 4.90513C13.9836 4.50729 14.0367 4.2763 14.1135 4.10024C14.3405 3.57997 14.7558 3.16473 15.276 2.93773C15.4521 2.86092 15.6831 2.80781 16.0809 2.77938C16.4859 2.75045 17.004 2.75 17.7472 2.75C18.4904 2.75 19.0085 2.75045 19.4135 2.77938C19.8113 2.80781 20.0423 2.86092 20.2184 2.93773C20.7387 3.16473 21.1539 3.57997 21.3809 4.10024C21.4577 4.2763 21.5108 4.50729 21.5393 4.90513C21.5682 5.31015 21.5686 5.82821 21.5686 6.57143C21.5686 7.31465 21.5682 7.83271 21.5393 8.23773C21.5108 8.63556 21.4577 8.86655 21.3809 9.04261C21.1539 9.56288 20.7387 9.97813 20.2184 10.2051C20.0423 10.2819 19.8113 10.3351 19.4135 10.3635C19.0085 10.3924 18.4904 10.3929 17.7472 10.3929Z"
                                    stroke="white" stroke-opacity="0.7" stroke-width="1.5"/>
                                <path
                                    d="M17.7472 21.25C17.004 21.25 16.4859 21.2496 16.0809 21.2207C15.6831 21.1922 15.4521 21.1391 15.276 21.0623C14.7558 20.8353 14.3405 20.4201 14.1135 19.8998C14.0367 19.7237 13.9836 19.4927 13.9552 19.0949C13.9262 18.6899 13.9258 18.1718 13.9258 17.4286C13.9258 16.6854 13.9262 16.1673 13.9552 15.7623C13.9836 15.3645 14.0367 15.1335 14.1135 14.9574C14.3405 14.4372 14.7558 14.0219 15.276 13.7949C15.4521 13.7181 15.6831 13.665 16.0809 13.6366C16.4859 13.6076 17.004 13.6072 17.7472 13.6072C18.4904 13.6072 19.0085 13.6076 19.4135 13.6366C19.8113 13.665 20.0423 13.7181 20.2184 13.7949C20.7387 14.0219 21.1539 14.4372 21.3809 14.9574C21.4577 15.1335 21.5108 15.3645 21.5393 15.7623C21.5682 16.1673 21.5686 16.6854 21.5686 17.4286C21.5686 18.1718 21.5682 18.6899 21.5393 19.0949C21.5108 19.4927 21.4577 19.7237 21.3809 19.8998C21.1539 20.4201 20.7387 20.8353 20.2184 21.0623C20.0423 21.1391 19.8113 21.1922 19.4135 21.2207C19.0085 21.2496 18.4904 21.25 17.7472 21.25Z"
                                    stroke="white" stroke-opacity="0.7" stroke-width="1.5"/>
                            </svg>
                        </div>
                        <span class="">{{ __('Dashboard') }}</span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->role == USER_ROLE_ADMIN)

                @can('Manage Application Setting')
                    <li>
                        <a href="#manage-application-menu" data-bs-toggle="collapse" role="button"
                           aria-expanded="{{ isset($showManageApplicationSetting) ? 'true' : '' }}"
                           aria-controls="manage-application-menu"
                           class="d-flex align-items-center cg-10 {{ isset($showManageApplicationSetting) ? 'active' : 'collapsed' }}">
                            <div class="d-flex">
                                <svg width="20" height="22" viewBox="0 0 20 22" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18.5 6L10 1L1.5 6V16L10 21L18.5 16V6Z" stroke="white"
                                          stroke-opacity="0.7"
                                          stroke-width="1.5" stroke-linejoin="round"/>
                                    <path d="M6 8.49902L9.9965 11L13.9975 8.49902M10 11V15.5" stroke="white"
                                          stroke-opacity="0.7" stroke-width="1.5" stroke-linecap="round"
                                          stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <span class="">{{ __('Application Settings') }}</span>
                        </a>
                        <div class="collapse {{ $showManageApplicationSetting ?? '' }}"
                             id="manage-application-menu"
                             data-bs-parent="#sidebarMenu">
                            <ul class="zSidebar-submenu">
                                <li><a class="{{ $activeApplicationSetting ?? '' }}"
                                       href="{{ route('admin.setting.application-settings') }}">{{ __('General Settings') }}</a>
                                </li>
                                <li><a class="{{ $activeConfigurationSetting ?? '' }}"
                                       href="{{ route('admin.setting.configuration-settings') }}">{{ __('Configurations
                                                                                                   Settings') }}</a>
                                </li>
                                <li><a class="{{ $activeCurrenciesSetting ?? '' }}"
                                       href="{{ route('admin.setting.currencies.index') }}">{{ __('Currency Settings') }}</a>
                                </li>
                                <li><a class="{{ $activeGatewaySetting ?? '' }}"
                                       href="{{ route('admin.setting.gateway.index') }}">{{ __('Payment Gateways') }}</a>
                                </li>
                                <li><a class="{{ $activeLanguagesSetting ?? '' }}"
                                       href="{{ route('admin.setting.languages.index') }}">{{ __('Language Settings') }}</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endcan

                <li>
                    <a href="{{ route('admin.roles.index') }}"
                       class="{{ $activeRoles ?? '' }} d-flex align-items-center cg-10">
                        <div class="d-flex">
                            <svg width="20" height="22" viewBox="0 0 20 22" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 12C13.3137 12 16 9.31371 16 6C16 2.68629 13.3137 0 10 0C6.68629 0 4 2.68629 4 6C4 9.31371 6.68629 12 10 12Z" stroke="white" stroke-opacity="0.7" stroke-width="1.5"/>
                                <path d="M10 14C5.02944 14 1 17.5817 1 22H19C19 17.5817 14.9706 14 10 14Z" stroke="white" stroke-opacity="0.7" stroke-width="1.5"/>
                            </svg>
                        </div>
                        <span class="">{{ __('Roles & Permissions') }}</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.users.index') }}"
                       class="{{ $activeUsers ?? '' }} d-flex align-items-center cg-10">
                        <div class="d-flex">
                            <svg width="20" height="22" viewBox="0 0 20 22" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 12C13.3137 12 16 9.31371 16 6C16 2.68629 13.3137 0 10 0C6.68629 0 4 2.68629 4 6C4 9.31371 6.68629 12 10 12Z" stroke="white" stroke-opacity="0.7" stroke-width="1.5"/>
                                <path d="M10 14C5.02944 14 1 17.5817 1 22H19C19 17.5817 14.9706 14 10 14Z" stroke="white" stroke-opacity="0.7" stroke-width="1.5"/>
                            </svg>
                        </div>
                        <span class="">{{ __('Users') }}</span>
                    </a>
                </li>
            @endif
        </ul>
        <ul>
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               class="d-inline-flex align-items-center cg-15 pt-17 pb-30 px-25">
                <img src="{{ asset('assets/images/icon/logout.svg') }}" alt=""/>
                <p class="fs-14 fw-500 lh-16 text-white-70">{{ __('Logout') }}</p>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </ul>
    </div>
</div>
