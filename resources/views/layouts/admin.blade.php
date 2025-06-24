<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <!-- Quill CSS -->
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
    <!-- Quill JS -->
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
    <!-- Compressor.js -->
    <script src="https://unpkg.com/compressorjs@1.2.1/dist/compressor.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/slugify@1.6.5/slugify.js"></script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&amp;family=Montserrat:ital,wght@0,700;1,700&amp;display=swap"
        rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/product.css') }}">
    @yield('head')
</head>

<body>
    <div class="d-flex">
        <div class="w-[299px]">
            <div class="logo"><i class="fa-solid fa-bag-shopping me-2"></i>eBazer</div>
            <div class="sidebar px-[14px] py-[17px]">
                <a href="{{ route('admin.dashboard') }}"
                    class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }} link_admin">
                    <svg class="me-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16"
                        height="16">
                        <path fill="currentColor"
                            d="M7,0H4A4,4,0,0,0,0,4V7a4,4,0,0,0,4,4H7a4,4,0,0,0,4-4V4A4,4,0,0,0,7,0ZM9,7A2,2,0,0,1,7,9H4A2,2,0,0,1,2,7V4A2,2,0,0,1,4,2H7A2,2,0,0,1,9,4Z">
                        </path>
                        <path fill="currentColor"
                            d="M20,0H17a4,4,0,0,0-4,4V7a4,4,0,0,0,4,4h3a4,4,0,0,0,4-4V4A4,4,0,0,0,20,0Zm2,7a2,2,0,0,1-2,2H17a2,2,0,0,1-2-2V4a2,2,0,0,1,2-2h3a2,2,0,0,1,2,2Z">
                        </path>
                        <path fill="currentColor"
                            d="M7,13H4a4,4,0,0,0-4,4v3a4,4,0,0,0,4,4H7a4,4,0,0,0,4-4V17A4,4,0,0,0,7,13Zm2,7a2,2,0,0,1-2,2H4a2,2,0,0,1-2-2V17a2,2,0,0,1,2-2H7a2,2,0,0,1,2,2Z">
                        </path>
                        <path fill="currentColor"
                            d="M20,13H17a4,4,0,0,0-4,4v3a4,4,0,0,0,4,4h3a4,4,0,0,0,4-4V17A4,4,0,0,0,20,13Zm2,7a2,2,0,0,1-2,2H17a2,2,0,0,1-2-2V17a2,2,0,0,1,2-2h3a2,2,0,0,1,2,2Z">
                        </path>
                    </svg>
                    Bảng điều khiển
                </a>

                <div x-data="{ open: false }" class="">
                    <!-- Nút dropdown -->
                    <div @click="open = !open"
                        class="dropdown_admin px-[24px] py-[12px] flex items-center justify-between cursor-pointer {{ request()->routeIs('admin.products.index') ? 'active' : '' }}">
                        <div class="flex items-center gap-2">
                            <svg class="me-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16"
                                height="16">
                                <path fill="currentColor"
                                    d="M23.621,6.836l-1.352-2.826c-.349-.73-.99-1.296-1.758-1.552L14.214,.359c-1.428-.476-3-.476-4.428,0L3.49,2.458c-.769,.256-1.41,.823-1.759,1.554L.445,6.719c-.477,.792-.567,1.742-.247,2.609,.309,.84,.964,1.49,1.802,1.796l-.005,6.314c-.002,2.158,1.372,4.066,3.418,4.748l4.365,1.455c.714,.238,1.464,.357,2.214,.357s1.5-.119,2.214-.357l4.369-1.457c2.043-.681,3.417-2.585,3.419-4.739l.005-6.32c.846-.297,1.508-.946,1.819-1.79,.317-.858,.228-1.799-.198-2.499ZM10.419,2.257c1.02-.34,2.143-.34,3.162,0l4.248,1.416-5.822,1.95-5.834-1.95,4.246-1.415ZM2.204,7.666l1.327-2.782c.048,.025,7.057,2.373,7.057,2.373l-1.621,3.258c-.239,.398-.735,.582-1.173,.434l-5.081-1.693c-.297-.099-.53-.325-.639-.619-.109-.294-.078-.616,.129-.97Zm3.841,12.623c-1.228-.409-2.052-1.554-2.051-2.848l.005-5.648,3.162,1.054c1.344,.448,2.792-.087,3.559-1.371l.278-.557-.005,10.981c-.197-.04-.391-.091-.581-.155l-4.366-1.455Zm11.897-.001l-4.37,1.457c-.19,.063-.384,.115-.581,.155l.005-10.995,.319,.64c.556,.928,1.532,1.459,2.561,1.459,.319,0,.643-.051,.96-.157l3.161-1.053-.005,5.651c0,1.292-.826,2.435-2.052,2.844Zm4-11.644c-.105,.285-.331,.504-.619,.6l-5.118,1.706c-.438,.147-.934-.035-1.136-.365l-1.655-3.323s7.006-2.351,7.054-2.377l1.393,2.901c.157,.261,.186,.574,.081,.859Z">
                                </path>
                            </svg>
                            Sản phẩm
                        </div>
                        {{-- Mũi tên phải --}}
                        <i class="fa-solid fa-chevron-right text-[#64748b] transition-all duration-200"
                            :class="open ? 'rotate-90' : ''"></i>
                    </div>

                    <!-- Nội dung dropdown -->
                    <ul x-show="open" x-transition
                        class="dropdown_products_content flex flex-col gap-2 px-[44px] py-[12px]">
                        <li class="text-sm text-[#64748b] flex items-center gap-2">
                            <span class="text-sm text-[#64748b]">•</span>
                            <a href="{{ route('admin.products.index') }}">Danh sách sản phẩm</a>
                        </li>
                        <li class="text-sm text-[#64748b] flex items-center gap-2">
                            <span class="text-sm text-[#64748b]">•</span>
                            <a href="{{ route('admin.attributes.index') }}">Thuộc tính sản phẩm</a>
                        </li>
                    </ul>
                </div>

                <a href="{{ route('admin.categories.index') }}"
                    class="{{ request()->routeIs('admin.categories.index') ? 'active' : '' }} link_admin">
                    <svg class="me-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16"
                        height="16">
                        <path fill="currentColor"
                            d="M22.713,4.077A2.993,2.993,0,0,0,20.41,3H4.242L4.2,2.649A3,3,0,0,0,1.222,0H1A1,1,0,0,0,1,2h.222a1,1,0,0,1,.993.883l1.376,11.7A5,5,0,0,0,8.557,19H19a1,1,0,0,0,0-2H8.557a3,3,0,0,1-2.82-2h11.92a5,5,0,0,0,4.921-4.113l.785-4.354A2.994,2.994,0,0,0,22.713,4.077ZM21.4,6.178l-.786,4.354A3,3,0,0,1,17.657,13H5.419L4.478,5H20.41A1,1,0,0,1,21.4,6.178Z">
                        </path>
                        <circle fill="currentColor" cx="7" cy="22" r="2"></circle>
                        <circle fill="currentColor" cx="17" cy="22" r="2"></circle>
                    </svg>
                    Danh mục
                </a>
                <a href="{{ route('admin.orders.index') }}"
                    class="{{ request()->routeIs('admin.orders.index') ? 'active' : '' }} link_admin">
                    <svg class="me-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16"
                        height="16">
                        <path fill="currentColor"
                            d="m11.349,24H0V3C0,1.346,1.346,0,3,0h12c1.654,0,3,1.346,3,3v5.059c-.329-.036-.662-.059-1-.059s-.671.022-1,.059V3c0-.552-.448-1-1-1H3c-.552,0-1,.448-1,1v19h7.518c.506.756,1.125,1.429,1.831,2Zm0-14h-7.349v2h5.518c.506-.756,1.125-1.429,1.831-2Zm-7.349,7h4c0-.688.084-1.356.231-2h-4.231v2Zm20,0c0,3.859-3.141,7-7,7s-7-3.141-7-7,3.141-7,7-7,7,3.141,7,7Zm-2,0c0-2.757-2.243-5-5-5s-5,2.243-5,5,2.243,5,5,5,5-2.243,5-5ZM14,5H4v2h10v-2Zm5.589,9.692l-3.228,3.175-1.63-1.58-1.393,1.436,1.845,1.788c.314.315.733.489,1.179.489s.865-.174,1.173-.482l3.456-3.399-1.402-1.426Z">
                        </path>
                    </svg>
                    Đơn hàng
                </a>
                <a href="{{ route('admin.reviews.index') }}"
                    class="{{ request()->routeIs('admin.reviews.index') ? 'active' : '' }} link_admin">
                    <svg class="me-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16"
                        height="16">
                        <path fill="currentColor"
                            d="M12.01,23.67c-.48,0-.96-.17-1.34-.51l-3.75-3.16h-2.92c-2.21,0-4-1.79-4-4V4C0,1.79,1.79,0,4,0H20c2.21,0,4,1.79,4,4v12c0,2.21-1.79,4-4,4h-2.85l-3.85,3.18c-.36,.32-.83,.49-1.29,.49ZM4,2c-1.1,0-2,.9-2,2v12c0,1.1,.9,2,2,2h3.29c.24,0,.46,.08,.64,.24l4.05,3.41,4.17-3.42c.18-.15,.4-.23,.64-.23h3.21c1.1,0,2-.9,2-2V4c0-1.1-.9-2-2-2H4Zm5.01,13.35c-.19,0-.39-.06-.55-.18-.31-.23-.44-.64-.32-1.01l.86-2.76-2.18-1.77c-.29-.25-.4-.65-.27-1.01,.13-.36,.48-.6,.86-.6h2.75l.97-2.61c.13-.36,.48-.6,.86-.6s.73,.24,.86,.6l.97,2.61h2.75c.38,0,.73,.24,.86,.6s.02,.77-.27,1.02l-2.17,1.77,.9,2.73c.12,.37,0,.78-.31,1.01-.31,.24-.73,.25-1.06,.04l-2.52-1.64-2.48,1.66c-.15,.1-.33,.15-.51,.15Z">
                        </path>
                    </svg>
                    Đánh giá
                </a>
                <a href="{{ route('admin.notifications.index') }}"
                    class="{{ request()->routeIs('admin.notifications.index') ? 'active' : '' }} link_admin">
                    <svg class="me-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16"
                        height="16">
                        <path fill="currentColor"
                            d="M12.01,23.67c-.48,0-.96-.17-1.34-.51l-3.75-3.16h-2.92c-2.21,0-4-1.79-4-4V4C0,1.79,1.79,0,4,0H20c2.21,0,4,1.79,4,4v12c0,2.21-1.79,4-4,4h-2.85l-3.85,3.18c-.36,.32-.83,.49-1.29,.49ZM4,2c-1.1,0-2,.9-2,2v12c0,1.1,.9,2,2,2h3.29c.24,0,.46,.08,.64,.24l4.05,3.41,4.17-3.42c.18-.15,.4-.23,.64-.23h3.21c1.1,0,2-.9,2-2V4c0-1.1-.9-2-2-2H4Zm5.01,13.35c-.19,0-.39-.06-.55-.18-.31-.23-.44-.64-.32-1.01l.86-2.76-2.18-1.77c-.29-.25-.4-.65-.27-1.01,.13-.36,.48-.6,.86-.6h2.75l.97-2.61c.13-.36,.48-.6,.86-.6s.73,.24,.86,.6l.97,2.61h2.75c.38,0,.73,.24,.86,.6s.02,.77-.27,1.02l-2.17,1.77,.9,2.73c.12,.37,0,.78-.31,1.01-.31,.24-.73,.25-1.06,.04l-2.52-1.64-2.48,1.66c-.15,.1-.33,.15-.51,.15Z">
                        </path>
                    </svg>
                    Thông báo
                </a>
                <a href="{{ route('admin.reports.index') }}" class="link_admin">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 24 24" fill="none">
                        <path fill="currentColor"
                            d="M22 6H2V2h20v4zM22 8H2v4h20V8zM22 14H2v4h20v-4zM22 20H2v2h20v-2z" />
                    </svg>
                    Báo cáo
                </a>
                <a href="{{ route('admin.coupon.index') }}"
                    class="{{ request()->routeIs('admin.coupon.index') ? 'active' : '' }} link_admin">
                    <svg class="me-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16"
                        height="16">
                        <path fill="currentColor"
                            d="M16,0h-.13a2.02,2.02,0,0,0-1.941,1.532,2,2,0,0,1-3.858,0A2.02,2.02,0,0,0,8.13,0H8A5.006,5.006,0,0,0,3,5V21a3,3,0,0,0,3,3H8.13a2.02,2.02,0,0,0,1.941-1.532,2,2,0,0,1,3.858,0A2.02,2.02,0,0,0,15.87,24H18a3,3,0,0,0,3-3V5A5.006,5.006,0,0,0,16,0Zm2,22-2.143-.063A4,4,0,0,0,8.13,22H6a1,1,0,0,1-1-1V17H7a1,1,0,0,0,0-2H5V5A3,3,0,0,1,8,2l.143.063A4.01,4.01,0,0,0,12,5a4.071,4.071,0,0,0,3.893-3H16a3,3,0,0,1,3,3V15H17a1,1,0,0,0,0,2h2v4A1,1,0,0,1,18,22Z">
                        </path>
                        <path fill="currentColor" d="M13,15H11a1,1,0,0,0,0,2h2a1,1,0,0,0,0-2Z"></path>
                    </svg>
                    Mã giảm giá
                </a>
                <a href="{{ route('admin.refunds.index') }}"
                    class="{{ request()->routeIs('admin.refunds.index') ? 'active' : '' }} link_admin">
                    <svg class="me-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16"
                        height="16">
                        <path fill="currentColor"
                            d="M16,0h-.13a2.02,2.02,0,0,0-1.941,1.532,2,2,0,0,1-3.858,0A2.02,2.02,0,0,0,8.13,0H8A5.006,5.006,0,0,0,3,5V21a3,3,0,0,0,3,3H8.13a2.02,2.02,0,0,0,1.941-1.532,2,2,0,0,1,3.858,0A2.02,2.02,0,0,0,15.87,24H18a3,3,0,0,0,3-3V5A5.006,5.006,0,0,0,16,0Zm2,22-2.143-.063A4,4,0,0,0,8.13,22H6a1,1,0,0,1-1-1V17H7a1,1,0,0,0,0-2H5V5A3,3,0,0,1,8,2l.143.063A4.01,4.01,0,0,0,12,5a4.071,4.071,0,0,0,3.893-3H16a3,3,0,0,1,3,3V15H17a1,1,0,0,0,0,2h2v4A1,1,0,0,1,18,22Z">
                        </path>
                        <path fill="currentColor" d="M13,15H11a1,1,0,0,0,0,2h2a1,1,0,0,0,0-2Z"></path>
                    </svg>
                    xử lý hoàn hàng
                </a>
                <a href="{{ route('admin.brands.index') }}"
                    class="{{ request()->routeIs('admin.brands.index') ? 'active' : '' }} link_admin">
                    <svg class="me-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16"
                        height="16">
                        <path fill="currentColor"
                            d="M16,0h-.13a2.02,2.02,0,0,0-1.941,1.532,2,2,0,0,1-3.858,0A2.02,2.02,0,0,0,8.13,0H8A5.006,5.006,0,0,0,3,5V21a3,3,0,0,0,3,3H8.13a2.02,2.02,0,0,0,1.941-1.532,2,2,0,0,1,3.858,0A2.02,2.02,0,0,0,15.87,24H18a3,3,0,0,0,3-3V5A5.006,5.006,0,0,0,16,0Zm2,22-2.143-.063A4,4,0,0,0,8.13,22H6a1,1,0,0,1-1-1V17H7a1,1,0,0,0,0-2H5V5A3,3,0,0,1,8,2l.143.063A4.01,4.01,0,0,0,12,5a4.071,4.071,0,0,0,3.893-3H16a3,3,0,0,1,3,3V15H17a1,1,0,0,0,0,2h2v4A1,1,0,0,1,18,22Z">
                        </path>
                        <path fill="currentColor" d="M13,15H11a1,1,0,0,0,0,2h2a1,1,0,0,0,0-2Z"></path>
                    </svg>
                    thương hiệu
                </a>
                <a href="{{ route('admin.users.index') }}" class="link_admin">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" viewBox="0 0 24 24" fill="none">
                        <circle opacity="0.4" cx="15" cy="6" r="3" fill="#1C274C" />
                        <ellipse opacity="0.4" cx="16" cy="17" rx="5" ry="3"
                            fill="#1C274C" />
                        <circle cx="9.00098" cy="6" r="4" fill="#1C274C" />
                        <ellipse cx="9.00098" cy="17.001" rx="7" ry="4" fill="#1C274C" />
                    </svg>
                    Người dùng
                </a>
                <a href="#" class="link_admin">
                    <svg class="me-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16"
                        height="16">
                        <path fill="currentColor"
                            d="m12,0C5.383,0,0,5.383,0,12s5.383,12,12,12,12-5.383,12-12S18.617,0,12,0Zm-4,21.164v-.164c0-2.206,1.794-4,4-4s4,1.794,4,4v.164c-1.226.537-2.578.836-4,.836s-2.774-.299-4-.836Zm9.925-1.113c-.456-2.859-2.939-5.051-5.925-5.051s-5.468,2.192-5.925,5.051c-2.47-1.823-4.075-4.753-4.075-8.051C2,6.486,6.486,2,12,2s10,4.486,10,10c0,3.298-1.605,6.228-4.075,8.051Zm-5.925-15.051c-2.206,0-4,1.794-4,4s1.794,4,4,4,4-1.794,4-4-1.794-4-4-4Zm0,6c-1.103,0-2-.897-2-2s.897-2,2-2,2,.897,2,2-.897,2-2,2Z">
                        </path>
                    </svg>
                    Hồ sơ
                </a>
                <div class="border-t border-gray pt-3 mt-3">
                    <a href="{{ route('admin.settings.index') }}"
                        class="{{ request()->routeIs('admin.settings.index') ? 'active' : '' }} link_admin">
                        <svg class="me-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16"
                            height="16">
                            <path fill="currentColor"
                                d="M24,10a.988.988,0,0,0-.024-.217l-1.3-5.868A4.968,4.968,0,0,0,17.792,0H6.208a4.968,4.968,0,0,0-4.88,3.915L.024,9.783A.988.988,0,0,0,0,10v1a3.984,3.984,0,0,0,1,2.643V19a5.006,5.006,0,0,0,5,5H18a5.006,5.006,0,0,0,5-5V13.643A3.984,3.984,0,0,0,24,11ZM2,10.109l1.28-5.76A2.982,2.982,0,0,1,6.208,2H7V5A1,1,0,0,0,9,5V2h6V5a1,1,0,0,0,2,0V2h.792A2.982,2.982,0,0,1,20.72,4.349L22,10.109V11a2,2,0,0,1-2,2H19a2,2,0,0,1-2-2,1,1,0,0,0-2,0,2,2,0,0,1-2,2H11a2,2,0,0,1-2-2,1,1,0,0,0-2,0,2,2,0,0,1-2,2H4a2,2,0,0,1-2-2ZM18,22H6a3,3,0,0,1-3-3V14.873A3.978,3.978,0,0,0,4,15H5a3.99,3.99,0,0,0,3-1.357A3.99,3.99,0,0,0,11,15h2a3.99,3.99,0,0,0,3-1.357A3.99,3.99,0,0,0,19,15h1a3.978,3.978,0,0,0,1-.127V19A3,3,0,0,1,18,22Z">
                            </path>
                        </svg>
                        Cài đặt cửa hàng
                    </a>
                    <a href="#" class="link_admin">
                        <svg class="me-1" height="16" viewBox="-78 -18 560 560.00187" width="16"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill="currentColor"
                                d="m260.722656 3.878906-.128906-.125c-2.355469-2.429687-5.605469-3.7890622-8.996094-3.74999975-.417968-.01953125-.839844.01953125-1.25.12890575h-191.953125c-34.503906.007813-62.46875 27.976563-62.484375 62.484376v399.898437c.015625 34.503906 27.980469 62.472656 62.484375 62.484375h278.308594c34.503906-.011719 62.472656-27.980469 62.484375-62.484375v-315.175781c-.023438-3.308594-1.320312-6.484375-3.625-8.867188zm3.371094 38.742188 92.726562 92.476562h-55.234374c-20.679688-.0625-37.433594-16.8125-37.492188-37.488281zm72.734375 457.261718h-278.433594c-20.679687-.058593-37.425781-16.8125-37.492187-37.492187v-399.902344c.066406-20.675781 16.8125-37.429687 37.492187-37.488281h180.707031v72.609375c.011719 34.5 27.980469 62.46875 62.484376 62.484375h72.609374v302.296875c.039063 20.671875-16.695312 37.464844-37.367187 37.492187zm0 0">
                            </path>
                            <path fill="currentColor"
                                d="m76.015625 241.195312h135.09375c6.898437 0 12.5-5.59375 12.5-12.496093 0-6.902344-5.601563-12.496094-12.5-12.496094h-135.09375c-6.902344 0-12.496094 5.59375-12.496094 12.496094 0 6.902343 5.59375 12.496093 12.496094 12.496093zm0 0">
                            </path>
                            <path fill="currentColor"
                                d="m319.207031 310.679688h-243.191406c-6.902344 0-12.496094 5.59375-12.496094 12.496093 0 6.898438 5.59375 12.496094 12.496094 12.496094h243.191406c6.898438 0 12.5-5.597656 12.5-12.496094 0-6.902343-5.601562-12.496093-12.5-12.496093zm0 0">
                            </path>
                            <path fill="currentColor"
                                d="m319.207031 405.28125h-243.191406c-6.902344 0-12.496094 5.59375-12.496094 12.496094s5.59375 12.496094 12.496094 12.496094h243.191406c6.898438 0 12.5-5.59375 12.5-12.496094s-5.601562-12.496094-12.5-12.496094zm0 0">
                            </path>
                        </svg>
                        Trang
                    </a>

                </div>
            </div>
        </div>
        <div class="flex-grow-1">
            <div class="header">
                <form class="search-box flex items-center relative">
                    <input class="form-control w-full" placeholder="Tìm kiếm..." />
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5 absolute right-3 top-1/2 -translate-y-1/2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>

                </form>
                <div class="right relative">
                    <!-- Nút thông báo -->
                    <div class="border border-1 p-1 rounded-[4px] w-[38px] h-[38px] relative cursor-pointer flex items-center justify-center"
                        id="notification-btn">
                        <div
                            class="bg-red-500 flex items-center justify-center rounded-full w-[16px] h-[16px] absolute top-0 right-0">
                            <span class="text-white text-xs">9</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                        </svg>
                    </div>
                    <!-- Dropdown thông báo -->
                    <div id="notification-dropdown"
                        class="absolute top-[110%] right-0 w-[250px] bg-white shadow-lg border rounded-md hidden">
                        <div class="p-2 text-sm font-semibold border-b">Thông báo</div>
                        <ul class="text-sm">
                            <li class="p-2 border-b cursor-pointer hover:bg-gray-100">Bạn có một tin nhắn mới</li>
                            <li class="p-2 border-b cursor-pointer hover:bg-gray-100">Cập nhật hệ thống</li>
                            <li class="p-2 cursor-pointer hover:bg-gray-100">Thời gian đăng ký sẽ hết hạn sớm!</li>
                        </ul>
                    </div>
                    <div class="relative">
                        <div
                            class="absolute top-0 right-0 w-[10px] h-[10px] bg-green-500 rounded-full border border-white">
                        </div>
                        <button id="dropdownInformationButton" data-dropdown-toggle="dropdownInformation"
                            class="text-white" type="button">
                            <img src="{{ asset('images/avatar.png') }}" class="avatar" alt="avatar">
                        </button>
                        <!-- Dropdown menu -->
                        <div id="dropdownInformation" class="z-10 hidden w-[200px]">
                            <div
                                class="bg-white divide-y divide-gray-600 rounded-lg shadow-sm w-44 dark:bg-gray-700 dark:divide-gray-100">
                                <div class="flex items-center px-4 py-3 text-sm text-gray-900 dark:text-black">
                                    <div class="font-medium truncate">{{ Auth::user()->fullname }}</div>
                                </div>
                                <ul class="py-2 text-sm text-gray-700 dark:text-black"
                                    aria-labelledby="dropdownInformationButton">
                                    <li>
                                        <a href="#"
                                            class="block px-4 py-2 hover:bg-gray-600 dark:hover:bg-gray-100 dark:hover:text-black">Cài
                                            đặt</a>
                                    </li>
                                </ul>
                                <div class="py-2">
                                    <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();"
                                        class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-600 dark:hover:bg-gray-100 dark:text-black dark:hover:text-black">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                                        </svg>
                                        Đăng xuất
                                    </a>
                                    <form id="logout-form-header" action="{{ route('logout') }}" method="POST"
                                        class="hidden">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Toast Notifications -->
                <div id="toast-container" class="fixed bottom-4 right-4 space-y-2">
                    <div id="toast-success"
                        class="hidden flex items-center w-full max-w-xs p-4 bg-white rounded-lg shadow-sm">
                        <div
                            class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg">
                            <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
                            </svg>
                        </div>
                        <div class="ms-3 text-sm font-normal">Danh mục đã được thêm thành công!</div>
                        <button type="button" class="ms-auto text-gray-400 hover:text-gray-900 p-1.5"
                            data-dismiss-target="#toast-success">
                            <svg class="w-3 h-3" aria-hidden="true" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-width="2" d="M1 1l6 6m0 0l6 6M7 7L13 1M7 7L1 13" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <main>
                @yield('content')
            </main>
        </div>
    </div>

    @yield('scripts')
    <script src="{{ asset('js/admin/product.js') }}"></script>
    <script src="{{ asset('js/admin/variant.js') }}"></script>
    <script src="{{ asset('js/admin/admin.js') }}"></script>
    <script src="{{ asset('js/admin/order.js') }}"></script>
    <script src="{{ asset('js/admin/category.js') }}"></script>
</body>

</html>
