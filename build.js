/**
 * Build the standalone host adapter and native editor controls.
 * @since 1.0.0
 */
const esbuild = require('esbuild');
Promise.all(
	[
		[
			'assets/js/autocomplete/host-address-autocomplete.js',
			'build/js/frontend/address-autocomplete.min.js',
			'WPFRMGEOAC_AddressAutocomplete',
		],
		[
			'assets/js/editor/form-editor.js',
			'build/js/admin/wpfrmgeo-form-editor.min.js',
			'WPFRMGEOAC_FormEditor',
		],
	].map(([entry, outfile, globalName]) =>
		esbuild.build({
			entryPoints: [entry],
			outfile,
			globalName,
			bundle: true,
			minify: true,
			sourcemap: true,
			target: ['es2017'],
			format: 'iife',
			absWorkingDir: __dirname,
		})
	)
).catch((error) => {
	console.error(error.message);
	process.exit(1);
});
