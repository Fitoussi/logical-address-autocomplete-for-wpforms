/** Native WPForms editor discovery and comma-list persistence. */
import Choices from './vendor/choices.js';
import {bindPlaceTypeSelection} from './place-type-selection.js';

const initialized = new WeakSet();

/** Enhance new native controls without replacing names or saving unsupported values. */
export function initializeControls() {
	for (const select of document.querySelectorAll('.wpfrmgeoac-multiple')) {
		if (initialized.has(select)) continue;
		initialized.add(select);
		const hidden = select.parentElement.querySelector('.wpfrmgeoac-list-value');
		select.choices = new Choices(select, {
			removeItemButton: true,
			maxItemCount: select.id.endsWith('_types') ? -1 : 5,
			allowHTML: false,
			shouldSort: false,
			searchResultLimit: 30,
			itemSelectText: '',
		});
		if (select.id.endsWith('_types')) bindPlaceTypeSelection(select, select.choices);
		select.addEventListener('change', () => {
			hidden.value = Array.from(select.selectedOptions).map((option) => option.value).join(',');
			hidden.dispatchEvent(new Event('change', { bubbles: true }));
		});
	}
}

/** Show a local accessible dialog; never call the host's add-field action. */
export function showFeature(button) {
	const config = globalThis.wpfrmgeoacEditor || {};
	const label = config.labels?.[button.dataset.wpfrmgeoacFeature];
	if (!label) return;
	const dialog = document.createElement('dialog');
	dialog.className = 'wpfrmgeoac-dialog';
	dialog.setAttribute('aria-labelledby', 'wpfrmgeoac-dialog-title');
	const title = document.createElement('h2');
	title.id = 'wpfrmgeoac-dialog-title';
	title.textContent = `${label} — ${config.title}`;
	const description = document.createElement('p');
	description.textContent = config.message;
	const learn = document.createElement('a');
	learn.textContent = config.learn;
	learn.href = config.url;
	learn.target = '_blank';
	learn.rel = 'noopener noreferrer';
	learn.className = 'button button-primary';
	const close = document.createElement('button');
	close.type = 'button';
	close.textContent = config.close;
	close.className = 'button';
	const dismiss = () => {
		dialog.remove();
		button.focus();
	};
	close.addEventListener('click', dismiss);
	dialog.addEventListener('cancel', (event) => {
		event.preventDefault();
		dismiss();
	});
	dialog.append(title, description, learn, close);
	document.body.append(dialog);
	dialog.showModal();
	close.focus();
}

/** Capture before native delegated handlers so promotions cannot insert or drag. */
function boot() {
	initializeControls();
	// WPForms intercepts Enter globally. Handle discovery keys before its
	// bubbling handlers and prevent the synthetic click from opening twice.
	document.addEventListener(
		'keydown',
		(event) => {
			const button = event.target.closest('button.wpfrmgeoac-promo');
			if (!button || !['Enter', ' ', 'Spacebar'].includes(event.key)) return;
			event.preventDefault();
			event.stopImmediatePropagation();
			showFeature(button);
		},
		true
	);
	document.addEventListener(
		'click',
		(event) => {
			const button = event.target.closest('button.wpfrmgeoac-promo');
			if (!button) return;
			event.preventDefault();
			event.stopImmediatePropagation();
			showFeature(button);
		},
		true
	);
	document.addEventListener(
		'dragstart',
		(event) => {
			if (event.target.closest('.wpfrmgeoac-promo')) {
				event.preventDefault();
				event.stopImmediatePropagation();
			}
		},
		true
	);
	let timer;
	new MutationObserver((records) => {
		if (
			records.some((record) =>
				Array.from(record.addedNodes).some(
					(node) =>
						node.nodeType === 1 &&
						(node.matches('.wpfrmgeoac-multiple') || node.querySelector('.wpfrmgeoac-multiple'))
				)
			)
		) {
			clearTimeout(timer);
			timer = setTimeout(initializeControls, 50);
		}
	}).observe(document.body, { childList: true, subtree: true });
}

if (document.readyState === 'loading')
	document.addEventListener('DOMContentLoaded', boot, { once: true });
else boot();
