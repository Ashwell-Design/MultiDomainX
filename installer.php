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
		<div class="pb" style="--progress=0%; --progress-text='0%';"></div>
		<div class="int">

		</div>
	</div>
	
	<div class="modal fade modal-xl show" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title" id="exampleModalLabel">Welcome</h1>
				</div>
				<div class="modal-body">
					<p>Thank you for choosing MultiDomainX, the powerful multi-domain management application. This installer will guide you through the process of setting up MultiDomainX on your system.</p>
					<p>Our installer ensures a seamless setup experience, allowing you to effortlessly manage multiple domains with ease.</p>
					<p>Please follow the instructions provided in each step to complete the installation successfully. If you encounter any issues or have questions along the way, our support team is ready to assist you.</p>
					<p>Let's get started and unlock the potential of effortless multi-domain management with MultiDomainX!</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#license">NEXT</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade modal-xl" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title" id="exampleModalLabel">License agreeement</h1>
				</div>
				<div class="modal-body">
					<pre class="license overflow-visible m-auto"></pre>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#welcome">BACK</button>
					<button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#activation">NEXT</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade modal-xl" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title" id="exampleModalLabel">Activation</h1>
				</div>
				<div class="modal-body">
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#license">BACK</button>
					<button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#installing">ACCEPT</button>>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade modal-xl" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header" id="exampleModalLabel">
					<h1 class="modal-title">Installing</h1>
				</div>
				<div class="modal-body">
				</div>
				<div class="modal-footer">
				</div>
			</div>
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