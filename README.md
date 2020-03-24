# URL-Shortener
URL Shortener Service Script

![URLSHORTNER](https://user-images.githubusercontent.com/26626045/77394658-ebc89f00-6dc5-11ea-9bea-c7a635bf8079.jpg)

### .htaccess Settings :- 

```
<IfModule mod_rewrite.c>
    RewriteEngine On

    # Redirect Trailing Slashes...
    RewriteRule ^(.*)/$ /$1 [L,R=301]

    # Handle Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

```

## ForntEnd View :-

![Preview](https://user-images.githubusercontent.com/26626045/77395230-1e26cc00-6dc7-11ea-8987-a7cc3d107bd6.png)

### Watch On Youtube :- https://youtu.be/k9RzaU-MvCA
