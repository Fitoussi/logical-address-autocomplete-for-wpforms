/** Native discovery controls must never enter the add-field pipeline. */
import test from 'node:test';
import assert from 'node:assert/strict';
import { createRequire } from 'node:module';
import { readFileSync } from 'node:fs';
const require = createRequire(import.meta.url);
const { JSDOM } = require('jsdom');
const bundle = readFileSync(new URL('../../build/js/admin/wpfrmgeo-form-editor.min.js', import.meta.url), 'utf8');

test('all four discovery buttons open local dialogs without insertion, with focus return', async () => {
	const dom = new JSDOM('<div id="wpforms-builder"><button class="wpfrmgeoac-promo" data-wpfrmgeoac-feature="map" aria-disabled="true">Map</button><button class="wpfrmgeoac-promo" data-wpfrmgeoac-feature="directions">Directions</button><button class="wpfrmgeoac-promo" data-wpfrmgeoac-feature="coordinates">Coordinates</button><button class="wpfrmgeoac-promo" data-wpfrmgeoac-feature="locator">Locator</button></div>', {runScripts:'outside-only'});
	const {window} = dom;
	let inserted = 0;
	window.HTMLDialogElement.prototype.showModal = function () { this.open = true; };
	window.wpfrmgeoacEditor = {labels:{map:'Map',directions:'Directions',coordinates:'Coordinates',locator:'Locator Button'},title:'Available in Geolocation for WPForms',message:'Preview only',learn:'Learn more',close:'Close',url:'https://wpgeoforms.com/'};
	window.eval(bundle);
	window.document.dispatchEvent(new window.Event('DOMContentLoaded'));
	window.document.addEventListener('click',()=>inserted++);
	for (const button of window.document.querySelectorAll('.wpfrmgeoac-promo')) {
		button.click();
		const dialog = window.document.querySelector('dialog');
		assert.ok(dialog?.open);
		assert.match(dialog.textContent,/Preview only/);
		assert.equal(inserted,0);
		assert.equal(dialog.querySelector('a').href,'https://wpgeoforms.com/');
		dialog.querySelector('button').click();
		assert.equal(window.document.querySelector('dialog'),null);
		assert.equal(window.document.activeElement,button);
		inserted=0;
	}
	dom.window.close();
});

test('discovery buttons cannot start native drag insertion', () => {
	const dom = new JSDOM('<button class="wpfrmgeoac-promo">Map</button>',{runScripts:'outside-only'});
	dom.window.eval(bundle);
	dom.window.document.dispatchEvent(new dom.window.Event('DOMContentLoaded'));
	const event = new dom.window.Event('dragstart',{bubbles:true,cancelable:true});
	assert.equal(dom.window.document.querySelector('button').dispatchEvent(event),false);
	dom.window.close();
});

test('Enter and Space open preview before host keyboard handlers', () => {
	for (const key of ['Enter', ' ']) {
		const dom = new JSDOM('<button class="wpfrmgeoac-promo" data-wpfrmgeoac-feature="map">Map</button>', { runScripts: 'outside-only' });
		const {window} = dom;
		window.HTMLDialogElement.prototype.showModal = function () { this.open = true; };
		window.wpfrmgeoacEditor = { labels: {map:'Map'}, title:'Premium', message:'Preview', learn:'Learn more', close:'Close', url:'https://wpgeoforms.com/' };
		let hostKeys=0;
		window.document.addEventListener('keydown', () => hostKeys++);
		window.eval(bundle);
		window.document.dispatchEvent(new window.Event('DOMContentLoaded'));
		window.document.querySelector('button').dispatchEvent(new window.KeyboardEvent('keydown',{key,bubbles:true,cancelable:true}));
		assert.ok(window.document.querySelector('dialog')?.open);
		assert.equal(hostKeys,0);
		dom.window.close();
	}
});
