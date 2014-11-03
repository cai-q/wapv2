wapv2
=====

v 2.0.0

--------

This is the wap site transformer of cnhubei.com.

###/admin
The admin page used by administrators, which is independent.

ps. This is a BAD DESIGN of this project I think. I underestimated the complexity of the admin pages, and I pay for it. I repeat a lot of codes.

###/cache
The cache of transformed page. Storing the most recent 2000 url maps of WAPurl and WAPurl.

Because we need to contact remote database during every process of getting the WAPurl by PCurl, which is very inefficiency, I made a cache to store the last 2000 maps in local file.

I implement an priority queue. And serialize it into a cache file after each transformation.

###/libs
The smarty libs.

###/man
The user guide. Unfinished.

###/models
The kernel classes. OO style.

I won't explain every class here. If you want to know more about every class, see the comments in files, or wait for my patch of guide files.

###/templates
The front pages' templates. Smarty used.
