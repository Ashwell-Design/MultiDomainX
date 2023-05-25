<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css" integrity="sha512-SbiR/eusphKoMVVXysTKG/7VseWii+Y3FdHrt0EpKgpToZeemhqHeZeLWLhJutz/2ut2Vw1uQEj2MbRF+TVBUA==" crossorigin="anonymous" referrerpolicy="no-referrer">
	<style>
		@import url('https://fonts.googleapis.com/css2?family=Comfortaa:wght@300&display=swap');
		*::-webkit-scrollbar {
			width: 5px;
			background-color: transparent;
		}
		*::-webkit-scrollbar-thumb {
			border-radius: 10px;
			-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
			background-color: #F5F5F5;
		}
		body{
			font-family: 'Comfortaa';
			padding: unset;
			margin: unset;
			background-image: url('https://i.pinimg.com/originals/f9/d1/df/f9d1df4811f6fb6703941b834d123e70.jpg');
			
			background-repeat: no-repeat;
			background-size: cover;
		}
		pre.license {
			width: fit-content;
			max-width: 100%;
		}
		.pb::before,
		.pb::after {
			position: fixed;
			display: inherit;
			bottom: inherit;
			height: inherit;
		}
		.pb::before {
			content: "";
			background-color: silver;
			opacity: 0.5;
			width: 100vw;
			z-index: -1;
		}
		.pb {
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
			z-index: 2000;
		}
		.pb::after {
			left: 0;
			bottom: 2rem;
			height: auto;
			left: 2rem;
			content: var(--progress-text);
			color: #00f1cf;
			z-index: -1;
			font-size: 2rem;
		}
	</style>
</head>
<body>
	<div class="pb" style="--progress:0%; --progress-text:'0%';"></div>
	
	<div class="modal modal-xl fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true" aria-labelledby="welcomeModalLabel" id="welcome">
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
	<div class="modal modal-xl fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true" aria-labelledby="licneseModalLabel" id="license">
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
	<div class="modal modal-xl fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true" aria-labelledby="activationModalLabel" id="activation">
		<div class="modal-dialog modal-fullscreen-xl-down" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title" id="activationModalLabel">Activation</h1>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="Input">License key</label>
						<input type="licenseKey" class="form-control" id="Input" aria-describedby="licenseHelp" placeholder="XXXXXX-XXXXXX-XXXXXX-XXXXXX-XXXXXX">
						<small id="licenseHelp" class="form-text text-muted">Please enter the licence key supplied.</small>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-danger" data-target="#license" onClick="changeModal()">BACK</button>
					<button type="button" class="btn btn-outline-primary" data-target="#validation" data-next="#installation" onClick="changeModal(); validateInstallation()">NEXT</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal modal-xl fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true" aria-labelledby="validationModalLabel" id="validation">
		<div class="modal-dialog modal-fullscreen-xl-down" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title" id="validationModalLabel">Validating</h1>
				</div>
				<div class="modal-body">
					<div class="progress">
						<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
					</div>
				</div>
				<div class="modal-footer">
				</div>
			</div>
		</div>
	</div>
	<div class="modal modal-xl fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true" aria-labelledby="installationModalLabel" id="installation">
		<div class="modal-dialog modal-fullscreen-xl-down" role="document">
			<div class="modal-content">
				<div class="modal-header" id="installationModalLabel">
					<h1 class="modal-title">Installing</h1>
				</div>
				<div class="modal-body">
					<div class="progress">
						<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
					</div>
				</div>
				<div class="modal-footer">
				</div>
			</div>
		</div>
	</div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.bundle.min.js" integrity="sha512-i9cEfJwUwViEPFKdC1enz4ZRGBj8YQo6QByFTF92YXHi7waCqyexvRD75S5NVTsSiTv7rKWqG9Y5eFxmRsOn0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="https://api.github.com/repos/Ashwell-Design/MultiDomainX/contents/installer.js?ref=Development"></script>
</body>