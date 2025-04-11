module.exports = {
	"proxy": "localhost/maria-diet-b2b/",
	"notify": false,
	"files": [
		"./css/*.min.css", 
		"./js/*.min.js", "./**/*.php",
		"../../plugins/**/*.php",
		"../../plugins/**/*.js",
		"../../plugins/**/*.css"
	]
};