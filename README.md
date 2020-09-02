## Cached Community

This WordPress plugin tries to use varnish like cache technologies while users are logged in. 

You have to setup your caching layer so that it does not skip cache on default wordpres cookies but recognizes the special login cookie and then skips the cache.

## Cookie Name

You can find the default cookie name in `classes/SpecialCookies::DEFAULT_COOKIE_NAME` or you can overwrite the cookie name via filter.

```php
if(class_exists('\CachedCommunity\Plugin')){
    function my_cookie_name($cookie_name){
        return "my_own_cookie_name";
    }
    add_filter(\CachedCommunity\Plugin::FILTER_COOKIE_NAME, 'my_cookie_name');
}
```

## No Cache

Some urls should never be cached for example a user account page. 
