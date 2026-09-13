@php
    $u = auth('admin')->user();
@endphp

<aside class="sidebar" id="sidebar">

    {{-- Brand --}}
    <div class="sidebar-brand">
        <div class="brand-icon"><i class="bi bi-mortarboard-fill"></i></div>
        <span class="brand-text">{{ __('messages.edu_platform') }}</span>
    </div>

    <nav class="sidebar-nav">

        {{-- ── Main ─────────────────────────────────────────── --}}
        <div class="nav-label">{{ __('messages.main') }}</div>
        <ul>
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}"
                   class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-speedometer2"></i>
                    <span>{{ __('messages.dashboard') }}</span>
                </a>
            </li>
        </ul>

      
        {{-- ── Portfolio Content ────────────────────────────── --}}
        <div class="nav-label">{{ __('messages.site_content') }}</div>
        <ul>
            <li class="nav-item">
                <a href="{{ route('admin.hero.edit') }}"
                   class="nav-link {{ request()->routeIs('admin.hero.*') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-display"></i>
                    <span>{{ __('messages.hero_section') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.about.edit') }}"
                   class="nav-link {{ request()->routeIs('admin.about.*') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-info-circle"></i>
                    <span>{{ __('messages.about_section') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.agencies.index') }}"
                   class="nav-link {{ request()->routeIs('admin.agencies.*') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-buildings"></i>
                    <span>{{ __('messages.agencies') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.clients.index') }}"
                   class="nav-link {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-people"></i>
                    <span>{{ __('messages.clients') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.site-settings.index') }}"
                   class="nav-link {{ request()->routeIs('admin.site-settings.*') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-sliders"></i>
                    <span>{{ __('messages.site_settings') }}</span>
                </a>
            </li>
        </ul>

        {{-- ── System ────────────────────────────────────────── --}}
        @if($u?->canAny(['role-table','employee-table','activity-log-table','contact-message-table','setting-edit']))
        <div class="nav-label">{{ __('messages.system') }}</div>
        <ul>
            @if($u?->can('role-table'))
            <li class="nav-item">
                <a href="{{ route('admin.role.index') }}"
                   class="nav-link {{ request()->routeIs('admin.role.*') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-shield-check"></i>
                    <span>{{ __('messages.roles_permissions') }}</span>
                </a>
            </li>
            @endif

            @if($u?->can('employee-table'))
            <li class="nav-item">
                <a href="{{ route('admin.employee.index') }}"
                   class="nav-link {{ request()->routeIs('admin.employee.*') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-people"></i>
                    <span>{{ __('messages.employees') }}</span>
                </a>
            </li>
            @endif

        </ul>
        @endif

    </nav>

    {{-- Sidebar Footer --}}
    <div class="sidebar-footer">
        <ul>
            <li class="nav-item">
                <a href="{{ route('admin.login.edit', auth('admin')->id()) }}" class="nav-link">
                    <i class="nav-icon bi bi-gear"></i>
                    <span>{{ __('messages.settings') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link"
                   onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
                    <i class="nav-icon bi bi-box-arrow-right"></i>
                    <span>{{ __('messages.sign_out') }}</span>
                </a>
            </li>
        </ul>
        <button class="sidebar-collapse-btn" id="sidebarCollapseBtn" title="{{ __('messages.collapse_sidebar') }}">
            <i class="bi bi-arrow-bar-left"></i>
        </button>
    </div>

</aside>
