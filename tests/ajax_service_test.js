TestCase("AjaxServiceTest", sinon.testCase({

	"test should wrap ajax-calls": function (stub, mock) {
		var options;
		stub(jQuery, "ajax");
		MT.ajax_service.contact_server("url_stub", {test: "test"});
		
		options = jQuery.ajax.args[0][0];
		assertEquals("json", options.dataType);
		assertEquals("POST", options.type);
		assertEquals("/url_stub.php", options.url);
		assertEquals({test: "test"}, options.data);
	},

	"test should save preferences": function (stub, mock) {
		stub(MT.ajax_service, "contact_server");
		MT.ajax_service.save_preference("ticking", "on");
		assert(MT.ajax_service.contact_server.called);
		assertEquals(["actions/set_preference", {name: "ticking", value: "on"}], MT.ajax_service.contact_server.args[0]);
	}	

}));