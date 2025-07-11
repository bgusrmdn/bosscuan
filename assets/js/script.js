document.addEventListener("DOMContentLoaded", function () {
  // =======================================================
  // == BAGIAN 1: LOGIKA UNTUK MENU GAME INTERAKTIF (index.php & beranda.php) ==
  // =======================================================
  const loginToast = document.getElementById("login-toast");

  let toastTimeout;
  function showToast(message) {
    if (!loginToast) return;
    const toastMessage = loginToast.querySelector("#toast-message");
    toastMessage.textContent = message;
    loginToast.classList.add("show");
    clearTimeout(toastTimeout);
    toastTimeout = setTimeout(() => {
      loginToast.classList.remove("show");
    }, 3000);
  }

  async function fetchProviders(category = "all", container) {
    if (!container) return;
    container.innerHTML = `<div class="p-5 text-center"><div class="spinner-border text-warning" role="status"></div></div>`;
    try {
      const response = await fetch(
        `api_get_providers.php?category=${encodeURIComponent(category)}`
      );
      if (!response.ok)
        throw new Error(`HTTP error! status: ${response.status}`);
      const providers = await response.json();
      displayProviders(providers, container);
    } catch (error) {
      container.innerHTML = `<p class="text-center text-danger p-3">Gagal memuat provider.</p>`;
      console.error("Fetch error:", error);
    }
  }

  function displayProviders(providers, container) {
    if (!container) return;
    container.innerHTML = ""; // Kosongkan kontainer

    if (!providers || providers.length === 0) {
      container.innerHTML = `<div class="p-3 text-center text-white-50">Tidak ada provider untuk kategori ini.</div>`;
      return;
    }

    const isDesktop = container.id.includes("desktop");
    const gridContainer = isDesktop ? document.createElement("div") : container;
    if (isDesktop) gridContainer.className = "provider-grid-desktop-inner";

    providers.forEach((provider) => {
      const providerItem = document.createElement("div");
      providerItem.className = "provider-logo-item";

      // Perbaikan pada path gambar dan fallback text
      providerItem.innerHTML = `
              <img src="${provider.logo_provider_url}" alt="${provider.nama_provider}" 
                   onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
              <span class="fallback-text" style="display:none;">${provider.nama_provider}</span>
          `;

      providerItem.addEventListener("click", (e) => {
        e.preventDefault();
        // Notifikasi untuk login
        Swal.fire({
          title: "Akses Dibatasi",
          text: "Silakan login terlebih dahulu untuk bermain!",
          icon: "warning",
          confirmButtonText: "Login Sekarang",
          showCancelButton: true,
          cancelButtonText: "Nanti Saja",
          background: "#212529",
          color: "#fff",
          confirmButtonColor: "#ffc107",
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = "login.php";
          }
        });
      });
      gridContainer.appendChild(providerItem);
    });

    if (isDesktop) {
      container.appendChild(gridContainer);
    }
  }

  function initializeGameMenu(menuId, containerId) {
    const menu = document.getElementById(menuId);
    const container = document.getElementById(containerId);

    if (menu && container) {
      menu.addEventListener("click", function (e) {
        const targetItem = e.target.closest(".menu-item-img");
        if (!targetItem) return;

        if (menu.querySelector(".active")) {
          menu.querySelector(".active").classList.remove("active");
        }
        targetItem.classList.add("active");

        const categoryFilter = targetItem.dataset.filter;
        fetchProviders(categoryFilter, container);
      });
      fetchProviders("all", container);
    }
  }

  initializeGameMenu("category-menu-mobile", "provider-list-container-mobile");
  initializeGameMenu(
    "category-menu-desktop",
    "provider-list-container-desktop"
  );

  // =======================================================
  // == BAGIAN 3: LOGIKA UNTUK HALAMAN PENDAFTARAN (daftar.php) ==
  // =======================================================
  const registrationForm = document.getElementById("registration-form");
  if (registrationForm) {
    const togglePasswordButtons = document.querySelectorAll(".toggle-password");
    togglePasswordButtons.forEach((button) => {
      button.addEventListener("click", function () {
        const input = this.previousElementSibling;
        const icon = this.querySelector("i");
        if (input.type === "password") {
          input.type = "text";
          icon.classList.remove("fa-eye");
          icon.classList.add("fa-eye-slash");
        } else {
          input.type = "password";
          icon.classList.remove("fa-eye-slash");
          icon.classList.add("fa-eye");
        }
      });
    });

    const passwordInput = document.getElementById("password");
    const confirmPasswordInput = document.getElementById("confirm_password");
    const passwordMatchMessage = document.getElementById(
      "password-match-message"
    );

    function validatePasswordMatch() {
      if (passwordInput.value === "" && confirmPasswordInput.value === "") {
        passwordMatchMessage.textContent = "";
        return;
      }
      if (passwordInput.value === confirmPasswordInput.value) {
        passwordMatchMessage.textContent = "✓ Password cocok!";
        passwordMatchMessage.className = "text-success form-text mt-1";
      } else {
        passwordMatchMessage.textContent = "✗ Password tidak cocok!";
        passwordMatchMessage.className = "text-danger form-text mt-1";
      }
    }
    if (passwordInput)
      passwordInput.addEventListener("keyup", validatePasswordMatch);
    if (confirmPasswordInput)
      confirmPasswordInput.addEventListener("keyup", validatePasswordMatch);

    const bankSelect = document.getElementById("bank_name");
    const accountNumberInput = document.getElementById("account_number");

    function applyAccountNumberValidation() {
      if (!bankSelect || !accountNumberInput) return;
      const selectedOption = bankSelect.options[bankSelect.selectedIndex];
      const minLength = selectedOption.getAttribute("data-minlength");
      const maxLength = selectedOption.getAttribute("data-maxlength");

      if (minLength && maxLength && minLength > 0) {
        accountNumberInput.setAttribute("minlength", minLength);
        accountNumberInput.setAttribute("maxlength", maxLength);
        accountNumberInput.placeholder = `Masukkan ${minLength}-${maxLength} digit nomor`;
        accountNumberInput.pattern = `\\d{${minLength},${maxLength}}`;
      } else {
        accountNumberInput.removeAttribute("minlength");
        accountNumberInput.removeAttribute("maxlength");
        accountNumberInput.removeAttribute("pattern");
        accountNumberInput.placeholder = "Nomor rekening / HP";
      }
    }

    if (bankSelect)
      bankSelect.addEventListener("change", applyAccountNumberValidation);
    if (accountNumberInput) {
      accountNumberInput.addEventListener("input", function () {
        const maxLength = this.getAttribute("maxlength");
        if (maxLength && this.value.length > maxLength) {
          this.value = this.value.slice(0, maxLength);
        }
      });
    }
    applyAccountNumberValidation();
  }

  // =======================================================
  // == BAGIAN 4: LOGIKA UNTUK MENU DROPDOWN HEADER (nav-guest.php) ==
  // =======================================================
  const providerLinks = document.querySelectorAll(".provider-link");
  providerLinks.forEach((link) => {
    link.addEventListener("click", function (e) {
      e.preventDefault();
      Swal.fire({
        title: "Akses Dibatasi",
        text: "Silakan login terlebih dahulu untuk melanjutkan!",
        icon: "warning",
        confirmButtonText: "Login Sekarang",
        showCancelButton: true,
        cancelButtonText: "Nanti Saja",
        background: "#212529",
        color: "#fff",
        confirmButtonColor: "#ffc107",
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = "login.php";
        }
      });
    });
  });

  const mobileDropdownToggles = document.querySelectorAll(
    ".mobile-dropdown-toggle"
  );
  mobileDropdownToggles.forEach((toggle) => {
    toggle.addEventListener("click", function (e) {
      e.preventDefault();
      this.classList.toggle("active");
      const menu = this.nextElementSibling;
      if (menu.style.display === "block") {
        menu.style.display = "none";
      } else {
        menu.style.display = "block";
      }
    });
  });

  // ========================================================
  // == BAGIAN 5: LOGIKA BARU UNTUK HALAMAN BERANDA (beranda.php) ==
  // ========================================================
  const berandaCategoryMenu = document.getElementById("beranda-category-menu");
  const gameDisplayContainer = document.getElementById(
    "game-display-container"
  );
  const gameSectionTitle = document.getElementById("game-section-title");
  const searchGameInput = document.getElementById("search-game-input");

  if (berandaCategoryMenu && gameDisplayContainer) {
    let currentBerandaCategory = "all";
    let currentBerandaProvider = "all"; // Menyimpan provider yang sedang aktif untuk filtering game
    let currentSearchQuery = "";
    let viewMode = "providers"; // 'providers' atau 'games'

    // Fungsi untuk memuat konten (providers atau games) di beranda.php
    async function loadGameContentBeranda() {
      if (!gameDisplayContainer) return;
      gameDisplayContainer.innerHTML = `<div class="p-5 text-center w-100"><div class="spinner-border text-warning"></div></div>`;

      let apiUrl = "";
      // Logika penentuan API URL dan judul bagian
      if (viewMode === "providers") {
        apiUrl = `api_get_providers.php?category=${encodeURIComponent(
          currentBerandaCategory
        )}`;
        gameSectionTitle.textContent =
          currentBerandaCategory === "all"
            ? "Semua Provider"
            : `Provider ${currentBerandaCategory}`;
      } else {
        // viewMode === "games"
        // Penting: pastikan currentBerandaProvider tidak "all" jika ingin memfilter game berdasarkan provider
        // Kalau "all", berarti user mungkin belum klik provider spesifik tapi langsung cari game
        // atau klik kategori dan ingin melihat semua game dari semua provider di kategori itu.
        // API get_games.php sudah menangani "all" provider dengan mengabaikan filter provider.
        apiUrl = `api_get_games.php?provider=${encodeURIComponent(
          currentBerandaProvider
        )}&category=${encodeURIComponent(
          currentBerandaCategory
        )}&search=${encodeURIComponent(currentSearchQuery)}`;

        // Atur judul berdasarkan provider dan kategori
        let title = "Semua Game";
        if (currentBerandaProvider !== "all") {
          title = `Game ${currentBerandaProvider}`;
        }
        if (currentBerandaCategory !== "all") {
          title += ` (${currentBerandaCategory})`;
        }
        if (currentSearchQuery !== "") {
          title += ` - Hasil Pencarian "${currentSearchQuery}"`;
        }
        gameSectionTitle.textContent = title;
      }

      try {
        const response = await fetch(apiUrl);
        if (!response.ok) {
          // Tambahkan cek untuk respons HTTP yang tidak OK
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json(); // Coba parsing JSON

        // Pastikan respons adalah objek dan bukan string error dari PHP
        if (typeof data !== "object" || data === null) {
          throw new Error("Respons API bukan JSON yang valid atau kosong.");
        }

        if (viewMode === "providers") {
          displayProvidersForBeranda(data);
        } else {
          // viewMode === "games"
          displayGamesForBeranda(data);
        }
      } catch (error) {
        // Ini akan menangkap error jaringan, error parsing JSON, atau error HTTP
        gameDisplayContainer.innerHTML = `<p class="text-center text-danger p-3 w-100">Gagal memuat konten. ${error.message}. Coba refresh halaman.</p>`;
        console.error("Fetch error for beranda game content:", error);
      }
    }

    // Fungsi untuk menampilkan daftar provider di beranda.php
    function displayProvidersForBeranda(providers) {
      gameDisplayContainer.innerHTML = "";
      if (!providers || providers.length === 0) {
        gameDisplayContainer.innerHTML = `<p class="text-center text-white-50 p-3 w-100">Tidak ada provider untuk kategori ini.</p>`;
        return;
      }

      providers.forEach((provider) => {
        const col = document.createElement("div");
        col.className = "col-6 col-md-4 col-lg-3";
        const providerCard = document.createElement("a");
        providerCard.href = "#";
        providerCard.className = "provider-card d-block";
        providerCard.dataset.provider = provider.nama_provider;
        providerCard.innerHTML = `
                <img src="assets/images/providers/${
                  provider.logo_provider
                }" class="img-fluid rounded" alt="${provider.nama_provider}"
                    onerror="this.src='https://placehold.co/100x70/222/FFF?text=${encodeURIComponent(
                      provider.nama_provider
                    )}';
                             this.style.backgroundColor='#333';
                             this.style.objectFit='contain';">
            `;

        providerCard.addEventListener("click", (e) => {
          e.preventDefault();
          viewMode = "games"; // Ganti mode tampilan ke game
          currentBerandaProvider = provider.nama_provider; // Set provider yang dipilih
          currentSearchQuery = ""; // Reset pencarian saat memilih provider baru
          if (searchGameInput) searchGameInput.value = ""; // Kosongkan input pencarian
          loadGameContentBeranda(); // Muat game untuk provider yang dipilih
        });

        col.appendChild(providerCard);
        gameDisplayContainer.appendChild(col);
      });
    }

    // Fungsi untuk menampilkan daftar game di beranda.php
    function displayGamesForBeranda(games) {
      gameDisplayContainer.innerHTML = "";
      if (!games || games.length === 0) {
        gameDisplayContainer.innerHTML = `<p class="text-center text-white-50 p-3 w-100">Tidak ada game ditemukan untuk pilihan ini.</p>`;
        return;
      }
      games.forEach((game) => {
        const col = document.createElement("div");
        col.className = "col-6 col-md-4 col-lg-3";
        col.innerHTML = `
                    <div class="game-card-v2">
                        <img src="${game.gambar_thumbnail_url}" alt="${
          game.nama_game
        }" class="img-fluid"
                            onerror="this.src='https://placehold.co/300x300/222/FFF?text=${encodeURIComponent(
                              game.nama_game
                            )}';">
                        <div class="game-card-overlay">
                            <span>${game.nama_game}</span>
                            <button class="btn btn-warning btn-sm">PLAY NOW</button>
                        </div>
                    </div>
                `;
        gameDisplayContainer.appendChild(col);
      });
    }

    // Event listener untuk menu kategori di beranda.php
    berandaCategoryMenu.addEventListener("click", (e) => {
      if (e.target.classList.contains("category-item")) {
        e.preventDefault();
        const activeItem = berandaCategoryMenu.querySelector(
          ".category-item.active"
        );
        if (activeItem) {
          activeItem.classList.remove("active");
        }
        e.target.classList.add("active");

        currentBerandaCategory = e.target.dataset.category;
        viewMode = "providers"; // Selalu kembali ke tampilan provider saat kategori berubah
        currentBerandaProvider = "all"; // Reset provider saat kategori berubah
        currentSearchQuery = ""; // Reset pencarian saat kategori berubah
        if (searchGameInput) searchGameInput.value = "";

        loadGameContentBeranda(); // Muat ulang konten
      }
    });

    // Event listener untuk pencarian game di beranda.php
    if (searchGameInput) {
      let searchTimeout;
      searchGameInput.addEventListener("input", () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
          currentSearchQuery = searchGameInput.value.trim();
          // Saat user mengetik pencarian, kita paksa mode ke 'games'
          viewMode = "games";
          // Jika belum ada provider spesifik yang dipilih (masih "all"),
          // pencarian akan berlaku untuk semua game dalam kategori aktif.
          // currentBerandaProvider akan tetap "all" jika tidak pernah diklik provider.
          loadGameContentBeranda();
        }, 500); // Debounce input pencarian untuk performa
      });
    }

    // Panggil pertama kali saat halaman beranda dimuat
    loadGameContentBeranda();
  }
});

/**
 * File: assets/js/script.js (REVISI FINAL FULL - Penambahan Transaksi.php JS)
 */

document.addEventListener("DOMContentLoaded", function () {
  // ... (SEMUA KODE JAVASCRIPT document.addEventListener('DOMContentLoaded') SEBELUMNYA TETAP SAMA) ...

  // ========================================================
  // == BAGIAN BARU: LOGIKA UNTUK HALAMAN TRANSAKSI (transaksi.php) ==
  // ========================================================
  const transactionTabs = document.getElementById("transactionTabs");
  if (transactionTabs) {
    const dateRangeWalletInput = document.getElementById("date_range_wallet");
    const typeWalletSelect = document.getElementById("type_wallet");
    const searchWalletSummaryBtn = document.getElementById(
      "search_wallet_summary"
    );
    const walletSummaryResultsBody = document.getElementById(
      "wallet-summary-results"
    );

    // Fungsi untuk mengambil dan menampilkan data Wallet Summary
    async function fetchWalletSummary() {
      if (
        !dateRangeWalletInput ||
        !typeWalletSelect ||
        !walletSummaryResultsBody
      )
        return;

      const dateRange = dateRangeWalletInput.value;
      const type = typeWalletSelect.value;

      walletSummaryResultsBody.innerHTML = `<tr><td colspan="3" class="text-center text-muted"><div class="spinner-border spinner-border-sm" role="status"></div> Loading...</td></tr>`;

      try {
        const response = await fetch(
          `api_get_wallet_transactions.php?type=${type}&date_range=${dateRange}`
        );
        const transactions = await response.json();

        displayWalletTransactions(transactions);
      } catch (error) {
        console.error("Error fetching wallet summary:", error);
        walletSummaryResultsBody.innerHTML = `<tr><td colspan="3" class="text-center text-danger">Gagal memuat data. Silakan coba lagi.</td></tr>`;
      }
    }

    // Fungsi untuk menampilkan data ke dalam tabel
    function displayWalletTransactions(transactions) {
      if (!walletSummaryResultsBody) return; // Tambahkan penjaga
      walletSummaryResultsBody.innerHTML = ""; // Kosongkan tabel

      if (!transactions || transactions.length === 0) {
        walletSummaryResultsBody.innerHTML = `<tr><td colspan="3" class="text-center text-muted">Tidak ada data transaksi untuk filter ini.</td></tr>`;
        return;
      }

      transactions.forEach((trx) => {
        const row = document.createElement("tr");

        // Format tanggal
        const date = new Date(trx.created_at);
        const formattedDate = date.toLocaleString("id-ID", {
          year: "numeric",
          month: "short",
          day: "numeric",
          hour: "2-digit",
          minute: "2-digit",
        });

        // Format status dengan badge
        let statusBadge;
        switch (trx.status) {
          case "approved":
            statusBadge = '<span class="badge bg-success">Approved</span>';
            break;
          case "rejected":
            statusBadge = '<span class="badge bg-danger">Rejected</span>';
            break;
          default: // 'pending'
            statusBadge =
              '<span class="badge bg-warning text-dark">Pending</span>';
            break;
        }

        // Format jumlah
        const formattedAmount = new Intl.NumberFormat("id-ID", {
          style: "currency",
          currency: "IDR",
          minimumFractionDigits: 0,
        }).format(trx.amount);

        // === PERUBAHAN UTAMA DI SINI ===
        let amountCell;
        if (trx.transaction_type === "Deposit") {
          amountCell = `<td class="text-success fw-bold">+ ${formattedAmount}</td>`;
        } else {
          // Ini akan menangani 'Withdraw'
          amountCell = `<td class="text-danger fw-bold">- ${formattedAmount}</td>`;
        }

        row.innerHTML = `
    <td><span class="fw-bold">${trx.transaction_type}</span></td>
    <td><small>${formattedDate}</small></td>
    ${amountCell}
    <td>${statusBadge}</td>
`;
        walletSummaryResultsBody.appendChild(row);
      });
    }

    // Event listener untuk tombol cari
    if (searchWalletSummaryBtn) {
      searchWalletSummaryBtn.addEventListener("click", fetchWalletSummary);
    }

    // Langsung panggil saat halaman dimuat pertama kali
    fetchWalletSummary();
    function initializeDateRangePicker(elementId) {
      const element = document.getElementById(elementId);
      if (element) {
        $(element).daterangepicker({
          startDate: moment(), // Set tanggal mulai ke hari ini (real-time)
          endDate: moment(), // Set tanggal selesai ke hari ini (real-time)
          locale: {
            format: "YYYY-MM-DD",
            separator: " - ",
          },
          ranges: {
            "Hari Ini": [moment(), moment()],
            Kemarin: [
              moment().subtract(1, "days"),
              moment().subtract(1, "days"),
            ],
            "7 Hari Terakhir": [moment().subtract(6, "days"), moment()],
            "30 Hari Terakhir": [moment().subtract(29, "days"), moment()],
            "Bulan Ini": [moment().startOf("month"), moment().endOf("month")],
            "Bulan Lalu": [
              moment().subtract(1, "month").startOf("month"),
              moment().subtract(1, "month").endOf("month"),
            ],
          },
        });
      }
    }

    initializeDateRangePicker("date_range_wallet");
    initializeDateRangePicker("date_range_bet");
  }

  // ... (Sisa kode JS Anda yang sudah ada sebelumnya)
});

/**
 * File: assets/js/script.js (REVISI FINAL FULL - Fungsionalitas Deposit.php Lengkap & Salin Rekening)
 */

// ... (SEMUA KODE JAVASCRIPT SEBELUMNYA TETAP SAMA) ...

document.addEventListener("DOMContentLoaded", function () {
  // ... (SEMUA KODE JAVASCRIPT document.addEventListener('DOMContentLoaded') SEBELUMNYA TETAP SAMA) ...

  // ========================================================
  // == BAGIAN BARU: LOGIKA UNTUK HALAMAN DEPOSIT (deposit.php) ==
  // ========================================================
  const depositChannelButtonsContainer = document.getElementById(
    "deposit-channel-buttons"
  );
  const qrisFormSection = document.getElementById("qris-form-section");
  const bankTransferFormSection = document.getElementById(
    "bank-transfer-form-section"
  );
  const depositAmountInputs = document.querySelectorAll(
    ".deposit-amount-input"
  );

  const purposeBankSelect = document.getElementById("purpose_bank");
  const selectedPurposeInfo = document.getElementById("selected_purpose_info");
  const depositBankForm = document.getElementById("deposit-bank-form"); // Untuk event listener submit
  const depositQrisForm = document.getElementById("deposit-qris-form"); // Untuk event listener submit QRIS

  // Accordion elements untuk Bank Transfer
  const bankDepositAccordion = document.getElementById("bankDepositAccordion"); // Wrapper accordion
  const collapseNotesBank = document.getElementById("collapseNotesBank");
  const collapseBankStatusBank = document.getElementById(
    "collapseBankStatusBank"
  );

  if (
    depositChannelButtonsContainer &&
    qrisFormSection &&
    bankTransferFormSection
  ) {
    // Fungsi untuk menginisialisasi atau memicu channel terpilih
    function setActiveDepositChannel(channel) {
      document.querySelectorAll(".deposit-channels button").forEach((btn) => {
        if (btn.dataset.channel === channel) {
          btn.classList.add("active");
          // Trigger Bootstrap collapse untuk show form yang aktif
          const targetElement = document.querySelector(btn.dataset.bsTarget);
          if (targetElement) {
            const bsCollapse = new bootstrap.Collapse(targetElement, {
              toggle: false,
            });
            bsCollapse.show();
          }
        } else {
          btn.classList.remove("active");
          // Trigger Bootstrap collapse untuk hide form yang tidak aktif
          const targetElement = document.querySelector(btn.dataset.bsTarget);
          if (targetElement) {
            const bsCollapse = new bootstrap.Collapse(targetElement, {
              toggle: false,
            });
            bsCollapse.hide();
          }
        }
      });

      // Logika visibilitas accordion Catatan & Status Bank berdasarkan channel
      if (channel === "qris") {
        // Sembunyikan accordion di channel Bank Transfer
        if (bankDepositAccordion) bankDepositAccordion.style.display = "none";
      } else {
        // channel === 'bank' (Transfer Bank, E-Wallet, Pulsa)
        // Tampilkan accordion di channel Bank Transfer
        if (bankDepositAccordion) bankDepositAccordion.style.display = "block";

        // Tutup accordion catatan dan status bank secara default saat tab ini aktif
        // Pastikan Bootstrap Collapse instance dibuat HANYA JIKA accordion belum diinisialisasi
        // atau selalu panggil hide() agar selalu tersembunyi defaultnya
        if (collapseNotesBank)
          new bootstrap.Collapse(collapseNotesBank, { toggle: false }).hide();
        if (collapseBankStatusBank)
          new bootstrap.Collapse(collapseBankStatusBank, {
            toggle: false,
          }).hide();
      }
    }

    depositChannelButtonsContainer.addEventListener("click", function (e) {
      const clickedBtn = e.target.closest("button");
      if (clickedBtn && clickedBtn.dataset.channel) {
        setActiveDepositChannel(clickedBtn.dataset.channel);
      }
    });

    // Memicu inisialisasi default saat halaman dimuat
    setActiveDepositChannel("qris");

    // === FUNGSI FORMAT ANGKA (50000 -> 50.000) & INFO MIN/MAX DEPOSIT ===
    function formatAndValidateAmountInput(inputElement) {
      let value = inputElement.value.replace(/\D/g, ""); // Hapus semua non-digit
      if (value === "") {
        inputElement.value = "";
        return;
      }
      value = parseInt(value, 10).toLocaleString("id-ID"); // Format sebagai ribuan dengan titik
      inputElement.value = value;

      // Dapatkan info bonus dan info rekening tujuan (jika ada)
      const bonusSelect = inputElement.id.includes("_qris")
        ? document.getElementById("bonus_qris")
        : document.getElementById("bonus_bank");
      const selectedBonusOption =
        bonusSelect.options[bonusSelect.selectedIndex];
      const minDepositBonus = parseFloat(
        selectedBonusOption.dataset.minDeposit || 0
      );
      const maxBonusAmount = parseFloat(
        selectedBonusOption.dataset.maxBonus || 0
      );
      const percentage = parseFloat(
        selectedBonusOption.dataset.percentage || 0
      );
      const turnover = parseFloat(selectedBonusOption.dataset.turnover || 1);

      const purposeSelect = inputElement.id.includes("_qris")
        ? null
        : document.getElementById("purpose_bank");
      let minDepositAcc = 0;
      let maxDepositAcc = null;
      if (purposeSelect && purposeSelect.value !== "") {
        const selectedPurposeOption =
          purposeSelect.options[purposeSelect.selectedIndex];
        minDepositAcc = parseFloat(
          selectedPurposeOption.dataset.minDepositAcc || 0
        );
        maxDepositAcc = parseFloat(
          selectedPurposeOption.dataset.maxDepositAcc || 0
        );
      }

      const overallMinDeposit = Math.max(minDepositBonus, minDepositAcc);

      const infoDiv = inputElement.nextElementSibling; // div form-text setelah input

      let infoText = `Min: IDR ${overallMinDeposit.toLocaleString("id-ID")}`;

      if (maxDepositAcc && maxDepositAcc > 0) {
        // Jika ada max deposit dari rekening
        infoText += ` | Max: IDR ${maxDepositAcc.toLocaleString("id-ID")}`;
      }

      if (percentage > 0 && selectedBonusOption.value !== "0") {
        // Tampilkan bonus info hanya jika bonus bukan "Tanpa Bonus" (id=0)
        infoText += ` | Bonus: ${percentage}% (TO ${turnover}x)`;
      }

      if (infoDiv && infoDiv.classList.contains("form-text")) {
        infoDiv.textContent = infoText;
      }
    }

    depositAmountInputs.forEach((input) => {
      input.addEventListener("input", function () {
        formatAndValidateAmountInput(this);
      });
      // Pemicu format saat load jika ada nilai default
      formatAndValidateAmountInput(input);
    });

    // === LOGIKA UNTUK DROPDOWN TUJUAN BANK / E-WALLET (purpose_bank) & TOMBOL SALIN ===
    if (purposeBankSelect) {
      purposeBankSelect.addEventListener("change", function () {
        const selectedOption = this.options[this.selectedIndex];
        const accountName = selectedOption.dataset.accountName;
        const accountNumber = selectedOption.dataset.accountNumber;
        const methodName = selectedOption.dataset.methodName;

        if (selectedPurposeInfo) {
          if (accountName && accountNumber) {
            selectedPurposeInfo.innerHTML = `
                          Transfer ke: <strong>${methodName}</strong><br>
                          Nama: <strong>${accountName}</strong><br>
                          Nomor: <strong><span id="admin-account-number">${accountNumber}</span></strong> 
                          <button type="button" class="btn btn-sm btn-outline-warning ms-2 copy-button" data-clipboard-target="#admin-account-number" title="Salin Nomor Rekening">
                              <i class="fas fa-copy"></i> Salin
                          </button>
                      `;
            // Inisialisasi event listener untuk tombol salin baru
            const copyButton =
              selectedPurposeInfo.querySelector(".copy-button");
            if (copyButton) {
              copyButton.addEventListener("click", function () {
                const targetId = this.dataset.clipboardTarget;
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                  navigator.clipboard
                    .writeText(targetElement.textContent)
                    .then(() => {
                      Swal.fire({
                        title: "Berhasil Disalin!",
                        text: targetElement.textContent,
                        icon: "success",
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 1500,
                        background: "#212529",
                        color: "#fff",
                      });
                    })
                    .catch((err) => {
                      console.error("Gagal menyalin: ", err);
                      Swal.fire({
                        title: "Gagal Salin!",
                        text: "Silakan salin manual.",
                        icon: "error",
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 1500,
                        background: "#212529",
                        color: "#fff",
                      });
                    });
                }
              });
            }
          } else {
            selectedPurposeInfo.innerHTML = "";
          }
        }
        // Setelah memilih tujuan, pemicu update info amount
        const amountInput = document.getElementById("amount_bank");
        if (amountInput) formatAndValidateAmountInput(amountInput);
      });
      // Pemicu change saat load jika ada nilai default
      purposeBankSelect.dispatchEvent(new Event("change"));
    }

    // === VALIDASI JUMLAH DEPOSIT SEBELUM SUBMIT ===
    function validateDepositAmount(
      amountInputId,
      bonusSelectId,
      purposeSelectId = null
    ) {
      const amountInput = document.getElementById(amountInputId);
      const bonusSelect = document.getElementById(bonusSelectId);

      if (!amountInput || !bonusSelect) return true; // Tidak ada elemen, lewati validasi

      const selectedBonusOption =
        bonusSelect.options[bonusSelect.selectedIndex];
      const minDepositBonus = parseFloat(
        selectedBonusOption.dataset.minDeposit || 0
      );

      let minDepositAcc = 0;
      let maxDepositAcc = null;
      if (purposeSelectId) {
        const purposeSelect = document.getElementById(purposeSelectId);
        if (purposeSelect && purposeSelect.value !== "") {
          const selectedPurposeOption =
            purposeSelect.options[purposeSelect.selectedIndex];
          minDepositAcc = parseFloat(
            selectedPurposeOption.dataset.minDepositAcc || 0
          );
          maxDepositAcc = parseFloat(
            selectedPurposeOption.dataset.maxDepositAcc || 0
          );
        }
      }

      const overallMinDeposit = Math.max(minDepositBonus, minDepositAcc);
      const cleanAmount = parseFloat(amountInput.value.replace(/\D/g, "")) || 0;

      if (cleanAmount < overallMinDeposit) {
        Swal.fire({
          title: "Deposit Kurang!",
          text: `Minimal deposit adalah IDR ${overallMinDeposit.toLocaleString(
            "id-ID"
          )}.`,
          icon: "warning",
          background: "#212529",
          color: "#fff",
          confirmButtonColor: "#ffc107",
        });
        return false;
      }
      if (maxDepositAcc && maxDepositAcc > 0 && cleanAmount > maxDepositAcc) {
        Swal.fire({
          title: "Deposit Berlebihan!",
          text: `Maksimal deposit untuk rekening ini adalah IDR ${maxDepositAcc.toLocaleString(
            "id-ID"
          )}.`,
          icon: "warning",
          background: "#212529",
          color: "#fff",
          confirmButtonColor: "#ffc107",
        });
        return false;
      }
      return true;
    }
  }
  function handleRefreshBalance(buttonId, balanceElementId) {
    const refreshButton = document.getElementById(buttonId);
    const balanceElement = document.querySelector(balanceElementId); // Menggunakan querySelector untuk fleksibilitas

    if (refreshButton && balanceElement) {
      refreshButton.addEventListener("click", async function () {
        const icon = this.querySelector("i");

        // Tambahkan animasi putar pada ikon
        icon.classList.add("fa-spin");

        try {
          const response = await fetch("api_get_balance.php");
          const data = await response.json();

          if (data.status === "success") {
            // Update tampilan saldo
            balanceElement.textContent = data.formatted_balance;
          } else {
            // Tampilkan pesan error jika gagal (opsional)
            console.error("Gagal refresh saldo:", data.message);
          }
        } catch (error) {
          console.error("Error koneksi saat refresh saldo:", error);
        } finally {
          // Hentikan animasi putar setelah 1 detik
          setTimeout(() => {
            icon.classList.remove("fa-spin");
          }, 1000);
        }
      });
    }
  }

  // Panggil fungsi untuk kedua tombol
  handleRefreshBalance("refresh-balance", "#user-balance span");
  handleRefreshBalance("sidebar-refresh-balance", "#sidebar-balance span");
});

// Logika untuk Floating Social Menu
const floatingMenu = document.querySelector(".floating-social-menu");
if (floatingMenu) {
  const toggleButton = floatingMenu.querySelector(".menu-toggle-button");
  toggleButton.addEventListener("click", function () {
    floatingMenu.classList.toggle("active");
  });
}
