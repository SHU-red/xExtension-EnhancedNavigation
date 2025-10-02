function init_nav_entries_override() {
	const nav_entries = document.getElementById('nav_entries');
	if (nav_entries) {
		nav_entries.querySelector('.previous_entry').onclick = function (e) {
			prev_entry(false);
			return false;
		};
		nav_entries.querySelector('.next_entry').onclick = function (e) {
			next_entry(false);
			return false;
		};
		nav_entries.querySelector('.up').onclick = function (e) {
			const active_item = (document.querySelector('.flux.current') || document.querySelector('.flux'));
			const windowTop = document.scrollingElement.scrollTop;
			const item_top = active_item.offsetParent.offsetTop + active_item.offsetTop;
			const nav_menu = document.querySelector('.nav_menu');
			let nav_menu_height = 0;
			if (getComputedStyle(nav_menu).position === 'fixed' || getComputedStyle(nav_menu).position === 'sticky') {
				nav_menu_height = nav_menu.offsetHeight;
			}
			document.scrollingElement.scrollTop = windowTop > item_top ? item_top - nav_menu_height : 0 - nav_menu_height;
			return false;
		};
		nav_entries.querySelector('.favorite').onclick = function (e) {
			const active_item = (document.querySelector('.flux.current') || document.querySelector('.flux'));
			mark_favorite(active_item);
			return false;
		};
		nav_entries.querySelector('.link').onclick = function (e) {
			const active_item = (document.querySelector('.flux.current') || document.querySelector('.flux'));
			window.open(active_item.dataset.link, '_blank');
			return false;
		};
	}
}

if (document.readyState && document.readyState !== 'loading') {
	init_nav_entries_override();
} else {
	if (window.console) {
		console.log('FreshRSS waiting for DOMContentLoaded…');
	}
	document.addEventListener('DOMContentLoaded', init_nav_entries_override, false);
}