$(function () {

    $('#side-menu').metisMenu();

});

//Loads the correct sidebar on window load,
//collapses the sidebar on window resize.
// Sets the min-height of #page-wrapper to window size
$(function () {

    $(window).bind("load resize", function () {
        topOffset = 50;
        width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.navbar-collapse').addClass('collapse');
            topOffset = 100; // 2-row-menu
        } else {
            $('div.navbar-collapse').removeClass('collapse');
        }

        height = ((this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height) - 1;
        height = height - topOffset;
        if (height < 1) height = 1;
        if (height > topOffset) {
            $("#page-wrapper").css("min-height", (height) + "px");
        }
    });





    // --- Active state untuk sidebar (MetisMenu v1.1.3 + markup kamu) ---
    (function setActiveMenu() {
        // normalisasi path: buang query/hash, lower, dan trailing slash
        function norm(p) {
            return decodeURI((p || '').split('?')[0].split('#')[0])
                .toLowerCase()
                .replace(/\/+$/, '');
        }
        // samakan base untuk kasus TBS:
        // - /.../{menu}/periode            -> /.../{menu}
        // - /.../{menu}/{periode}/view...  -> /.../{menu}
        function baseTbs(path) {
            return norm(
                path
                    .replace(/\/[^/]+\/view(?:\/.*)?$/, '') // hapus "/{periode}/view(/...)"
                    .replace(/\/periode$/, '')              // hapus "/periode"
            );
        }

        var currentPath = norm(location.pathname || '/');
        var currentBase = baseTbs(currentPath);

        // cari link terbaik (paling spesifik)
        var best = null;

        $('#side-menu a[href]').each(function () {
            var href = this.getAttribute('href');
            if (!href || href === '#') return;

            var a = document.createElement('a');
            a.href = href;

            var linkPath = norm(a.pathname || '');
            var linkBase = baseTbs(linkPath);

            // cocok jika:
            // - currentPath diawali linkPath (match biasa)
            // - ATAU base TBS sama (agar /{periode}/view ikut aktifkan link /periode)
            var ok = (linkPath && currentPath.indexOf(linkPath) === 0) ||
                (linkBase && currentBase === linkBase);

            if (!ok) return;

            var score = Math.max(linkPath.length, linkBase.length);
            if (!best || score > best.score) best = { $a: $(this), score: score };
        });

        if (!best) return;

        var $a = best.$a;

        // 1) tandai A dan LI link yang aktif
        var $li = $a.addClass('active').closest('li').addClass('active');

        // 2) buka semua parent submenu (second/third level) dan tandai parent LI + toggle A
        $a.parents('ul.nav-second-level, ul.nav-third-level').each(function () {
            var $ul = $(this);

            // pastikan punya class collapse (BS3) lalu buka
            if (!$ul.hasClass('collapse')) $ul.addClass('collapse');
            $ul.addClass('in').css('height', 'auto');

            // tandai parent <li> aktif
            $ul.parent('li').addClass('active');

            // highlight anchor toggle parent (bisa href="#" ATAU href nyata seperti /master/karyawan)
            $ul.prev('a').addClass('active');
        });

        // 3) case parent yang punya href nyata (contoh: Master Data /master/karyawan)
        //    kalau sedang di child (/master/tarif), parent A juga kita aktifkan:
        var $parentA = $li.parents('li').children('a').first();
        if ($parentA.length) $parentA.addClass('active');
    })();



    // var url = window.location;

    // var element = $('ul.nav a').filter(function () {
    //     return this.href == url || url.href.indexOf(this.href) == 0;
    // }).addClass('active').parent().parent().addClass('in').parent();


    // if (element.is('li')) {
    //     element.addClass('active');
    // }

    // var currentUrl = window.location.href.split(/[?#]/)[0];
    // console.log(currentUrl);
    // $('#side-menu a').each(function () {
    //     if (this.href === currentUrl) {
    //         $(this).addClass('active');

    //         // Expand parent menu
    //         $(this).parents('li').addClass('active'); // Untuk semua parent <li>
    //         $(this).closest('ul').addClass('in');     // Bootstrap-style collapse
    //         $(this).parents('ul').addClass('mm-show'); // Untuk metisMenu
    //         $(this).parents('li').children('a').attr('aria-expanded', true);
    //     }
    // });
});
