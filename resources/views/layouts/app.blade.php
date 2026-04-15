<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon.ico') }}">
    <title>{{ $title ?? 'Public Transport Management System' }}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        .form-select.select2-hidden-accessible + .select2 {
            width: 100% !important;
        }

        .form-select.select2-hidden-accessible + .select2 .select2-selection--single {
            min-height: calc(1.5em + 0.75rem + 2px);
            height: calc(1.5em + 0.75rem + 2px);
            border: var(--bs-border-width) solid var(--bs-border-color);
            border-radius: 0.375rem;
            display: flex;
            align-items: center;
            padding: 0.375rem 2.25rem 0.375rem 0.75rem;
            background-color: var(--bs-body-bg);
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.075);
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-select.select2-hidden-accessible + .select2 .select2-selection__rendered {
            color: var(--bs-body-color);
            line-height: 1.5;
            padding-left: 0;
            padding-right: 0;
            font-size: 1rem;
            font-weight: 400;
        }

        .form-select.select2-hidden-accessible + .select2 .select2-selection__arrow {
            height: 100%;
            right: 0.75rem;
            width: 0.75rem;
        }

        .form-select.select2-hidden-accessible + .select2 .select2-selection__clear {
            font-size: 1.4em;
            line-height: 1;
        }

        .form-select.select2-hidden-accessible + .select2 .select2-selection--multiple {
            min-height: calc(1.5em + 0.75rem + 2px);
            border: var(--bs-border-width) solid var(--bs-border-color);
            border-radius: 0.375rem;
            padding: 0.215rem 0.75rem;
            background-color: var(--bs-body-bg);
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.075);
        }

        .form-select.is-invalid.select2-hidden-accessible + .select2 .select2-selection--single {
            border-color: #dc3545;
        }

        .form-select.is-invalid.select2-hidden-accessible + .select2 .select2-selection--multiple {
            border-color: #dc3545;
        }

        .flatpickr-input.form-control[readonly] {
            background-color: #fff;
        }

        .flatpickr-input.form-control {
            font-size: 1rem;
            letter-spacing: 0.01em;
        }

        .flatpickr-calendar {
            background: #fff;
            border: 1px solid #dfe7e1;
            border-radius: 1.15rem;
            box-shadow: 0 0.75rem 2rem rgba(29, 36, 34, 0.08);
            font-family: "Inter", sans-serif;
            padding: 0.65rem 0.75rem 0.65rem;
            width: 20.5rem;
            max-width: calc(100vw - 2rem);
        }

        .flatpickr-calendar:before,
        .flatpickr-calendar:after {
            display: block;
            left: 2rem;
        }

        .flatpickr-calendar.arrowTop:before {
            border-bottom-color: #dfe7e1;
        }

        .flatpickr-calendar.arrowTop:after {
            border-bottom-color: #fff;
        }

        .flatpickr-months {
            align-items: center;
            margin-bottom: 0.45rem;
        }

        .flatpickr-months .flatpickr-month,
        .flatpickr-months .flatpickr-prev-month,
        .flatpickr-months .flatpickr-next-month {
            height: 2.35rem;
        }

        .flatpickr-months .flatpickr-prev-month,
        .flatpickr-months .flatpickr-next-month {
            top: 0.05rem;
            padding: 0.7rem;
            color: #59667c;
            fill: #59667c;
        }

        .flatpickr-current-month {
            position: relative;
            left: auto;
            width: calc(100% - 5rem);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
            height: 2.35rem;
            padding: 0.2rem 0;
            font-size: 0.95rem;
            font-weight: 600;
        }

        .flatpickr-current-month .cur-month {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1f2a44;
            margin-left: 0;
            padding: 0;
        }

        .flatpickr-current-month .numInputWrapper {
            width: 4.1rem;
            min-width: 4.1rem;
            margin-left: 0.2rem;
            background: #fff;
            padding-right: 1rem;
        }

        .flatpickr-current-month input.cur-year {
            width: 100%;
            min-width: 3rem;
            font-size: 0.95rem;
            font-weight: 700;
            color: #1f2a44;
            padding: 0;
            text-align: left;
        }

        .flatpickr-current-month .numInputWrapper span {
            opacity: 1;
            width: 0.85rem;
            border: 1px solid #2f6547;
            background: #fff;
            right: 0;
            padding: 0;
        }

        .flatpickr-current-month .numInputWrapper span:hover {
            background: #f2f7f4;
        }

        .flatpickr-current-month .numInputWrapper span.arrowUp {
            top: 1px;
        }

        .flatpickr-current-month .numInputWrapper span.arrowUp:after {
            border-left: 4px solid transparent;
            border-right: 4px solid transparent;
            border-bottom: 5px solid #2f6547;
            top: 22%;
        }

        .flatpickr-current-month .numInputWrapper span.arrowDown:after {
            border-left: 4px solid transparent;
            border-right: 4px solid transparent;
            border-top: 5px solid #2f6547;
            top: 32%;
        }

        .flatpickr-weekdays {
            height: 1.9rem;
            margin-bottom: 0.15rem;
        }

        span.flatpickr-weekday {
            color: #1f2a44;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: none;
            letter-spacing: 0;
        }

        .flatpickr-days,
        .dayContainer {
            width: 100%;
            min-width: 100%;
            max-width: 100%;
        }

        .flatpickr-day {
            max-width: 2.55rem;
            height: 2.55rem;
            line-height: 2.55rem;
            border-radius: 0.45rem;
            font-size: 0.8rem;
            color: #56637a;
        }

        .flatpickr-day.selected,
        .flatpickr-day.startRange,
        .flatpickr-day.endRange,
        .flatpickr-day.selected.inRange,
        .flatpickr-day.startRange.inRange,
        .flatpickr-day.endRange.inRange,
        .flatpickr-day.selected:focus,
        .flatpickr-day.startRange:focus,
        .flatpickr-day.endRange:focus,
        .flatpickr-day.selected:hover,
        .flatpickr-day.startRange:hover,
        .flatpickr-day.endRange:hover,
        .flatpickr-day.selected.prevMonthDay,
        .flatpickr-day.startRange.prevMonthDay,
        .flatpickr-day.endRange.prevMonthDay,
        .flatpickr-day.selected.nextMonthDay,
        .flatpickr-day.startRange.nextMonthDay,
        .flatpickr-day.endRange.nextMonthDay {
            background: #4a825d;
            border-color: #4a825d;
            color: #fff;
        }

        .flatpickr-day.today {
            border-color: transparent;
            color: #56637a;
        }

        .flatpickr-day:hover,
        .flatpickr-day:focus,
        .flatpickr-day.inRange,
        .flatpickr-day.prevMonthDay:hover,
        .flatpickr-day.nextMonthDay:hover {
            background: #eef4ef;
            border-color: #eef4ef;
        }

        .flatpickr-day.prevMonthDay,
        .flatpickr-day.nextMonthDay {
            color: #9aa8bd;
        }

        .form-select.select2-hidden-accessible + .select2 .select2-selection__placeholder {
            color: #6c757d;
            font-size: 1rem;
        }

        .form-select.select2-hidden-accessible + .select2.select2-container--focus .select2-selection--single,
        .form-select.select2-hidden-accessible + .select2.select2-container--focus .select2-selection--multiple,
        .form-select.select2-hidden-accessible + .select2.select2-container--open .select2-selection--single,
        .form-select.select2-hidden-accessible + .select2.select2-container--open .select2-selection--multiple {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .select2-dropdown {
            border: var(--bs-border-width) solid var(--bs-border-color);
            border-radius: 0.375rem;
            overflow: hidden;
        }

        .select2-search--dropdown .select2-search__field {
            border: var(--bs-border-width) solid var(--bs-border-color);
            border-radius: 0.375rem;
            min-height: calc(1.5em + 0.75rem + 2px);
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
        }

        .select2-results__option {
            font-size: 1rem;
            padding: 0.5rem 0.75rem;
        }
    </style>
</head>
<body>
    <div class="app-layout">
        @include('partials.sidebar')

        <div class="app-main">
            @include('partials.header', ['pageBadge' => $pageBadge ?? 'PMRU GB Portal'])

            <main class="app-content container-fluid">
                @include('partials.alerts')
                @yield('content')
            </main>
        </div>
    </div>

    @include('partials.mobile-nav')
    @include('partials.toasts')
    @include('partials.delete-modal')
    @include('partials.session-expired-modal')

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>
    <script src="{{ asset('js/flatpickr.js') }}"></script>
    <script src="{{ asset('js/apexcharts.js') }}"></script>
    <script>
        window.appInitSelect2 = function (scope) {
            if (!window.jQuery || !window.jQuery.fn || !window.jQuery.fn.select2) {
                return;
            }

            const root = scope || document;
            const elements = root.querySelectorAll
                ? root.querySelectorAll('select:not([data-no-select2])')
                : [];

            window.jQuery(elements).each(function () {
                const element = window.jQuery(this);
                const firstOption = this.options.length ? this.options[0] : null;
                const placeholder = element.data('placeholder')
                    || (firstOption && firstOption.value === '' ? firstOption.text : '');

                if (element.hasClass('select2-hidden-accessible')) {
                    return;
                }

                element.select2({
                    width: '100%',
                    placeholder: placeholder,
                    allowClear: !this.required && !!placeholder,
                });
            });
        };

        window.appInitFlatpickr = function (scope) {
            if (typeof window.flatpickr !== 'function') {
                return;
            }

            const root = scope || document;
            const today = new Date();
            const todayString = today.toISOString().slice(0, 10);

            root.querySelectorAll('input[type="date"], input[data-flatpickr]').forEach(function (element) {
                if (element._flatpickr) {
                    return;
                }

                if (!element.hasAttribute('max')) {
                    element.setAttribute('max', todayString);
                }

                if (element.type === 'date') {
                    element.type = 'text';
                }

                window.flatpickr(element, {
                    dateFormat: 'Y-m-d',
                    altInput: true,
                    altFormat: 'd-m-Y',
                    allowInput: true,
                    disableMobile: true,
                    maxDate: element.getAttribute('max') || 'today',
                    monthSelectorType: 'static',
                    onReady: function (selectedDates, dateStr, instance) {
                        const placeholder = element.getAttribute('data-placeholder') || 'dd-mm-yyyy';

                        element.setAttribute('placeholder', placeholder);

                        if (instance.altInput) {
                            instance.altInput.setAttribute('placeholder', placeholder);
                        }
                    },
                });
            });
        };

        window.appInitEnhancements = function (scope) {
            window.appInitSelect2(scope);
            window.appInitFlatpickr(scope);
        };

        window.appLoadLiveRegion = function (targetSelector, url, options) {
            const settings = options || {};
            const currentRegion = document.querySelector(targetSelector);

            if (!currentRegion || !window.fetch) {
                window.location.assign(url);
                return Promise.resolve();
            }

            currentRegion.classList.add('opacity-75');
            currentRegion.style.pointerEvents = 'none';

            return window.fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
            }).then(function (response) {
                return response.text();
            }).then(function (html) {
                const parser = new DOMParser();
                const documentFragment = parser.parseFromString(html, 'text/html');
                const nextRegion = documentFragment.querySelector(targetSelector);

                if (!nextRegion) {
                    window.location.assign(url);
                    return;
                }

                currentRegion.replaceWith(nextRegion);

                if (settings.updateHistory !== false) {
                    window.history.replaceState({}, '', url);
                }

                window.appInitEnhancements(nextRegion);
                window.appInitLiveRegions(nextRegion);
                document.dispatchEvent(new CustomEvent('app:fragment-updated', {
                    detail: {
                        container: nextRegion,
                        targetSelector: targetSelector,
                    },
                }));

                if (settings.focusSelector) {
                    const nextFocusField = document.querySelector(settings.focusSelector);

                    if (nextFocusField) {
                        nextFocusField.focus();

                        if (typeof settings.focusValue === 'string' && 'value' in nextFocusField) {
                            nextFocusField.value = settings.focusValue;
                            nextFocusField.setSelectionRange(settings.focusValue.length, settings.focusValue.length);
                        }
                    }
                }
            }).catch(function (error) {
                console.error('Live region update failed', error);
                window.location.assign(url);
            }).finally(function () {
                const restoredRegion = document.querySelector(targetSelector);

                if (restoredRegion) {
                    restoredRegion.classList.remove('opacity-75');
                    restoredRegion.style.pointerEvents = '';
                }
            });
        };

        window.appInitLiveRegions = function (scope) {
            const root = scope || document;

            root.querySelectorAll('.js-live-search-form[data-live-search-target]').forEach(function (form) {
                if (form.dataset.liveSearchBound === 'true') {
                    return;
                }

                form.dataset.liveSearchBound = 'true';

                const input = form.querySelector('.js-live-search-input');
                let timer = null;

                form.addEventListener('submit', function (event) {
                    event.preventDefault();

                    const action = form.getAttribute('action') || window.location.href;
                    const targetSelector = form.dataset.liveSearchTarget;
                    const url = new URL(action, window.location.origin);
                    const params = new URLSearchParams(window.location.search);
                    const formData = new FormData(form);

                    formData.forEach(function (value, key) {
                        if (typeof value !== 'string' || value.trim() === '') {
                            params.delete(key);
                            return;
                        }

                        params.set(key, value);
                    });

                    params.delete('page');
                    url.search = params.toString();

                    window.appLoadLiveRegion(targetSelector, url.toString(), {
                        focusSelector: '.js-live-search-form[data-live-search-target="' + targetSelector + '"] .js-live-search-input',
                        focusValue: input ? input.value : '',
                    });
                });

                if (input) {
                    input.addEventListener('input', function () {
                        window.clearTimeout(timer);
                        timer = window.setTimeout(function () {
                            form.requestSubmit();
                        }, 250);
                    });
                }
            });

            root.querySelectorAll('form[data-live-submit-target]').forEach(function (form) {
                if (form.dataset.liveSubmitBound === 'true') {
                    return;
                }

                form.dataset.liveSubmitBound = 'true';

                form.addEventListener('submit', function (event) {
                    event.preventDefault();

                    const action = form.getAttribute('action') || window.location.href;
                    const targetSelector = form.dataset.liveSubmitTarget;
                    const url = new URL(action, window.location.origin);
                    const params = new URLSearchParams(window.location.search);
                    const formData = new FormData(form);

                    Array.from(params.keys()).forEach(function (key) {
                        if (form.querySelector('[name="' + key + '"]')) {
                            params.delete(key);
                        }
                    });

                    formData.forEach(function (value, key) {
                        if (typeof value !== 'string' || value.trim() === '') {
                            params.delete(key);
                            return;
                        }

                        params.append(key, value);
                    });

                    params.delete('page');
                    url.search = params.toString();

                    window.appLoadLiveRegion(targetSelector, url.toString());
                });
            });
        };

        window.appHandleSessionExpired = function () {
            try {
                window.sessionStorage.setItem('transport-session-expired', 'true');
            } catch (error) {
                // Ignore storage issues and continue with the redirect.
            }

            window.location.assign(@json(route('login')));
        };

        window.appPreserveScroll = {
            storageKey: 'app-preserved-scroll',
            remember: function (targetPath) {
                try {
                    window.sessionStorage.setItem(this.storageKey, JSON.stringify({
                        path: targetPath,
                        scrollY: window.scrollY || window.pageYOffset || 0,
                        timestamp: Date.now(),
                    }));
                } catch (error) {
                    // Ignore storage issues and continue with normal navigation.
                }
            },
            restore: function () {
                try {
                    const rawValue = window.sessionStorage.getItem(this.storageKey);

                    if (!rawValue) {
                        return;
                    }

                    const payload = JSON.parse(rawValue);
                    const isFresh = payload && payload.timestamp && (Date.now() - payload.timestamp) < 15000;

                    if (!isFresh || payload.path !== window.location.pathname) {
                        window.sessionStorage.removeItem(this.storageKey);
                        return;
                    }

                    const restoreScroll = function () {
                        window.scrollTo({
                            top: Number(payload.scrollY) || 0,
                            left: 0,
                            behavior: 'auto',
                        });
                    };

                    window.sessionStorage.removeItem(this.storageKey);
                    window.requestAnimationFrame(function () {
                        restoreScroll();
                        window.requestAnimationFrame(restoreScroll);
                    });
                } catch (error) {
                    window.sessionStorage.removeItem(this.storageKey);
                }
            },
        };

        document.addEventListener('click', function (event) {
            const toggleButton = event.target.closest('[data-app-sidebar-toggle]');

            if (!toggleButton) {
                return;
            }

            event.preventDefault();

            if (window.innerWidth < 992) {
                const mobileMenu = document.getElementById('appMobileMenu');

                if (mobileMenu && window.bootstrap && window.bootstrap.Offcanvas) {
                    window.bootstrap.Offcanvas.getOrCreateInstance(mobileMenu).toggle();
                }

                return;
            }

            document.body.classList.toggle('sidebar-collapsed');
        });

        document.addEventListener('submit', function (event) {
            const form = event.target.closest('form');

            if (!form) {
                return;
            }

            const method = (form.getAttribute('method') || 'get').toUpperCase();

            if (method !== 'GET') {
                return;
            }

            const action = form.getAttribute('action') || window.location.href;
            const targetUrl = new URL(action, window.location.origin);

            if (targetUrl.origin === window.location.origin && targetUrl.pathname === window.location.pathname) {
                window.appPreserveScroll.remember(targetUrl.pathname);
            }
        });

        document.addEventListener('click', function (event) {
            const link = event.target.closest('a[href]');

            if (!link) {
                return;
            }

            const href = link.getAttribute('href');

            if (!href || href.startsWith('#') || link.target === '_blank' || event.defaultPrevented) {
                return;
            }

            const targetUrl = new URL(href, window.location.origin);

            if (targetUrl.origin === window.location.origin && targetUrl.pathname === window.location.pathname) {
                window.appPreserveScroll.remember(targetUrl.pathname);
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            const toasts = document.querySelectorAll('[data-app-toast]');
            const deleteModalElement = document.getElementById('deleteConfirmModal');
            const deleteModalMessage = document.getElementById('deleteConfirmModalMessage');
            const deleteModalSubmit = document.getElementById('deleteConfirmModalSubmit');
            let pendingDeleteForm = null;

            window.appPreserveScroll.restore();
            window.appInitEnhancements(document);
            window.appInitLiveRegions(document);

            if (toasts.length && window.bootstrap && window.bootstrap.Toast) {
                toasts.forEach(function (toastElement) {
                    window.bootstrap.Toast.getOrCreateInstance(toastElement).show();
                });
            }

            if (window.jQuery) {
                window.jQuery(document).ajaxError(function (event, xhr) {
                    const responseUrl = xhr.responseURL || '';

                    if (xhr.status === 401 || xhr.status === 419 || responseUrl.includes('/login')) {
                        window.appHandleSessionExpired();
                    }
                });
            }

            if (window.fetch && !window.fetch.__transportSessionAware) {
                const originalFetch = window.fetch.bind(window);
                const loginPath = new URL(@json(route('login')), window.location.origin).pathname;

                window.fetch = function () {
                    return originalFetch.apply(window, arguments).then(function (response) {
                        const responseUrl = response.url ? new URL(response.url, window.location.origin) : null;
                        const redirectedToLogin = response.redirected && responseUrl && responseUrl.pathname === loginPath;

                        if (response.status === 401 || response.status === 419 || redirectedToLogin) {
                            window.appHandleSessionExpired();
                            throw new Error('Session expired.');
                        }

                        return response;
                    });
                };

                window.fetch.__transportSessionAware = true;
            }

            if (deleteModalElement && deleteModalMessage && deleteModalSubmit && window.bootstrap && window.bootstrap.Modal) {
                const deleteModal = window.bootstrap.Modal.getOrCreateInstance(deleteModalElement);

                document.addEventListener('submit', function (event) {
                    const form = event.target.closest('form[data-confirm-delete]');

                    if (!form || form.dataset.deleteConfirmed === 'true') {
                        return;
                    }

                    event.preventDefault();
                    pendingDeleteForm = form;
                    deleteModalMessage.innerHTML = form.dataset.deleteMessage || 'Are you sure you want to delete this record?';
                    deleteModal.show();
                });

                deleteModalSubmit.addEventListener('click', function () {
                    if (!pendingDeleteForm) {
                        return;
                    }

                    pendingDeleteForm.dataset.deleteConfirmed = 'true';
                    pendingDeleteForm.submit();
                });

                deleteModalElement.addEventListener('hidden.bs.modal', function () {
                    if (pendingDeleteForm) {
                        delete pendingDeleteForm.dataset.deleteConfirmed;
                    }

                    pendingDeleteForm = null;
                });
            }
        });

        document.addEventListener('submit', function (event) {
            const perPageForm = event.target.closest('.table-per-page-form');

            if (!perPageForm) {
                return;
            }

            const liveRegion = perPageForm.closest('[data-live-region]');

            if (!liveRegion) {
                return;
            }

            event.preventDefault();

            const url = new URL(window.location.href);
            const formData = new FormData(perPageForm);

            formData.forEach(function (value, key) {
                if (typeof value !== 'string' || value.trim() === '') {
                    url.searchParams.delete(key);
                    return;
                }

                url.searchParams.set(key, value);
            });

            url.searchParams.delete('page');

            window.appLoadLiveRegion('#' + liveRegion.id, url.toString());
        });

        document.addEventListener('click', function (event) {
            const paginationLink = event.target.closest('.table-pagination-list .page-link');

            if (!paginationLink) {
                return;
            }

            const pageItem = paginationLink.closest('.page-item');
            const liveRegion = paginationLink.closest('[data-live-region]');
            const href = paginationLink.getAttribute('href');

            if (!liveRegion || !href || href === '#' || (pageItem && pageItem.classList.contains('disabled'))) {
                return;
            }

            event.preventDefault();
            window.appLoadLiveRegion('#' + liveRegion.id, href);
        });

        window.appInitEnhancements(document);
    </script>
    @stack('scripts')
</body>
</html>
