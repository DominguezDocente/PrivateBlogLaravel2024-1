@php

    $currentUrl = Request::url();

@endphp

<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link {{ $currentUrl != url('/') ? 'collapsed' : '' }}" href="{{ route('home.index') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>

        @if(\App\Helpers\RoleHelper::isAuthorized('Secciones.showSections'))
            <li class="nav-item">
                <a class="nav-link {{ !str_contains($currentUrl, 'sections') ? 'collapsed' : '' }}" href="{{ route('sections.index') }}">
                <i class="bi bi-puzzle"></i>
                <span>Secciones</span>
                </a>
            </li>
        @endif

        @if(\App\Helpers\RoleHelper::isAuthorized('Blogs.showBlogs'))
            <li class="nav-item">
                <a class="nav-link {{ !str_contains($currentUrl, 'blogs') ? 'collapsed' : '' }}" href="{{ route('blogs.index') }}">
                <i class="bi bi-card-text"></i>
                <span>Blogs</span>
                </a>
            </li>
        @endif

        @if(\App\Helpers\RoleHelper::isAuthorized('Roles.showRoles'))
            <li class="nav-item">
                <a class="nav-link {{ !str_contains($currentUrl, 'roles') ? 'collapsed' : '' }}" href="{{ route('roles.index') }}">
                <i class="bi bi-shield-lock"></i>
                <span>Roles</span>
                </a>
            </li>
        @endif

        @if(\App\Helpers\RoleHelper::isAuthorized('Usuarios.showUsers'))
            <li class="nav-item">
                <a class="nav-link {{ !str_contains($currentUrl, 'users') ? 'collapsed' : '' }}" href="{{ route('users.index') }}">
                <i class="bi bi-file-person"></i>
                <span>Usuarios</span>
                </a>
            </li>
        @endif

    </ul>

  </aside>
