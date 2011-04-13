(function (global) {
	
	var audio_stub = function () {
		var event_callbacks = {},
		    audio = {
				play: function () {},
				pause: function () {},
				addEventListener: function (event_type, callback) { 
					event_callbacks[event_type] = callback;
				},
				trigger: function (event_type) { 
					if (event_callbacks[event_type]) {
						event_callbacks[event_type].apply(this); 
					}
				},
				readyState: 4
			};
		sinon.spy(audio, "play");
		sinon.spy(audio, "pause");
		return audio;
	};
	
	TestCase("SoundPlayerTest", sinon.testCase({
		setUp: function (stub, mock) {
			stub(document, "write");
			this.audio_elements = {
				alarm: audio_stub(),
				ticking_1: audio_stub(),
				ticking_2: audio_stub()
			};
			stub(MT.sound_player, "get_audio_elements").returns(this.audio_elements);
		},

		"test should choose player dependent on browser capabilities": function (stub, mock) {
			stub(MT.sound_player, "audio_tag_supported").returns(true);
			assertEquals("audio", MT.sound_player.create().type);

			stub(MT.sound_player, "audio_tag_supported").returns(false);
			assertEquals("flash", MT.sound_player.create().type);
		},
		
		"test flash player should initialize swf": function (stub, mock) {
			stub(global, "AC_FL_RunContent");
			MT.sound_player.create_flash_player();
			assert(AC_FL_RunContent.called);
		},
		
		"test flash player should play and stop alarm": function (stub, mock) {
			var player = MT.sound_player.create_flash_player();
			stub(player.swf);
			
			player.play_alarm();
			assert(player.swf.playSound.called);

			player.stop_alarm();
			assert(player.swf.stopSound.called);
		},
		
		"test flash player should declare no support for ticking": function (stub, mock) {
			assertFalse(MT.sound_player.create_flash_player().supports_ticking);
		},
		
		"test audio player should play and stop alarm": function (stub, mock) {
			var player = MT.sound_player.create_audio_player();
				
			player.play_alarm();
			assertEquals(0, this.audio_elements.alarm.currentTime);
			assert(this.audio_elements.alarm.play.called);

			player.stop_alarm();
			assert(this.audio_elements.alarm.pause.called);
		},
		
		"test audio player should declare support for ticking": function (stub, mock) {
			assert(MT.sound_player.create_audio_player().supports_ticking);
		},
		
		"test audio player should loop ticking": function (stub, mock) {
			var player = MT.sound_player.create_audio_player();
			
			player.start_ticking();
			assert(this.audio_elements.ticking_1.play.called);
			
			this.audio_elements.ticking_1.trigger("ended");
			assert(this.audio_elements.ticking_2.play.called);

			this.audio_elements.ticking_2.trigger("ended");
			assert(this.audio_elements.ticking_1.play.calledTwice);
		},
		
		"test audio player should stop ticking": function (stub, mock) {
			var player = MT.sound_player.create_audio_player();
			
			player.stop_ticking();
			assert(this.audio_elements.ticking_1.pause.called);
			assert(this.audio_elements.ticking_2.pause.called);
			
			assertEquals(0, this.audio_elements.ticking_1.currentTime);
			assertEquals(0, this.audio_elements.ticking_2.currentTime);
		},
		
		"test should not play sound if audio element not ready": function () {
			var player = MT.sound_player.create_audio_player();
			this.audio_elements.alarm.readyState = 0;
			player.play_alarm();
			assertFalse(this.audio_elements.alarm.play.called);
		}
	
	}));

}(this));
