<!-- Sidebar -->
<div data-aos="fade-right" data-aos-duration="1000" class="zSidebar"
    data-background="{{ asset('assets/images/sidebar-graphic.png') }}">
    <div class="zSidebar-overlay"></div>
    <div class="zSidebar-wrap h-100">

        <!-- Logo -->
        <a href="{{ route('admin.dashboard') }}" class="zSidebar-logo">
            <img src="{{ getSettingImage('app_logo') }}" alt="{{ getOption('app_name') }}" />
        </a>

        <!-- Menu & Logout -->
        <div class="zSidebar-fixed">
            <ul class="zSidebar-menu" id="sidebarMenu">

                {{-- ── Core ─────────────────────────────────────────────────── --}}

                {{-- ── Overview  ─────────────────────────────────────────────── --}}
                <li class="zSidebar-label">
                    <span>{{ __('Overview ') }}</span>
                </li>
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                        class="d-flex align-items-center cg-21 {{ @$activeDashboard }}">
                        <div class="d-flex">
                            <svg width="20" height="19" viewBox="0 0 20 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M10.1757 0.044908C10.0586 0.0406133 9.94141 0.0406133 9.82434 0.044908C9.05153 0.0732586 8.35564 0.336049 7.59263 0.7735C6.84907 1.1998 5.99304 1.82175 4.90689 2.61089L4.84785 2.65379C3.76168 3.44293 2.90563 4.06488 2.27042 4.64031C1.6186 5.23079 1.15363 5.81142 0.887852 6.53765C0.847591 6.64766 0.811378 6.75911 0.779286 6.87178C0.567436 7.61553 0.602321 8.35857 0.78258 9.21941C0.958244 10.0583 1.28523 11.0646 1.70011 12.3415L3.14557 16.7902C3.45713 17.749 4.35067 18.3982 5.35888 18.3982C6.64415 18.3982 7.68608 17.3563 7.68608 16.071V13.7803C7.68608 13.2433 8.12135 12.8081 8.65827 12.8081H11.3417C11.8787 12.8081 12.3139 13.2433 12.3139 13.7803V16.071C12.3139 17.3563 13.3559 18.3982 14.6411 18.3982C15.6493 18.3982 16.5429 17.749 16.8544 16.7902L18.2999 12.3415C18.7148 11.0646 19.0418 10.0583 19.2174 9.21941C19.3977 8.35857 19.4326 7.61553 19.2207 6.87178C19.1886 6.75912 19.1524 6.64766 19.1122 6.53765C18.8464 5.81142 18.3814 5.23079 17.7296 4.64031C17.0944 4.06487 16.2383 3.44293 15.1522 2.65378L15.0931 2.61088C14.007 1.82174 13.1509 1.1998 12.4074 0.7735C11.6444 0.336049 10.9485 0.0732586 10.1757 0.044908Z"
                                    fill="#7881A4" />
                            </svg>
                        </div>
                        <span>{{ __('Dashboard') }}</span>
                    </a>
                </li>

                {{-- ── Messaging ─────────────────────────────────────────────── --}}
                <li class="zSidebar-label">
                    <span>{{ __('Messaging') }}</span>
                </li>

                {{-- Platforms --}}
                <li>
                    <a href="{{ route('admin.platforms.index') }}"
                        class="d-flex align-items-center cg-21 {{ @$activePlatforms }}">
                        <div class="d-flex">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8.4 3H4.6C3.72 3 3 3.72 3 4.6V8.4C3 9.28 3.72 10 4.6 10H8.4C9.28 10 10 9.28 10 8.4V4.6C10 3.72 9.28 3 8.4 3Z" stroke="#7881A4" stroke-width="1.5"/>
                                <path d="M19.4 3H15.6C14.72 3 14 3.72 14 4.6V8.4C14 9.28 14.72 10 15.6 10H19.4C20.28 10 21 9.28 21 8.4V4.6C21 3.72 20.28 3 19.4 3Z" stroke="#7881A4" stroke-width="1.5"/>
                                <path d="M8.4 14H4.6C3.72 14 3 14.72 3 15.6V19.4C3 20.28 3.72 21 4.6 21H8.4C9.28 21 10 20.28 10 19.4V15.6C10 14.72 9.28 14 8.4 14Z" stroke="#7881A4" stroke-width="1.5"/>
                                <path d="M17.5 14V21M14 17.5H21" stroke="#7881A4" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <span>{{ __('Platforms') }}</span>
                    </a>
                </li>

                {{-- Connect Account --}}
                <li>
                    <a href="{{ route('admin.meta-oauth.picker') }}"
                        class="d-flex align-items-center cg-21 {{ @$activeMetaPicker }}">
                        <div class="d-flex">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M14 11.998C14 9.506 11.683 7 8.857 7C7.429 7 6.143 7.636 5.286 8.636C4.429 9.636 4 10.998 4 11.998C4 13.002 4.429 14.364 5.286 15.364C6.143 16.364 7.429 17 8.857 17C11.683 17 14 14.494 14 11.998Z" stroke="#7881A4" stroke-width="1.5" stroke-miterlimit="10"/>
                                <path d="M10 11.998C10 14.49 12.317 17 15.143 17C16.571 17 17.857 16.364 18.714 15.364C19.571 14.364 20 13.002 20 11.998C20 10.994 19.571 9.632 18.714 8.632C17.857 7.632 16.571 7 15.143 7C12.317 7 10 9.506 10 11.998Z" stroke="#7881A4" stroke-width="1.5" stroke-miterlimit="10"/>
                            </svg>
                        </div>
                        <span>{{ __('Connect Account') }}</span>
                    </a>
                </li>

                {{-- Inbox --}}
                <li>
                    <a href="{{ route('admin.inbox.index') }}"
                        class="d-flex align-items-center cg-21 {{ @$activeInbox }}">
                        <div class="d-flex">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8 12H8.01M12 12H12.01M16 12H16.01M21 12C21 16.418 16.97 20 12 20C10.5 20 9.07 19.66 7.8 19.06L3 20L4.44 15.77C3.53 14.43 3 12.78 3 11C3 6.582 7.03 3 12 3C16.97 3 21 6.582 21 11C21 11.34 20.98 11.67 20.94 12H21Z" stroke="#7881A4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <span>{{ __('Inbox') }}</span>
                    </a>
                </li>

                {{-- ── AI ────────────────────────────────────────────────────── --}}
                <li class="zSidebar-label">
                    <span>{{ __('AI Agent') }}</span>
                </li>

                {{-- AI Settings --}}
                <li>
                    <a href="{{ route('admin.ai-agent.index') }}"
                        class="d-flex align-items-center cg-21 {{ @$activeAiAgent }}">
                        <div class="d-flex">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2C8.13 2 5 5.13 5 9C5 11.38 6.19 13.47 8 14.74V17C8 17.55 8.45 18 9 18H15C15.55 18 16 17.55 16 17V14.74C17.81 13.47 19 11.38 19 9C19 5.13 15.87 2 12 2Z" stroke="#7881A4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M9 21H15" stroke="#7881A4" stroke-width="1.5" stroke-linecap="round"/>
                                <path d="M9 18V19.5C9 20.33 9.67 21 10.5 21H13.5C14.33 21 15 20.33 15 19.5V18" stroke="#7881A4" stroke-width="1.5"/>
                            </svg>
                        </div>
                        <span>{{ __('AI Configuration') }}</span>
                    </a>
                </li>

                {{-- Agent Knowledge --}}
                <li>
                    <a href="{{ route('admin.ai-agent.knowledge') }}"
                        class="d-flex align-items-center cg-21 {{ @$activeAiKnowledge }}">
                        <div class="d-flex">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke="#7881A4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <span>{{ __('Agent Knowledge') }}</span>
                    </a>
                </li>

                {{-- Quick Reply Templates --}}
                <li>
                    <a href="{{ route('admin.reply-templates.index') }}"
                        class="d-flex align-items-center cg-21 {{ @$activeReplyTemplates }}">
                        <div class="d-flex">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V9L13 2Z" stroke="#7881A4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M13 2V9H20" stroke="#7881A4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M9 15H15" stroke="#7881A4" stroke-width="1.5" stroke-linecap="round"/>
                                <path d="M9 11H15" stroke="#7881A4" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <span>{{ __('Reply Templates') }}</span>
                    </a>
                </li>

                {{-- ── Email ───────────────────────────────────────────────────── --}}
                <li class="zSidebar-label">
                    <span>{{ __('Email') }}</span>
                </li>

                {{-- Mail Configuration --}}
                <li>
                    <a href="{{ route('admin.mail.config') }}"
                        class="d-flex align-items-center cg-21 {{ @$activeMailConfig }}">
                        <div class="d-flex">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 4H20C21.1 4 22 4.9 22 6V18C22 19.1 21.1 20 20 20H4C2.9 20 2 19.1 2 18V6C2 4.9 2.9 4 4 4Z" stroke="#7881A4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M22 6L12 13L2 6" stroke="#7881A4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <span>{{ __('Mail Config') }}</span>
                    </a>
                </li>

                {{-- Email Templates --}}
                <li>
                    <a href="{{ route('admin.mail.templates') }}"
                        class="d-flex align-items-center cg-21 {{ @$activeMailTemplates }}">
                        <div class="d-flex">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M14 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V8L14 2Z" stroke="#7881A4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M14 2V8H20M16 13H8M16 17H8M10 9H8" stroke="#7881A4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <span>{{ __('Email Templates') }}</span>
                    </a>
                </li>

                {{-- ── Configuration ──────────────────────────────────────────── --}}
                <li class="zSidebar-label">
                    <span>{{ __('Configuration') }}</span>
                </li>

                {{-- Payment Gateways --}}
                @can('Manage Application Setting')
                <li>
                    <a href="{{ route('admin.setting.gateway.index') }}"
                        class="d-flex align-items-center cg-21 {{ @$activeGateway }}">
                        <div class="d-flex">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2 8.5H22" stroke="#7881A4" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M6 16.5H8" stroke="#7881A4" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M10.5 16.5H14.5" stroke="#7881A4" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M22 14.03V16.11C22 19.62 21.11 20.5 17.56 20.5H6.44C2.89 20.5 2 19.62 2 16.11V7.89C2 4.38 2.89 3.5 6.44 3.5H17.56C21.11 3.5 22 4.38 22 7.89V10.5" stroke="#7881A4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <span>{{ __('Payment Gateways') }}</span>
                    </a>
                </li>
                @endcan

                {{-- Meta App Config (FB/IG/WA credentials) --}}
                <li>
                    <a href="{{ route('admin.meta-app.index') }}"
                        class="d-flex align-items-center cg-21 {{ @$activeMetaApp }}">
                        <div class="d-flex">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z" stroke="#7881A4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M7 7h.01" stroke="#7881A4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <span>{{ __('Meta App Config') }}</span>
                    </a>
                </li>

                {{-- ── Management ──────────────────────────────────────────────── --}}
                <li class="zSidebar-label">
                    <span>{{ __('Management') }}</span>
                </li>

                {{-- Team Members --}}
                <li>
                    <a href="{{ route('admin.users.index') }}"
                        class="d-flex align-items-center cg-21 {{ @$activeUsers }}">
                        <div class="d-flex">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17 21V19C17 17.9391 16.5786 16.9217 15.8284 16.1716C15.0783 15.4214 14.0609 15 13 15H5C3.93913 15 2.92172 15.4214 2.17157 16.1716C1.42143 16.9217 1 17.9391 1 19V21" stroke="#7881A4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M9 11C11.2091 11 13 9.20914 13 7C13 4.79086 11.2091 3 9 3C6.79086 3 5 4.79086 5 7C5 9.20914 6.79086 11 9 11Z" stroke="#7881A4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M23 21V19C22.9993 18.1137 22.7044 17.2528 22.1614 16.5523C21.6184 15.8519 20.8581 15.3516 20 15.13" stroke="#7881A4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M16 3.13C16.8604 3.35031 17.623 3.85071 18.1676 4.55232C18.7122 5.25392 19.0078 6.11683 19.0078 7.005C19.0078 7.89317 18.7122 8.75608 18.1676 9.45768C17.623 10.1593 16.8604 10.6597 16 10.88" stroke="#7881A4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <span>{{ __('Team Members') }}</span>
                    </a>
                </li>

                {{-- Role & Permission --}}
                @can('role-permission')
                    <li>
                        <a href="{{ route('admin.roles.index') }}"
                            class="d-flex align-items-center cg-21 {{ @$activeRole }}">
                            <div class="d-flex">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 1L3 5V11C3 16.55 6.84 21.74 12 23C17.16 21.74 21 16.55 21 11V5L12 1Z" stroke="#7881A4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <span>{{ __('Role & Permission') }}</span>
                        </a>
                    </li>
                @endcan

                {{-- ── Billing ─────────────────────────────────────────────────── --}}
                <li class="zSidebar-label">
                    <span>{{ __('Billing') }}</span>
                </li>

                {{-- My Subscription --}}
                <li>
                    <a href="{{ route('admin.subscription.index') }}"
                        class="d-flex align-items-center cg-21 {{ @$activeSubscription }}">
                        <div class="d-flex">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21Z" stroke="#7881A4" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M8.25 10.5L10.5 12.75L15.75 7.5" stroke="#7881A4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M7 16.5H17" stroke="#7881A4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <span>{{ __('My Subscription') }}</span>
                    </a>
                </li>

                


                {{-- ── Account ──────────────────────────────────────────────────── --}}
                <li class="zSidebar-label">
                    <span>{{ __('Account') }}</span>
                </li>

                {{-- Profile --}}
                <li>
                    <a href="{{ route('admin.setting.profile.index') }}"
                        class="d-flex align-items-center cg-21 {{ @$activeProfile }}">
                        <div class="d-flex">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20 21V19C20 17.9391 19.5786 16.9217 18.8284 16.1716C18.0783 15.4214 17.0609 15 16 15H8C6.93913 15 5.92172 15.4214 5.17157 16.1716C4.42143 16.9217 4 17.9391 4 19V21" stroke="#7881A4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M12 11C14.2091 11 16 9.20914 16 7C16 4.79086 14.2091 3 12 3C9.79086 3 8 4.79086 8 7C8 9.20914 9.79086 11 12 11Z" stroke="#7881A4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <span>{{ __('My Profile') }}</span>
                    </a>
                </li>

            </ul>

            <a href="{{ route('logout') }}" class="d-inline-flex align-items-center cg-15 pt-17 pb-30 px-30">
                <p class="fs-14 fw-600 lh-14 text-sidebar-text">{{ __('Logout') }}</p>
            </a>
        </div>
    </div>
</div>
