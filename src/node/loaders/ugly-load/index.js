const UglifyJS = require('uglify-js')

module.exports = function(source, map) {
	const result = UglifyJS.minify(source).code

	if (map) return this.callback(null, result, map)

	return result
}
