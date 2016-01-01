# Legacy URL Handler
 Per the clients request; a PHP class was needed to determine if an incoming URL was from the legacy CMS Wordpress and if true redirect the request to the new sub directory. 
 
 Once executed, the class will determine if there is a existing permalink with the matching URL substring in the WordPress wp_posts table.
 
 If a post is found the class will 301 redirect the client to the correct URL. 
