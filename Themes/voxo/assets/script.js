$(document).ready(function(){
	// Get the context menu element
	const $contextmenu = $('#contextmenu');
	if($contextmenu.length > 0) {
		// Hide the element when focus is lost
		$(document).on('click keydown', (event) => {
			const $target = $(event.target);
			const forAttr = $target.attr('for');

			if (typeof forAttr !== 'undefined' && forAttr !== null && forAttr !== '') {
				if(forAttr == 'newtab') {
					window.open($($target[0]).attr('href'), '_blank');
				} else if(forAttr == 'newwindow') {
					const width = 800;
					const height = 600;
					const left = (screen.width / 2) - (width / 2);
					const top = (screen.height / 2) - (height / 2);
					const newWindow = window.open($($target[0]).attr('href'), 'newWindow', `toolbar=yes,location=yes,directories=yes,status=yes,menubar=yes,scrollbars=yes,resizable=yes,width=${width},height=${height},left=${left},top=${top}`);
					if (newWindow) {
						newWindow.focus();
					}					
				} else if(forAttr == 'copylink') {
					// Copy a string to the clipboard
					navigator.clipboard.writeText(
						$($target[0]).attr('href')
					).then(() => {
						console.log('Text copied to clipboard');
					}).catch((error) => {
						console.error('Error copying text to clipboard:', error);
					});
				} else if(forAttr == 'sharelink') {
					// Open the system share window
					navigator.share({
						title: '',
						text: '',
						url: $($target[0]).attr('href'),
					}).then(() => {
						console.log('Content shared successfully');
					}).catch((error) => {
						console.error('Error sharing content:', error);
					});
				} else if(forAttr == 'back') {
					// Check if the browser can go back
					if (window.history && window.history.length > 1) {
						// Go back to the previous page
						window.history.back();
					}
				} else if(forAttr == 'forward') {
					// Check if the browser can go forward
					if (window.history && window.history.length > 1 && window.history.length > window.history.currentIndex + 1) {
						// Go forward to the next page
						window.history.forward();
					}
				} else if(forAttr == 'reload') {
					// Reload the current window
					location.reload();
				} else if(forAttr == 'copy') {
					// Copy highlighted text to the clipboard
					const textToCopy = window.getSelection().toString();
					if (textToCopy.length > 0) {
						event.preventDefault();
						event.originalEvent.clipboardData.setData('text/plain', textToCopy);
					}
				} else if(forAttr == 'cut') {
					// Cut highlighted text to the clipboard
					const textToCut = window.getSelection().toString();
					if (textToCut.length > 0) {
						event.preventDefault();
						event.originalEvent.clipboardData.setData('text/plain', textToCut);
						window.getSelection().deleteFromDocument();
					}
				} else if(forAttr == 'paste') {
				} else if(forAttr == 'selectall') {
				} else if(forAttr == 'undo') {
				} else if(forAttr == 'redo') {
				} else if(forAttr == 'sharepage') {
				} else if(forAttr == 'savepage') {
				} else if(forAttr == 'print') {
				} else if(forAttr == 'viewsource') {
				}
				$contextmenu.hide();
			} else if($target.closest($contextmenu).length === 0 || event.keyCode === 27) {
				$contextmenu.hide();
			}
		});
		// Hide the element when user scrolls
		$(window).on('scroll', () => {
			$contextmenu.hide();
		});
		// Show the context menu and set its position to the cursor position when right-click event occurs
		$(document).on('contextmenu', (event) => {
			const $target = $(event.target);
			// prevent the default context menu from showing
			event.preventDefault();

			// Check if the target element is a link
			const $link = $target.closest('[href]');
			const isLink = $link.length > 0 && ($link[0].nodeName === 'A' || $link.closest('a').length > 0);

			// Check if the target element is a text input or selected text
			const selection = window.getSelection();
			const hasSelection = selection && selection.toString().length > 0;
			const isTextInput = $target.is('input[type="text"], textarea');

			if(isLink) {
				$contextmenu.find('li[for=links]').show();
				$contextmenu.find('li[for=browser]').hide();
				$contextmenu.find('li[for=text]').hide();
				$contextmenu.find('li[for=default]').show();
			} else if(hasSelection || isTextInput) {
				$contextmenu.find('li[for=links]').hide();
				$contextmenu.find('li[for=browser]').hide();
				$contextmenu.find('li[for=text]').show();
				$contextmenu.find('li[for=default]').show();
			} else {
				$contextmenu.find('li[for=links]').hide();
				$contextmenu.find('li[for=browser]').show();
				$contextmenu.find('li[for=text]').hide();
				$contextmenu.find('li[for=default]').show();
			}

			// Calculate the maximum allowable position of the context menu and the position of the context menu based on the cursor position
			const contextMenuWidth = $contextmenu.outerWidth();
			const contextMenuHeight = $contextmenu.outerHeight();
			const windowWidth = $(window).width();
			const windowHeight = $(window).height();
			const x = event.clientX;
			const y = event.clientY;
		  
			// Calculate the maximum allowable position of the context menu
			const maxTop = windowHeight - contextMenuHeight - 5;
			const maxLeft = windowWidth - contextMenuWidth - 5;
		  
			// Set the position of the context menu
			const top = y <= maxTop ? y : maxTop;
			const left = x <= maxLeft ? x : maxLeft;

			// Show the context menu
			$contextmenu.css({
				top: top,
				left: left
			}).show().focus();
		});
	}	
});