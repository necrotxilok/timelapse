
var App = {};

// App Data Binding Methods
(function() {
	var Cache = {};

	App.Get = function(url, callback) {
		$.get(url, function(response) {
			callback(response);
		}).fail(function() {
			ServiceError();
		}).always(function() {
			App.Buttons.Enable();
		});
	}
	App.Post = function(url, data, callback) {
		$.post(url, data, function(response) {
			callback(response);
		}).fail(function() {
			//ServiceError();
			callback({err: -1, msg: 'Unexpected error'});
		}).always(function() {
			App.Buttons.Enable();
		});
	}
	function ServiceError() {
		// [TODO] Activate connection down and wait until connected
		alert("Unable to connect to service");
	}
	App.GetData = function(binding, callback) {
		var bind = App.Binding[binding];
		if (!bind) {
			console.error("Undefined binding '" + binding + "' for GET data!");
			return;
		}
		// Return cached data
		if (bind.cache && Cache[binding]) {
			callback(Cache[binding]);
			return;
		}
		// Get data from binding url
		var data = {};
		App.Get(bind.url, function(response) {
			if (response.err) {
				console.error("Error while getting data from the binging '" + binding + "'");
				return;
			}
			if (bind.id) {
				// Index values
				$.each(response.data, function(i, dt) {
					data[dt[bind.id]] = dt;
				});
			} else {
				data = response.data;
			}
			//console.log(bind.data);
			if (bind.cache) {
				Cache[binding] = data;
			}
			callback(data);
		});
	}
	App.PostData = function(binding, postData, onsave) {
		var bind = App.Binding[binding];
		if (!bind) {
			console.error("Undefined binding '" + binding + "' for POST data!");
			return;
		}
		// Get data from binding url
		App.Post(bind.url, postData, function(response) {
			if (response.err) {
				onsave(response);
				return;
			}
			if (bind.clear) {
				$.each(bind.clear, function(i, binding) {
					App.ClearCache(binding);
				});
			}
			if (bind.update) {
				$.each(bind.update, function(i, binding) {
					App.SetCache(binding, response.data);
				});
			}
			onsave(response);
		});
	}	
	App.GetCached = function(binding, id) {
		var bind = App.Binding[binding];
		if (!bind) {
			console.error("Undefined binding '" + binding + "' for GET CACHED data!");
			return;
		}
		if (!bind.cache) {
			console.error("The binding '" + binding + "' does not have cache activated");
			return;
		}
		if (!Cache[binding]) {
			console.error("You must GET data from the binding '" + binding + "' before access the cache");
			return;
		}
		return Cache[binding][id];
	}
	App.SetCache = function(binding, data) {
		var bind = App.Binding[binding];
		if (!bind) {
			console.error("Undefined binding '" + binding + "' for CLEAR cache!");
			return;
		}
		if (!bind.cache) {
			console.error("The binding '" + binding + "' does not have cache activated");
			return;
		}
		Cache[binding] = data;
	}
	App.ClearCache = function(binding) {
		var bind = App.Binding[binding];
		if (!bind) {
			console.error("Undefined binding '" + binding + "' for CLEAR cache!");
			return;
		}
		if (!bind.cache) {
			console.error("The binding '" + binding + "' does not have cache activated");
			return;
		}
		delete Cache[binding];
	}
	App.ClearAllCache = function() {
		$.each(App.Binding, function(binding, bind) {
			if (Cache[binding]) {
				delete Cache[binding];
			}
		});
	}
})();


// Wait For All Images Loaded
(function() {
	App.WaitForImages = function(selector, callback) {
		var $this = $(selector);
		var total = $this.length;
		var loaded = 0;

		var load = function() {
			loaded++;
			if (loaded == total && typeof(callback) == "function") {
				callback();
			}
		}

		$(selector).each(function() {
			var src = $(this).css('background-image');
			var url = src.match(/\((.*?)\)/)[1].replace(/('|")/g,'');
			
			if (!url) {
				url = $(this).attr('src');
			}
			if (!url) {
				load();
				return;
			}

			var img = new Image();
			img.onload = load;
			img.src = url;
			if (img.complete) {
				img.onload();
			}
		});
	}
})();


// Request Button Activator/Deactivator
(function() {
	App.Buttons = {};
	var buttonText = "";
	var $button;
	App.Buttons.Disable = function(button) {
		$button = $(button);
		buttonText = $button.text();
		$button.html('<span uk-spinner></span>');
		$button.attr("disabled", true);
		$('body').append('<div class="disable-overlay"></div>');
	};
	App.Buttons.Enable = function() {
		if ($button) {
			$button.text(buttonText);
			$button.removeAttr("disabled");
			$('.disable-overlay').remove();
			$button = null;
		}
	};
})();


// App Templates and Data Rendering
(function() {
	var bindingsList = [];

	var GetBindingFor = function(container) {
		var $el = $(container);
		var binding = $el.data('binding');
		if (binding) {
			bindingsList.push({
				$el: $el,
				binding: binding,
				type: $el.data('type'),
				template: $el.data('template')
			});
		}
	}
	var RenderBindings = function($container) {
		$.each(bindingsList, function(idx, bind) {
			if (!bind.data) {
				return;
			}
			if (bind.type == 'list') {
				if (bind.template) {
					bind.$el.empty();
					$.each(bind.data, function(idx, row) {
						App.Template.Append(bind.$el, bind.template, row);
					});
				}
			} else {
				App.SetFieldData(bind.binding, bind.$el, bind.data);
			}
		});
		$container.find(".loading-data").remove();
	}

	App.LoadBindings = function(container, callback) {
		bindingsList = [];
		var $container = $(container);
		GetBindingFor(container);
		$container.find('[data-binding]').each(function() {
			GetBindingFor(this);
		});
		$container.find(".loading-data").remove();
		if (bindingsList.length > 0) {
			$container.append('<div class="loading-data"><div class="uk-flex uk-flex-center uk-flex-middle uk-height-viewport"><span uk-spinner="ratio: 3.5"></span></div></div>');
			var counter = 0;
			$.each(bindingsList, function(idx, bind) {
				App.GetData(bind.binding, function(data) {
					bindingsList[idx].data = data;
					counter++;
					if (counter == bindingsList.length) {
						RenderBindings($container);
						if (callback) {
							callback(data);
						}
					}
				});
			});
		}
	}

	App.Templates = {};
	App.Template = {};
	App.Template.Append = function($container, tplName, data) {
		var tpl = App.Templates[tplName];
		if (!tpl) {
			console.error("The template '" + tplName + "' does not exists!!");
			return;
		}
		var html = tpl.html;
		$.each(data, function(key, value) {
			html = html.replace(new RegExp('\{\{' + key + '\}\}', 'g'), value ? value : "");
		});
		var $obj = $('<div/>').html(html);
		if (tpl.onload) {
			tpl.onload($obj, data);
		}
		$container.append($obj.html());
	}

	App.SetFieldData = function(binding, container, data) {
		var $container = $(container);
		$container.find('[field]').each(function() {
			var $field = $(this);
			var fieldBinding = $field.attr('binding');
			if (fieldBinding != binding) {
				return;
			}

			var field = $field.attr('field');
			var value = data[field];
			var tag = $field.prop('tagName');
			if (tag == 'INPUT') {
				if ($field.attr('type') == 'checkbox') {
					$field.prop('checked', value);
				} else {
					$field.val(value);
				}
			} else if (tag == 'SELECT') {
				$field.val(value);
			} else if (tag == 'IMG') {
				$field.attr('src', value);
			} else {
				$field.text(value);
			}
		});
	}

	App.GetFieldData = function(binding, container) {
		var data = {};
		var $container = $(container);
		$container.find('[field]').each(function() {
			var $field = $(this);
			var fieldBinding = $field.attr('binding');
			if (fieldBinding != binding) {
				return data;
			}

			var field = $field.attr('field');
			var tag = $field.prop('tagName');

			if (tag == 'INPUT') {
				if ($field.attr('type') == 'checkbox') {
					data[field] = $field.prop('checked');
				} else {
					data[field] = $field.val().trim();
				}
			} else if (tag == 'SELECT') {
				data[field] = $field.val();
			} else if (tag == 'IMG') {
				data[field] = $field.attr('src').trim();
			} else {
				data[field] = $field.text().trim();
			}
		});
		return data;
	}

	App.FormPostData = function(form, onsave) {
		var $form = $(form);
		var post = $form.data('post');
		var binding = $form.data('update-binding');
		if (!binding) binding = post;
		var $submit = $form.find("[type='submit']");

		App.Buttons.Disable($submit);

		var data = App.GetFieldData(binding, $form);
		data.__id__ = $form.data('id');
		
		App.PostData(post, data, function(response) {
			$submit.prop('disabled', false);

			if (response.err) {
				App.NotifyError(response.msg);
				return;
			}
			App.NotifySuccess(response.msg);

			if (response.data) {
				App.SetFieldData(binding, 'body', response.data);
			}

			if (onsave) {
				onsave(data);
			}
		});
	}

})();


// Base actions
(function() {
	App.Load = {};
	
	App.LoadPage = function(page) {
		$('.page').hide();
		$(page).show();
	}
	App.LoadSection = function(section) {
		$('.section').hide();
		var $section = $(section);
		$section.show();

		$('.section-link').parent().removeClass('uk-active');
		$('.section-link[href="' + section + '"]').parent().addClass('uk-active');

		var load = $section.data('load');
		if (load) {
			App.Load[load]();
		} else {
			App.LoadBindings($section);
		}
	}
	App.Start = function(uData) {
		App.ClearAllCache();
		$(".section-link.user-name").text(uData.name);
		if (uData.role == 'admin') {
			$(".section-link.sys-access").show();
		} else {
			$(".section-link.sys-access").hide();
		}
		App.LoadPage('#main');
		App.LoadSection('#dashboardSection');
	}
	App.Logout = function() {
		$('.loading').show();
		App.Get('api/auth/logout', function(response) {
			if (response.err) {
				return;
			}
			App.LoadPage('#login');
			$('.loading').hide();
		});
	}
	App.NotifyError = function(msg) {
		UIkit.notification(/*'<span uk-icon=\'icon: ban\'></span> ' +*/ msg, {pos: 'bottom-center', status: 'danger'});
	}
	App.NotifySuccess = function(msg) {
		UIkit.notification(/*'<span uk-icon=\'icon: check\'></span> ' +*/ msg, {pos: 'bottom-center', status: 'success'});
	}
})();

