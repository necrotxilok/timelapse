<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Timelapse</title>

		<link rel="shortcut icon" href="public/img/favicon.png?beta3" type="image/x-icon">

		<link rel="stylesheet" href="public/vendor/uikit/css/uikit.min.css">
		<link rel="stylesheet" href="public/vendor/jquery-ui/jquery-ui.min.css">
		<link rel="stylesheet" href="public/css/theme.css">
		<link rel="stylesheet" href="public/css/base.css">

		<script src="public/vendor/jquery-3.3.1.min.js"></script>
		<script src="public/vendor/uikit/js/uikit.min.js"></script>
	</head>

	<body>

		<div class="loading">
			<div class="uk-flex uk-flex-center uk-flex-middle uk-height-viewport">
				<span uk-spinner="ratio: 4.5"></span>
			</div>
		</div>

		<div id="login" class="page uk-text-center uk-height-viewport">
			<div class="uk-flex uk-flex-center uk-flex-middle uk-height-viewport random-pic-1920 waitforimages">

				<div class="uk-card uk-card-default uk-card-body">
					<img src="public/img/timelapse-logo.png" style="width:200px;">
					<h4>Init Session</h4>
					<form class="uk-panel uk-panel-box uk-form" id="login">
						<div class="uk-margin">
							<input type="text" class="uk-width-1-1 uk-form-large" id="user" name="username" placeholder="Username" required>
						</div>
						<div class="uk-margin">
							<input type="password" class="uk-width-1-1 uk-form-large" id="pass" name="Password" placeholder="Password" required>
						</div>
						<div class="uk-margin">
							<button type="submit" class="uk-width-1-1 uk-button uk-button-primary uk-button-large" id="loginButton">Login</button>
						</div>
						<div id="error" style="color: #f00;"></div>
					</form>
				</div>

			</div>
		</div>

		<div id="main" class="page" style="display: none;">
			<div uk-sticky="media: 960" class="uk-navbar-container tm-navbar-container uk-sticky uk-sticky-fixed">
				<div class="uk-container uk-container-expand">
					<nav class="uk-navbar">
						<div class="uk-navbar-left">
							<a href="/" class="uk-navbar-item uk-logo">
								<img src="public/img/timelapse-logo-white.png">
							</a>
						</div> 
						<div class="uk-navbar-right">
							<ul class="uk-navbar-nav">
								<li class="uk-active"><a href="#dashboardSection" class="section-link">Dashboard</a></li> 
								<li><a href="#timingSection" class="section-link">Timing</a></li> 
								<li><a href="#userSection" class="section-link user-name" binding="CurrentUser" field="name">User</a></li>
								<li><a href="#systemSection" class="section-link sys-access"><span uk-icon="cog"></span></a></li>
								<li><a href="#logout" uk-toggle>Logout <span class="uk-margin-small-left" uk-icon="sign-out"></span></a></li>
							</ul>
						</div>
					</nav>
				</div>
			</div>
			<div class="uk-container uk-margin">
				<div id="dashboardSection" class="section" data-load="GetDashboard">
					<h1>Dashboard</h1>
					<div uk-grid class="uk-grid-small uk-child-width-expand">
						<div class="uk-padding"><div id="globalChart"></div></div>
						<div class="uk-padding">
							<table id="userTotalsTable" class="uk-table uk-table-divider">
								<thead>
									<tr> <th class="date">User</th> <th class="hours uk-text-right">Hours</th> </tr>
								</thead>
								<tbody id="userTotals" class="uk-margin" data-binding="UserTotals" data-type="list" data-template="userTotalCardTemplate">
									<script type="text/template" id="userTotalCardTemplate">
										<tr> 
											<td class="user" uk-grid>
												<div class="uk-width-auto"><img class="uk-border-circle uk-background-muted" src="{{image}}" width="50" alt=""></div>
												<h3 class="uk-width-expand uk-padding-small">{{name}}</h3>
											</td> 
											<td class="hours uk-text-right"><h3 class="uk-padding-small">{{hours}}</h3></td> 
										</tr>
									</script>
								</tbody>
								<tfoot>
									<tr> <td class="date"><b>PROJECT HOURS</b></td> <td class="hours uk-text-right"><b id="projectHours"></b></td> </tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
				<div id="timingSection" class="section" style="display: none;" data-load="GetTiming">
					<h1>Timing</h1>
					<div uk-grid class="uk-grid-small uk-child-width-expand">
						<div id="timingCalendar"></div>
						<table id="timingListTable" class="uk-table uk-table-divider">
							<thead>
								<tr> <th class="date">Date</th> <th class="hours">Hours</th> </tr>
							</thead>
							<tbody id="timingList" class="uk-margin" data-binding="Timing" data-type="list" data-template="timingTemplate">
								<script type="text/template" id="timingTemplate">
									<tr> <td class="date">{{date}}</td> <td class="hours">{{hours}}</td> </tr>
								</script>
							</tbody>
							<tfoot>
								<tr> <td class="date"><b>TOTAL</b></td> <td class="hours"><b id="totalHours"></b></td> </tr>
							</tfoot>
						</table>
					</div>
					<div id="modalDayTiming" class="uk-modal" uk-modal>
						<div class="uk-modal-dialog">
							<button class="uk-modal-close-default uk-close uk-icon" type="button" uk-close=""></button>
							<div class="uk-modal-header">
								<h2 class="uk-modal-title">Select hours</h2>
							</div>
							<div class="uk-modal-body">
								<div>
									<div class="uk-child-width-expand uk-grid-small" uk-grid>
										<div><button class="uk-button uk-button-large uk-width-1-1 uk-modal-close" data-hours="1">1 h</button></div>
										<div><button class="uk-button uk-button-large uk-width-1-1 uk-modal-close" data-hours="2">2 h</button></div>
										<div><button class="uk-button uk-button-large uk-width-1-1 uk-modal-close" data-hours="3">3 h</button></div>
										<div><button class="uk-button uk-button-large uk-width-1-1 uk-modal-close" data-hours="4">4 h</button></div>
									</div>
									<div class="uk-child-width-expand uk-grid-small" uk-grid>
										<div><button class="uk-button uk-button-large uk-width-1-1 uk-modal-close" data-hours="5">5 h</button></div>
										<div><button class="uk-button uk-button-large uk-width-1-1 uk-modal-close" data-hours="6">6 h</button></div>
										<div><button class="uk-button uk-button-large uk-width-1-1 uk-modal-close" data-hours="7">7 h</button></div>
										<div><button class="uk-button uk-button-large uk-width-1-1 uk-modal-close" data-hours="8">8 h</button></div>
									</div>
									<div class="uk-child-width-expand uk-grid-small" uk-grid>
										<div><button class="uk-button uk-button-large uk-width-1-1 uk-modal-close" data-hours="9">9 h</button></div>
										<div><button class="uk-button uk-button-large uk-width-1-1 uk-modal-close" data-hours="10">10 h</button></div>
										<div><button class="uk-button uk-button-large uk-width-1-1 uk-modal-close" data-hours="11">11 h</button></div>
										<div><button class="uk-button uk-button-large uk-width-1-1 uk-modal-close" data-hours="12">12 h</button></div>
									</div>
									<div class="uk-child-width-expand uk-grid-small" uk-grid>
										<div><button class="uk-button uk-button-danger uk-button-large uk-width-1-1 uk-modal-close" data-hours="0">0 h</button></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="userSection" class="section" style="display: none;" data-binding="CurrentUser">
					<h1>User Profile</h1>
					<div uk-grid="" class="uk-grid">
						<div class="uk-width-2-3@s uk-first-column">
							<form class="uk-form-horizontal data-form" data-post="ChangeProfile" data-update-binding="CurrentUser">
								<div class="uk-margin">
									<label class="uk-form-label" for="profileUser">User ID</label>
									<div class="uk-form-controls">
										<div class="uk-inline">
											<span class="uk-form-icon uk-icon" uk-icon="icon: user"></span>
											<input class="uk-input uk-form-blank uk-form-width-large" id="profileUser" binding="CurrentUser" field="user" type="text" readonly>
										</div>
									</div>
								</div>
								<div class="uk-margin">
									<label class="uk-form-label" for="profileName">Name</label>
									<div class="uk-form-controls">
										<div class="uk-inline">
											<span class="uk-form-icon uk-icon" uk-icon="icon: star"></span>
											<input class="uk-input uk-form-width-large" id="profileName" type="text" binding="CurrentUser" field="name">
										</div>
									</div>
								</div>
								<div class="uk-margin">
									<label class="uk-form-label" for="profileEmail">Email</label>
									<div class="uk-form-controls">
										<div class="uk-inline">
											<span class="uk-form-icon uk-icon" uk-icon="icon: mail"></span>
											<input class="uk-input uk-form-width-large" id="profileEmail" type="email"  binding="CurrentUser" field="email">
										</div>
									</div>
								</div>
								<div class="uk-margin">
									<div class="uk-form-controls">
										<button type="submit" class="uk-button uk-button-primary uk-button-large">Save</button>
									</div>
								</div>
							</form>
						</div>
						<div class="uk-width-1-3@s uk-text-center">
							<!-- https://www.gravatar.com/avatar/00000000000000000000000000000000?s=200&d=robohash -->
							<img class="uk-border-circle uk-background-muted" binding="CurrentUser" field="image" src="" alt="">
						</div>
					</div>
					<h1>Change Password</h1>
					<div uk-grid="" class="uk-grid">
						<div class="uk-width-2-3@s uk-first-column">
							<form class="uk-form-horizontal" id="changepass">
								<div class="uk-margin">
									<label class="uk-form-label" for="newPass1">New Password</label>
									<div class="uk-form-controls">
										<div class="uk-inline">
											<span class="uk-form-icon uk-icon" uk-icon="icon: lock"></span>
											<input class="uk-input uk-form-width-large" id="newPass1" type="password" required>
										</div>
									</div>
								</div>
								<div class="uk-margin">
									<label class="uk-form-label" for="newPass2">Confirm Password</label>
									<div class="uk-form-controls">
										<div class="uk-inline">
											<span class="uk-form-icon uk-icon" uk-icon="icon: lock"></span>
											<input class="uk-input uk-form-width-large" id="newPass2" type="password" required>
										</div>
									</div>
								</div>
								<div class="uk-margin">
									<div class="uk-form-controls">
										<button type="submit" class="uk-button uk-button-danger uk-button-large" id="changePasswordButton">Change</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div id="systemSection" class="section" style="display: none;">
					<h1>System Configuration</h1>
					<h2>System log</h2>
					<button class="uk-button uk-button-secondary uk-button-large" id="adminViewLog" href="#modalAdminViewLog" uk-toggle>View System Log <span class="uk-margin-small-left uk-icon" uk-icon="icon: list"></span></button>
					<div id="modalAdminViewLog" class="uk-modal-full" uk-modal>
						<div class="uk-modal-dialog" style="height: 100%">
							<button class="uk-modal-close-full uk-close uk-close-large uk-icon" type="button" uk-close=""></button>
							<div class="uk-modal-header">
								<h2 class="uk-modal-title">System Log</h2>
							</div>
							<div class="uk-modal-body">

							</div>
						</div>
					</div>
					<h2>Users</h2>
					<button class="uk-button uk-button-primary uk-button-large" id="adminAddUser" href="#modalAdminEditUser" uk-toggle>Add user <span class="uk-margin-small-left uk-icon" uk-icon="icon: plus"></span></button>
					<div class="admin-users uk-margin" data-binding="AdminUsers" data-type="list" data-template="userCardTemplate">
						<script type="text/template" id="userCardTemplate">
							<div class="uk-card uk-grid uk-grid-collapse" style="border-top: 1px solid #efefef;">
								<div class="uk-card-body uk-width-expand">
									<div class="uk-nav-default uk-grid-small uk-flex-middle uk-grid" uk-grid="">
										<div class="uk-width-auto uk-first-column">
											<img class="uk-border-circle uk-background-muted" src="{{image}}" width="50" alt="">
										</div>
										<div class="uk-width-expand">
											<h3 class="uk-card-title uk-margin-remove-bottom">{{name}}</h3>
											<p class="uk-text-meta uk-margin-remove-top"><time datetime="{{lastlogon}}">{{lastlogon}}</time></p>
										</div>
									</div>
								</div>
								<div class="uk-margin uk-margin-right uk-width-auto">
									<button class="uk-button uk-button-default btn-user-active" data-id="{{user}}" style="border: none;"> active</button>
									<button class="uk-button uk-button-primary btn-edit-user" data-id="{{user}}" href="#modalAdminEditUser" uk-toggle>Edit</button>
									<button class="uk-button uk-button-danger btn-remove-user" data-id="{{user}}">Remove</button>
								</div>
							</div>
						</script>
					</div>
					<div id="modalAdminEditUser" class="uk-modal-container uk-modal" uk-modal>
						<div class="uk-modal-dialog">
							<button class="uk-modal-close-default uk-close uk-icon" type="button" uk-close=""></button>
							<div class="uk-modal-header">
								<h2 class="uk-modal-title">Edit user</h2>
							</div>
							<div class="uk-modal-body uk-overflow-auto" uk-overflow-auto="">
								<div uk-grid="" class="uk-grid">
									<div class="uk-width-2-3@s uk-first-column">
										<form class="uk-form-horizontal data-form" id="AdminEditUser" data-post="AdminEditUser">
											<div class="uk-margin">
												<label class="uk-form-label" for="editUserUser">User ID</label>
												<div class="uk-form-controls">
													<div class="uk-inline">
														<span class="uk-form-icon uk-icon" uk-icon="icon: user"></span>
														<input class="uk-input uk-form-width-large" id="editUserUser" binding="AdminEditUser" field="user" type="text" required>
													</div>
												</div>
											</div>
											<div class="uk-margin">
												<label class="uk-form-label" for="editUserRole">Role</label>
												<div class="uk-form-controls">
													<div class="uk-inline">
														<select class="uk-select uk-form-width-large uk-first-column" id="editUserRole" binding="AdminEditUser" field="role" required>
															<option value="basic">Basic user</option>
															<option value="admin">Administrator</option>
														</select>
													</div>
												</div>
											</div>
											<div class="uk-margin">
												<label class="uk-form-label" for="editUserPass">Password</label>
												<div class="uk-form-controls">
													<div class="uk-inline">
														<span class="uk-form-icon uk-icon" uk-icon="icon: lock"></span>
														<input class="uk-input uk-form-width-large" id="editUserPass" binding="AdminEditUser" field="pass" type="text" required>
													</div>
												</div>
											</div>
											<div class="uk-margin">
												<label class="uk-form-label" for="editUserName">Name</label>
												<div class="uk-form-controls">
													<div class="uk-inline">
														<span class="uk-form-icon uk-icon" uk-icon="icon: star"></span>
														<input class="uk-input uk-form-width-large" id="editUserName" type="text" binding="AdminEditUser" field="name">
													</div>
												</div>
											</div>
											<div class="uk-margin">
												<label class="uk-form-label" for="editUserEmail">Email</label>
												<div class="uk-form-controls">
													<div class="uk-inline">
														<span class="uk-form-icon uk-icon" uk-icon="icon: mail"></span>
														<input class="uk-input uk-form-width-large" id="editUserEmail" type="email" binding="AdminEditUser" field="email">
													</div>
												</div>
											</div>
											<div class="uk-margin">
												<label class="uk-form-label" for="editUserActive">Active</label>
												<div class="uk-form-controls">
													<div class="uk-inline">
														<input class="uk-checkbox" id="editUserActive" type="checkbox" checked binding="AdminEditUser" field="active">
													</div>
												</div>
											</div>
											<input type="submit" hidden>
										</form>
									</div>
									<div class="uk-width-1-3@s uk-text-center">
										<!-- https://www.gravatar.com/avatar/00000000000000000000000000000000?s=200&d=robohash -->
										<img class="uk-border-circle uk-background-muted" binding="AdminEditUser" field="image" src="" alt="">
									</div>
								</div>
							</div>
							<div class="uk-modal-footer uk-text-right">
								<button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
								<button class="uk-button uk-button-primary" type="button">Save</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div id="logout" uk-modal>
			<div class="uk-modal-dialog">
				<button class="uk-modal-close-default" type="button" uk-close></button>
				<div class="uk-modal-header">
					<h2 class="uk-modal-title">Logout Session</h2>
				</div>
				<div class="uk-modal-body">
					<p>Are you sure you want to close the session?</p>
				</div>
				<div class="uk-modal-footer uk-text-right">
					<button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
					<button class="uk-button uk-button-primary uk-modal-close" type="button" onclick="App.Logout();">Logout</button>
				</div>
			</div>
		</div>

		<div class="scripts">
			<script type="text/javascript" src="public/vendor/uikit/js/uikit-icons.min.js"></script>
			<script type="text/javascript" src="public/vendor/jquery-ui/jquery-ui.min.js"></script>
			<script type="text/javascript" src="public/vendor/datatables/jquery.dataTables.js"></script>
			<script type="text/javascript" src="public/vendor/chart.min.js"></script>
			<script type="text/javascript" src="public/js/app.js"></script>
			<script type="text/javascript" src="public/js/binding.js"></script>
			<script type="text/javascript" src="public/js/load.js"></script>
		</div>
	</body>

</html>