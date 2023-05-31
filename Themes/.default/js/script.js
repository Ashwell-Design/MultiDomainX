// Initializes the google translate functionality
function googleTranslateElementInit() {
	new google.translate.TranslateElement({
		pageLanguage: 'en',
		includedLanguages: 'en,ar,bn,de,es,fa,fr,gu,hi,hu,id,it,ja,jv,ko,ms,mr,pl,pt,ro,ru,sw,ta,te,th,tr,uk,ur,vi,zh-CN,zh-TW,am,az,be,bg,ca,cs,cy,da,el,et,eu,fi,ga,gl,he,hr,ht,is,ka,km,kn',
		layout: google.translate.TranslateElement.InlineLayout.SIMPLE
	}, 'googtrans');
}
// Define the changeLanguage function to handle language changes
function changeLanguage(lang) {
	if($('.skiptranslate').contents().find('a[title=Close]').length > 0) {
		($('.skiptranslate').contents().find('a[title=Close]')[0]).click();
	}
	if(lang != "en") {
		// Stop the Google Translate script
		window.google.translate.TranslateElement = null;
		// Set the language cookie
		document.cookie = 'googtrans=/en/' + lang + '; path=/;';
		// Refresh the page to apply the language change
		location.reload();
	}
}
// Loads a table
function loadTable(elem) {
	var elem = $(elem)[0];
	if($(elem).length > 0 && $(elem).attr('preload-status') != 'Loaded') {
		extension = $(elem).attr('preload-attributes');
		const [table, col_ids, buttonString] = extension.split('-', 3);
		const cols = col_ids.split(".");
		const thead = $(elem).children('thead')[0];
		const tbody = $(elem).children('tbody')[0];

		const data_rows = document.createElement("tr");
		const td = document.createElement("td");

		const xhr = new XMLHttpRequest();

		initSqlJs({
			locateFile: filename => `https://cdnjs.cloudflare.com/ajax/libs/sql.js/1.6.1/${filename}`
		}).then(function(SQL){
			xhr.open('GET', '/central.sqlite', true);
			xhr.responseType = 'arraybuffer';
			xhr.onload = e => {
				const uInt8Array = new Uint8Array(xhr.response);
				const db = new SQL.Database(uInt8Array);
				/** 
				 * TABLE HEADER
				 */
				var stmt = db.prepare("PRAGMA table_info("+table+")");
				stmt.run()
				var i=0;
				header_row = $("<tr></tr>").appendTo(thead);
				while (stmt.step()) {
					if(cols.includes(i.toString())) {
						$("<th></th>").appendTo(header_row).html(Object.values(stmt.getAsObject())[1]);
					}
					i++;
				}
				var i=0;
				/** 
				 * TABLE HEADER
				 */
				var stmt = db.prepare("PRAGMA table_info("+table+")");
				stmt.run()
				while (stmt.step()) {
					var row = stmt.getAsObject();
					console.log(row[1]);
				}
			};
			xhr.send();
		});
	}
}
$(document).ready(function(){
	$('[preload=true]').each(function() {
		const command = ($(this).attr('preload-function').length > 0)? $(this).attr('preload-function'): '';
		var height = (this.clientHeight>0)? this.clientHeight: 20;
		this.style.height = height + 'px';
		$(this).attr('preload-status', 'Loading');
		window[command]($(this));
		$(this).attr('preload-status', 'Loaded');
	});

	$(document).on('click keydown', (event) => {
		const $target = $(event.target);
		const $dropdownMenu = $('.dropdown-menu.lang');
		if($('.dropdown-menu.lang').is(':visible')) {
			if ($target.closest($dropdownMenu).length) {
				if($target.is('.dropdown-item')) {
					changeLanguage($target.data('value'));
				}
			} else {
			  // User clicked outside the menu
			  $dropdownMenu.hide();
			}
		} else {
			if($target.closest('#languageSelector').length) {
				$('.dropdown-menu.lang').toggle();
			}
		}
	});

	// Callback function to execute when mutations are observed
	var callback = function(mutationsList, observer) {
		// Check for specific attribute changes
		mutationsList.forEach(function(mutation) {
			if (mutation.type === 'attributes' && mutation.attributeName === 'lang') {
				// Do something when the attribute changes
				$('.langChanger').val(mutation.target.getAttribute('lang'));
				if(mutation.target.getAttribute('lang') !== 'en') {
					$('.langChanger').hide();
					$('.resetLang').show();
				} else {
					$('.langChanger').show();
					$('.resetLang').hide();
				}
			}
		});
	};
	// Create an observer instance linked to the callback function
	var observer = new MutationObserver(callback);
	// Start observing the target node for configured mutations
	observer.observe($('html').get(0), { attributes: true, childList: false, subtree: false });

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
			const cursorX = event.clientX;
			const cursorY = event.clientY;
		  
			// Calculate the maximum allowable position of the context menu
			const maxTop = windowHeight - contextMenuHeight - 5;
			const maxLeft = windowWidth - contextMenuWidth - 5;
		  
			// Set the position of the context menu
			const top = cursorY <= maxTop ? cursorY : maxTop;
			const left = cursorX <= maxLeft ? cursorX : maxLeft;

			// Show the context menu
			$contextmenu.css({
				top: top,
				left: left,
				display: 'flex'
			}).focus();
		});
	}
});