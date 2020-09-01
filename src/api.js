"use strict";

(function ($) {

	var storage = localStorage;
	var CC = CachedCommunity;
	var ajaxurl = CC.ajaxurl;
	var commands = CC.commands;

	// $.ajaxSetup({
	// 	beforeSend: function(xhr){
	// 		xhr.setRequestHeader(CC.headers.setup_204.key, CC.headers.setup_204.value);
	// 	}
	// });

	CC.EVENT = {
		user_update: "cached_community_user_update",
		user_data_update: "cached_community_user_data_update",
	};

	/**
	 * save user to js
	 * @type {null}
	 */
	CC.user = null;

	/**
	 * get nonce from community domain
	 * @private
	 */
	function _nonce(cb) {
		_request({cmd: commands.nonce}).then(function (data) {
			cb(data);
		}).fail(function (data, status, error) {
			console.error(data, status, error);
			cb(true);
		});
	}

	CC.nonce = _nonce;

	/**
	 * get logged in state
	 * @param cb
	 * @private
	 */
	function _is_logged_in(cb) {
		_request({cmd: commands.state}).done(function (data, textStatus, jqXHR) {
			// console.log("is logged in result", data);
			_save_user_state(data.user);
			if (typeof cb !== typeof undefined) {
				cb(!("success" === textStatus), data.logged_in, data.user);
			}
		}).fail(function (data, status, error) {
			console.error(data, status, error);
			cb(true);
		});
	}

	CC.is_logged_in = _is_logged_in;

	/**
	 * get logged in state
	 * @param cb
	 * @private
	 */
	function _get_user_data(fields, cb) {
		_request({cmd: commands.data, fields: fields}).done(function (data, textStatus, jqXHR) {
			_save_user_data(data);
			if (typeof cb !== typeof undefined) {
				cb(!("success" === textStatus), data);
			}
		}).fail(function (data, status, error) {
			console.error(data, status, error);
			cb(true);

		});
	}

	CC.get_user_data = _get_user_data;

	/**
	 * set nonce to params
	 * @param params
	 * @param cb
	 * @private
	 */
	function _set_nonce(params, cb) {
		_nonce(function (nonce) {
			cb(
					_.assign(params, nonce)
			);
		});
	}

	CC.set_nonce = _set_nonce;

	/**
	 * check activity stream
	 * @param cb
	 * @private
	 */
	function _get_activity(cb) {
		_request({cmd: commands.activity}).done(function (data, textStatus, jqXHR) {
			if (typeof cb !== typeof undefined) {
				cb(("success" !== textStatus), data.items);
			}
		}).fail(function (data, status, error) {
			console.error(data, status, error);
			cb(true);
		});
		;
	}

	CC.get_activity = _get_activity;

	/**
	 * login
	 * @param user
	 * @param password
	 * @param cb function(error)
	 * @private
	 */
	function _login(user, password, cb) {
		_set_nonce({
			user: user,
			password: password,
			cmd: commands.login,
		}, function (params) {
			_request(params).then(function (data, textStatus, jqXHR) {
				_save_user_state(data.user);
				if (typeof cb != typeof undefined) {
					cb(!("success" == textStatus && data.logged_in == true), data);
				}
			}).fail(function (data, status, error) {
				console.error(data, status, error);
				cb(true);
			});
		});

	}

	CC.login = _login;

	/**
	 * logout
	 * @param cb function(error)
	 * @private
	 */
	function _logout(cb) {
		_set_nonce({
			cmd: commands.logout,
		}, function (params) {
			_request(params).then(function (data, textStatus, jqXHR) {
				_delete_user_state();
				_delete_user_data();
				if (typeof cb != typeof undefined) cb(!("success" == textStatus && data.success == true), data)
			}).fail(function (data, status, error) {
				console.error(data, status, error);
				cb(true);
			});
			;
		});
	}

	CC.logout = _logout;

	/**
	 * set user object
	 * @private
	 */
	function _save_user_state(user) {
		/*
		 * do not overwrite values but those from server
		 */
		CC.user = _.assign(CC.user || {}, user);
		storage.setItem('cached_community_user', JSON.stringify(CC.user));
		document.body.dispatchEvent(_get_event(CC.EVENT.user_update));
	}

	function _delete_user_state() {
		storage.removeItem('cached_community_user');
		document.body.dispatchEvent(_get_event(CC.EVENT.user_update));
	}

	/**
	 * set user object
	 * @private
	 */
	function _save_user_data(data) {
		/*
		 * do not overwrite values but those from server
		 */
		Object.keys(data).forEach(field => {
			CC['userData_' + field] = data[field];
			storage.setItem('cached_community_user_data_' + field, CC['userData_' + field]);
		});
		document.body.dispatchEvent(_get_event(CC.EVENT.user_data_update));
	}

	function _delete_user_data() {
		//delete all fields
		Object.keys(localStorage).forEach(key => {
			if(key.indexOf('cached_community_user_data_') === 0) {
				storage.removeItem(key);
			}
		});
		document.body.dispatchEvent(_get_event(CC.EVENT.user_data_update));
	}

	function _get_event(event) {
		var event = null;
		if (typeof Event === 'function') {
			event = new Event(event);
		} else {
			event = document.createEvent('Event');
			event.initEvent(event, true, true);
		}
		return event;
	}

	/**
	 * get user object
	 *
	 */
	function _get_user(hard) {
		if (CC.user != null && (typeof hard === typeof undefined || hard === false)) return CC.user;
		if (hard === true) {

		}
		// quick restore state if null
		CC.user = JSON.parse(storage.getItem('cached_community_user'));
		return CC.user;
	}

	CC.get_user = _get_user;

	/**
	 *
	 * use then(...), .done(...), .fail(...), .always(...)
	 *
	 * @param {object} params
	 * @private
	 * @return jqXHR
	 */
	function _request(params) {
		// console.log("cached community request", ajaxurl, params);
		return $.ajax({
			method: 'POST',
			url: ajaxurl,
			data: params,
			xhrFields: {
				withCredentials: true,
			},
			dataType: "json",
			crossDomain: true,
			timeout: 30000,
			cache: false,
		});
	}

	// general request endpoint for ajax extensions
	CC.request = function (cmd, params) {
		params["cmd"] = cmd;
		return _request(params);
	};

	/**
	 * ajax calls to community domain
	 * @param url
	 * @param params
	 */
	function _ajax(url, params) {

		var defaults = {
			method: 'POST',
			xhrFields: {
				withCredentials: true,
			},
			crossDomain: true,
			timeout: 10000,
			cache: false,
		};

		for (var key in params) {
			if (!params.hasOwnProperty(key)) continue;
			defaults[key] = params[key];
		}

		return $.ajax("//" + CC.domains.community + url, params);
	}

	CC.ajax = _ajax;

})(jQuery);