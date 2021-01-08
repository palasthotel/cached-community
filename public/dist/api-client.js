!function(){"use strict";function e(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}function t(t){for(var r=1;r<arguments.length;r++){var o=null!=arguments[r]?arguments[r]:{};r%2?e(Object(o),!0).forEach((function(e){n(t,e,o[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(o)):e(Object(o)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(o,e))}))}return t}function n(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}function r(e){return(r="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}!function(e){var n=localStorage,o=CachedCommunity.ajax;function u(e){CachedCommunity.user=t(t({},CachedCommunity.user||{}),e),n.setItem("cached_community_user",JSON.stringify(CachedCommunity.user)),document.body.dispatchEvent(i(CachedCommunity.EVENT.user_update))}function c(){return JSON.parse(n.getItem("cached_community_user"))}function i(e){var t;return"function"==typeof Event?t=new Event(e):(t=document.createEvent("Event")).initEvent(e,!0,!0),t}c(),CachedCommunity.EVENT={user_update:"cached_community_user_update",user_data_update:"cached_community_user_data_update"};var a=function(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{};return m(e,"POST",t)},m=function(t,n){var r=arguments.length>2&&void 0!==arguments[2]?arguments[2]:{};return new Promise((function(o,u){e.ajax({method:n,url:t,data:r,dataType:"json",cache:!1,xhrFields:{withCredentials:!0}}).then((function(e){o(e)})).fail((function(e,t,n){u(e,t,n)}))}))};window.CachedCommunity=t(t({},CachedCommunity),{},{user:c(),login:function(e,t){var n=!(arguments.length>2&&void 0!==arguments[2])||arguments[2];return a(o.login,{user:e,password:t,remember:n}).then((function(e){return u(e.user),e}))},logout:function(){return a(o.logout).then((function(e){return n.removeItem("cached_community_user"),document.body.dispatchEvent(i(CachedCommunity.EVENT.user_update)),e}))},fetchUserState:function(){return function(e){return m(e,"GET",arguments.length>1&&void 0!==arguments[1]?arguments[1]:{})}(o.login).then((function(e){return u(e),e}))},is_logged_in:function(){return"undefined"!==r(CachedCommunity.user)&&null!==CachedCommunity.user&&CachedCommunity.user.logged_in}})}(jQuery)}();
//# sourceMappingURL=api-client.map