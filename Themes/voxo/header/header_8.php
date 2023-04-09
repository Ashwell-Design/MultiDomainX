<nav class="py-2 bg-body-tertiary border-bottom">
	<div class="container d-flex flex-wrap">
		<ul class="nav me-auto">
			<%[l:nav]%>
		</ul>
		<ul class="nav">
			<li class="nav-item"><a href="#" class="nav-link link-dark px-2">Login</a></li>
			<li class="nav-item"><a href="#" class="nav-link link-dark px-2">Sign up</a></li>
		</ul>
	</div>
</nav>
<header class="py-3 mb-4 border-bottom">
	<div class="container d-flex flex-wrap justify-content-center">
		<a href="/" class="d-flex align-items-center mb-3 mb-lg-0 me-lg-auto link-body-emphasis text-decoration-none" style="color: inherit;">
			<span class="fs-4"><%[g:Company Name]%></span>
		</a>
		<form class="col-12 col-lg-auto mb-3 mb-lg-0" role="search">
			<input type="search" class="form-control" placeholder="Search..." aria-label="Search">
		</form>
	</div>
</header>