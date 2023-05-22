<!DOCTYPE html>
<head>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.3/css/bootstrap.min.css" integrity="sha512-SbiR/eusphKoMVVXysTKG/7VseWii+Y3FdHrt0EpKgpToZeemhqHeZeLWLhJutz/2ut2Vw1uQEj2MbRF+TVBUA==" crossorigin="anonymous" referrerpolicy="no-referrer" as="style" onload="this.onload=null;this.rel='stylesheet'">
	<style>
		@import url('https://fonts.googleapis.com/css2?family=Comfortaa:wght@300&display=swap');
		body{
			font-family: 'Comfortaa';
			padding: unset;
			margin: unset;
			background-image: url('https://i.pinimg.com/originals/f9/d1/df/f9d1df4811f6fb6703941b834d123e70.jpg');
			background-position: center center;
			background-repeat: no-repeat;
			background-size: cover;
		}
		/* CONTAINER */
			.cont {
				display: block;
				width: 100vw;
				height: 100vh;
				overflow: hidden;
			}
			.cont::before {
				content: "";
				background: #16ccfe;
				height: 100vh;
				width: 100vw;
				opacity: 0.3;
				position: fixed;
				z-index: -5;
			}
		/* PROGRESS BAR */
			.cont .pb::before,
			.cont .pb::after {
				position: fixed;
				display: inherit;
				bottom: inherit;
				height: inherit;
			}
			.cont .pb::before {
				content: "";
				background-color: silver;
				opacity: 0.5;
				width: 100vw;
				z-index: -1;
			}
			.cont .pb {
				--progress: ;
				--progress-text: '';
				position: absolute;
				display: block;
				height: 5px;
				bottom: 0;
				opacity: 1;
				background: linear-gradient(#00f1cf, #00e6c7, #00f1cf);
				width: var(--progress);
			}
			.cont .pb::after {
				left: 0;
				bottom: 2rem;
				height: auto;
				left: 2rem;
				content: var(--progress-text);
				color: #00f1cf;
				z-index: -1;
				font-size: 2rem;
			}
		/* ELEMENTS */
			.elem .c { }
			.elem .c .1 { }
			.elem .c .2 { }
			.elem .c .3 { }
		/* FORM */
			.card::-webkit-scrollbar {
				width: 5px;
				background-color: #F5F5F5;
			}
			.card::-webkit-scrollbar-track {
				-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
				border-radius: 10px;
				background-color: #F5F5F5;

			}
			.card::-webkit-scrollbar-thumb {
				border-radius: 10px;
				-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
				background-color: #555;
			}
			.card {
				--anim-dura: .6s;
				--anim-dura-alt: .55s;
				display: flex;
				opacity: 0;
				width: calc(100% - 2rem);
				max-height: calc(100% - 2rem);
				padding: 1rem;
				position: fixed;
				top: 50%;
				left: 50%;
				background: white;
				box-shadow: 0 0 6px #0001, 0 6px 6px #0003;
				border-radius: .5rem;
				transform: translate(-50%, -50%);
				transition: all var(--anim-dura) cubic-bezier(0.68, -0.55, 0.265, 1.55);
				transition-delay: 0s;
				overflow-y: scroll;
			}
			.card.open {
				opacity: 1;
				transform: translate(-50%, -50%);
				transition-delay: 0s;
			}
			.card:not(.open.idle) {
				transition-delay: var(--anim-dura);
			}
			.card.open.idle {
				box-shadow: 0 0 2px #0001, 0 2px 2px #0002;
				transform: translate(-50%, -38%) scale(.8);
				transition-delay: 0s;
			}
			.card.open:after {
				/*content: '';*/
				opacity: 0;
				width: 100%;
				height: 100%;
				position: absolute;
				top: 100vh;
				left: 100vw;
				background: #0001;
				border-radius: inherit;
				transition: opacity var(--anim-dura) cubic-bezier(0.68, -0.55, 0.265, 1.55);
				transition-delay: var(--anim-dura-alt);
			}
			.card.open.idle:after {
				top: 0;
				left: 0;
				opacity: 1;
				transition-delay: var(--anim-dura-alt);
			}
			.card.card-popup:not(.open) {
				transform: translate(-50%, -50%) scale(.8);
			}
			.card.card-slideover {
				transition-delay: var(--anim-dura);
			}
			.card.card-slideover:not(.open) {
				transform: translate(-50%, 100vh);
				transition-delay: 0s;
			}
			.card h1 {
				font-size: 2rem;
			}
			.card .btn-deck {
				display: flex;
			}
			.card .btn-deck button:not(:last-child) {
				margin-right: 1rem;
			}
			.card .btn-deck button ~ button {
				padding: 0 1rem;
			}
		/* INTRODUCTION */
			.cont .int { }
	</style>
</head>
<body>
	<div class="cont">
		<div class="elem">
			<div class="c one"></div>
			<div class="c two"></div>
			<div class="c three"></div>
		</div>
		<div class="frm">
			<div class="modal fade" tabindex="-1" role="dialog">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h1 class="modal-title">Welcome</h1>
						</div>
						<div class="modal-body">
							<p>Thank you for choosing MultiDomainX, the powerful multi-domain management application. This installer will guide you through the process of setting up MultiDomainX on your system.</p>
							<p>Our installer ensures a seamless setup experience, allowing you to effortlessly manage multiple domains with ease.</p>
							<p>Please follow the instructions provided in each step to complete the installation successfully. If you encounter any issues or have questions along the way, our support team is ready to assist you.</p>
							<p>Let's get started and unlock the potential of effortless multi-domain management with MultiDomainX!</p>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-outline-primary" data-method="open" data-target="#license">NEXT</button>
						</div>
					</div>
				</div>
			</section>
			<section class="card card-slideover w-75" id="license">
				<h1>License agreeement</h1>
				<pre class="license overflow-visible m-auto"></pre>
				<div class="btn-deck">
					<button type="button" class="btn btn-outline-danger" data-method="close" data-target="#welcome">BACK</button>
					<button type="button" class="btn btn-outline-primary" data-method="open" data-target="#activation">NEXT</button>
				</div>
			</section>
			<section class="card card-slideover w-75" id="activation">
				<h1>Activation</h1>
				<p>

				</p>
				<div class="btn-deck">
					<button type="button" class="btn btn-outline-danger" data-method="close" data-target="#license">BACK</button>
					<button type="button" class="btn btn-outline-primary" data-method="open" data-target="#installing">ACCEPT</button>
				</div>
			</section>
			<section class="card card-slideover w-75" id="installing">
				<h1>Installing</h1>
				<p>

				</p>
				<div class="btn-deck">
					
				</div>
			</section>
		</div>
		<div class="pb" style="--progress=0%; --progress-text='0%';"></div>
		<div class="int">

		</div>
	</div>
	<script>
		// PROGRESS BAR
		updateProgress = (mode, value) => {
			if(value < 101) {
				var newProgress;
				const element = document.querySelector('.pb');
				const computedStyle = getComputedStyle(element);
				var progress = parseInt(computedStyle.getPropertyValue('--progress'));
				switch(mode) {
					case 'add':
						newProgress = progress+value;
						if(newProgress>100) newProgress=100;
						break;
					case 'set':
						newProgress = value;
						break;
					case 'sub':
						newProgress = progress-value;
						if(newProgress<0) newProgress=0;
						break;
				}
				element.style.setProperty('--progress', `${newProgress}%`);
				element.style.setProperty('--progress-text', `'${newProgress}%'`);
			}
		}
		updateProgress('set', 37)

		// LICENSE
		function httpGet(theUrl) {
			var xmlHttp = new XMLHttpRequest();
			xmlHttp.open( "GET", theUrl, false ); // false for synchronous request
			xmlHttp.send( null );
			return xmlHttp.responseText;
		}
		var license = JSON.parse(httpGet('https://api.github.com/repos/Ashwell-Design/MultiDomainX/contents/LICENSE.txt'));
		const licenseElem = document.querySelector('pre.license');
		licenseElem.innerHTML = atob(license['content'])
	</script>
</body>