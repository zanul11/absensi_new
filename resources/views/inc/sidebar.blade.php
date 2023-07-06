<!--  BEGIN SIDEBAR  -->
<div class="sidebar-wrapper sidebar-theme">
    @php
    $currentUrl = Request::path();

    //get menu berdasarkan role user
    switch(auth()->user()->role){

    case 'admin' :
    $masterMenu = config('menus.menu_admin');
    break;

    default :
    $masterMenu = config('menus.menu_super');

    }
    @endphp
    <nav id="sidebar">
        <div class="shadow-bottom"></div>
        <ul class="list-unstyled menu-categories" id="accordionExample">
            <li class="menu {{ ($category_name === $currentUrl) ? 'active' : '' }}">
                <a href="{{route('home')}}" data-active="{{ ($category_name === $currentUrl) ? 'true' : 'false' }}" aria-expanded="{{ ($category_name === $currentUrl) ? 'true' : 'false' }}" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        <span>Home </span>
                    </div>
                </a>
            </li>


            @foreach($masterMenu as $menu)
            <li class="menu {{ ($menu['url'] === $category_name) ? 'active' : '' }}">
                <a href="{{($menu['caret'])?'#'.$menu['url']:route($menu['url'].'.index')}}" data-active="{{ ($menu['url'] === $category_name) ? 'true' : 'false' }}" aria-expanded="{{ ($menu['url'] === $category_name) ? 'true' : 'false' }}" data-toggle="{{($menu['caret'])?'collapse':''}}" class="dropdown-toggle">
                    <div class="">
                        {!!$menu['icon']!!}
                        <span>{{ucwords($menu['title'])}}</span>
                    </div>
                    @if($menu['caret'])
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                    @endif
                </a>
                @if($menu['caret'])
                <ul class="collapse submenu list-unstyled {{ ($menu['url'] === $category_name) ? 'show' : '' }}" id="{{$menu['url']}}" data-parent="#accordionExample">
                    @foreach($menu['sub_menu'] as $subMenu)
                    <li class="{{ ($subMenu['url'] === $page_name) ? 'active' : '' }}">
                        <a href="{{route($subMenu['url'].'.index')}}"> {{ucwords($subMenu['title'])}} </a>
                    </li>
                    @endforeach
                </ul>
                @endif
            </li>
            @endforeach

            <!-- <li class="menu {{ ($category_name === 'dashboard') ? 'active' : '' }}">
                <a href="#dashboard" data-active="{{ ($category_name === 'dashboard') ? 'true' : 'false' }}" data-toggle="collapse" aria-expanded="{{ ($category_name === 'dashboard') ? 'true' : 'false' }}" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        <span>Dashboard</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled {{ ($category_name === 'dashboard') ? 'show' : '' }}" id="dashboard" data-parent="#accordionExample">
                    <li class="{{ ($page_name === 'sales') ? 'active' : '' }}">
                        <a href="/sales"> Sales </a>
                    </li>
                    <li class="{{ ($page_name === 'analytics') ? 'active' : '' }}">
                        <a href="/analytics"> Analytics </a>
                    </li>
                </ul>
            </li> -->
            <!-- <li class="menu {{ ($category_name === 'fonticons') ? 'active' : '' }}">
                <a href="/font_icons" data-active="{{ ($category_name === 'fonticons') ? 'true' : 'false' }}" aria-expanded="{{ ($category_name === 'fonticons') ? 'true' : 'false' }}" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-target">
                            <circle cx="12" cy="12" r="10"></circle>
                            <circle cx="12" cy="12" r="6"></circle>
                            <circle cx="12" cy="12" r="2"></circle>
                        </svg>
                        <span>Font Icons</span>
                    </div>
                </a>
            </li> -->
        </ul>
    </nav>
</div>
<!--  END SIDEBAR  -->