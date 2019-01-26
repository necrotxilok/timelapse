

// Data Models
(function() {
	
	var def_base = {
		'url': '',				// (* REQUIRED) Url to get data
		'id': null,				// Name of ID field to index the list (Only for arrays of data)
		'cache': false, 		// If true store data in cache
		'clear': null, 			// List of models to clear cache after post new data
		'update': null 			// List of models to update after getting post data
	};

	App.Binding = {};


	// Current user actions

	App.Binding.CurrentUser = {
		url: 'api/auth/session',
		cache: true
	};

	App.Binding.ChangePassword = {
		url: 'api/auth/change_password'
	};

	App.Binding.ChangeProfile = {
		url: 'api/auth/change_profile',
		clear: ['UserTotals', 'AdminUsers'],
		update: ['CurrentUser']
	};


	// Users list

	App.Binding.UserTotals = {
		url: 'api/timelapse/user_totals',
		id: 'user',
		cache: true
	};


	// Timing

	App.Binding.Timing = {
		url: 'api/timelapse/timing',
		id: 'date',
		cache: true
	};

	App.Binding.UpdateTiming = {
		url: 'api/timelapse/update_timing',
		clear: ['Timing', 'UserTotals']
	};

	// Administrator

	App.Binding.AdminUsers = {
		url: 'api/auth/admin_users',
		id: 'user',
		cache: true
	};

	App.Binding.AdminEditUser = {
		url: 'api/auth/admin_edit_user',
		clear: ['UserTotals', 'AdminUsers']
	}

	App.Binding.AdminRemoveUser = {
		url: 'api/auth/admin_remove_user',
		clear: ['UserTotals', 'AdminUsers']
	}

	App.Binding.AdminActivateUser = {
		url: 'api/auth/admin_activate_user',
		clear: ['UserTotals', 'AdminUsers']
	}

	// Admin System Log

	App.Binding.SystemLog = {
		url: 'api/system/log'
	}


	// Assign default config to all bindings

	$.each(App.Binding, function(model, settings) {
		App.Binding[model] = $.extend({}, def_base, settings);
	});

})();
