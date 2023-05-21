<!DOCTYPE html>
<head>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.3/css/bootstrap.min.css" integrity="sha512-SbiR/eusphKoMVVXysTKG/7VseWii+Y3FdHrt0EpKgpToZeemhqHeZeLWLhJutz/2ut2Vw1uQEj2MbRF+TVBUA==" crossorigin="anonymous" referrerpolicy="no-referrer" as="style" onload="this.onload=null;this.rel='stylesheet'">
</head>
<body>
	<div class="cont">
		<div class="elem">
			<div class="c one"></div>
			<div class="c two"></div>
			<div class="c three"></div>
		</div>
		<div class="frm">
			<section class="card card-popup open" id="welcome">
				<h1>Welcome</h1>
				<div class="btn-deck">
					<button type="button" class="btn" data-method="open" data-target="#license">NEXT</button>
				</div>
			</section>
			<section class="card card-slideover" id="license">
				<h1>License agreeement</h1>
				<div class="btn-deck">
					<button type="button" class="btn btn-close" data-method="close" data-target="#welcome"></button>
					<button type="button" class="btn" data-method="open" data-target="#card-3">NEXT</button>
				</div>
			</section>
			<section class="card card-slideover" id="activation">
				<h1>Activation</h1>
				<div class="btn-deck">
					<button type="button" class="btn btn-close" data-method="close" data-target="#license"></button>
					<button type="button" class="btn" data-method="open" data-target="#card-3">NEXT</button>
				</div>
			</section>
			<section class="card card-slideover" id="card-3">
				<h1>Installing</h1>
				<div class="btn-deck">
					
				</div>
			</section>
		</div>
		<div class="pb" style="--progress=0%; --progress-text='0%';"></div>
		<div class="int">

		</div>
	</div>
</body>