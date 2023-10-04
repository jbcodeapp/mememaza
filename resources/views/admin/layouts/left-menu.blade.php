<?php /* ?>
<a href="index3.html" class="brand-link">
      <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">{{ SITE_HEADING }}</span>
    </a>
	<?php */ ?>

<div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        
        <div class="info">
          <a href="#" class="d-block">Mememaza</a>
        </div>
      </div>
      <!-- Sidebar Menu -->
	  

      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item menu-open">
            <a href="{{ url('dashboard') }}" class="nav-link {{ (request()->is('dashboard')) ? 'active' : '' }}"><i class="nav-icon fas fa-tachometer-alt"></i><p>Dashboard</p></a>
          </li>
		  
		  <li class="nav-item menu-open">
            <a href="{{ url('categories') }}" class="nav-link {{ (request()->is('categories')) ? 'active' : '' }}"><i class="nav-icon fas fa-tachometer-alt"></i><p>Category</p></a>
          </li>
		  
		  <li class="nav-item menu-open">
            <a href="{{ url('reels') }}" class="nav-link {{ (request()->is('reels')) ? 'active' : '' }}"><i class="nav-icon fas fa-tachometer-alt"></i><p>Reel</p></a>
          </li>
		  
			<li class="nav-item menu-open">
            <a href="{{ url('post') }}" class="nav-link {{ (request()->is('post')) ? 'active' : '' }}"><i class="nav-icon fas fa-tachometer-alt"></i><p>Post</p></a>
          </li>
		  
		  <li class="nav-item menu-open">
            <a href="{{ url('banner') }}" class="nav-link {{ (request()->is('banner')) ? 'active' : '' }}"><i class="nav-icon fas fa-tachometer-alt"></i><p>Banner</p></a>
          </li>
		  
		  <li class="nav-item menu-open">
            <a href="{{ url('story') }}" class="nav-link {{ (request()->is('story')) ? 'active' : '' }}"><i class="nav-icon fas fa-tachometer-alt"></i><p>Story</p></a>
          </li>
		  
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>