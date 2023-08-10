= Thoughtflow about a refactor/creation of a static micro CMS =
1. It should have an online backoffice
2. Front should be static-build. A few dynamic characteristics could be added using JS and a really small PHP backend: cart, product stock, etc. (but on later iterations)
3. Backoffice should include a page editor with https://grapesjs.com
4. Basic backoffice should include page/article, menu, user and plugin management
5. Theme support: a small clean theme (HTML5Boilerplate-based), combined with a decent theme-builder should be good for anything.
6. Theme builder must allow for building the generic theme blocs: header, footer, main, etc.
7. Backoffice must be entirely independant of front. Must be installable on a different domain/subdomain as long as it is on the same server (disk-writing rights)
8. Backoffice must be able to use SQLite or MySQL (small sites rarely need a full MySQL installation, but it may be useful for larger ones)
9. We should look into integrating Matomo easily (maybe the first plugin to have)
