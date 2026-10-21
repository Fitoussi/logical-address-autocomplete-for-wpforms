import test from 'node:test';
import assert from 'node:assert/strict';
import {readFileSync} from 'node:fs';
import {createRequire} from 'node:module';

const require = createRequire(import.meta.url);
const {JSDOM} = require('jsdom');
const base = new URL('../../', import.meta.url);
const types = JSON.parse(readFileSync(new URL('assets/data/place-types.json', base)));
const vendor = readFileSync(new URL('assets/js/editor/vendor/choices.js', base), 'utf8');
const helper = readFileSync(new URL('assets/js/editor/place-type-selection.js', base), 'utf8');

function setup(saved = []) {
	const dom = new JSDOM('<div><select multiple></select><span class="autocomplete-type-collection-note" hidden></span></div>', {runScripts:'outside-only', pretendToBeVisual:true});
	const {window} = dom;
	const select = window.document.querySelector('select');
	for (const value of new Set([...Object.values(types).flat(), ...saved])) {
		select.append(new window.Option(value, value, false, saved.includes(value)));
	}
	window.eval(vendor);
	window.eval(helper.replace('export function ', 'function ') + '\nwindow.bindPlaceTypeSelection = bindPlaceTypeSelection;');
	const choices = new window.Choices(select, {maxItemCount:-1, removeItemButton:true, allowHTML:false, shouldSort:false});
	let hydrating = false;
	window.bindPlaceTypeSelection(select, choices, () => hydrating);
	const changes = [];
	select.addEventListener('change', () => changes.push(Array.from(choices.getValue(true))));
	return {
		select, choices, changes,
		noteVisible: () => !window.document.querySelector('.autocomplete-type-collection-note').hidden,
		values: () => Array.from(choices.getValue(true)),
		choose(value) {
			choices.setChoiceByValue(value);
			select.dispatchEvent(new window.CustomEvent('change', {detail:{value},bubbles:true}));
		},
		hydrate(values) {
			hydrating = true;
			choices.removeActiveItems();
			choices.setChoiceByValue(values);
			hydrating = false;
		},
		close: () => dom.window.close(),
	};
}

test('bundled snapshot includes all 514 Google Tables A/B types plus two collections', () => {
	const values = Object.values(types).flat();
	assert.equal(values.length, 516);
	assert.equal(new Set(values).size, values.length);
	for (const value of ['(cities)', '(regions)', 'street_address', 'cafe', 'ebike_charging_station', 'parking_garage', 'postal_town']) assert.ok(values.includes(value));
});

test('loading and hydrating mixed or unknown saved types never rewrites or saves them', () => {
	const ui = setup(['(cities)', 'restaurant', 'custom_place']);
	assert.equal(ui.noteVisible(), true);
	assert.deepEqual(ui.values(), ['(cities)', 'restaurant', 'custom_place']);
	assert.equal(ui.changes.length, 0);
	ui.hydrate(['(regions)', 'custom_place']);
	assert.equal(ui.noteVisible(), true);
	assert.deepEqual(ui.values(), ['(regions)', 'custom_place']);
	assert.equal(ui.changes.length, 0);
	ui.hydrate(['restaurant']);
	assert.equal(ui.noteVisible(), false);
	assert.deepEqual(ui.values(), ['restaurant']);
	assert.equal(ui.changes.length, 0);
	ui.close();
});

test('the newest exclusive collection replaces ordinary types and the other collection', () => {
	const ui = setup(['restaurant', 'cafe']);
	assert.equal(ui.noteVisible(), false);
	ui.choose('(cities)');
	assert.equal(ui.noteVisible(), true);
	assert.deepEqual(ui.values(), ['(cities)']);
	assert.deepEqual(ui.changes, [['(cities)']]);
	ui.choose('(regions)');
	assert.equal(ui.noteVisible(), true);
	assert.deepEqual(ui.values(), ['(regions)']);
	assert.deepEqual(ui.changes, [['(cities)'], ['(regions)']]);
	ui.close();
});

test('an ordinary choice removes the collection and still combines with ordinary choices', () => {
	const ui = setup(['(regions)']);
	ui.choose('cafe');
	assert.equal(ui.noteVisible(), false);
	ui.choose('restaurant');
	assert.deepEqual(ui.values(), ['cafe', 'restaurant']);
	assert.deepEqual(ui.changes, [['cafe'], ['cafe', 'restaurant']]);
	ui.close();
});

test('the collection note is hidden for empty selections and after removing the last collection', () => {
	const ui = setup();
	assert.equal(ui.noteVisible(), false);
	ui.choose('(cities)');
	assert.equal(ui.noteVisible(), true);
	ui.choices.removeActiveItemsByValue('(cities)');
	assert.equal(ui.noteVisible(), false);
	assert.deepEqual(ui.values(), []);
	ui.close();
});

test('five ordinary types remain safe and a collection can replace them at the limit', () => {
	const saved = ['restaurant', 'cafe', 'school', 'hospital', 'airport'];
	const ui = setup(saved);
	const initial = ui.values();
	ui.choose('store');
	assert.deepEqual(ui.values(), initial);
	assert.deepEqual(ui.changes, [initial]);
	ui.choose('(cities)');
	assert.deepEqual(ui.values(), ['(cities)']);
	ui.close();
});

test('removing a historical collection does not silently erase remaining saved types', () => {
	const ui = setup(['(cities)', '(regions)', 'custom_place']);
	ui.choices.removeActiveItemsByValue('(cities)');
	ui.select.dispatchEvent(new ui.select.ownerDocument.defaultView.CustomEvent('change', {detail:{value:'(cities)'},bubbles:true}));
	assert.deepEqual(ui.values(), ['(regions)', 'custom_place']);
	ui.close();
});
