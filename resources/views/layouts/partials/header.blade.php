<header id="navbar">
    <div id="navbar-container" class="boxed">

        <!--Brand logo & name-->
        <!--================================-->
        <div class="navbar-header">
            <a href="/" class="navbar-brand">
                <img src="{{asset("images/logo_lanza.png")}}" alt="" class="brand-icon">
{{--                <img src="{{asset("images/logoAqui.png")}}" alt="lanza" class="brand-icon">--}}
            </a>
        </div>
        <!--================================-->
        <!--End brand logo & name-->


        <!--Navbar Dropdown-->
        <!--================================-->
        <div class="navbar-content clearfix">
            <ul class="nav navbar-top-links pull-left">

                <!--Navigation toogle button-->
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <li class="tgl-menu-btn">
                    <a class="mainnav-toggle" href="#">
                        <i class="demo-pli-view-list"></i>
                    </a>
                </li>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End Navigation toogle button-->

            </ul>
            <ul class="nav navbar-top-links pull-right">

                <!--User dropdown-->
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <li id="dropdown-user" class="dropdown">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle text-right">
                                <span class="ic-user pull-right">
                                    <!--<img class="img-circle img-user media-object" src="img/profile-photos/1.png" alt="Profile Picture">-->
                                    <i class="demo-pli-male"></i>
                                </span>
                        <div class="username hidden-xs">{{ Auth::user()->name }}</div>
                    </a>


                    <div class="dropdown-menu dropdown-menu-md dropdown-menu-right panel-default">

                        <!-- Dropdown heading  -->
                        {{--<div class="pad-all bord-btm">--}}
                            {{--<p class="text-main mar-btm"><span class="text-bold">750GB</span> of 1,000GB Used</p>--}}
                            {{--<div class="progress progress-sm">--}}
                                {{--<div class="progress-bar" style="width: 70%;">--}}
                                    {{--<span class="sr-only">70%</span>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        
                      


                        <!-- User dropdown menu -->
                        <ul class="head-list">
                            {{--<li>--}}
                                {{--<a href="#"><i class="demo-pli-male icon-lg icon-fw"></i> Profile</a>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                                {{--<a href="#"><span class="badge badge-danger pull-right">9</span><i--}}
                                            {{--class="demo-pli-mail icon-lg icon-fw"></i> Messages</a>--}}
                            {{--</li>--}}
                            <li>
                                <a href="#"><i
                                            class="demo-pli-gear icon-lg icon-fw"></i> Configuraciones</a>
                            </li>
                            {{--<li>--}}
                                {{--<a href="#"><i class="demo-pli-information icon-lg icon-fw"></i> Help</a>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                                {{--<a href="#"><i class="demo-pli-computer-secure icon-lg icon-fw"></i> Lock screen</a>--}}
                            {{--</li>--}}
                        </ul>

                        <!-- Dropdown footer -->
                        <div class="pad-all text-right">
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                               class="btn btn-love">
                                <i class="demo-pli-unlock"></i> Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </div>
                </li>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End user dropdown-->

            </ul>
        </div>
        <!--================================-->
        <!--End Navbar Dropdown-->

    </div>
</header>