<?php use App\Enums\Menu; ?>

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <h4>Photorama</h4>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        <!-- <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Setup</span>
        </li> -->
             <!-- cabang -->
            <li class="menu-item <?= @$menuactive_cabang ?>">
                <a href="<?= BASE_URL ?>admin/branch" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-git-branch"></i>
                    <div data-i18n="Account Settings" class="text-center">Cabang</div>
                </a>
            </li>
            <!-- Background -->
            <li class="menu-item <?= @$menuactive_bg ?>">
                <a href="<?= BASE_URL ?>admin/background" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-cog"></i>
                    <div data-i18n="Account Settings" class="text-center">Background</div>
                </a>
            </li>
        
            <li class="menu-item <?= @$menuactive_fr ?>">
                <a href="<?= BASE_URL ?>admin/frame" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-cog"></i>
                    <div data-i18n="Account Settings" class="text-center">Frame</div>
                </a>
            </li>
            <li class="menu-item <?= @$menuactive_price ?>">
                <a href="<?= BASE_URL ?>admin/price" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-dollar-circle"></i>
                    <div data-i18n="Account Settings" class="text-center">Price</div>
                </a>
            </li>
            <li class="menu-item <?= @$menuactive_voc ?>">
                <a href="<?= BASE_URL ?>admin/voucher" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-purchase-tag-alt"></i>
                    <div data-i18n="Account Settings" class="text-center">Voucher</div>
                </a>
            </li>
            <li class="menu-item <?= @$menuactive_qr ?>">
                <a href="<?= BASE_URL ?>admin/qris" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-qr-scan"></i>
                    <div data-i18n="Account Settings" class="text-center">QRIS</div>
                </a>
            </li>
            <li class="menu-item <?= @$menuactive_timer ?>">
                <a href="<?= BASE_URL ?>admin/timer" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-time"></i>
                    <div data-i18n="Account Settings" class="text-center">Timer</div>
                </a>
            </li>
            <li class="menu-item <?= @$menuactive_photo ?>">
                <a href="<?= BASE_URL ?>admin/photo" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-photo-album"></i>
                    <div data-i18n="Account Settings" class="text-center">Photos</div>
                </a>
            </li>
    </ul>
</aside>


<div class="layout-page">
    <!-- Navbar -->
    <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
            </a>
        </div>

        <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

            <ul class="navbar-nav flex-row align-items-center ms-auto">
                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <span class="avatar-initial rounded-circle bg-label-warning">M</span>
                        <!-- <img src="<?= BASE_URL ?>assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" /> -->
                    </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                <div class="avatar avatar-online">
                                    <span class="avatar-initial rounded-circle bg-label-warning">A</span>
                                </div>
                                </div>
                                <div class="flex-grow-1">
                                <span class="fw-semibold d-block">Admin</span>
                                <small class="text-muted">Admin</small>
                                </div>
                            </div>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?=BASE_URL?>auth/logout">
                            <i class="bx bx-power-off me-2"></i>
                            <span class="align-middle">Log Out</span>
                            </a>
                        </li>
                        </ul>
                </li>
                <!--/ User -->
            </ul>
        </div>
    </nav>
        <!-- / Navbar -->