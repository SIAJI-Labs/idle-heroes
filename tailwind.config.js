module.exports = {
	mode: 'jit',
	important: true,
	content: [
		"./resources/**/*.blade.php",
		"./resources/**/*.js",
		"./resources/**/*.vue",
	],
	theme: {
		extend: {},
	},
	plugins: [],
	prefix: 'tw__',
}