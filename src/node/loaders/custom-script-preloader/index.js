var path = require( "path" );
var UglifyJS = require( "uglify-js" );
var fs = require( 'fs' );

module.exports = function ( content )
{
    if ( !this.debug && this.request.indexOf( "raw-loader" ) > 0 )
    {
        var file = this.resourcePath;
        var relativePath = path.relative( "", path.dirname( file ) );
        var fileName = path.basename( file, ".js" );
        var sourceMapFileName = fileName + ".js.map";
        var newPath = relativePath + "/";

        var result = UglifyJS.minify( [ file ] );

        return result.code;
    }

    return content;
};