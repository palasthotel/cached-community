!function(e){var t={};function n(o){if(t[o])return t[o].exports;var r=t[o]={i:o,l:!1,exports:{}};return e[o].call(r.exports,r,r.exports,n),r.l=!0,r.exports}n.m=e,n.c=t,n.d=function(e,t,o){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:o})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var o=Object.create(null);if(n.r(o),Object.defineProperty(o,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var r in e)n.d(o,r,function(t){return e[t]}.bind(null,r));return o},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=7)}([function(e,t){e.exports=window.wp.element},function(e,t){e.exports=window.wp.data},function(e,t){e.exports=window.wp.domReady},function(e,t){e.exports=window.wp.plugins},function(e,t){e.exports=window.wp.editPost},function(e,t){e.exports=window.wp.components},,function(e,t,n){"use strict";n.r(t);var o=n(0),r=n(2),c=n.n(r),i=n(3),u=n(4),a=n(5),d=n(1);c()(()=>{Object(i.registerPlugin)("cached-community",{render:()=>{const{i18n:e}=GutenbergCachedCommunity,[t,n]=(()=>{const e=Object(d.useSelect)(e=>{var t;return null===(t=e("core/editor").getEditedPostAttribute("meta"))||void 0===t?void 0:t.cached_community_deactivate_caching}),t=Object(d.useDispatch)("core/editor",[e]);return[!e,e=>{t.editPost({meta:{cached_community_deactivate_caching:!e}})}]})();return Object(o.createElement)(u.PluginDocumentSettingPanel,{title:"Cached Community"},Object(o.createElement)(a.ToggleControl,{label:t?e.caching_activated_label:e.caching_deactivated_label,help:e.caching_help,checked:t,onChange:n}))}})})}]);