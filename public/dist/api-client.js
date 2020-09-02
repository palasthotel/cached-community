/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/api-client.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/api-client.js":
/*!***************************!*\
  !*** ./src/api-client.js ***!
  \***************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

(function ($) {
  var storage = localStorage;
  var CC = CachedCommunity;
  var ajax = CC.ajax;
  CC.EVENT = {
    user_update: "cached_community_user_update",
    user_data_update: "cached_community_user_data_update"
  };
  /**
   * get login state
   */

  var fetchUserState = function fetchUserState() {
    return _getRequest(ajax.login).then(function (data) {
      console.debug("fetchUserState", data);

      _save_user_state(data);

      return data;
    });
  };
  /**
   * login
   * @param {string} user
   * @param {string} password
   * @param {boolean} remember
   * @private
   */


  var login = function login(user, password) {
    var remember = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : true;
    return _postRequest(ajax.login, {
      user: user,
      password: password,
      remember: remember
    }).then(function (data) {
      console.debug("login", data);

      _save_user_state(data.user);

      return data;
    });
  };
  /**
   * get logged in state
   * @private
   */


  var isLoggedIn = function isLoggedIn() {
    return _typeof(CC.user) === _typeof({}) && CC.user.logged_in;
  };
  /**
   * check activity stream
   * @private
   */


  var get_activity = function get_activity() {
    return _getRequest();
  };
  /**
   * logout
   * @private
   */


  var logout = function logout() {
    return _postRequest(ajax.logout).then(function (data) {
      _delete_user_state();

      return data;
    });
  }; // -------------------------------------------------------------------------------------
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
    CC.user = _objectSpread(_objectSpread({}, CC.user || {}), user);
    storage.setItem('cached_community_user', JSON.stringify(CC.user));
    document.body.dispatchEvent(_get_event(CC.EVENT.user_update));
  }

  function _delete_user_state() {
    storage.removeItem('cached_community_user');
    document.body.dispatchEvent(_get_event(CC.EVENT.user_update));
  }

  function _restore_user_state() {
    return JSON.parse(storage.getItem('cached_community_user'));
  }

  function _get_event(event) {
    var _event;

    if (typeof Event === 'function') {
      _event = new Event(event);
    } else {
      _event = document.createEvent('Event');

      _event.initEvent(event, true, true);
    }

    return _event;
  } // -------------------------------------------------------------------------------------
  // ajax requests
  // -------------------------------------------------------------------------------------


  var _getRequest = function _getRequest(url) {
    var params = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
    return _request(url, "GET", params);
  };

  var _postRequest = function _postRequest(url) {
    var params = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
    return _request(url, "POST", params);
  };
  /**
   *
   * @param {string} url
   * @param {string} method
   * @param {object} params
   * @param {string|boolean} nonce
   * @private
   * @return jqXHR
   */


  var _request = function _request(url, method) {
    var params = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
    return new Promise(function (resolve, reject) {
      console.log("_request");
      $.ajax({
        method: method,
        url: url,
        data: params,
        dataType: "json",
        cache: false,
        xhrFields: {
          withCredentials: true
        }
      }).then(function (data) {
        console.log("response ", data);
        resolve(data);
      }).fail(function (data, status, error) {
        reject(data, status, error);
      });
    });
  }; // ---------------------------
  // init object
  // ---------------------------


  CachedCommunity = _objectSpread(_objectSpread({}, CC), {}, {
    user: _restore_user_state(),
    login: login,
    logout: logout,
    fetchUserState: fetchUserState,
    is_logged_in: isLoggedIn
  });
})(jQuery);

/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAiLCJ3ZWJwYWNrOi8vLy4vc3JjL2FwaS1jbGllbnQuanMiXSwibmFtZXMiOlsiJCIsInN0b3JhZ2UiLCJsb2NhbFN0b3JhZ2UiLCJDQyIsIkNhY2hlZENvbW11bml0eSIsImFqYXgiLCJFVkVOVCIsInVzZXJfdXBkYXRlIiwidXNlcl9kYXRhX3VwZGF0ZSIsImZldGNoVXNlclN0YXRlIiwiX2dldFJlcXVlc3QiLCJsb2dpbiIsInRoZW4iLCJkYXRhIiwiY29uc29sZSIsImRlYnVnIiwiX3NhdmVfdXNlcl9zdGF0ZSIsInVzZXIiLCJwYXNzd29yZCIsInJlbWVtYmVyIiwiX3Bvc3RSZXF1ZXN0IiwiaXNMb2dnZWRJbiIsImxvZ2dlZF9pbiIsImdldF9hY3Rpdml0eSIsImxvZ291dCIsIl9kZWxldGVfdXNlcl9zdGF0ZSIsInNldEl0ZW0iLCJKU09OIiwic3RyaW5naWZ5IiwiZG9jdW1lbnQiLCJib2R5IiwiZGlzcGF0Y2hFdmVudCIsIl9nZXRfZXZlbnQiLCJyZW1vdmVJdGVtIiwiX3Jlc3RvcmVfdXNlcl9zdGF0ZSIsInBhcnNlIiwiZ2V0SXRlbSIsImV2ZW50IiwiX2V2ZW50IiwiRXZlbnQiLCJjcmVhdGVFdmVudCIsImluaXRFdmVudCIsInVybCIsInBhcmFtcyIsIl9yZXF1ZXN0IiwibWV0aG9kIiwiUHJvbWlzZSIsInJlc29sdmUiLCJyZWplY3QiLCJsb2ciLCJkYXRhVHlwZSIsImNhY2hlIiwieGhyRmllbGRzIiwid2l0aENyZWRlbnRpYWxzIiwiZmFpbCIsInN0YXR1cyIsImVycm9yIiwiaXNfbG9nZ2VkX2luIiwialF1ZXJ5Il0sIm1hcHBpbmdzIjoiO1FBQUE7UUFDQTs7UUFFQTtRQUNBOztRQUVBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBO1FBQ0E7UUFDQTtRQUNBOztRQUVBO1FBQ0E7O1FBRUE7UUFDQTs7UUFFQTtRQUNBO1FBQ0E7OztRQUdBO1FBQ0E7O1FBRUE7UUFDQTs7UUFF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SkgsRSIsImZpbGUiOiJhcGktY2xpZW50LmpzIiwic291cmNlc0NvbnRlbnQiOlsiIFx0Ly8gVGhlIG1vZHVsZSBjYWNoZVxuIFx0dmFyIGluc3RhbGxlZE1vZHVsZXMgPSB7fTtcblxuIFx0Ly8gVGhlIHJlcXVpcmUgZnVuY3Rpb25cbiBcdGZ1bmN0aW9uIF9fd2VicGFja19yZXF1aXJlX18obW9kdWxlSWQpIHtcblxuIFx0XHQvLyBDaGVjayBpZiBtb2R1bGUgaXMgaW4gY2FjaGVcbiBcdFx0aWYoaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0pIHtcbiBcdFx0XHRyZXR1cm4gaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0uZXhwb3J0cztcbiBcdFx0fVxuIFx0XHQvLyBDcmVhdGUgYSBuZXcgbW9kdWxlIChhbmQgcHV0IGl0IGludG8gdGhlIGNhY2hlKVxuIFx0XHR2YXIgbW9kdWxlID0gaW5zdGFsbGVkTW9kdWxlc1ttb2R1bGVJZF0gPSB7XG4gXHRcdFx0aTogbW9kdWxlSWQsXG4gXHRcdFx0bDogZmFsc2UsXG4gXHRcdFx0ZXhwb3J0czoge31cbiBcdFx0fTtcblxuIFx0XHQvLyBFeGVjdXRlIHRoZSBtb2R1bGUgZnVuY3Rpb25cbiBcdFx0bW9kdWxlc1ttb2R1bGVJZF0uY2FsbChtb2R1bGUuZXhwb3J0cywgbW9kdWxlLCBtb2R1bGUuZXhwb3J0cywgX193ZWJwYWNrX3JlcXVpcmVfXyk7XG5cbiBcdFx0Ly8gRmxhZyB0aGUgbW9kdWxlIGFzIGxvYWRlZFxuIFx0XHRtb2R1bGUubCA9IHRydWU7XG5cbiBcdFx0Ly8gUmV0dXJuIHRoZSBleHBvcnRzIG9mIHRoZSBtb2R1bGVcbiBcdFx0cmV0dXJuIG1vZHVsZS5leHBvcnRzO1xuIFx0fVxuXG5cbiBcdC8vIGV4cG9zZSB0aGUgbW9kdWxlcyBvYmplY3QgKF9fd2VicGFja19tb2R1bGVzX18pXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm0gPSBtb2R1bGVzO1xuXG4gXHQvLyBleHBvc2UgdGhlIG1vZHVsZSBjYWNoZVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5jID0gaW5zdGFsbGVkTW9kdWxlcztcblxuIFx0Ly8gZGVmaW5lIGdldHRlciBmdW5jdGlvbiBmb3IgaGFybW9ueSBleHBvcnRzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLmQgPSBmdW5jdGlvbihleHBvcnRzLCBuYW1lLCBnZXR0ZXIpIHtcbiBcdFx0aWYoIV9fd2VicGFja19yZXF1aXJlX18ubyhleHBvcnRzLCBuYW1lKSkge1xuIFx0XHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCBuYW1lLCB7IGVudW1lcmFibGU6IHRydWUsIGdldDogZ2V0dGVyIH0pO1xuIFx0XHR9XG4gXHR9O1xuXG4gXHQvLyBkZWZpbmUgX19lc01vZHVsZSBvbiBleHBvcnRzXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnIgPSBmdW5jdGlvbihleHBvcnRzKSB7XG4gXHRcdGlmKHR5cGVvZiBTeW1ib2wgIT09ICd1bmRlZmluZWQnICYmIFN5bWJvbC50b1N0cmluZ1RhZykge1xuIFx0XHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCBTeW1ib2wudG9TdHJpbmdUYWcsIHsgdmFsdWU6ICdNb2R1bGUnIH0pO1xuIFx0XHR9XG4gXHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShleHBvcnRzLCAnX19lc01vZHVsZScsIHsgdmFsdWU6IHRydWUgfSk7XG4gXHR9O1xuXG4gXHQvLyBjcmVhdGUgYSBmYWtlIG5hbWVzcGFjZSBvYmplY3RcbiBcdC8vIG1vZGUgJiAxOiB2YWx1ZSBpcyBhIG1vZHVsZSBpZCwgcmVxdWlyZSBpdFxuIFx0Ly8gbW9kZSAmIDI6IG1lcmdlIGFsbCBwcm9wZXJ0aWVzIG9mIHZhbHVlIGludG8gdGhlIG5zXG4gXHQvLyBtb2RlICYgNDogcmV0dXJuIHZhbHVlIHdoZW4gYWxyZWFkeSBucyBvYmplY3RcbiBcdC8vIG1vZGUgJiA4fDE6IGJlaGF2ZSBsaWtlIHJlcXVpcmVcbiBcdF9fd2VicGFja19yZXF1aXJlX18udCA9IGZ1bmN0aW9uKHZhbHVlLCBtb2RlKSB7XG4gXHRcdGlmKG1vZGUgJiAxKSB2YWx1ZSA9IF9fd2VicGFja19yZXF1aXJlX18odmFsdWUpO1xuIFx0XHRpZihtb2RlICYgOCkgcmV0dXJuIHZhbHVlO1xuIFx0XHRpZigobW9kZSAmIDQpICYmIHR5cGVvZiB2YWx1ZSA9PT0gJ29iamVjdCcgJiYgdmFsdWUgJiYgdmFsdWUuX19lc01vZHVsZSkgcmV0dXJuIHZhbHVlO1xuIFx0XHR2YXIgbnMgPSBPYmplY3QuY3JlYXRlKG51bGwpO1xuIFx0XHRfX3dlYnBhY2tfcmVxdWlyZV9fLnIobnMpO1xuIFx0XHRPYmplY3QuZGVmaW5lUHJvcGVydHkobnMsICdkZWZhdWx0JywgeyBlbnVtZXJhYmxlOiB0cnVlLCB2YWx1ZTogdmFsdWUgfSk7XG4gXHRcdGlmKG1vZGUgJiAyICYmIHR5cGVvZiB2YWx1ZSAhPSAnc3RyaW5nJykgZm9yKHZhciBrZXkgaW4gdmFsdWUpIF9fd2VicGFja19yZXF1aXJlX18uZChucywga2V5LCBmdW5jdGlvbihrZXkpIHsgcmV0dXJuIHZhbHVlW2tleV07IH0uYmluZChudWxsLCBrZXkpKTtcbiBcdFx0cmV0dXJuIG5zO1xuIFx0fTtcblxuIFx0Ly8gZ2V0RGVmYXVsdEV4cG9ydCBmdW5jdGlvbiBmb3IgY29tcGF0aWJpbGl0eSB3aXRoIG5vbi1oYXJtb255IG1vZHVsZXNcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubiA9IGZ1bmN0aW9uKG1vZHVsZSkge1xuIFx0XHR2YXIgZ2V0dGVyID0gbW9kdWxlICYmIG1vZHVsZS5fX2VzTW9kdWxlID9cbiBcdFx0XHRmdW5jdGlvbiBnZXREZWZhdWx0KCkgeyByZXR1cm4gbW9kdWxlWydkZWZhdWx0J107IH0gOlxuIFx0XHRcdGZ1bmN0aW9uIGdldE1vZHVsZUV4cG9ydHMoKSB7IHJldHVybiBtb2R1bGU7IH07XG4gXHRcdF9fd2VicGFja19yZXF1aXJlX18uZChnZXR0ZXIsICdhJywgZ2V0dGVyKTtcbiBcdFx0cmV0dXJuIGdldHRlcjtcbiBcdH07XG5cbiBcdC8vIE9iamVjdC5wcm90b3R5cGUuaGFzT3duUHJvcGVydHkuY2FsbFxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5vID0gZnVuY3Rpb24ob2JqZWN0LCBwcm9wZXJ0eSkgeyByZXR1cm4gT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsKG9iamVjdCwgcHJvcGVydHkpOyB9O1xuXG4gXHQvLyBfX3dlYnBhY2tfcHVibGljX3BhdGhfX1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5wID0gXCJcIjtcblxuXG4gXHQvLyBMb2FkIGVudHJ5IG1vZHVsZSBhbmQgcmV0dXJuIGV4cG9ydHNcbiBcdHJldHVybiBfX3dlYnBhY2tfcmVxdWlyZV9fKF9fd2VicGFja19yZXF1aXJlX18ucyA9IFwiLi9zcmMvYXBpLWNsaWVudC5qc1wiKTtcbiIsIlwidXNlIHN0cmljdFwiO1xuXG4oZnVuY3Rpb24gKCQpIHtcblxuXHR2YXIgc3RvcmFnZSA9IGxvY2FsU3RvcmFnZTtcblx0dmFyIENDID0gQ2FjaGVkQ29tbXVuaXR5O1xuXHRjb25zdCBhamF4ID0gQ0MuYWpheDtcblxuXHRDQy5FVkVOVCA9IHtcblx0XHR1c2VyX3VwZGF0ZTogXCJjYWNoZWRfY29tbXVuaXR5X3VzZXJfdXBkYXRlXCIsXG5cdFx0dXNlcl9kYXRhX3VwZGF0ZTogXCJjYWNoZWRfY29tbXVuaXR5X3VzZXJfZGF0YV91cGRhdGVcIixcblx0fTtcblxuXHQvKipcblx0ICogZ2V0IGxvZ2luIHN0YXRlXG5cdCAqL1xuXHRjb25zdCBmZXRjaFVzZXJTdGF0ZSA9ICgpPT4gX2dldFJlcXVlc3QoYWpheC5sb2dpbikudGhlbihkYXRhID0+IHtcblx0XHRjb25zb2xlLmRlYnVnKFwiZmV0Y2hVc2VyU3RhdGVcIiwgZGF0YSk7XG5cdFx0X3NhdmVfdXNlcl9zdGF0ZShkYXRhKTtcblx0XHRyZXR1cm4gZGF0YTtcblx0fSk7XG5cblx0LyoqXG5cdCAqIGxvZ2luXG5cdCAqIEBwYXJhbSB7c3RyaW5nfSB1c2VyXG5cdCAqIEBwYXJhbSB7c3RyaW5nfSBwYXNzd29yZFxuXHQgKiBAcGFyYW0ge2Jvb2xlYW59IHJlbWVtYmVyXG5cdCAqIEBwcml2YXRlXG5cdCAqL1xuXHRjb25zdCBsb2dpbiA9ICh1c2VyLCBwYXNzd29yZCwgcmVtZW1iZXIgPSB0cnVlKSA9PiBfcG9zdFJlcXVlc3QoXG5cdFx0YWpheC5sb2dpbixcblx0XHR7dXNlciwgcGFzc3dvcmQsIHJlbWVtYmVyfVxuXHQpLnRoZW4oZGF0YSA9PiB7XG5cdFx0Y29uc29sZS5kZWJ1ZyhcImxvZ2luXCIsIGRhdGEpO1xuXHRcdF9zYXZlX3VzZXJfc3RhdGUoZGF0YS51c2VyKTtcblx0XHRyZXR1cm4gZGF0YTtcblx0fSlcblxuXHQvKipcblx0ICogZ2V0IGxvZ2dlZCBpbiBzdGF0ZVxuXHQgKiBAcHJpdmF0ZVxuXHQgKi9cblx0Y29uc3QgaXNMb2dnZWRJbiA9ICgpID0+IHR5cGVvZiBDQy51c2VyID09PSB0eXBlb2Yge30gJiYgQ0MudXNlci5sb2dnZWRfaW47XG5cblx0LyoqXG5cdCAqIGNoZWNrIGFjdGl2aXR5IHN0cmVhbVxuXHQgKiBAcHJpdmF0ZVxuXHQgKi9cblx0Y29uc3QgZ2V0X2FjdGl2aXR5ID0gKCkgPT4gX2dldFJlcXVlc3QoKVxuXG5cdC8qKlxuXHQgKiBsb2dvdXRcblx0ICogQHByaXZhdGVcblx0ICovXG5cdGNvbnN0IGxvZ291dCA9ICgpID0+IF9wb3N0UmVxdWVzdChhamF4LmxvZ291dCkudGhlbihkYXRhID0+IHtcblx0XHRfZGVsZXRlX3VzZXJfc3RhdGUoKTtcblx0XHRyZXR1cm4gZGF0YTtcblx0fSk7XG5cblx0Ly8gLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLVxuXHQvLyBjYWNoZVxuXHQvLyAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXG5cblx0LyoqXG5cdCAqIHNldCB1c2VyIG9iamVjdFxuXHQgKiBAcHJpdmF0ZVxuXHQgKi9cblx0ZnVuY3Rpb24gX3NhdmVfdXNlcl9zdGF0ZSh1c2VyKSB7XG5cdFx0Lypcblx0XHQgKiBkbyBub3Qgb3ZlcndyaXRlIHZhbHVlcyBidXQgdGhvc2UgZnJvbSBzZXJ2ZXJcblx0XHQgKi9cblx0XHRDQy51c2VyID0ge1xuXHRcdFx0Li4uKENDLnVzZXIgfHwge30pLFxuXHRcdFx0Li4udXNlcixcblx0XHR9O1xuXHRcdHN0b3JhZ2Uuc2V0SXRlbSgnY2FjaGVkX2NvbW11bml0eV91c2VyJywgSlNPTi5zdHJpbmdpZnkoQ0MudXNlcikpO1xuXHRcdGRvY3VtZW50LmJvZHkuZGlzcGF0Y2hFdmVudChfZ2V0X2V2ZW50KENDLkVWRU5ULnVzZXJfdXBkYXRlKSk7XG5cdH1cblxuXHRmdW5jdGlvbiBfZGVsZXRlX3VzZXJfc3RhdGUoKSB7XG5cdFx0c3RvcmFnZS5yZW1vdmVJdGVtKCdjYWNoZWRfY29tbXVuaXR5X3VzZXInKTtcblx0XHRkb2N1bWVudC5ib2R5LmRpc3BhdGNoRXZlbnQoX2dldF9ldmVudChDQy5FVkVOVC51c2VyX3VwZGF0ZSkpO1xuXHR9XG5cblx0ZnVuY3Rpb24gX3Jlc3RvcmVfdXNlcl9zdGF0ZSgpe1xuXHRcdHJldHVybiBKU09OLnBhcnNlKHN0b3JhZ2UuZ2V0SXRlbSgnY2FjaGVkX2NvbW11bml0eV91c2VyJykpO1xuXHR9XG5cblx0ZnVuY3Rpb24gX2dldF9ldmVudChldmVudCkge1xuXHRcdGxldCBfZXZlbnQ7XG5cdFx0aWYgKHR5cGVvZiBFdmVudCA9PT0gJ2Z1bmN0aW9uJykge1xuXHRcdFx0X2V2ZW50ID0gbmV3IEV2ZW50KGV2ZW50KTtcblx0XHR9IGVsc2Uge1xuXHRcdFx0X2V2ZW50ID0gZG9jdW1lbnQuY3JlYXRlRXZlbnQoJ0V2ZW50Jyk7XG5cdFx0XHRfZXZlbnQuaW5pdEV2ZW50KGV2ZW50LCB0cnVlLCB0cnVlKTtcblx0XHR9XG5cdFx0cmV0dXJuIF9ldmVudDtcblx0fVxuXG5cdC8vIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cblx0Ly8gYWpheCByZXF1ZXN0c1xuXHQvLyAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXG5cdGNvbnN0IF9nZXRSZXF1ZXN0ID0gKHVybCwgcGFyYW1zID0ge30pPT4gX3JlcXVlc3QodXJsLCBcIkdFVFwiLCBwYXJhbXMpO1xuXHRjb25zdCBfcG9zdFJlcXVlc3QgPSAodXJsLCBwYXJhbXMgPSB7fSk9PiBfcmVxdWVzdCh1cmwsIFwiUE9TVFwiLCBwYXJhbXMpO1xuXG5cdC8qKlxuXHQgKlxuXHQgKiBAcGFyYW0ge3N0cmluZ30gdXJsXG5cdCAqIEBwYXJhbSB7c3RyaW5nfSBtZXRob2Rcblx0ICogQHBhcmFtIHtvYmplY3R9IHBhcmFtc1xuXHQgKiBAcGFyYW0ge3N0cmluZ3xib29sZWFufSBub25jZVxuXHQgKiBAcHJpdmF0ZVxuXHQgKiBAcmV0dXJuIGpxWEhSXG5cdCAqL1xuXHRjb25zdCBfcmVxdWVzdCA9ICh1cmwsIG1ldGhvZCAsIHBhcmFtcz0ge30pID0+IG5ldyBQcm9taXNlKChyZXNvbHZlLCByZWplY3QpPT57XG5cdFx0Y29uc29sZS5sb2coXCJfcmVxdWVzdFwiKVxuXHRcdCQuYWpheCh7XG5cdFx0XHRtZXRob2QsXG5cdFx0XHR1cmwsXG5cdFx0XHRkYXRhOiBwYXJhbXMsXG5cdFx0XHRkYXRhVHlwZTogXCJqc29uXCIsXG5cdFx0XHRjYWNoZTogZmFsc2UsXG5cdFx0XHR4aHJGaWVsZHM6IHtcblx0XHRcdFx0d2l0aENyZWRlbnRpYWxzOiB0cnVlLFxuXHRcdFx0fSxcblx0XHR9KVxuXHRcdFx0LnRoZW4oZGF0YT0+e1xuXHRcdFx0XHRjb25zb2xlLmxvZyhcInJlc3BvbnNlIFwiLCBkYXRhKVxuXHRcdFx0XHRyZXNvbHZlKGRhdGEpO1xuXHRcdFx0fSlcblx0XHRcdC5mYWlsKChkYXRhLCBzdGF0dXMsIGVycm9yKSA9PiB7XG5cdFx0XHRcdHJlamVjdChkYXRhLCBzdGF0dXMsIGVycm9yKVxuXHRcdFx0fSk7XG5cdH0pXG5cblxuXHQvLyAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cblx0Ly8gaW5pdCBvYmplY3Rcblx0Ly8gLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXG5cdENhY2hlZENvbW11bml0eSA9IHtcblx0XHQuLi5DQyxcblx0XHR1c2VyOiBfcmVzdG9yZV91c2VyX3N0YXRlKCksXG5cdFx0bG9naW4sXG5cdFx0bG9nb3V0LFxuXHRcdGZldGNoVXNlclN0YXRlLFxuXHRcdGlzX2xvZ2dlZF9pbjogaXNMb2dnZWRJbixcblx0fVxuXG59KShqUXVlcnkpO1xuIl0sInNvdXJjZVJvb3QiOiIifQ==