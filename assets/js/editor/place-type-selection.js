/**
 * Enforce Google's exclusive collections only after an explicit user change.
 * Hydration and stored settings are never normalized or saved by this helper.
 */
export function bindPlaceTypeSelection(select, choices, isHydrating = () => false) {
	const collections = ['(cities)', '(regions)'];
	const note = select.closest('.choices')?.parentElement.querySelector('.autocomplete-type-collection-note');
	const updateNote = () => {
		if (note) note.hidden = !choices.getValue(true).some((value) => collections.includes(value));
	};
	// Choices also emits these during hydration; visibility never changes values.
	select.addEventListener('addItem', updateNote);
	select.addEventListener('removeItem', updateNote);
	updateNote();
	select.addEventListener('change', (event) => {
		if (isHydrating()) return;
		const values = choices.getValue(true);
		const changed = event.detail?.value;
		// A removal must not reinterpret the remaining historical configuration.
		if (!changed || !values.includes(changed)) return;
		const keep = collections.includes(changed)
			? [changed]
			: values.filter((value) => !collections.includes(value)).slice(0, 5);
		for (const value of values) {
			if (!keep.includes(value)) choices.removeActiveItemsByValue(value);
		}
	});
}
