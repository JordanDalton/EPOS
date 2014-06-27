    <!-- Static navbar -->
    <div class="navbar navbar-inverse navbar-blue navbar-static-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="{{ route('home') }}"><i class="fa fa-list"></i> <span class="lato">EPOS</span></a>
          <p class="navbar-text lato" style="font-weight:normal">Electronic Purchase Order System</p>

        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            @if( Auth::check() )
              @if( Auth::user()->isAdmin() )
                <li><a href="{{ route('approvals.index') }}"><i class="fa fa-usd"></i> Accounting</a></li>
              @endif
              <li><a href="{{ route('pos.index') }}"><i class="fa fa-list"></i> My Purchase Orders</a></li>
              <li><a href="{{ route('logout.index') }}"><i class="fa fa-sign-out"></i> Sign Out</a></li>
            @endif
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>
