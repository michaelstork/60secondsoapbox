module.exports = {
	"extends": "eslint:recommended",
	"parserOptions": {
		"sourceType": "module"
	},
	"env": {
	    "browser": true,
	    "commonjs": true,
	    "node": true,
	    "es6": true
	},
	"plugins": [
        "html"
    ],
    "rules": {
    	"no-console": 0
    }
};