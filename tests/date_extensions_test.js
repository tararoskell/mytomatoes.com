TestCase("DateExtensions", {
	"test should get timestamp": function () {
		assertEquals("1970-01-01 01:00:00", new Date(0).toTimestamp());
	},
	"test should get clock": function () {
		assertEquals("01:00", new Date(0).toClock());
	},
	"test should get 12-hour clock": function () {
		assertEquals("03:00 PM", new Date(1000 * 60 * 60 * 14).to12hrClock());
	},
	"test should move to midnight": function () {
		assertEquals("1970-01-01 00:00:00", new Date(0).moveToMidnight().toString("yyyy-MM-dd HH:mm:ss"));
	}
});