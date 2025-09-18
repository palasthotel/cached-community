# Cached Community Plugin - Summary & Testing Guide

## **What This Plugin Does**

The **Cached Community** plugin solves a common WordPress problem: **user-specific content conflicts with caching**. When you have caching enabled (like Varnish, Cloudflare, etc.), logged-in users often see cached content meant for logged-out users, breaking login states, user-specific content, and dynamic features.

**The plugin's solution:**
- **Identifies "community pages"** - pages that need user-specific content (login forms, user profiles, personalized content)
- **Bypasses cache selectively** - Only community pages get cache bypass, regular pages stay cached for performance
- **Handles comments intelligently** - Automatically purges cache when comments are posted/approved so new comments appear immediately
- **Uses special cookies** - Tracks user states without breaking cache for most of the site

## **Core Mechanisms**

### **Cache Bypass Process:**
1. Plugin detects if current page is a "community page"
2. If yes, redirects to same URL + `?no_cache` parameter  
3. Sets `nocache_headers()` to tell caching systems to bypass cache
4. User gets fresh, uncached content

### **Comment Cache Purging:**
- When comments are posted/approved, plugin sends HTTP `PURGE` requests
- Immediately invalidates cache for that specific post
- New comments appear right away without waiting for cache expiration

### **Community Page Detection:**
Pages become "community pages" through:
- **URL-based:** URLs listed in `cached_community_community_urls` filter
- **Post meta:** Individual posts/pages with `cached_community_deactivate_caching = 1`
- **Custom filters:** Developers can override with `cached_community_is_community_page` filter

## **How to Test the Plugin**

### **Quick Test Method:**
1. **Edit any post/page in WordPress admin**
2. **Enable Custom Fields:**
   - Click three dots (⋮) → Preferences → Panels → Enable "Custom fields"
3. **Add custom field:**
   - Name: `cached_community_deactivate_caching`
   - Value: `1`
4. **Visit that post/page** - should redirect to URL with `?no_cache`
5. **Remove custom field** - behavior stops

### **What Success Looks Like:**
- ✅ **With custom field:** Page redirects to `?no_cache` URL, gets cache bypass headers
- ✅ **Without custom field:** No redirect, normal caching behavior
- ✅ **Comments:** New comments trigger cache purge (check debug logs)

### **Debug Helper (Optional):**
Create `/wp-content/mu-plugins/debug-community.php`:
```php
<?php
function debug_community_logic() {
    if (!current_user_can('manage_options')) return;
    if (class_exists('\CachedCommunity\Plugin')) {
        $plugin = \CachedCommunity\Plugin::instance();
        $is_community = $plugin->request->is_community_page();
        echo "<div style='background: #fff3cd; padding: 15px; margin: 20px;'>";
        echo "Is Community Page: " . ($is_community ? '✅ YES' : '❌ NO') . "<br>";
        echo "Has no_cache param: " . (isset($_GET['no_cache']) ? '✅ YES' : '❌ NO') . "<br>";
        echo "Current URL: " . $_SERVER['REQUEST_URI'] . "<br>";
        echo "</div>";
    }
}
add_action('wp_footer', 'debug_community_logic');