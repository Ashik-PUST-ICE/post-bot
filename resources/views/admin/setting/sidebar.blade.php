<ul class="settings-sidebar zList-three">
    <li>
        <a href="{{ route('admin.setting.profile.index') }}"
            class="d-flex justify-content-between align-items-center cg-10 {{ @$activeProfile }}">
            <span class="fs-18 fw-600 lh-22 text-black">{{ __('Profile') }}</span>
            <div class="d-flex text-black"><i class="fa-solid fa-angle-right"></i></div>
        </a>
    </li>

    
        @can('setting-email-template') <li>
        <a href="{{ route('admin.setting.email-template') }}"
            class="d-flex justify-content-between align-items-center cg-10 {{ @$activeEmailSetting }}">
            <span class="fs-18 fw-600 lh-22 text-black">{{ __('Email Template') }}</span>
            <div class="d-flex text-black"><i class="fa-solid fa-angle-right"></i></div>
        </a>
        </li>
        @endcan
        @can('setting-notify-template')
        <li>
            <a href="{{ route('admin.setting.notify-template') }}"
                class="d-flex justify-content-between align-items-center cg-10 {{ @$activeNotifySetting }}">
                <span class="fs-18 fw-600 lh-22 text-black">{{ __('Notification Template') }}</span>
                <div class="d-flex text-black"><i class="fa-solid fa-angle-right"></i></div>
            </a>
        </li>
        @endcan
        @can('setting-multi-language')
        <li>
            <a href="{{ route('admin.setting.languages.index') }}"
                class="d-flex justify-content-between align-items-center cg-10 {{ @$activeLanguagesSetting }}">
                <span class="fs-18 fw-600 lh-22 text-black">{{ __('Multi Language') }}</span>
                <div class="d-flex text-black"><i class="fa-solid fa-angle-right"></i></div>
            </a>
        </li>
        @endcan
        @can('setting-configuration-settings')
        <li>
            <a href="{{ route('admin.setting.configuration-settings') }}"
                class="d-flex justify-content-between align-items-center cg-10 {{ @$activeConfigurationSetting }}">
                <span class="fs-18 fw-600 lh-22 text-black">{{ __('App Configuration ') }}</span>
                <div class="d-flex text-black"><i class="fa-solid fa-angle-right"></i></div>
            </a>
        </li>
        @endcan
        @can('setting-application-settings')
        <li>
            <a href="{{ route('admin.setting.application-settings') }}"
                class="d-flex justify-content-between align-items-center cg-10 {{ @$subApplicationSettingActiveClass }}">
                <span class="fs-18 fw-600 lh-22 text-black">{{ __('Application Setting ') }}</span>
                <div class="d-flex text-black"><i class="fa-solid fa-angle-right"></i></div>
            </a>
        </li>
        @endcan
        @can('setting-storage-settings')
        <li>
            <a href="{{ route('admin.setting.storage.index') }}"
                class="d-flex justify-content-between align-items-center cg-10 {{ @$subStorageSettingActiveClass }}">
                <span class="fs-18 fw-600 lh-22 text-black">{{ __('Storage Setting ') }}</span>
                <div class="d-flex text-black"><i class="fa-solid fa-angle-right"></i></div>
            </a>
        </li>
        @endcan
        @can('setting-logo-settings')
        <li>
            <a href="{{ route('admin.setting.logo-settings') }}"
                class="d-flex justify-content-between align-items-center cg-10 {{ @$subLogoSettingActiveClass }}">
                <span class="fs-18 fw-600 lh-22 text-black">{{ __('Logo Setting') }}</span>
                <div class="d-flex text-black"><i class="fa-solid fa-angle-right"></i></div>
            </a>
        </li>
        @endcan
        @can('setting-maintenance-mode')
        <li>
            <a href="{{ route('admin.setting.maintenance') }}"
                class="d-flex justify-content-between align-items-center cg-10 {{ @$subMaintenanceModeActiveClass }}">
                <span class="fs-18 fw-600 lh-22 text-black">{{ __('Maintenance Mode ') }}</span>
                <div class="d-flex text-black"><i class="fa-solid fa-angle-right"></i></div>
            </a>
        </li>
        @endcan
        @can('setting-cache-settings')
        <li>
            <a href="{{ route('admin.setting.cache-settings') }}"
                class="d-flex justify-content-between align-items-center cg-10 {{ @$subCacheActiveClass }}">
                <span class="fs-18 fw-600 lh-22 text-black">{{ __('Cache Settings') }}</span>
                <div class="d-flex text-black"><i class="fa-solid fa-angle-right"></i></div>
            </a>
        </li>
        @endcan
    
</ul>
