
$( document ).ready(() => {
	// MODALS
	changeModal = () => {
		var curr = $(event.currentTarget).closest('.modal')[0];
		var next = $(event.currentTarget).attr('data-target');
		$(curr).modal('hide');
		$(next).modal({
			backdrop: 'static',
			keyboard: false
		}).modal('show');
	}
	$('#welcome').modal({
		backdrop: 'static',
		keyboard: false
	});
	$('#welcome').modal('show')

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
	httpGet = (url) => {
		var xmlHttp = new XMLHttpRequest();
		xmlHttp.open( "GET", url, false ); // false for synchronous request
		xmlHttp.send( null );
		return xmlHttp.responseText;
	}
	var license = JSON.parse(httpGet('https://api.github.com/repos/Ashwell-Design/MultiDomainX/contents/LICENSE.txt'));
	const licenseElem = document.querySelector('pre.license');
	licenseElem.innerHTML = atob(license['content'])

	// VALIDATION
	//httpGet();
	validateInstallation = () => {
		console.log('Validating');
		var curr = $(event.currentTarget).attr('data-target');
		var next = $(event.currentTarget).attr('data-next');
		setTimeout(() => {
			$(curr).modal('hide');
			$(next).modal({
				backdrop: 'static',
				keyboard: false
			}).modal('show');
		}, 3000);

	}
})