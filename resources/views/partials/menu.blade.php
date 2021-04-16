<div class="sidebar">
    <nav class="sidebar-nav">

        <ul class="nav">
            <li class="nav-item">
                <a href="{{ route('admin.home') }}" class="nav-link">
                    <i class="nav-icon fas fa-fw fa-tachometer-alt">

                    </i>
                    {{ trans('global.dashboard') }}
                </a>
            </li>            
            @if(Auth::check() && Auth::user()->rl == '1')
                <li class="nav-item nav-dropdown">
                    <a class="nav-link  nav-dropdown-toggle" href="#">
                        <i class="fa-fw fas fa-users nav-icon">

                        </i>
                        {{ trans('cruds.userManagement.title') }}
                    </a>
                    <ul class="nav-dropdown-items">
                        
                        
                        <li class="nav-item">
                            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-user nav-icon">

                                </i>
                                {{ trans('cruds.user.title') }}
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
            
            @if(Auth::check() && (Auth::user()->rl == '1' || Auth::user()->rl == '2' || Auth::user()->rl == '3' ))
                <li class="nav-item">
                        @if(Auth::user()->rl == '1')
                            <a href="{{ route('superadmin.index') }}" class="nav-link {{ request()->is('superadmin') || request()->is('superadmin/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-user nav-icon">
                                </i>Superadmin(dealer)
                            </a>
                        @elseif(Auth::user()->rl == '2'  )
                            <a href="{{ route('admin.index') }}" class="nav-link {{ request()->is('admin') || request()->is('admin/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-user nav-icon">
                                </i>Admin(client)
                            </a>
                        @else
                            <a href="{{ route('player.index') }}" class="nav-link {{ request()->is('player') || request()->is('player/*') ? 'active' : '' }}">
                                <i class="fa-fw fas fa-user nav-icon">
                                </i>Player(users)
                            </a>
                        @endif

                    
                </li>
            @endif

            <?php if(Auth::user()->rl == 3) {  ?>
                <li class="nav-item">
                    <a href="{{ route('admin.player.try-manual.create') }}" class="nav-link {{ request()->is('admin/player/try-manual') ? 'active' : '' }}">
                        <i class="fa-fw fas fa-user nav-icon">
                        </i>
                        Try manualy
                    </a>
                </li>
            <?php }  ?>

            <li class="nav-item">
                <a href="{{ route('auth.change_password') }}" class="nav-link">
                    <i class="nav-icon fas fa-fw fa-key">

                    </i>
                    Change password
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                    <i class="nav-icon fas fa-fw fa-sign-out-alt">

                    </i>
                    {{ trans('global.logout') }}
                </a>
            </li>
        </ul>

    </nav>
    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>