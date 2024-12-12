 <!-- NAV TOP Start   -->
 <nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
     <div class="navbar-header">
         <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
             <span class="sr-only">Toggle navigation</span>
             <span class="icon-bar"></span>
             <span class="icon-bar"></span>
             <span class="icon-bar"></span>
         </button>
         <a class="navbar-brand" href="index.html">Binary admin</a>
     </div>
     <div style="color: white;padding: 15px 50px 5px 50px;float: right;font-size: 16px;">
         Last access : 11 Dec
         2024 &nbsp; <a href="{{ route('logout') }}" class="btn btn-danger square-btn-adjust">Logout</a> </div>
 </nav>
 <!-- NAV TOP End -->

 <!-- NAV SIDE Start  -->
 <nav class="navbar-default navbar-side" role="navigation">
     <div class="sidebar-collapse">
         <ul class="nav" id="main-menu">
             <li class="text-center">
                 <img src="assets/img/find_user.png" class="user-image img-responsive" />
             </li>


             <li>
                 <a href="{{ route('home') }}" class="{{ request()->is('/') ? 'active-menu' : '' }}">
                     <i class="fa fa-dashboard fa-3x"></i> Dashboard
                 </a>
             </li>

             <li>
                 <a href="{{ route('users') }}" class="{{ request()->is('users') ? 'active-menu' : '' }}">
                     <i class="fa fa-table fa-3x"></i> Admin
                 </a>
             </li>

         </ul>

     </div>

 </nav>
 <!-- NAV SIDE End -->
