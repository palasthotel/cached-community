"use strict";

(function ($) {

	const storage = localStorage;
	const ajax = CachedCommunity.ajax;
	const user = _restore_user_state();

	CachedCommunity.EVENT = {
		user_update: "cached_community_user_update",
		user_data_update: "cached_community_user_data_update",
	};

	/**
	 * get login state
	 */
	const fetchUserState = ()=> _getRequest(ajax.login).then(data => {
		_save_user_state(data);
		return data;
	});

	/**
	 * login
	 * @param {string} user
	 * @param {string} password
	 * @param {boolean} remember
	 * @private
	 */
	const login = (user, password, remember = true) => _postRequest(
		ajax.login,
		{user, password, remember}
	).then(data => {
		_save_user_state(data.user);
		return data;
	})

	/**
	 * get logged in state
	 * @private
	 */
	const isLoggedIn = () => {
		return typeof CachedCommunity.user !== typeof undefined && CachedCommunity.user !== null && CachedCommunity.user.logged_in;
	}

	/**
	 * check activity stream
	 * @private
	 */
	const get_activity = () => _getRequest()

	/**
	 * logout
	 * @private
	 */
	const logout = () => _postRequest(ajax.logout).then(data => {
		_delete_user_state();
		return data;
	});

	// -------------------------------------------------------------------------------------
	// cache
	// -------------------------------------------------------------------------------------

	/**
	 * set user object
	 * @private
	 */
	function _save_user_state(user) {
		/*
		 * do not overwrite values but those from server
		 */
		CachedCommunity.user = {
			...(CachedCommunity.user || {}),
			...user,
		};
		storage.setItem('cached_community_user', JSON.stringify(CachedCommunity.user));
		document.body.dispatchEvent(_get_event(CachedCommunity.EVENT.user_update));
	}

	function _delete_user_state() {
		storage.removeItem('cached_community_user');
		document.body.dispatchEvent(_get_event(CachedCommunity.EVENT.user_update));
	}

	function _restore_user_state(){
		return JSON.parse(storage.getItem('cached_community_user'));
	}

	function _get_event(event) {
		let _event;
		if (typeof Event === 'function') {
			_event = new Event(event);
		} else {
			_event = document.createEvent('Event');
			_event.initEvent(event, true, true);
		}
		return _event;
	}

	// -------------------------------------------------------------------------------------
	// ajax requests
	// -------------------------------------------------------------------------------------
	const _getRequest = (url, params = {})=> _request(url, "GET", params);
	const _postRequest = (url, params = {})=> _request(url, "POST", params);

	/**
	 *
	 * @param {string} url
	 * @param {string} method
	 * @param {object} params
	 * @param {string|boolean} nonce
	 * @private
	 * @return jqXHR
	 */
	const _request = (url, method , params= {}) => new Promise((resolve, reject)=>{
		$.ajax({
			method,
			url,
			data: params,
			dataType: "json",
			cache: false,
			xhrFields: {
				withCredentials: true,
			},
		})
			.then(data=>{
				resolve(data);
			})
			.fail((data, status, error) => {
				reject(data, status, error)
			});
	})


	// ---------------------------
	// init object
	// ---------------------------
	window.CachedCommunity = {
		...CachedCommunity,
		user: _restore_user_state(),
		login,
		logout,
		fetchUserState,
		is_logged_in: isLoggedIn,
	}

})(jQuery);
