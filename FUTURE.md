# Thoughtflow about a refactor/creation of a static micro CMS

1. It should have an online backoffice
2. Front should be static-build. A few dynamic characteristics could be added using JS and a really small PHP backend: cart, product stock, etc. (but on later iterations)
3. Backoffice should include a page editor with https://grapesjs.com
4. Basic backoffice should include page/article, menu, user and plugin management
5. Theme support: a small clean theme (HTML5Boilerplate-based), combined with a decent theme-builder should be good for anything.
6. Theme builder must allow for building the generic theme blocs: header, footer, main, etc.
7. Backoffice must be entirely independant of front. Must be installable on a different domain/subdomain as long as it is on the same server (disk-writing rights)
8. Backoffice must be able to use SQLite or MySQL (small sites rarely need a full MySQL installation, but it may be useful for larger ones)
9. We should look into integrating Matomo easily (maybe the first plugin to have)
10. Menu should natively be able of being a MegaMenu.

# Architecture

As usual, F3 + Cortex.
Let's begin with SQLite (we'll add a way to change and configure MySQL later on, F3 will guarantee the ease of change)
GrapeJS

We will need:
1. Page editor: searchable list of pages, with a GrapeJS editor by default
2. Post editor: same thing, but with categories and tags and shit and maybe in markdown by default
3. Categories: nestable, of course. They just need a title, slug, description and maybe header image.
4. Menu editor: must have a name, a link (or page/post id, maybe), and a content field for the megamenu possiblities, editable with GrapeJS
5. Theme editor: it allows the edit the header and the footer. It may include more blocks later on (we'll see)
6. User editor: quite standard. We might need user levels, so let's take it into consideration.
7. General configuration: Site title, description, favicon, noindex/nofollow, output directory, ...
8. Plugin architecture: plugins must be able to add blocks to the editor and entries to the backoffice menu... maybe more later.
9. Installer: A controller that will set everything up
