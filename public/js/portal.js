(function () {
  'use strict';

  function toast(message, type) {
    type = type || 'info';
    var container = document.getElementById('toast-container');
    if (!container) return;
    var el = document.createElement('div');
    el.className = 'portal-toast ' + type;
    el.textContent = message;
    container.appendChild(el);
    setTimeout(function () { el.remove(); }, 3500);
  }

  function isLoggedIn() {
    return localStorage.getItem('isLoggedIn') === 'true';
  }

  function getUser() {
    var email = localStorage.getItem('userEmail') || '';
    var name = localStorage.getItem('userName') || (email ? email.split('@')[0] : 'User');
    var avatar = localStorage.getItem('userAvatar');
    return { email: email, name: name, avatar: avatar };
  }

  function syncAuthUI() {
    var loggedIn = isLoggedIn();
    var user = getUser();

    var loggedInEl = document.getElementById('navbar-logged-in');
    var loginBtn = document.getElementById('navbar-login-btn');
    var sidebarLogged = document.getElementById('sidebar-account-logged');
    var sidebarGuest = document.getElementById('sidebar-account-guest');

    if (loggedInEl) loggedInEl.classList.toggle('hidden', !loggedIn);
    if (loggedInEl) loggedInEl.classList.toggle('sm:flex', loggedIn);
    if (loginBtn) loginBtn.classList.toggle('hidden', loggedIn);
    if (loginBtn) loginBtn.classList.toggle('sm:flex', !loggedIn);
    if (sidebarLogged) sidebarLogged.classList.toggle('hidden', !loggedIn);
    if (sidebarGuest) sidebarGuest.classList.toggle('hidden', loggedIn);

    ['navbar', 'sidebar', 'modal'].forEach(function (prefix) {
      var letter = document.getElementById(prefix + '-avatar-letter');
      var img = document.getElementById(prefix + '-avatar-img');
      var icon = document.getElementById(prefix + '-avatar-icon');
      var username = document.getElementById(prefix + '-username');
      var email = document.getElementById(prefix + '-email');

      if (letter) letter.textContent = user.name.charAt(0).toUpperCase();
      if (username) username.textContent = user.name;
      if (email) email.textContent = user.email;

      if (img && user.avatar) {
        img.src = user.avatar;
        img.classList.remove('hidden');
        if (letter) letter.classList.add('hidden');
        if (icon) icon.classList.add('hidden');
      } else if (img) {
        img.classList.add('hidden');
        if (letter) letter.classList.remove('hidden');
        if (icon) icon.classList.remove('hidden');
      }
    });

    var modalEmail = document.getElementById('modal-email');
    var modalEditName = document.getElementById('modal-edit-name');
    if (modalEmail) modalEmail.value = user.email;
    if (modalEditName) modalEditName.value = user.name;
  }

  function initTheme() {
    if (document.querySelector('.page-landing')) {
      document.documentElement.classList.remove('dark');
      return;
    }
    var saved = localStorage.getItem('portal-theme') || 'dark';
    var isDark = saved === 'dark';
    document.documentElement.classList.toggle('dark', isDark);
    var icon = document.getElementById('theme-icon');
    if (icon) {
      icon.setAttribute('data-lucide', isDark ? 'sun' : 'moon');
      if (window.lucide) lucide.createIcons();
    }
  }

  function toggleTheme() {
    var isDark = document.documentElement.classList.contains('dark');
    var newDark = !isDark;
    document.documentElement.classList.toggle('dark', newDark);
    localStorage.setItem('portal-theme', newDark ? 'dark' : 'light');
    var icon = document.getElementById('theme-icon');
    if (icon) {
      icon.setAttribute('data-lucide', newDark ? 'sun' : 'moon');
      if (window.lucide) lucide.createIcons();
    }
    toast('Tema berhasil diubah ke mode ' + (newDark ? 'Gelap' : 'Terang') + '!', 'success');
  }

  function logout() {
    localStorage.removeItem('isLoggedIn');
    localStorage.removeItem('userEmail');
    localStorage.removeItem('userName');
    localStorage.removeItem('userAvatar');
    syncAuthUI();
    toast('Anda berhasil keluar akun!', 'success');
    window.location.href = '/home';
  }

  function openPanel(id) {
    var panel = document.getElementById(id);
    var overlay = document.getElementById('panel-overlay');
    if (panel) {
      panel.classList.remove('panel-hidden');
      panel.classList.add('panel-visible');
    }
    if (overlay) overlay.classList.remove('hidden');
  }

  function closePanels() {
    ['side-menu', 'settings-panel', 'chatbot-panel'].forEach(function (id) {
      var panel = document.getElementById(id);
      if (panel) {
        panel.classList.add('panel-hidden');
        panel.classList.remove('panel-visible');
      }
    });
    var overlay = document.getElementById('panel-overlay');
    if (overlay) overlay.classList.add('hidden');
    var menuOpen = document.getElementById('menu-icon-open');
    var menuClose = document.getElementById('menu-icon-close');
    if (menuOpen) menuOpen.classList.remove('hidden');
    if (menuClose) menuClose.classList.add('hidden');
  }

  function openAccountModal() {
    var modal = document.getElementById('account-modal');
    if (modal) {
      modal.classList.remove('hidden');
      syncAuthUI();
    }
    closePanels();
  }

  function closeAccountModal() {
    var modal = document.getElementById('account-modal');
    if (modal) modal.classList.add('hidden');
  }

  function initNavbar() {
    var btnMenu = document.getElementById('btn-menu');
    var btnCloseMenu = document.getElementById('btn-close-menu');
    var btnChatbot = document.getElementById('btn-chatbot');
    var btnCloseChatbot = document.getElementById('btn-close-chatbot');
    var btnTheme = document.getElementById('btn-theme');
    var overlay = document.getElementById('panel-overlay');
    var navbarAvatar = document.getElementById('navbar-avatar');
    var btnOpenAccount = document.getElementById('btn-open-account-modal');
    var btnLogout = document.getElementById('btn-logout');
    var btnLogoutModal = document.getElementById('btn-logout-modal');
    var btnCloseModal = document.getElementById('btn-close-account-modal');
    var modalBackdrop = document.getElementById('account-modal-backdrop');
    var btnSaveProfile = document.getElementById('btn-save-profile');
    var btnUploadAvatar = document.getElementById('btn-upload-avatar');
    var modalAvatar = document.getElementById('modal-avatar');
    var avatarInput = document.getElementById('avatar-input');

    if (btnMenu) btnMenu.addEventListener('click', function () {
      var menuOpen = document.getElementById('menu-icon-open');
      var menuClose = document.getElementById('menu-icon-close');
      var isOpen = document.getElementById('side-menu').classList.contains('panel-visible');
      if (isOpen) {
        closePanels();
      } else {
        closePanels();
        openPanel('side-menu');
        if (menuOpen) menuOpen.classList.add('hidden');
        if (menuClose) menuClose.classList.remove('hidden');
      }
    });

    if (btnCloseMenu) btnCloseMenu.addEventListener('click', closePanels);
    if (overlay) overlay.addEventListener('click', closePanels);

    if (btnChatbot) btnChatbot.addEventListener('click', function () {
      closePanels();
      openPanel('chatbot-panel');
    });
    if (btnCloseChatbot) btnCloseChatbot.addEventListener('click', closePanels);
    if (btnTheme) btnTheme.addEventListener('click', toggleTheme);
    if (navbarAvatar) navbarAvatar.addEventListener('click', openAccountModal);
    if (btnOpenAccount) btnOpenAccount.addEventListener('click', openAccountModal);
    if (btnLogout) btnLogout.addEventListener('click', logout);
    if (btnLogoutModal) btnLogoutModal.addEventListener('click', logout);
    if (btnCloseModal) btnCloseModal.addEventListener('click', closeAccountModal);
    if (modalBackdrop) modalBackdrop.addEventListener('click', closeAccountModal);

    var selectedAccent = localStorage.getItem('portal-accent') || '#e53935';
    document.querySelectorAll('.accent-color-btn').forEach(function (btn) {
      if (btn.dataset.accent === selectedAccent) {
        btn.classList.add('border-foreground', 'scale-110');
      }
      btn.addEventListener('click', function () {
        selectedAccent = btn.dataset.accent;
        document.querySelectorAll('.accent-color-btn').forEach(function (b) {
          b.classList.remove('border-foreground', 'scale-110');
        });
        btn.classList.add('border-foreground', 'scale-110');
      });
    });

    var btnApplyAccent = document.getElementById('btn-apply-accent');
    if (btnApplyAccent) btnApplyAccent.addEventListener('click', function () {
      localStorage.setItem('portal-accent', selectedAccent);
      document.documentElement.style.setProperty('--accent-primary', selectedAccent);
      toast('Warna aksen tampilan berhasil diperbarui!', 'success');
    });

    var cardStyle = localStorage.getItem('portal-card') || 'original';
    document.querySelectorAll('.card-style-btn').forEach(function (btn) {
      if (btn.dataset.cardStyle === cardStyle) {
        btn.classList.add('bg-[#e53935]', 'text-white');
        btn.classList.remove('bg-muted', 'text-muted-foreground');
      }
      btn.addEventListener('click', function () {
        cardStyle = btn.dataset.cardStyle;
        localStorage.setItem('portal-card', cardStyle);
        document.querySelectorAll('.card-style-btn').forEach(function (b) {
          b.classList.remove('bg-[#e53935]', 'text-white');
          b.classList.add('bg-muted', 'text-muted-foreground');
        });
        btn.classList.add('bg-[#e53935]', 'text-white');
        btn.classList.remove('bg-muted', 'text-muted-foreground');
        toast('Gaya kartu berhasil diubah menjadi ' + (cardStyle === 'original' ? 'Original' : 'Custom') + '!', 'success');
      });
    });

    if (btnSaveProfile) btnSaveProfile.addEventListener('click', function () {
      var name = document.getElementById('modal-edit-name').value.trim();
      if (name.length < 3) {
        toast('Gagal menyimpan! Nama minimal 3 karakter.', 'error');
        return;
      }
      localStorage.setItem('userName', name);
      syncAuthUI();
      toast('Pengaturan profil berhasil disimpan!', 'success');
      closeAccountModal();
    });

    function handleAvatarUpload(file) {
      if (!file || !file.type.startsWith('image/')) {
        toast('Gagal! Berkas yang diunggah harus berupa gambar.', 'error');
        return;
      }
      var reader = new FileReader();
      reader.onloadend = function () {
        localStorage.setItem('userAvatar', reader.result);
        syncAuthUI();
        toast('Foto profil berhasil diperbarui!', 'success');
      };
      reader.readAsDataURL(file);
    }

    if (btnUploadAvatar) btnUploadAvatar.addEventListener('click', function () { avatarInput.click(); });
    if (modalAvatar) modalAvatar.addEventListener('click', function () { avatarInput.click(); });
    if (avatarInput) avatarInput.addEventListener('change', function (e) {
      if (e.target.files[0]) handleAvatarUpload(e.target.files[0]);
    });

    var searchForm = document.getElementById('navbar-search-form');
    if (searchForm) searchForm.addEventListener('submit', function (e) {
      var input = document.getElementById('navbar-search-input');
      if (input && !input.value.trim()) {
        e.preventDefault();
        toast('Gagal mencari! Kata kunci pencarian tidak boleh kosong.', 'error');
      }
    });
  }

  function handleBookmark(scholarshipId, callback) {
    if (!isLoggedIn()) {
      window.location.href = '/login?redirect=' + encodeURIComponent(window.location.pathname) + '&reason=bookmark';
      return false;
    }
    if (callback) callback();
    return true;
  }

  function buildScholarshipCardHtml(s) {
    var statusColor = s.status === 'Dibuka' ? 'bg-green-600' : (s.status === 'Akan Datang' ? 'bg-blue-600' : 'bg-gray-600');
    return '<a href="/scholarship/' + s.id + '" class="scholarship-card group block rounded-xl overflow-hidden" data-scholarship-id="' + s.id + '">' +
      '<div class="scholarship-card-image relative overflow-hidden flex items-center justify-center p-6" style="aspect-ratio:4/3">' +
      '<img src="' + s.image + '" alt="' + s.title + '" class="max-w-[85%] max-h-[85%] object-contain group-hover:scale-105 transition-transform duration-300">' +
      '<div class="absolute top-2 right-2 text-xl drop-shadow-md leading-none">' + (s.flag || '🌐') + '</div>' +
      (s.level ? '<div class="absolute top-2 left-2 px-1.5 py-0.5 text-[10px] font-bold text-white bg-black/70 rounded backdrop-blur-sm">' + s.level + '</div>' : '') +
      '<div class="absolute bottom-2 left-2 px-2 py-0.5 text-[10px] font-bold text-white rounded-full shadow ' + statusColor + '">' + s.status + '</div>' +
      '<div class="absolute inset-0 bg-black/10 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none"><span class="bg-[#e53935] text-white text-[11px] font-bold px-3 py-1 rounded-full shadow-lg">Lihat Detail →</span></div>' +
      '</div><div class="p-2.5 bg-card"><h3 class="text-xs font-semibold text-foreground line-clamp-2 leading-tight mb-1.5 group-hover:text-[#e53935] transition-colors min-h-[32px]">' + s.title + '</h3>' +
      '<div class="flex items-center justify-between text-[10px] text-muted-foreground"><span class="truncate max-w-[70%]">' + s.location + '</span><span>' + s.updated_ago + '</span></div>' +
      '<div class="flex items-center justify-between text-[10px] text-muted-foreground mt-0.5"><span class="truncate max-w-[70%]">' + s.amount + '</span><span class="text-[#e53935] shrink-0 font-bold">↗ Detail</span></div></div></a>';
  }

  function initLibrary() {
    var page = document.getElementById('library-page');
    if (!page) return;

    var allScholarships = getScholarships();
    var initialQ = (page.dataset.initialQ || '').toLowerCase();
    var state = {
      sort: 'Relevansi',
      matchMode: 'ANY',
      status: 'Semua Status',
      levels: [],
      country: 'Semua Negara',
      q: initialQ
    };

    var grid = document.getElementById('library-grid');
    var empty = document.getElementById('library-empty');
    var countEl = document.getElementById('library-count');

    function levelMatches(scholarshipLevel, filterLevel) {
      var slug = filterLevel.split('/')[0].trim().toLowerCase();
      return (scholarshipLevel || '').toLowerCase().includes(slug);
    }

    function getFiltered() {
      var result = allScholarships.slice();
      if (state.q) {
        result = result.filter(function (s) {
          return s.title.toLowerCase().includes(state.q) || s.provider.toLowerCase().includes(state.q);
        });
      }
      if (state.status !== 'Semua Status') {
        result = result.filter(function (s) { return s.status === state.status; });
      }
      if (state.country !== 'Semua Negara') {
        result = result.filter(function (s) { return s.location === state.country; });
      }
      if (state.levels.length > 0) {
        result = result.filter(function (s) {
          if (state.matchMode === 'ALL') {
            return state.levels.every(function (lvl) { return levelMatches(s.level, lvl); });
          }
          return state.levels.some(function (lvl) { return levelMatches(s.level, lvl); });
        });
      }
      if (state.sort === 'A-Z') {
        result.sort(function (a, b) { return a.title.localeCompare(b.title); });
      }
      return result;
    }

    function render() {
      var filtered = getFiltered();
      if (countEl) countEl.textContent = filtered.length;
      if (!grid || !empty) return;
      if (filtered.length === 0) {
        grid.innerHTML = '';
        grid.classList.add('hidden');
        empty.classList.remove('hidden');
      } else {
        grid.classList.remove('hidden');
        empty.classList.add('hidden');
        grid.innerHTML = filtered.map(buildScholarshipCardHtml).join('');
      }
    }

    document.querySelectorAll('.library-sort-btn').forEach(function (btn) {
      btn.addEventListener('click', function () {
        state.sort = btn.dataset.sort;
        document.querySelectorAll('.library-sort-btn').forEach(function (b) {
          b.className = 'library-sort-btn px-2.5 py-1 rounded-full text-[11px] font-semibold transition-colors ' +
            (b.dataset.sort === state.sort ? 'bg-[#e53935] text-white' : 'bg-muted text-muted-foreground hover:bg-accent hover:text-foreground');
        });
        render();
      });
    });

    document.querySelectorAll('[data-match]').forEach(function (el) {
      el.addEventListener('click', function () {
        state.matchMode = el.dataset.match;
        document.querySelectorAll('[data-match]').forEach(function (m) {
          m.classList.toggle('active', m.dataset.match === state.matchMode);
        });
        render();
      });
    });

    document.querySelectorAll('[data-status]').forEach(function (el) {
      el.addEventListener('click', function () {
        state.status = el.dataset.status;
        document.querySelectorAll('[data-status]').forEach(function (s) {
          s.classList.toggle('active', s.dataset.status === state.status);
        });
        render();
      });
    });

    document.querySelectorAll('[data-level]').forEach(function (el) {
      el.addEventListener('click', function () {
        var lvl = el.dataset.level;
        var willActivate = !el.classList.contains('active');
        document.querySelectorAll('[data-level="' + lvl + '"]').forEach(function (e) {
          e.classList.toggle('active', willActivate);
          e.textContent = willActivate ? '✓' : '';
        });
        if (willActivate) {
          if (state.levels.indexOf(lvl) === -1) state.levels.push(lvl);
        } else {
          state.levels = state.levels.filter(function (l) { return l !== lvl; });
        }
        render();
      });
    });

    document.querySelectorAll('.library-country-select').forEach(function (sel) {
      sel.addEventListener('change', function () {
        state.country = sel.value;
        document.querySelectorAll('.library-country-select').forEach(function (s) { s.value = state.country; });
        render();
      });
    });

    document.querySelectorAll('.library-filter-reset').forEach(function (btn) {
      btn.addEventListener('click', function () {
        state = { sort: 'Relevansi', matchMode: 'ANY', status: 'Semua Status', levels: [], country: 'Semua Negara', q: initialQ };
        document.querySelectorAll('.library-sort-btn').forEach(function (b) {
          b.className = 'library-sort-btn px-2.5 py-1 rounded-full text-[11px] font-semibold transition-colors ' +
            (b.dataset.sort === 'Relevansi' ? 'bg-[#e53935] text-white' : 'bg-muted text-muted-foreground hover:bg-accent hover:text-foreground');
        });
        document.querySelectorAll('[data-match]').forEach(function (m) { m.classList.toggle('active', m.dataset.match === 'ANY'); });
        document.querySelectorAll('[data-status]').forEach(function (s) { s.classList.toggle('active', s.dataset.status === 'Semua Status'); });
        document.querySelectorAll('[data-level]').forEach(function (l) { l.classList.remove('active'); l.textContent = ''; });
        document.querySelectorAll('.library-country-select').forEach(function (s) { s.value = 'Semua Negara'; });
        render();
      });
    });

    var mobileBtn = document.getElementById('btn-mobile-filter');
    if (mobileBtn) {
      mobileBtn.addEventListener('click', function () {
        document.getElementById('mobile-filter').classList.toggle('hidden');
      });
    }

    render();
  }

  function initLoginForm() {
    var form = document.getElementById('login-form');
    if (!form) return;

    var urlParams = new URLSearchParams(window.location.search);
    var reason = urlParams.get('reason');
    if (reason === 'bookmark') {
      setTimeout(function() {
        toast('Silakan login terlebih dahulu untuk menyimpan beasiswa ke bookmark.', 'info');
      }, 300);
    } else if (reason === 'register') {
      setTimeout(function() {
        toast('Silakan login terlebih dahulu untuk melakukan pendaftaran beasiswa.', 'info');
      }, 300);
    }

    var isLogin = true;
    var showPassword = false;
    var tabLogin = document.getElementById('tab-login');
    var tabRegister = document.getElementById('tab-register');
    var nameField = document.getElementById('name-field');
    var rememberRow = document.getElementById('remember-row');
    var submitBtn = document.getElementById('login-submit');
    var passwordInput = document.getElementById('password-input');
    var btnTogglePassword = document.getElementById('btn-toggle-password');
    var redirect = form.dataset.redirect || '/home';

    function updateTabs() {
      if (tabLogin) {
        tabLogin.className = 'flex-1 py-2.5 rounded-lg text-sm font-semibold transition-all ' +
          (isLogin ? 'bg-[#e53935] text-white shadow' : 'text-muted-foreground hover:text-foreground');
      }
      if (tabRegister) {
        tabRegister.className = 'flex-1 py-2.5 rounded-lg text-sm font-semibold transition-all ' +
          (!isLogin ? 'bg-[#e53935] text-white shadow' : 'text-muted-foreground hover:text-foreground');
      }
      if (nameField) nameField.classList.toggle('hidden', isLogin);
      if (rememberRow) rememberRow.classList.toggle('hidden', !isLogin);
      if (submitBtn) submitBtn.textContent = isLogin ? 'Login' : 'Daftar Sekarang';
    }

    if (tabLogin) tabLogin.addEventListener('click', function () { isLogin = true; updateTabs(); });
    if (tabRegister) tabRegister.addEventListener('click', function () { isLogin = false; updateTabs(); });

    if (btnTogglePassword) btnTogglePassword.addEventListener('click', function () {
      showPassword = !showPassword;
      passwordInput.type = showPassword ? 'text' : 'password';
      var icon = btnTogglePassword.querySelector('[data-lucide]');
      if (icon) {
        icon.setAttribute('data-lucide', showPassword ? 'eye-off' : 'eye');
        if (window.lucide) lucide.createIcons();
      }
    });

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var email = document.getElementById('email-input').value;
      var password = passwordInput.value;
      var name = document.getElementById('name-input') ? document.getElementById('name-input').value : '';

      var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
        toast('Gagal! Format email tidak valid (contoh: nama@domain.com).', 'error');
        return;
      }
      if (password.length < 6) {
        toast('Gagal! Kata sandi harus minimal 6 karakter.', 'error');
        return;
      }
      if (!isLogin && name.trim().length < 3) {
        toast('Gagal! Nama lengkap harus minimal 3 karakter.', 'error');
        return;
      }

      var finalName = name.trim() || email.split('@')[0];
      localStorage.setItem('isLoggedIn', 'true');
      localStorage.setItem('userEmail', email);
      localStorage.setItem('userName', finalName);

      toast(isLogin ? 'Login Berhasil! Selamat datang kembali, ' + finalName + '.' : 'Registrasi Berhasil! Selamat datang, ' + finalName + '.', 'success');
      window.location.href = redirect;
    });

    updateTabs();
  }

  function initDashboard() {
    var container = document.getElementById('dashboard-bookmarks');
    if (!container) return;

    if (!isLoggedIn()) {
      window.location.href = '/login?redirect=' + encodeURIComponent('/dashboard');
      return;
    }

    var scholarships = JSON.parse(container.dataset.scholarships || '[]');
    var bookmarks = JSON.parse(localStorage.getItem('bookmarks') || '[]');
    var searchQ = '';
    var activeTab = 'bookmark';

    function render() {
      var bookmarked = scholarships.filter(function (s) { return bookmarks.indexOf(s.id) !== -1; });
      var filtered = bookmarked.filter(function (s) {
        if (!searchQ) return true;
        var q = searchQ.toLowerCase();
        return s.title.toLowerCase().includes(q) || s.provider.toLowerCase().includes(q);
      });

      document.getElementById('bookmark-count').textContent = bookmarks.length;
      document.getElementById('tab-bookmark-label').textContent = 'Bookmark (' + bookmarks.length + ')';

      var grid = document.getElementById('dashboard-grid');
      var empty = document.getElementById('dashboard-empty');

      if (activeTab === 'bookmark' && filtered.length > 0) {
        grid.classList.remove('hidden');
        empty.classList.add('hidden');
        grid.innerHTML = filtered.map(function (s) {
          return buildScholarshipCardHtml(s);
        }).join('');
      } else {
        grid.classList.add('hidden');
        empty.classList.remove('hidden');
        var title = document.getElementById('empty-title');
        var desc = document.getElementById('empty-desc');
        if (activeTab === 'bookmark') {
          title.textContent = searchQ ? 'Tidak ada beasiswa yang cocok' : 'Belum ada beasiswa yang disimpan';
          desc.textContent = searchQ ? 'Ubah kata kunci pencarian Anda' : 'Mulai tambahkan beasiswa ke bookmark!';
        } else {
          title.textContent = 'Belum ada beasiswa yang ditandai';
          desc.textContent = 'Tandai beasiswa yang ingin kamu pantau!';
        }
      }
    }

    var searchInput = document.getElementById('dashboard-search');
    if (searchInput) searchInput.addEventListener('input', function (e) { searchQ = e.target.value; render(); });

    document.getElementById('tab-bookmark').addEventListener('click', function () {
      activeTab = 'bookmark';
      document.getElementById('tab-bookmark').className = 'px-4 py-2.5 text-sm font-semibold transition-colors border-b-2 -mb-px border-[#e53935] text-[#e53935]';
      document.getElementById('tab-marked').className = 'px-4 py-2.5 text-sm font-semibold transition-colors border-b-2 -mb-px border-transparent text-muted-foreground hover:text-foreground';
      render();
    });

    document.getElementById('tab-marked').addEventListener('click', function () {
      activeTab = 'marked';
      document.getElementById('tab-marked').className = 'px-4 py-2.5 text-sm font-semibold transition-colors border-b-2 -mb-px border-[#e53935] text-[#e53935]';
      document.getElementById('tab-bookmark').className = 'px-4 py-2.5 text-sm font-semibold transition-colors border-b-2 -mb-px border-transparent text-muted-foreground hover:text-foreground';
      render();
    });

    render();
  }

  function initScholarshipDetail() {
    var page = document.getElementById('scholarship-detail-page');
    if (!page) return;

    var id = page.dataset.id;
    var btnBookmark = document.getElementById('btn-bookmark');
    var btnScrollRegister = document.getElementById('btn-scroll-register');
    var registerForm = document.getElementById('scholarship-register-form');

    if (btnScrollRegister) {
      btnScrollRegister.addEventListener('click', function () {
        var section = document.getElementById('registration-form-section');
        if (section) section.scrollIntoView({ behavior: 'smooth', block: 'start' });
      });
    }

    if (btnBookmark) {
      var bookmarks = JSON.parse(localStorage.getItem('bookmarks') || '[]');
      var isBookmarked = bookmarks.indexOf(id) !== -1;

      function updateBookmarkUI() {
        btnBookmark.className = 'btn-3d-outline flex items-center gap-2 px-5 py-3 rounded-xl font-bold text-sm transition-all ' +
          (isBookmarked ? 'bg-[#e53935]/15 text-[#e53935] border-[#e53935]' : 'text-foreground');
        btnBookmark.innerHTML = '<i data-lucide="bookmark" class="w-4 h-4 ' + (isBookmarked ? 'fill-[#e53935]' : '') + '"></i> ' + (isBookmarked ? 'Disimpan' : 'Simpan');
        if (window.lucide) lucide.createIcons();
      }

      updateBookmarkUI();

      btnBookmark.addEventListener('click', function () {
        if (!handleBookmark(id)) return;

        var list = JSON.parse(localStorage.getItem('bookmarks') || '[]');
        if (isBookmarked) {
          list = list.filter(function (item) { return item !== id; });
          isBookmarked = false;
          toast('Beasiswa berhasil dihapus dari Bookmark.', 'info');
        } else {
          list.push(id);
          isBookmarked = true;
          toast('Beasiswa berhasil disimpan ke Bookmark!', 'success');
        }
        localStorage.setItem('bookmarks', JSON.stringify(list));
        updateBookmarkUI();
      });
    }

    if (registerForm) {
      registerForm.addEventListener('submit', function (e) {
        e.preventDefault();
        var motivation = registerForm.motivation.value.trim();
        if (motivation.length < 50) {
          toast('Motivation letter minimal 50 karakter.', 'error');
          return;
        }
        var apps = JSON.parse(localStorage.getItem('scholarshipApplications') || '[]');
        apps.push({
          scholarship_id: registerForm.dataset.scholarshipId,
          scholarship_title: registerForm.dataset.scholarshipTitle,
          submitted_at: new Date().toISOString(),
          name: registerForm.full_name.value
        });
        localStorage.setItem('scholarshipApplications', JSON.stringify(apps));
        toast('Pendaftaran beasiswa berhasil dikirim! Tim kami akan meninjau aplikasi Anda.', 'success');
        registerForm.reset();
      });
    }
  }

  window.PortalBeasiswa = { toast: toast, isLoggedIn: isLoggedIn, syncAuthUI: syncAuthUI };

  function getScholarships() {
    var local = localStorage.getItem('custom_scholarships');
    if (local) return JSON.parse(local);

    var adminPage = document.getElementById('admin-page');
    if (adminPage && adminPage.dataset.initialScholarships) {
      var data = JSON.parse(adminPage.dataset.initialScholarships);
      localStorage.setItem('custom_scholarships', JSON.stringify(data));
      return data;
    }

    var libraryPage = document.getElementById('library-page');
    if (libraryPage && libraryPage.dataset.scholarships) {
      return JSON.parse(libraryPage.dataset.scholarships);
    }

    var rawGrid = document.getElementById('home-updates-grid');
    if (rawGrid && rawGrid.dataset.rawScholarships) {
      return JSON.parse(rawGrid.dataset.rawScholarships);
    }

    return [];
  }

  function initHomeClientRendering() {
    var updatesGrid = document.getElementById('home-updates-grid');
    var latestGrid = document.getElementById('home-latest-grid');
    var trendingList = document.getElementById('home-trending-list');
    if (!updatesGrid && !latestGrid && !trendingList) return;

    var scholarships = getScholarships();
    if (!scholarships || scholarships.length === 0) return;

    if (updatesGrid) {
      updatesGrid.innerHTML = scholarships.slice(0, 4).map(function(s) {
        return buildScholarshipCardHtml(s);
      }).join('');
    }

    if (latestGrid) {
      latestGrid.innerHTML = scholarships.slice(4, 12).map(function(s) {
        return buildScholarshipCardHtml(s);
      }).join('');
    }

    if (trendingList) {
      trendingList.innerHTML = scholarships.slice(0, 3).map(function(s) {
        return '<a href="' + s.external_link + '" target="_blank" rel="noopener noreferrer" class="flex gap-2 p-2 rounded-lg hover:bg-muted transition-colors group cursor-pointer">' +
          '<div class="relative shrink-0 w-10 h-10 bg-white flex items-center justify-center p-1.5 border border-border rounded overflow-hidden">' +
          '<img src="' + s.image + '" alt="' + s.title + '" class="max-w-full max-h-full object-contain">' +
          '</div>' +
          '<div class="flex-1 min-w-0">' +
          '<p class="text-foreground text-[11px] font-semibold line-clamp-2 leading-tight group-hover:text-[#e53935] transition-colors">' + s.title + '</p>' +
          '<p class="text-muted-foreground text-[10px] mt-0.5">' + s.level + '</p>' +
          '<p class="text-muted-foreground text-[10px]">' + s.updated_ago + '</p>' +
          '</div></a>';
      }).join('');
    }
  }

  function initDetailClientRendering() {
    var page = document.getElementById('scholarship-detail-page');
    if (!page) return;
    var id = page.dataset.id;
    var scholarships = getScholarships();
    var s = scholarships.find(function(item) { return item.id === id; });
    if (!s) return;

    var bannerImg = page.querySelector('div.relative.h-48 img, div.relative.h-64 img');
    if (bannerImg) bannerImg.src = s.image;

    var titleH1 = page.querySelector('h1');
    if (titleH1) titleH1.textContent = s.title;

    var providerP = page.querySelector('p.text-muted-foreground');
    if (providerP) providerP.textContent = s.provider;

    var registerLink = document.getElementById('btn-register-link');
    if (registerLink) {
      registerLink.addEventListener('click', function(e) {
        if (!isLoggedIn()) {
          e.preventDefault();
          window.location.href = '/login?redirect=' + encodeURIComponent(window.location.pathname) + '&reason=register';
        }
      });
    }
  }

  function initRegisterPage() {
    var page = document.getElementById('scholarship-register-page');
    if (!page) return;

    if (!isLoggedIn()) {
      window.location.href = '/login?redirect=' + encodeURIComponent(window.location.pathname) + '&reason=register';
      return;
    }

    var id = page.dataset.id;
    var scholarships = getScholarships();
    var s = scholarships.find(function(item) { return item.id === id; });
    if (s) {
      var titleSpan = page.querySelector('h1 span');
      if (titleSpan) titleSpan.textContent = s.title;
      var bannerImg = page.querySelector('div.relative.h-28 img');
      if (bannerImg) bannerImg.src = s.image;
    }

    var registerForm = document.getElementById('scholarship-register-form');
    if (registerForm) {
      var fileInputs = registerForm.querySelectorAll('input[type="file"]');
      fileInputs.forEach(function(input) {
        input.addEventListener('change', function(e) {
          var file = e.target.files[0];
          var previewId = input.dataset.previewId;
          var previewDiv = document.getElementById(previewId);
          if (!previewDiv) return;

          if (!file) {
            previewDiv.innerHTML = '';
            previewDiv.classList.add('hidden');
            return;
          }

          previewDiv.classList.remove('hidden');
          if (file.type.startsWith('image/')) {
            var reader = new FileReader();
            reader.onload = function(evt) {
              previewDiv.innerHTML = '<img src="' + evt.target.result + '" class="w-12 h-12 object-cover rounded-lg border border-border shrink-0 shadow-sm">' +
                '<div class="flex-1 min-w-0"><p class="text-xs text-foreground font-semibold truncate">' + file.name + '</p>' +
                '<p class="text-[10px] text-muted-foreground">' + (file.size/1024).toFixed(1) + ' KB</p></div>' +
                '<button type="button" class="btn-remove-preview text-muted-foreground hover:text-[#e53935]"><i data-lucide="trash-2" class="w-4 h-4"></i></button>';
              if (window.lucide) lucide.createIcons();

              previewDiv.querySelector('.btn-remove-preview').addEventListener('click', function() {
                input.value = '';
                previewDiv.innerHTML = '';
                previewDiv.classList.add('hidden');
              });
            };
            reader.readAsDataURL(file);
          } else {
            previewDiv.innerHTML = '<div class="w-10 h-10 rounded-lg bg-red-100 dark:bg-red-950/40 text-red-500 flex items-center justify-center shrink-0">' +
              '<i data-lucide="file-text" class="w-5 h-5"></i></div>' +
              '<div class="flex-1 min-w-0"><p class="text-xs text-foreground font-semibold truncate">' + file.name + '</p>' +
              '<p class="text-[10px] text-muted-foreground">' + (file.size/1024).toFixed(1) + ' KB</p></div>' +
              '<button type="button" class="btn-remove-preview text-muted-foreground hover:text-[#e53935]"><i data-lucide="trash-2" class="w-4 h-4"></i></button>';
            if (window.lucide) lucide.createIcons();

            previewDiv.querySelector('.btn-remove-preview').addEventListener('click', function() {
              input.value = '';
              previewDiv.innerHTML = '';
              previewDiv.classList.add('hidden');
            });
          }
        });
      });

      registerForm.addEventListener('submit', function(e) {
        e.preventDefault();
        var motivation = registerForm.motivation.value.trim();
        if (motivation.length < 50) {
          toast('Motivation letter minimal 50 karakter.', 'error');
          return;
        }

        var apps = JSON.parse(localStorage.getItem('scholarshipApplications') || '[]');
        apps.push({
          scholarship_id: id,
          scholarship_title: s ? s.title : 'Beasiswa',
          submitted_at: new Date().toISOString(),
          name: registerForm.full_name.value
        });
        localStorage.setItem('scholarshipApplications', JSON.stringify(apps));
        toast('Pendaftaran beasiswa berhasil dikirim! Tim kami akan meninjau aplikasi Anda.', 'success');
        registerForm.reset();
        
        fileInputs.forEach(function(inp) {
          var pId = inp.dataset.previewId;
          var pDiv = document.getElementById(pId);
          if (pDiv) {
            pDiv.innerHTML = '';
            pDiv.classList.add('hidden');
          }
        });

        setTimeout(function() {
          window.location.href = '/scholarship/' + id;
        }, 1500);
      });
    }
  }

  function initChatbot() {
    var btnChatbot = document.getElementById('btn-chatbot');
    var chatbotPanel = document.getElementById('chatbot-panel');
    var btnCloseChatbot = document.getElementById('btn-close-chatbot');
    var btnSendChatbot = document.getElementById('btn-send-chatbot');
    var chatbotInput = document.getElementById('chatbot-input');
    var chatbotMessages = document.getElementById('chatbot-messages');
    var overlay = document.getElementById('panel-overlay');

    if (btnChatbot) {
      btnChatbot.addEventListener('click', function() {
        closePanels();
        chatbotPanel.classList.remove('panel-hidden');
        chatbotPanel.classList.add('panel-visible');
        if (overlay) overlay.classList.remove('hidden');
      });
    }

    if (btnCloseChatbot) btnCloseChatbot.addEventListener('click', closePanels);

    var responses = {
      "bagaimana cara daftar beasiswa?": "Untuk mendaftar beasiswa:\n1. Masuk ke halaman **Katalog Beasiswa**.\n2. Pilih beasiswa yang Anda inginkan.\n3. Klik tombol **Daftar Beasiswa** (Anda harus login terlebih dahulu).\n4. Isi formulir pendaftaran lengkap dan unggah dokumen berkas Anda.",
      "apa saja syarat unggahan ktp & berkas?": "Syarat unggahan berkas adalah:\n1. **KTP** (format JPG/PNG/PDF, ukuran maks 2MB).\n2. **Ijazah Terakhir** & **Transkrip Nilai** resmi.\n3. **CV** / Resume pendukung (opsional).\n4. **Motivation Letter** minimal 50 karakter.",
      "bagaimana cara bookmark beasiswa?": "Untuk menyimpan beasiswa:\n1. Buka halaman detail beasiswa.\n2. Klik tombol **Simpan** (icon Bookmark).\n3. Beasiswa yang disimpan akan muncul di **Dashboard** Anda di bagian tab **Bookmark**.",
      "bagaimana membuka link beasiswa resmi?": "Pada halaman detail beasiswa, silakan klik tombol **Kunjungi Website Resmi** di bawah judul beasiswa. Tautan tersebut akan mengarahkan Anda langsung ke portal resmi penyelenggara beasiswa di tab baru."
    };

    function appendMessage(text, sender) {
      var wrapper = document.createElement('div');
      wrapper.className = 'flex gap-2.5 max-w-[85%] ' + (sender === 'user' ? 'ml-auto justify-end' : '');
      
      var avatar = '';
      if (sender === 'bot') {
        avatar = '<div class="w-7 h-7 rounded-full bg-[#e53935]/10 border border-[#e53935]/20 flex items-center justify-center text-[#e53935] shrink-0">' +
          '<i data-lucide="bot" class="w-3.5 h-3.5"></i></div>';
      }
      
      var bubbleClass = sender === 'user' 
        ? 'bg-[#e53935] text-white rounded-tr-none' 
        : 'bg-muted text-foreground rounded-tl-none';

      var formattedText = text
        .replace(/\n/g, '<br>')
        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');

      wrapper.innerHTML = avatar + '<div class="' + bubbleClass + ' px-3 py-2 rounded-2xl text-xs leading-relaxed shadow-sm">' + formattedText + '</div>';
      chatbotMessages.appendChild(wrapper);
      if (window.lucide) lucide.createIcons();
      chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    }

    function handleChatbotSubmit() {
      var query = chatbotInput.value.trim();
      if (!query) return;

      appendMessage(query, 'user');
      chatbotInput.value = '';

      setTimeout(function() {
        var lower = query.toLowerCase();
        var reply = "Maaf, saya tidak mengerti pertanyaan tersebut. Coba gunakan tombol pertanyaan populer di bawah untuk respon instan!";
        
        for (var key in responses) {
          if (lower.indexOf(key) !== -1 || key.indexOf(lower) !== -1) {
            reply = responses[key];
            break;
          }
        }
        appendMessage(reply, 'bot');
      }, 500);
    }

    if (btnSendChatbot) btnSendChatbot.addEventListener('click', handleChatbotSubmit);
    if (chatbotInput) chatbotInput.addEventListener('keydown', function(e) {
      if (e.key === 'Enter') handleChatbotSubmit();
    });

    document.querySelectorAll('.chatbot-chip').forEach(function(chip) {
      chip.addEventListener('click', function() {
        var text = chip.textContent;
        appendMessage(text, 'user');
        
        setTimeout(function() {
          var reply = responses[text.toLowerCase()] || "Respon tidak ditemukan.";
          appendMessage(reply, 'bot');
        }, 300);
      });
    });
  }

  function initAdminPanel() {
    var page = document.getElementById('admin-page');
    if (!page) return;

    var scholarships = getScholarships();
    var btnAdd = document.getElementById('btn-add-scholarship');
    var modal = document.getElementById('crud-modal');
    var btnCloseModal = document.getElementById('btn-close-crud-modal');
    var modalBackdrop = document.getElementById('crud-modal-backdrop');
    var form = document.getElementById('crud-form');
    var searchInput = document.getElementById('admin-search');
    var tbody = document.getElementById('admin-table-body');
    var emptyEl = document.getElementById('admin-empty-table');

    function renderTable(filterText) {
      var list = scholarships;
      if (filterText) {
        var q = filterText.toLowerCase();
        list = list.filter(function(s) {
          return s.title.toLowerCase().includes(q) || s.provider.toLowerCase().includes(q);
        });
      }

      document.getElementById('stat-total-scholarships').textContent = scholarships.length;
      var apps = JSON.parse(localStorage.getItem('scholarshipApplications') || '[]');
      document.getElementById('stat-total-applications').textContent = apps.length;

      if (list.length === 0) {
        tbody.innerHTML = '';
        emptyEl.classList.remove('hidden');
        return;
      }
      emptyEl.classList.add('hidden');

      tbody.innerHTML = list.map(function(s) {
        var statusColor = s.status === 'Dibuka' ? 'text-green-600 dark:text-green-400 font-bold' : (s.status === 'Akan Datang' ? 'text-blue-600 dark:text-blue-400 font-bold' : 'text-gray-500 font-bold');
        return '<tr class="hover:bg-muted/30 transition-colors border-b border-border">' +
          '<td class="p-4"><div class="w-8 h-8 rounded border border-border bg-white flex items-center justify-center p-1"><img src="' + s.image + '" class="max-w-full max-h-full object-contain"></div></td>' +
          '<td class="p-4 font-bold text-foreground">' + s.title + '</td>' +
          '<td class="p-4 text-muted-foreground">' + s.provider + '</td>' +
          '<td class="p-4 font-semibold">' + s.level + '</td>' +
          '<td class="p-4 text-muted-foreground flex items-center gap-1 mt-2.5">' + (s.flag || '🌐') + ' ' + s.location + '</td>' +
          '<td class="p-4 text-muted-foreground">' + s.deadline + '</td>' +
          '<td class="p-4 ' + statusColor + '">' + s.status + '</td>' +
          '<td class="p-4 text-right whitespace-nowrap">' +
          '<button type="button" class="btn-edit-s text-blue-500 hover:text-blue-700 font-bold mr-3" data-id="' + s.id + '">Edit</button>' +
          '<button type="button" class="btn-delete-s text-red-500 hover:text-red-700 font-bold" data-id="' + s.id + '">Hapus</button>' +
          '</td></tr>';
      }).join('');

      tbody.querySelectorAll('.btn-edit-s').forEach(function(btn) {
        btn.addEventListener('click', function() {
          var sId = btn.dataset.id;
          var s = scholarships.find(function(item) { return item.id === sId; });
          if (!s) return;

          document.getElementById('field-id').value = s.id;
          document.getElementById('field-title').value = s.title;
          document.getElementById('field-provider').value = s.provider;
          document.getElementById('field-location').value = s.location;
          document.getElementById('field-flag').value = s.flag || '';
          document.getElementById('field-level').value = s.level || '';
          document.getElementById('field-amount').value = s.amount || '';
          document.getElementById('field-deadline').value = s.deadline || '';
          document.getElementById('field-status').value = s.status || 'Dibuka';
          
          var logoSelect = document.getElementById('field-logo-select');
          if (logoSelect) {
            // Find option with exact relative path or basename
            var matchingOption = Array.from(logoSelect.options).find(function(opt) {
              return opt.value.includes(s.image.split('/').pop());
            });
            if (matchingOption) logoSelect.value = matchingOption.value;
          }
          document.getElementById('field-image').value = s.image;
          document.getElementById('field-external-link').value = s.external_link || s.externalLink || '';
          document.getElementById('field-updated-ago').value = s.updated_ago || s.updatedAgo || '2 jam lalu';

          document.getElementById('crud-modal-title').textContent = 'Edit Data Beasiswa';
          modal.classList.remove('hidden');
        });
      });

      tbody.querySelectorAll('.btn-delete-s').forEach(function(btn) {
        btn.addEventListener('click', function() {
          var sId = btn.dataset.id;
          if (confirm('Apakah Anda yakin ingin menghapus beasiswa ini?')) {
            scholarships = scholarships.filter(function(item) { return item.id !== sId; });
            localStorage.setItem('custom_scholarships', JSON.stringify(scholarships));
            toast('Beasiswa berhasil dihapus!', 'success');
            renderTable(searchInput ? searchInput.value : '');
          }
        });
      });
    }

    if (btnAdd) {
      btnAdd.addEventListener('click', function() {
        form.reset();
        document.getElementById('field-id').value = '';
        document.getElementById('crud-modal-title').textContent = 'Tambah Beasiswa Baru';
        modal.classList.remove('hidden');
      });
    }

    function closeModal() {
      modal.classList.add('hidden');
    }

    if (btnCloseModal) btnCloseModal.addEventListener('click', closeModal);
    if (modalBackdrop) modalBackdrop.addEventListener('click', closeModal);

    var logoSelect = document.getElementById('field-logo-select');
    if (logoSelect) {
      document.getElementById('field-image').value = logoSelect.value;
      logoSelect.addEventListener('change', function() {
        document.getElementById('field-image').value = logoSelect.value;
      });
    }

    if (form) {
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        var id = document.getElementById('field-id').value;
        var logoVal = document.getElementById('field-image').value || (logoSelect ? logoSelect.value : '/images/logos/lpdp.png');
        
        var sData = {
          id: id || String(Date.now()),
          title: document.getElementById('field-title').value.trim(),
          provider: document.getElementById('field-provider').value.trim(),
          location: document.getElementById('field-location').value.trim(),
          flag: document.getElementById('field-flag').value.trim(),
          level: document.getElementById('field-level').value.trim(),
          amount: document.getElementById('field-amount').value.trim(),
          deadline: document.getElementById('field-deadline').value.trim(),
          status: document.getElementById('field-status').value,
          image: logoVal,
          external_link: document.getElementById('field-external-link').value.trim(),
          updated_ago: document.getElementById('field-updated-ago').value.trim()
        };

        if (id) {
          scholarships = scholarships.map(function(item) {
            return item.id === id ? sData : item;
          });
          toast('Data beasiswa berhasil diperbarui!', 'success');
        } else {
          scholarships.push(sData);
          toast('Beasiswa baru berhasil ditambahkan!', 'success');
        }

        localStorage.setItem('custom_scholarships', JSON.stringify(scholarships));
        closeModal();
        renderTable(searchInput ? searchInput.value : '');
      });
    }

    if (searchInput) {
      searchInput.addEventListener('input', function(e) {
        renderTable(e.target.value);
      });
    }

    renderTable();
  }

  document.addEventListener('DOMContentLoaded', function () {
    initTheme();
    initNavbar();
    syncAuthUI();
    initLoginForm();
    initDashboard();
    initLibrary();
    initScholarshipDetail();
    initHomeClientRendering();
    initDetailClientRendering();
    initRegisterPage();
    initChatbot();
    initAdminPanel();
    if (window.lucide) lucide.createIcons();
  });
})();
