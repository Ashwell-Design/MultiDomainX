<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css" integrity="sha512-SbiR/eusphKoMVVXysTKG/7VseWii+Y3FdHrt0EpKgpToZeemhqHeZeLWLhJutz/2ut2Vw1uQEj2MbRF+TVBUA==" crossorigin="anonymous" referrerpolicy="no-referrer">
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
				transition: width 1s;
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
			/*
			.elem .c { }
			.elem .c .one { }
			.elem .c .two { }
			.elem .c .three { }
			*/
		/* FORM */
			*::-webkit-scrollbar {
				width: 5px;
				background-color: #F5F5F5;
			}
			*::-webkit-scrollbar-track {
				-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
				border-radius: 10px;
				background-color: #F5F5F5;

			}
			*::-webkit-scrollbar-thumb {
				border-radius: 10px;
				-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
				background-color: #555;
			}
			.show {
				display: block;
			}
		/* INTRODUCTION */
			/*
			.cont .int { }
			*/
	</style>
</head>
<body class="modal-open">
	<div class="cont">
		<div class="elem">
			<div class="c one"></div>
			<div class="c two"></div>
			<div class="c three"></div>
		</div>
		<div class="pb" style="--progress:0%; --progress-text:'0%';"></div>
		<div class="int">

		</div>
	</div>
	
	<div class="modal modal-xl fade show" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="welcomeModalLabel" id="welcome">
		<div class="modal-dialog modal-fullscreen-xl-down" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title" id="welcomeModalLabel">Welcome</h1>
				</div>
				<div class="modal-body">
					<p>Thank you for choosing MultiDomainX, the powerful multi-domain management application. This installer will guide you through the process of setting up MultiDomainX on your system.</p>
					<p>Our installer ensures a seamless setup experience, allowing you to effortlessly manage multiple domains with ease.</p>
					<p>Please follow the instructions provided in each step to complete the installation successfully. If you encounter any issues or have questions along the way, our support team is ready to assist you.</p>
					<p>Let's get started and unlock the potential of effortless multi-domain management with MultiDomainX!</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-target="#license" onClick="changeModal()">NEXT</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal modal-xl fade" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="licneseModalLabel" id="license">
		<div class="modal-dialog modal-fullscreen-xl-down" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title" id="licneseModalLabel">License agreeement</h1>
				</div>
				<div class="modal-body">
					<pre class="license overflow-visible m-auto"></pre>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-danger" data-target="#welcome" onClick="changeModal()">BACK</button>
					<button type="button" class="btn btn-outline-primary" data-target="#activation" onClick="changeModal()">NEXT</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal modal-xl fade" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="activationModalLabel" id="activation">
		<div class="modal-dialog modal-fullscreen-xl-down" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title" id="activationModalLabel">Activation</h1>
				</div>
				<div class="modal-body">
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-danger" data-target="#license" onClick="changeModal()">BACK</button>
					<button type="button" class="btn btn-outline-primary" data-target="#installing" onClick="changeModal()">ACCEPT</button>>
				</div>
			</div>
		</div>
	</div>
	<div class="modal modal-xl fade" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="installationModalLabel" id="installation">
		<div class="modal-dialog modal-fullscreen-xl-down" role="document">
			<div class="modal-content">
				<div class="modal-header" id="installationModalLabel">
					<h1 class="modal-title">Installing</h1>
				</div>
				<div class="modal-body">
				</div>
				<div class="modal-footer">
				</div>
			</div>
		</div>
	</div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.bundle.min.js" integrity="sha512-i9cEfJwUwViEPFKdC1enz4ZRGBj8YQo6QByFTF92YXHi7waCqyexvRD75S5NVTsSiTv7rKWqG9Y5eFxmRsOn0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script>
		// MODALS
		changeModal = () => {
			var curr = $(event.currentTarget).closest('.modal')[0];
			var next = $(event.currentTarget).attr('data-target');

			$(curr).modal('hide');
			$(next).modal('show');
		}
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