
// Loading functions
(function() {

	// On Document Ready
	$(function() {
		App.Get('api/auth/session', function(response) {
			App.WaitForImages(".waitforimages", function() {
				$('.loading').hide();
				if (response.err) {
					return;
				}
				App.Start(response.data);
			});
		});
	});


	// Main menu Links
	$('.section-link').on('click', function(event) {
		event.preventDefault();
		var section = $(this).attr('href');
		App.LoadSection(section);
		onDataFormSave = null;
	});


	// Login Action
	$("#login").on('submit', function(e) {
		e.preventDefault();
		var $form = $(this);
		var $error = $form.find("#error");
		
		$error.empty();
		App.Buttons.Disable("#loginButton");

		var data = {
			'user': $form.find("#user").val().trim(),
			'pass': $form.find("#pass").val().trim()
		};

		if (data['user'] && data['pass']) {
			App.Post('api/auth/login', data, function(response) {
				if (response.err) {
					$error.html(response.msg);
					return;
				}
				//console.log("Login OK!", response.data);
				App.Start(response.data);
			});
		}
	});


	// Change Password Action
	$('#changepass').on('submit', function(e) {
		e.preventDefault();

		var $form = $(this);
		var $error = $form.find("#error");
		
		var pass1 = $form.find("#newPass1").val().trim();
		var pass2 = $form.find("#newPass2").val().trim();

		if (!pass1 || !pass2) {
			App.NotifyError("Empty password!");
			return;
		}

		if (pass1 != pass2) {
			App.NotifyError("Passwords does not match!");
			return;			
		}

		App.Buttons.Disable("#changePasswordButton");
		
		App.Post('api/auth/change_password', {pass: pass1}, function(response) {
			if (response.err) {
				//$error.html(response.msg);
				App.NotifyError(response.msg);
				return;
			}
			//console.log("Login OK!", response.data);
			App.NotifySuccess(response.msg);

			$form.find("#newPass1").val("");
			$form.find("#newPass2").val("");

			App.ClearCache('AdminUsers');
		});
	});


	var onDataFormSave;

	// Generic Data Form Submit
	$('.data-form').on('submit', function(event) {
		event.preventDefault();
		//var $form = $(this);
		App.FormPostData(this, onDataFormSave);
	});


	// -------------------------------------------------------------------

	chartColors = {
		red: 'rgb(255, 99, 132)',
		orange: 'rgb(255, 159, 64)',
		yellow: 'rgb(255, 205, 86)',
		green: 'rgb(75, 192, 192)',
		blue: 'rgb(54, 162, 235)',
		purple: 'rgb(153, 102, 255)',
		grey: 'rgb(201, 203, 207)'
	};

	chartColorsArr = [
		'#1e87f0',
		'#f0506e',
		//chartColors.red,
		chartColors.orange,
		chartColors.yellow,
		chartColors.green,
		chartColors.blue,
		chartColors.purple,
		chartColors.grey,
		chartColors.orange,
		chartColors.green,
		chartColors.purple,
		chartColors.yellow,
		chartColors.blue,
		chartColors.red,
		chartColors.grey
	];


	function RenderChart(data) {
		var labels = [];
		var hours = [];

		$.each(data, function(i, dt) {
			labels.push(dt.name);
			hours.push(dt.hours);
		});

		$('#globalChart').html('<canvas></canvas>');
		var $canvas = $('#globalChart canvas');
		$canvas.css('min-height', 600);
		$canvas.css('height', window.innerHeight - 350);

		var ctx = $canvas.get(0).getContext('2d');
		var chart = new Chart(ctx, {
			// The type of chart we want to create
			type: 'pie',

			// The data for our dataset
			data: {
				labels: labels,
				datasets: [{
					backgroundColor: chartColorsArr,
					borderColor: chartColorsArr,
					data: hours,
				}]
			},

			// Configuration options go here
			options: {
				legend: {
					display: false
				}
			}
		});
	}

	projectHours = 0;
	$('#projectHours').text(projectHours);

	function GetDashboard() {
		uIndex = 0;
		projectHours = 0;
		$('#projectHours').text(projectHours);
		$('.chartjs-size-monitor').remove();
		App.LoadBindings('#dashboardSection', function(data) {
			RenderChart(data);
			$('#projectHours').text(projectHours);
		});
	}

	App.Load.GetDashboard = GetDashboard;

	var uIndex = 0;
	App.Templates.userTotalCardTemplate = {
		html: $('#userTotalCardTemplate').html(),
		onload: function($obj, data) {
			projectHours += parseInt(data.hours);
			$obj.find('img').attr('style', 'border-color:' + chartColorsArr[uIndex % chartColorsArr.length]);
			uIndex++;
		}
	};












	// -------------------------------------------------------------------

	var currentDate;

	function changeCalendarIcons() {
		$('#timingCalendar').find('.ui-datepicker-prev').html('<div uk-icon="icon: chevron-left; ratio: 1.6"></div>');
		$('#timingCalendar').find('.ui-datepicker-next').html('<div uk-icon="icon: chevron-right; ratio: 1.6"></div>');
	}

	// Timing
	$('#timingCalendar').datepicker({
		firstDay: 1,
		dateFormat: "dd-mm-yy",
		maxDate: "+0D",
		onChangeMonthYear: function(year, month, inst) {
			setTimeout(function() {
				changeCalendarIcons();
				ProcessTiming();
			}, 0);
		},
		onSelect: function(dateText, inst) {
			UIkit.modal('#modalDayTiming').show();
			setTimeout(function() {
				changeCalendarIcons();
				$('#modalDayTiming').find('.uk-modal-title').html('Select hours for ' + dateText);
				ProcessTiming();
			}, 0);
			currentDate = $("#timingCalendar").datepicker( "getDate" );
			var date = $.datepicker.formatDate( "yy-mm-dd", currentDate);
			$('#modalDayTiming').find('.uk-button').removeClass('uk-button-primary');
			if (allTiming[date]) {
				var timing = allTiming[date];
				if (timing.hours > 0) {
					$('#modalDayTiming').find('.uk-button[data-hours="'+timing.hours+'"]').addClass('uk-button-primary');
				}
			}
		}
	});
	$('#timingCalendar > .ui-datepicker-inline').removeClass('ui-widget');

	var today = $.datepicker.formatDate( "dd-mm-yy", new Date() );
	$("#timingCalendar").datepicker( "setDate",  today );
	changeCalendarIcons();

	var totalHours = 0;
	App.Templates.timingTemplate = {
		html: $('#timingTemplate').html(),
		onload: function($obj, data) {
			totalHours += parseInt(data.hours);
			$('#totalHours').text(totalHours);
		}
	};
	$('#timingList').empty();
	$('#modalDayTiming').on('click', '.uk-button', function(e) {
		e.preventDefault();
		var hours = $(this).data('hours');
		var data = {
			date: $.datepicker.formatDate( "yy-mm-dd", currentDate),
			hours: hours
		};
		App.PostData('UpdateTiming', data, function(response) {
			//App.LoadSection("#timingSection");
			GetTiming();
			if (response.err) {
				//$error.html(response.msg);
				App.NotifyError(response.msg);
				return;
			}
			//console.log("Login OK!", response.data);
			App.NotifySuccess(response.msg);
		});
	});
	$('#modalDayTiming').on('click', function() {
		//$("#timingCalendar").datepicker( "setDate",  today );
		ProcessTiming();
	});

	var allTiming = {};

	function ProcessTiming() {
		$('#timingCalendar [data-handler="selectDay"]').each(function() {
			var $day = $(this);
			var year = $day.data('year');
			var month = $day.data('month');
			var day = $day.text();

			var date = $.datepicker.formatDate( "yy-mm-dd", new Date(year, month, day) );

			$day.find('a').removeClass('ui-state-highlight');

			if (allTiming[date]) {
				$day.find('a').addClass('ui-state-active');
			} else {
				$day.find('a').removeClass('ui-state-active');
			}
		});
	}

	function GetTiming() {
		//$("#timingCalendar").datepicker( "setDate",  today );
		totalHours = 0;
		$('#totalHours').text(totalHours);
		changeCalendarIcons();
		App.LoadBindings('#timingSection', function(data) {
			allTiming = data;
			ProcessTiming();
		});
	}

	App.Load.GetTiming = GetTiming;



	// -------------------------------------------------------------------

	// Admin View Log
	$('#systemSection').on('click', '#adminViewLog', function() {
		var $modalBody = $('#modalAdminViewLog .uk-modal-body');
		$modalBody.empty();
		$modalBody.html('<div class="loading-data"><div class="uk-flex uk-flex-center uk-flex-middle uk-height-viewport"><span uk-spinner="ratio: 3.5"></span></div></div>');
		App.Get('api/system/log', function(response) {
			if (response.err) {
				$modalBody.html(response.msg);
				return;
			}
			if (response.data && response.data.length > 0) {
				$modalBody.html('<table id="logTable"></table>');
				$modalBody.find('#logTable').DataTable({
					paging: false,
					info: false,
					ordering: false,
					//order: [[ 0, 'desc' ]]
					data: response.data,
						columns: [
						{ data: 'datetime', title: 'Date', "render": function ( data, type, row, meta ) {
							return data.replace(/\..*/i, "");
							} 
						},
						{ data: 'user', title: 'User' },
						{ data: 'action', title: 'Action' },
						{ data: 'sql', title: 'SQL' }
					]
				});
			}
		});
	});


	// Admin Users
	App.Templates.userCardTemplate = {
		html: $('#userCardTemplate').html(),
		onload: function($obj, data) {
			if (data.active == "") {
				$obj.find('.uk-card').css('opacity', 0.5);
				$obj.find('.uk-card').addClass('uk-background-muted', 0.5);
				$obj.find('.btn-user-active').text('inactive');
			}
		}
	};
	$('.admin-users').empty();
	function InitEditUserForm(title) {
		$('#modalAdminEditUser .uk-modal-title').html(title);
		$('#AdminEditUser [type="submit"]').prop('disabled', false);
		$('#AdminEditUser input').first().focus();

		onDataFormSave = function(data) {
			UIkit.modal('#modalAdminEditUser').hide();
			onDataFormSave = null;
			App.LoadSection("#systemSection");
			var currentUser = App.GetCached('CurrentUser', 'user');
			if (data['__id__'] == currentUser) {
				App.ClearCache('CurrentUser');
				App.GetData('CurrentUser', function(data) {
					App.SetFieldData('CurrentUser', 'body', data);
				});
			}
		}
	}
	$('#systemSection').on('click', '.btn-edit-user', function() {
		InitEditUserForm('Edit user');

		var $button = $(this);
		var id = $button.data('id');
		var dt = App.GetCached('AdminUsers', id);
		$('#AdminEditUser').data('id', id);

		App.SetFieldData('AdminEditUser', $('#modalAdminEditUser'), dt);
	});
	$('#systemSection').on('click', '#adminAddUser', function() {
		InitEditUserForm('Add new user');
		
		$('#AdminEditUser').data('id', null);

		var dt = {
			user: null,
			name: null,
			email: null,
			image: null,
			active: true
		};

		App.SetFieldData('AdminEditUser', $('#modalAdminEditUser'), dt);
	});
	$('#modalAdminEditUser').on('click', '.uk-button-primary', function(event) {
		event.preventDefault();
		var $submit = $('#AdminEditUser [type="submit"]');
		$submit.click()
		if ($submit.prop('disabled')) {
			App.Buttons.Disable(this);
		}
	});
	$('#systemSection').on('click', '.btn-remove-user', function() {
		var $button = $(this);
		var id = $button.data('id');
		var dt = App.GetCached('AdminUsers', id);

		var data = {
			user: id
		};

		UIkit.modal.confirm(
			'<h3 class="uk-text-danger">Warning!! Removing User!</h3>' + 
			'<p>Are you sure you want to remove the user <b>' + dt.name + '</b>?<br>' +
			'<span class="uk-text-meta">All the timing of this user will be removed too.</span></p>' +
			'<span class="uk-label uk-label-danger">This action has no undo!</span>'
		).then(function() {
			App.PostData('AdminRemoveUser', data, function(response) {
				if (response.err) {
					//$error.html(response.msg);
					App.NotifyError(response.msg);
					return;
				}
				//console.log("Login OK!", response.data);
				App.NotifySuccess(response.msg);
				App.LoadSection("#systemSection");
			});
		}, function() {});
	});
	$('#systemSection').on('click', '.btn-user-active', function() {
		var $button = $(this);
		var id = $button.data('id');
		var data = {
			user: id
		};
		App.PostData('AdminActivateUser', data, function(response) {
			App.LoadSection("#systemSection");
			if (response.err) {
				//$error.html(response.msg);
				App.NotifyError(response.msg);
				return;
			}
			//console.log("Login OK!", response.data);
			App.NotifySuccess(response.msg);
		});
	});

})();

