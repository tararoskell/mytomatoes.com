(function ($) {

	TestCase("PreferencesTest", sinon.testCase({

		setUp: function () {
			/*:DOC += 
				<div id="preferences">
					<h3></h3>
					<input type="checkbox" id="pref1" name="one" />
					<input type="checkbox" id="pref2" name="two" />
				</div> 
			*/
			MT.initialize_preferences();
		},

		"test should toggle preferences pane when clicking header": function (stub, mock) {
			var preferences = $("#preferences"),
				header = preferences.find("h3");

			header.trigger("click");
			assert(preferences.is(".open"));

			header.trigger("click");
			assert(preferences.is(":not(.open)"));
		},
		
		"test should save preferences straight away": function (stub, mock) {
			stub(MT.ajax_service);
			
			$("#pref1").attr("checked", true).trigger("click");
			assert(MT.ajax_service.save_preference.calledWith("one", true));
			
			$("#pref2").attr("checked", false).trigger("click");
			assert(MT.ajax_service.save_preference.calledWith("two", false));
		}

	}));

}(jQuery));
