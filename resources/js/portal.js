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

  // Memeriksa apakah pengguna yang masuk memiliki status administrator
  function isAdmin() {
    return localStorage.getItem('isAdmin') === 'true';
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
    var userIsAdmin = isAdmin();

    var loggedInEl = document.getElementById('navbar-logged-in');
    var loginBtn = document.getElementById('navbar-login-btn');
    var sidebarLogged = document.getElementById('sidebar-account-logged');
    var sidebarGuest = document.getElementById('sidebar-account-guest');
    var adminLink = document.getElementById('sidebar-admin-link'); // Menu Admin Panel di sidebar

    if (loggedInEl) loggedInEl.classList.toggle('hidden', !loggedIn);
    if (loggedInEl) loggedInEl.classList.toggle('sm:flex', loggedIn);
    if (loginBtn) loginBtn.classList.toggle('hidden', loggedIn);
    if (loginBtn) loginBtn.classList.toggle('sm:flex', !loggedIn);
    if (sidebarLogged) sidebarLogged.classList.toggle('hidden', !loggedIn);
    if (sidebarGuest) sidebarGuest.classList.toggle('hidden', loggedIn);
    
    // Tampilkan menu Admin Panel hanya jika user sudah login DAN merupakan admin
    if (adminLink) adminLink.classList.toggle('hidden', !(loggedIn && userIsAdmin));

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
    fetch('/api/logout', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': getCsrfToken()
      }
    })
    .finally(function() {
      localStorage.removeItem('isLoggedIn');
      localStorage.removeItem('isAdmin');
      localStorage.removeItem('userEmail');
      localStorage.removeItem('userName');
      localStorage.removeItem('userAvatar');
      localStorage.removeItem('bookmarks'); // Bersihkan bookmark lokal
      syncAuthUI();
      toast('Anda berhasil keluar akun!', 'success');
      window.location.href = '/home';
    });
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
    var container = document.getElementById('login-page-container');
    var form = document.getElementById('login-form');
    if (!form || !container) return;

    var urlParams = new URLSearchParams(window.location.search);
    var reason = urlParams.get('reason');
    var role = urlParams.get('role') || container.dataset.initialRole;
    var redirect = form.dataset.redirect || '/home';

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
    var isAdminMode = (role === 'admin' || container.classList.contains('mode-admin'));

    var tabGroup = document.getElementById('auth-tab-group');
    var tabLogin = document.getElementById('tab-login');
    var tabRegister = document.getElementById('tab-register');
    var nameField = document.getElementById('name-field');
    var rememberRow = document.getElementById('remember-row');
    var submitBtn = document.getElementById('login-submit');
    var passwordInput = document.getElementById('password-input');
    var btnTogglePassword = document.getElementById('btn-toggle-password');
    var switchFooter = document.getElementById('switch-footer');
    var btnSwitchAuth = document.getElementById('btn-switch-auth');
    
    // UI elements untuk diperbarui pada panel kiri (promosi) secara dinamis
    var leftTitle = document.getElementById('left-title');
    var leftSubtitle = document.getElementById('left-subtitle');
    var advantageTitle = document.getElementById('advantage-title');
    var advantageList = document.querySelector('#left-panel ul');
    var welcomeTitle = document.getElementById('form-welcome-title');
    var welcomeSubtitle = document.getElementById('form-welcome-subtitle');

    function updateUIState() {
      // Bersihkan class mode lama
      container.classList.remove('mode-login', 'mode-register', 'mode-admin');

      if (isAdminMode) {
        container.classList.add('mode-admin');
        if (tabGroup) tabGroup.classList.add('hidden');
        if (switchFooter) switchFooter.classList.add('hidden');
        if (nameField) nameField.classList.add('hidden');
        if (rememberRow) rememberRow.classList.remove('hidden');
        
        if (leftTitle) leftTitle.textContent = 'Portal Management Administrator';
        if (leftSubtitle) leftSubtitle.textContent = 'Kelola data beasiswa dan spanduk iklan dalam satu dashboard terintegrasi.';
        if (advantageTitle) advantageTitle.textContent = 'Modul Administrator';
        if (advantageList) {
          advantageList.innerHTML = 
            '<li class="flex items-center gap-2.5"><span class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-[10px]">✓</span>CRUD Manajemen Data Beasiswa</li>' +
            '<li class="flex items-center gap-2.5"><span class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-[10px]">✓</span>CRUD Manajemen Spanduk Iklan</li>' +
            '<li class="flex items-center gap-2.5"><span class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-[10px]">✓</span>Ringkasan Statistik Real-time</li>' +
            '<li class="flex items-center gap-2.5"><span class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-[10px]">✓</span>Akses Terenkripsi & Aman</li>';
        }
        if (welcomeTitle) welcomeTitle.innerHTML = 'Login Administrator <span class="wave-hand">🔐</span>';
        if (welcomeSubtitle) welcomeSubtitle.textContent = 'Masuk menggunakan kredensial admin Anda.';
        if (submitBtn) {
          submitBtn.textContent = 'Masuk Ke Panel Admin';
        }
        
        // Update label & placeholder
        var emailLabel = document.getElementById('email-label');
        var emailInput = document.getElementById('email-input');
        if (emailLabel) emailLabel.textContent = 'Username / Email Admin';
        if (emailInput) emailInput.placeholder = 'Masukkan username atau email admin';

        // Update tag versi mobile
        var mobileHeaderTag = document.getElementById('mobile-header-tag');
        if (mobileHeaderTag) {
          mobileHeaderTag.textContent = 'ADMIN';
          mobileHeaderTag.className = 'text-[9px] font-bold uppercase tracking-wider bg-slate-500/10 text-slate-400 px-2.5 py-1 rounded-full';
        }
        var mobileLogoIcon = document.getElementById('mobile-logo-icon');
        var mobileLogoAccent = document.getElementById('mobile-logo-accent');
        if (mobileLogoIcon) mobileLogoIcon.style.color = '#475569';
        if (mobileLogoAccent) mobileLogoAccent.style.color = '#475569';

      } else {
        // Mode Pengguna (User)
        if (tabGroup) tabGroup.classList.remove('hidden');
        if (switchFooter) switchFooter.classList.remove('hidden');

        var emailLabel = document.getElementById('email-label');
        var emailInput = document.getElementById('email-input');
        if (emailLabel) emailLabel.textContent = 'Alamat Email';
        if (emailInput) emailInput.placeholder = 'nama@email.com';

        var mobileHeaderTag = document.getElementById('mobile-header-tag');
        if (mobileHeaderTag) {
          mobileHeaderTag.textContent = 'USER';
          mobileHeaderTag.className = 'text-[9px] font-bold uppercase tracking-wider bg-[#e53935]/10 text-[#e53935] px-2.5 py-1 rounded-full';
        }
        var mobileLogoIcon = document.getElementById('mobile-logo-icon');
        var mobileLogoAccent = document.getElementById('mobile-logo-accent');
        if (mobileLogoIcon) mobileLogoIcon.style.color = '#e53935';
        if (mobileLogoAccent) mobileLogoAccent.style.color = '#e53935';

        if (isLogin) {
          container.classList.add('mode-login');
          if (nameField) nameField.classList.add('hidden');
          if (rememberRow) rememberRow.classList.remove('hidden');
          
          if (tabLogin) tabLogin.className = 'flex-1 py-2.5 rounded-lg text-xs font-bold transition-all bg-[#e53935] text-white shadow';
          if (tabRegister) tabRegister.className = 'flex-1 py-2.5 rounded-lg text-xs font-bold transition-all text-muted-foreground hover:text-foreground';

          if (leftTitle) leftTitle.textContent = 'Temukan Beasiswa Impian Anda';
          if (leftSubtitle) leftSubtitle.textContent = 'Akses ribuan informasi beasiswa dalam negeri maupun luar negeri secara terpusat, mudah, dan gratis.';
          if (welcomeTitle) welcomeTitle.innerHTML = 'Selamat Datang <span class="wave-hand">👋</span>';
          if (welcomeSubtitle) welcomeSubtitle.textContent = 'Login untuk melanjutkan ke PortalBeasiswa.';
          if (submitBtn) {
            submitBtn.textContent = 'Masuk';
          }
          if (switchFooter) {
            var footerText = document.getElementById('switch-footer-text');
            if (footerText) footerText.textContent = 'Belum punya akun? ';
            if (btnSwitchAuth) btnSwitchAuth.textContent = 'Daftar Sekarang';
          }
        } else {
          container.classList.add('mode-register');
          if (nameField) nameField.classList.remove('hidden');
          if (rememberRow) rememberRow.classList.add('hidden');

          if (tabLogin) tabLogin.className = 'flex-1 py-2.5 rounded-lg text-xs font-bold transition-all text-muted-foreground hover:text-foreground';
          if (tabRegister) tabRegister.className = 'flex-1 py-2.5 rounded-lg text-xs font-bold transition-all bg-[#6366f1] text-white shadow';

          if (leftTitle) leftTitle.textContent = 'Mulai Langkah Pendidikan Anda';
          if (leftSubtitle) leftSubtitle.textContent = 'Daftar akun sekarang untuk mulai menyimpan bookmark beasiswa dan mengirim lamaran beasiswa impian.';
          if (welcomeTitle) welcomeTitle.innerHTML = 'Daftar Akun Baru <span class="wave-hand">🚀</span>';
          if (welcomeSubtitle) welcomeSubtitle.textContent = 'Lengkapi data untuk membuat akun PortalBeasiswa.';
          if (submitBtn) {
            submitBtn.textContent = 'Daftar Sekarang';
          }
          if (switchFooter) {
            var footerText = document.getElementById('switch-footer-text');
            if (footerText) footerText.textContent = 'Sudah punya akun? ';
            if (btnSwitchAuth) btnSwitchAuth.textContent = 'Login Sekarang';
          }
        }
        
        // Reset card keunggulan untuk user
        if (advantageTitle) advantageTitle.textContent = 'Mengapa PortalBeasiswa?';
        if (advantageList) {
          advantageList.innerHTML = 
            '<li class="flex items-center gap-2.5"><span class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-[10px]">✓</span>Informasi beasiswa terupdate secara real-time</li>' +
            '<li class="flex items-center gap-2.5"><span class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-[10px]">✓</span>Simpan beasiswa favorit untuk dipantau kemudian</li>' +
            '<li class="flex items-center gap-2.5"><span class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-[10px]">✓</span>Pantau deadline pendaftaran dengan notifikasi sistem</li>' +
            '<li class="flex items-center gap-2.5"><span class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-[10px]">✓</span>Form pendaftaran yang mudah digunakan oleh pelajar</li>';
        }
      }
      if (window.lucide) lucide.createIcons();
    }

    if (tabLogin) tabLogin.addEventListener('click', function () { isLogin = true; updateUIState(); });
    if (tabRegister) tabRegister.addEventListener('click', function () { isLogin = false; updateUIState(); });
    if (btnSwitchAuth) {
      btnSwitchAuth.addEventListener('click', function (e) {
        e.preventDefault();
        isLogin = !isLogin;
        updateUIState();
      });
    }

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
      var email = document.getElementById('email-input').value.trim();
      var password = passwordInput.value;
      var name = document.getElementById('name-input') ? document.getElementById('name-input').value.trim() : '';

      if (email.length === 0) {
        toast('Gagal! Username atau email tidak boleh kosong.', 'error');
        return;
      }
      if (password.length < 6) {
        toast('Gagal! Kata sandi harus minimal 6 karakter.', 'error');
        return;
      }
      if (!isLogin && !isAdminMode && name.length < 3) {
        toast('Gagal! Nama lengkap harus minimal 3 karakter.', 'error');
        return;
      }

      var url = isLogin || isAdminMode ? '/api/login' : '/api/register';
      var payload = { email: email, password: password };
      if (!isLogin && !isAdminMode) {
        payload.name = name;
      }

      fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': getCsrfToken()
        },
        body: JSON.stringify(payload)
      })
      .then(function(res) {
        if (!res.ok) {
          return res.json().then(function(err) { throw err; });
        }
        return res.json();
      })
      .then(function(data) {
        if (data.success) {
          localStorage.setItem('isLoggedIn', 'true');
          localStorage.setItem('isAdmin', data.user.role === 'admin' ? 'true' : 'false');
          localStorage.setItem('userEmail', data.user.email);
          localStorage.setItem('userName', data.user.name);

          // Sinkronisasi bookmark dari database SQLite ke LocalStorage saat login berhasil
          fetch('/api/bookmarks')
            .then(function(r) { return r.json(); })
            .then(function(bms) {
              localStorage.setItem('bookmarks', JSON.stringify(bms));
            })
            .finally(function() {
              toast(data.message || 'Berhasil!', 'success');
              setTimeout(function() {
                window.location.href = (data.user.role === 'admin') ? '/admin' : redirect;
              }, 1000);
            });
        }
      })
      .catch(function(err) {
        toast(err.message || 'Gagal melakukan otentikasi. Silakan periksa kredensial Anda.', 'error');
      });
    });

    updateUIState();
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

    function fetchAndRender() {
      fetch('/api/bookmarks')
        .then(function(res) { return res.json(); })
        .then(function(bms) {
          localStorage.setItem('bookmarks', JSON.stringify(bms));
          bookmarks = bms;
          render();
        })
        .catch(function() {
          render();
        });
    }

    fetchAndRender();
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

        fetch('/api/bookmarks/toggle', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken()
          },
          body: JSON.stringify({ scholarship_id: id })
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
          if (data.success) {
            var list = JSON.parse(localStorage.getItem('bookmarks') || '[]');
            if (data.status === 'removed') {
              list = list.filter(function (item) { return item !== id; });
              isBookmarked = false;
              toast(data.message, 'info');
            } else {
              list.push(id);
              isBookmarked = true;
              toast(data.message, 'success');
            }
            localStorage.setItem('bookmarks', JSON.stringify(list));
            updateBookmarkUI();
          } else {
            toast(data.message || 'Gagal mengubah bookmark.', 'error');
          }
        })
        .catch(function() {
          toast('Terjadi kesalahan koneksi.', 'error');
        });
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

  window.PortalBeasiswa = { toast: toast, isLoggedIn: isLoggedIn, isAdmin: isAdmin, syncAuthUI: syncAuthUI };

  function getCsrfToken() {
    var meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.getAttribute('content') : '';
  }

  function getScholarships() {
    var adminPage = document.getElementById('admin-page');
    if (adminPage && adminPage.dataset.initialScholarships) {
      return JSON.parse(adminPage.dataset.initialScholarships);
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

  function initAboutModal() {
    var modal = document.getElementById('about-modal');
    var btns = document.querySelectorAll('#btn-about, #btn-register-contact');
    var btnClose = document.querySelectorAll('#btn-close-about-modal, #btn-close-about-modal-btn');
    var backdrop = document.getElementById('about-modal-backdrop');

    if (!modal) return;

    btns.forEach(function(btn) {
      btn.addEventListener('click', function() {
        modal.classList.remove('hidden');
      });
    });

    function hide() {
      modal.classList.add('hidden');
    }

    btnClose.forEach(function(b) { b.addEventListener('click', hide); });
    if (backdrop) backdrop.addEventListener('click', hide);
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

        var formData = new FormData(registerForm);
        formData.append('scholarship_id', id);
        var titleSpan = page.querySelector('h1 span');
        formData.append('scholarship_title', titleSpan ? titleSpan.textContent : 'Beasiswa');

        fetch('/api/scholarship/register', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': getCsrfToken()
          },
          body: formData
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
          if (data.success) {
            toast('Pendaftaran beasiswa berhasil dikirim! Data disimpan ke database.', 'success');
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
          } else {
            toast(data.message || 'Gagal mengirim pendaftaran.', 'error');
          }
        })
        .catch(function(err) {
          console.error(err);
          toast('Terjadi kesalahan jaringan atau validasi berkas.', 'error');
        });
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

    // Proteksi halaman admin: alihkan ke halaman login jika pengguna belum masuk atau bukan administrator
    if (!isLoggedIn() || !isAdmin()) {
      window.location.href = '/login?role=admin&redirect=' + encodeURIComponent('/admin');
      return;
    }

    var tabScholarships = document.getElementById('tab-manage-scholarships');
    var tabAds = document.getElementById('tab-manage-ads');
    var sectionScholarships = document.getElementById('section-scholarships');
    var sectionAds = document.getElementById('section-ads');
    var btnAddScholarship = document.getElementById('btn-add-scholarship');
    var btnAddAd = document.getElementById('btn-add-ad');

    if (tabScholarships && tabAds) {
      tabScholarships.addEventListener('click', function() {
        tabScholarships.className = 'px-5 py-3 border-b-2 border-[#e53935] font-black text-xs text-[#e53935] tracking-wide uppercase transition-all';
        tabAds.className = 'px-5 py-3 border-b-2 border-transparent font-bold text-xs text-muted-foreground hover:text-foreground tracking-wide uppercase transition-all';
        sectionScholarships.classList.remove('hidden');
        sectionAds.classList.add('hidden');
        btnAddScholarship.classList.remove('hidden');
        btnAddAd.classList.add('hidden');
      });

      tabAds.addEventListener('click', function() {
        tabAds.className = 'px-5 py-3 border-b-2 border-[#e53935] font-black text-xs text-[#e53935] tracking-wide uppercase transition-all';
        tabScholarships.className = 'px-5 py-3 border-b-2 border-transparent font-bold text-xs text-muted-foreground hover:text-foreground tracking-wide uppercase transition-all';
        sectionAds.classList.remove('hidden');
        sectionScholarships.classList.add('hidden');
        btnAddScholarship.classList.add('hidden');
        btnAddAd.classList.remove('hidden');
      });
    }

    var scholarships = JSON.parse(page.dataset.initialScholarships || '[]');
    var modalS = document.getElementById('crud-modal');
    var btnCloseModalS = document.getElementById('btn-close-crud-modal');
    var modalBackdropS = document.getElementById('crud-modal-backdrop');
    var formS = document.getElementById('crud-form');
    var searchInputS = document.getElementById('admin-search-scholarships');
    var tbodyS = document.getElementById('admin-scholarships-table-body');
    var emptyS = document.getElementById('admin-empty-scholarships');

    function renderScholarshipsTable(filterText) {
      var list = scholarships;
      if (filterText) {
        var q = filterText.toLowerCase();
        list = list.filter(function(s) {
          return s.title.toLowerCase().includes(q) || s.provider.toLowerCase().includes(q);
        });
      }

      document.getElementById('stat-total-scholarships').textContent = scholarships.length;

      if (list.length === 0) {
        tbodyS.innerHTML = '';
        emptyS.classList.remove('hidden');
        return;
      }
      emptyS.classList.add('hidden');

      tbodyS.innerHTML = list.map(function(s) {
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

      tbodyS.querySelectorAll('.btn-edit-s').forEach(function(btn) {
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
            var matchingOption = Array.from(logoSelect.options).find(function(opt) {
              return opt.value.includes(s.image.split('/').pop());
            });
            if (matchingOption) logoSelect.value = matchingOption.value;
          }
          document.getElementById('field-image').value = s.image;
          document.getElementById('field-external-link').value = s.external_link || s.externalLink || '';
          document.getElementById('field-updated-ago').value = s.updated_ago || s.updatedAgo || '2 jam lalu';

          document.getElementById('crud-modal-title').textContent = 'Edit Data Beasiswa';
          modalS.classList.remove('hidden');
        });
      });

      tbodyS.querySelectorAll('.btn-delete-s').forEach(function(btn) {
        btn.addEventListener('click', function() {
          var sId = btn.dataset.id;
          if (confirm('Apakah Anda yakin ingin menghapus beasiswa ini?')) {
            fetch('/admin/scholarships/' + sId, {
              method: 'DELETE',
              headers: { 'X-CSRF-TOKEN': getCsrfToken() }
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
              if (data.success) {
                scholarships = scholarships.filter(function(item) { return item.id !== sId; });
                toast(data.message, 'success');
                renderScholarshipsTable(searchInputS ? searchInputS.value : '');
              } else {
                toast(data.message || 'Gagal menghapus.', 'error');
              }
            });
          }
        });
      });
    }

    if (btnAddScholarship) {
      btnAddScholarship.addEventListener('click', function() {
        formS.reset();
        document.getElementById('field-id').value = '';
        document.getElementById('crud-modal-title').textContent = 'Tambah Beasiswa Baru';
        modalS.classList.remove('hidden');
      });
    }

    function closeModalS() { modalS.classList.add('hidden'); }
    if (btnCloseModalS) btnCloseModalS.addEventListener('click', closeModalS);
    if (modalBackdropS) modalBackdropS.addEventListener('click', closeModalS);

    var logoSelect = document.getElementById('field-logo-select');
    if (logoSelect) {
      logoSelect.addEventListener('change', function() {
        document.getElementById('field-image').value = logoSelect.value;
      });
    }

    if (formS) {
      formS.addEventListener('submit', function(e) {
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

        var url = id ? '/admin/scholarships/' + id : '/admin/scholarships';
        var method = id ? 'PUT' : 'POST';

        fetch(url, {
          method: method,
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken()
          },
          body: JSON.stringify(sData)
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
          if (data.success) {
            if (id) {
              scholarships = scholarships.map(function(item) { return item.id === id ? data.data : item; });
            } else {
              scholarships.push(data.data);
            }
            toast(data.message, 'success');
            closeModalS();
            renderScholarshipsTable(searchInputS ? searchInputS.value : '');
          } else {
            toast(data.message || 'Gagal menyimpan.', 'error');
          }
        });
      });
    }

    if (searchInputS) {
      searchInputS.addEventListener('input', function(e) {
        renderScholarshipsTable(e.target.value);
      });
    }

    var ads = JSON.parse(page.dataset.initialAds || '[]');
    var btnAddAdEl = document.getElementById('btn-add-ad');
    var modalAd = document.getElementById('ad-modal');
    var btnCloseModalAd = document.getElementById('btn-close-ad-modal');
    var modalBackdropAd = document.getElementById('ad-modal-backdrop');
    var formAd = document.getElementById('ad-form');
    var searchInputAd = document.getElementById('admin-search-ads');
    var tbodyAd = document.getElementById('admin-ads-table-body');
    var emptyAd = document.getElementById('admin-empty-ads');

    function renderAdsTable(filterText) {
      var list = ads;
      if (filterText) {
        var q = filterText.toLowerCase();
        list = list.filter(function(ad) {
          return ad.title.toLowerCase().includes(q) || ad.description.toLowerCase().includes(q);
        });
      }

      document.getElementById('stat-total-ads').textContent = ads.length;

      if (list.length === 0) {
        tbodyAd.innerHTML = '';
        emptyAd.classList.remove('hidden');
        return;
      }
      emptyAd.classList.add('hidden');

      tbodyAd.innerHTML = list.map(function(ad) {
        return '<tr class="hover:bg-muted/30 transition-colors border-b border-border">' +
          '<td class="p-4"><div class="w-24 h-8 rounded border border-border flex items-center justify-center p-1 font-bold text-[9px] text-white" style="background: linear-gradient(135deg, ' + ad.bg_from + ', ' + ad.bg_to + ')">' + ad.cta_text + '</div></td>' +
          '<td class="p-4 font-bold text-foreground">' + ad.title + '</td>' +
          '<td class="p-4 text-muted-foreground font-semibold">' + ad.subtitle + '</td>' +
          '<td class="p-4 text-muted-foreground">' + ad.description + '</td>' +
          '<td class="p-4"><span class="px-1.5 py-0.5 rounded bg-red-100 text-red-600 dark:bg-red-950/40 dark:text-red-400 font-bold uppercase text-[9px]">' + ad.tag + '</span></td>' +
          '<td class="p-4 text-blue-500 font-semibold">' + ad.link + '</td>' +
          '<td class="p-4 text-right whitespace-nowrap">' +
          '<button type="button" class="btn-edit-ad text-blue-500 hover:text-blue-700 font-bold mr-3" data-id="' + ad.id + '">Edit</button>' +
          '<button type="button" class="btn-delete-ad text-red-500 hover:text-red-700 font-bold" data-id="' + ad.id + '">Hapus</button>' +
          '</td></tr>';
      }).join('');

      tbodyAd.querySelectorAll('.btn-edit-ad').forEach(function(btn) {
        btn.addEventListener('click', function() {
          var adId = parseInt(btn.dataset.id);
          var ad = ads.find(function(item) { return item.id === adId; });
          if (!ad) return;

          document.getElementById('ad-field-id').value = ad.id;
          document.getElementById('ad-field-title').value = ad.title;
          document.getElementById('ad-field-subtitle').value = ad.subtitle;
          document.getElementById('ad-field-tag').value = ad.tag;
          document.getElementById('ad-field-description').value = ad.description;
          document.getElementById('ad-field-bg-from').value = ad.bg_from;
          document.getElementById('ad-field-bg-to').value = ad.bg_to;
          document.getElementById('ad-field-cta-text').value = ad.cta_text;
          document.getElementById('ad-field-link').value = ad.link;

          document.getElementById('ad-modal-title').textContent = 'Edit Spanduk Iklan';
          modalAd.classList.remove('hidden');
        });
      });

      tbodyAd.querySelectorAll('.btn-delete-ad').forEach(function(btn) {
        btn.addEventListener('click', function() {
          var adId = parseInt(btn.dataset.id);
          if (confirm('Apakah Anda yakin ingin menghapus iklan ini?')) {
            fetch('/admin/ads/' + adId, {
              method: 'DELETE',
              headers: { 'X-CSRF-TOKEN': getCsrfToken() }
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
              if (data.success) {
                ads = ads.filter(function(item) { return item.id !== adId; });
                toast(data.message, 'success');
                renderAdsTable(searchInputAd ? searchInputAd.value : '');
              } else {
                toast(data.message || 'Gagal menghapus.', 'error');
              }
            });
          }
        });
      });
    }

    if (btnAddAdEl) {
      btnAddAdEl.addEventListener('click', function() {
        formAd.reset();
        document.getElementById('ad-field-id').value = '';
        document.getElementById('ad-modal-title').textContent = 'Tambah Spanduk Iklan Baru';
        modalAd.classList.remove('hidden');
      });
    }

    function closeModalAd() {
      modalAd.classList.add('hidden');
      if (document.getElementById('ad-field-image-url')) document.getElementById('ad-field-image-url').value = '';
      if (document.getElementById('ad-image-preview')) document.getElementById('ad-image-preview').src = '';
      if (document.getElementById('ad-image-preview-container')) document.getElementById('ad-image-preview-container').classList.add('hidden');
    }
    if (btnCloseModalAd) btnCloseModalAd.addEventListener('click', closeModalAd);
    if (modalBackdropAd) modalBackdropAd.addEventListener('click', closeModalAd);

    if (formAd) {
      formAd.addEventListener('submit', function(e) {
        e.preventDefault();
        var id = document.getElementById('ad-field-id').value;
        
        var adData = {
          title:       document.getElementById('ad-field-title').value.trim(),
          subtitle:    document.getElementById('ad-field-subtitle').value.trim(),
          tag:         document.getElementById('ad-field-tag').value.trim(),
          description: document.getElementById('ad-field-description').value.trim(),
          bg_from:     document.getElementById('ad-field-bg-from').value,
          bg_to:       document.getElementById('ad-field-bg-to').value,
          cta_text:    document.getElementById('ad-field-cta-text').value.trim(),
          link:        document.getElementById('ad-field-link').value.trim(),
          image_url:   document.getElementById('ad-field-image-url') ? document.getElementById('ad-field-image-url').value : null,
        };

        var url = id ? '/admin/ads/' + id : '/admin/ads';
        var method = id ? 'PUT' : 'POST';

        fetch(url, {
          method: method,
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken()
          },
          body: JSON.stringify(adData)
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
          if (data.success) {
            if (id) {
              var parsedId = parseInt(id);
              ads = ads.map(function(item) { return item.id === parsedId ? data.data : item; });
            } else {
              ads.push(data.data);
            }
            toast(data.message, 'success');
            closeModalAd();
            renderAdsTable(searchInputAd ? searchInputAd.value : '');
          } else {
            toast(data.message || 'Gagal menyimpan.', 'error');
          }
        });
      });
    }

    var btnUploadAdImage = document.getElementById('btn-ad-upload-image');
    var adImageFileInput = document.getElementById('ad-field-image-file');
    var adImageUrlInput  = document.getElementById('ad-field-image-url');
    var adImagePreview   = document.getElementById('ad-image-preview');
    var adImageContainer = document.getElementById('ad-image-preview-container');
    var adUploadStatus   = document.getElementById('ad-upload-status');

    if (btnUploadAdImage && adImageFileInput) {
      btnUploadAdImage.addEventListener('click', function() {
        adImageFileInput.click();
      });

      adImageFileInput.addEventListener('change', function(e) {
        var file = e.target.files[0];
        if (!file) return;

        if (adUploadStatus) { adUploadStatus.classList.remove('hidden'); }
        if (adImageContainer) { adImageContainer.classList.add('hidden'); }

        var formData = new FormData();
        formData.append('image', file);

        fetch('/admin/ads/upload-image', {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': getCsrfToken() },
          body: formData
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
          if (data.success) {
            if (adImageUrlInput) adImageUrlInput.value = data.url;
            if (adImagePreview) adImagePreview.src = data.url;
            if (adImageContainer) adImageContainer.classList.remove('hidden');
            toast('Gambar berhasil diupload dan URL tersimpan!', 'success');
          } else {
            toast('Gagal mengupload gambar.', 'error');
          }
        })
        .catch(function() {
          toast('Terjadi kesalahan saat mengupload gambar.', 'error');
        })
        .finally(function() {
          if (adUploadStatus) adUploadStatus.classList.add('hidden');
          adImageFileInput.value = '';
        });
      });
    }

    if (searchInputAd) {
      searchInputAd.addEventListener('input', function(e) {
        renderAdsTable(e.target.value);
      });
    }

    renderScholarshipsTable();
    renderAdsTable();
  }

  document.addEventListener('DOMContentLoaded', function () {
    initTheme();
    initNavbar();
    syncAuthUI();
    initLoginForm();
    initDashboard();
    initLibrary();
    initScholarshipDetail();
    initRegisterPage();
    initChatbot();
    initAdminPanel();
    initAboutModal();
    if (window.lucide) lucide.createIcons();
  });
})();
