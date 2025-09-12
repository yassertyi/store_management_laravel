<div class="dashboard-nav dashboard--nav">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="menu-wrapper">
                    <div class="logo me-5">
                        <a href="{{ url('/') }}"><img src="{{ asset('static/images/logo2.png') }}" alt="logo" /></a>
                        <div class="menu-toggler">
                            <i class="la la-bars"></i>
                            <i class="la la-times"></i>
                        </div>
                        <!-- end menu-toggler -->
                        <div class="user-menu-open">
                            <i class="la la-user"></i>
                        </div>
                        <!-- end user-menu-open -->
                    </div>
                    <div class="dashboard-search-box">
                        <div class="contact-form-action">
                            <form action="#">
                                <div class="form-group mb-0">
                                    <input class="form-control" type="text" name="text" placeholder="ابحث هنا" />
                                    <button class="search-btn"><i class="la la-search"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="nav-btn ms-auto d-flex align-items-center gap-3">
    @include('frontend.admin.partials.notifications')
    @include('frontend.admin.partials.messages')
    @include('frontend.admin.partials.user-menu')
</div>

                    <!-- end nav-btn -->
                </div>
                <!-- end menu-wrapper -->
            </div>
            <!-- end col-lg-12 -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container-fluid -->
</div>
<!-- end dashboard-nav -->