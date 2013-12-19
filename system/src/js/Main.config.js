require.config({
	baseUrl: Tuto.baseURL + "/src/js/",
	deps : [ 'Main' ],
	paths :
	{
		"jquery" : "../../libs/js/jquery-2.0.3.min",
		"base64" : "app/modules/base64",
		"tutomvc" : "tutomvc.src"
	},
	map : 
	{
		"app/modules/jquery" : { "jquery" : "jquery" },
		"*" : { "jquery" : "app/modules/jquery" }
	}
});