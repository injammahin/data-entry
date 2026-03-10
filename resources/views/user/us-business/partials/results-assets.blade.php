@once
    @push('styles')
        <style>
            .sg-shell {
                background: #eef1f4;
                border-top: 1px solid #cfd4da;
                margin-top: 10px;
            }

            .sg-breadcrumb-bar {
                background: linear-gradient(90deg, #0d0f14 0%, #121722 100%);
                color: #fff;
                padding: 16px 22px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
                flex-wrap: wrap;
            }

            .sg-breadcrumbs {
                display: flex;
                align-items: center;
                gap: 12px;
                font-size: 14px;
                color: rgba(255,255,255,.84);
            }

            .sg-breadcrumbs span.muted {
                color: rgba(255,255,255,.52);
            }

            .sg-filter-strip {
                background: #f3f4f6;
                border-top: 1px solid #bfc6ce;
                border-bottom: 1px solid #cfd4da;
                padding: 10px 16px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                flex-wrap: wrap;
            }

            .sg-filter-left,
            .sg-filter-right {
                display: flex;
                align-items: center;
                gap: 10px;
                flex-wrap: wrap;
            }

            .sg-filter-btn,
            .sg-filter-chip {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                border-radius: 7px;
                padding: 7px 12px;
                font-size: 13px;
                font-weight: 700;
                text-decoration: none;
            }

            .sg-filter-btn {
                background: #536f87;
                color: #fff;
            }

            .sg-filter-chip {
                background: #44a300;
                color: #fff;
                border-radius: 999px;
            }

            .sg-clear-link {
                color: #738190;
                font-size: 12px;
                font-weight: 800;
                text-transform: uppercase;
                text-decoration: none;
            }

            .sg-top-toolbar {
                background: #f3f4f6;
                border-bottom: 1px solid #cfd4da;
                padding: 14px 12px 10px;
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 14px;
                flex-wrap: wrap;
            }

            .sg-top-toolbar-left {
                display: flex;
                align-items: stretch;
                gap: 12px;
                flex-wrap: wrap;
            }

            .sg-count-box {
                min-width: 100px;
                padding-right: 16px;
                margin-right: 4px;
                border-right: 1px solid #cfd4da;
            }

            .sg-count-box .num {
                font-size: 40px;
                line-height: 1;
                font-weight: 800;
                color: #36506d;
                letter-spacing: -.04em;
            }

            .sg-count-box .label {
                font-size: 14px;
                color: #7a8794;
            }

            .sg-action-group {
                display: flex;
                align-items: flex-start;
                gap: 18px;
                padding: 0 8px;
                border-right: 1px solid #d7dbe1;
            }

            .sg-action-group:last-child {
                border-right: none;
            }

            .sg-action-col {
                min-width: max-content;
            }

            .sg-action-title {
                font-size: 10px;
                text-transform: uppercase;
                font-weight: 800;
                color: #667486;
                margin-bottom: 8px;
                text-align: center;
            }

            .sg-actions {
                display: flex;
                align-items: center;
                gap: 16px;
                flex-wrap: wrap;
            }

            .sg-action {
                position: relative;
                border: none;
                background: transparent;
                color: #5b6976;
                padding: 0;
                font-size: 12px;
                text-decoration: none;
                cursor: pointer;
                display: inline-flex;
                flex-direction: column;
                align-items: center;
                gap: 6px;
                font-weight: 600;
            }

            .sg-action i {
                font-size: 18px;
            }

            .sg-action:hover {
                color: #1f67c7;
            }

            .sg-action .new-badge {
                position: absolute;
                top: -4px;
                right: -12px;
                background: #1b63da;
                color: #fff;
                font-size: 9px;
                font-weight: 800;
                border-radius: 4px;
                padding: 1px 5px;
            }

            .sg-top-toolbar-right {
                display: flex;
                align-items: center;
                gap: 14px;
                flex-wrap: wrap;
            }

            .sg-tab-switch {
                display: inline-flex;
                border: 1px solid #bfc6ce;
                border-radius: 999px;
                overflow: hidden;
                background: #f8f8f8;
            }

            .sg-tab-switch a {
                padding: 11px 24px;
                font-size: 13px;
                font-weight: 800;
                color: #4b5b6d;
                text-decoration: none;
                background: transparent;
            }

            .sg-tab-switch a.active {
                background: #234154;
                color: #fff;
            }

            .sg-column-btn {
                width: 36px;
                height: 36px;
                border: 1px solid #cfd4da;
                background: #fff;
                border-radius: 4px;
                cursor: pointer;
                color: #586877;
                font-size: 16px;
            }

            .sg-column-btn:hover {
                background: #eef6ff;
                color: #1f67c7;
            }

            .sg-tooltip-wrap {
                position: relative;
                display: inline-flex;
            }

            .sg-tooltip-bubble {
                position: absolute;
                top: calc(100% + 8px);
                left: 50%;
                transform: translateX(-50%);
                background: #fff;
                color: #333;
                border-radius: 4px;
                padding: 10px 12px;
                font-size: 12px;
                box-shadow: 0 8px 20px rgba(0,0,0,.18);
                white-space: nowrap;
                opacity: 0;
                visibility: hidden;
                transition: .2s ease;
                z-index: 50;
                border: 1px solid #dbe1e8;
            }

            .sg-tooltip-bubble::before {
                content: "";
                position: absolute;
                top: -6px;
                left: 50%;
                transform: translateX(-50%);
                border-left: 6px solid transparent;
                border-right: 6px solid transparent;
                border-bottom: 6px solid #fff;
            }

            .sg-tooltip-wrap:hover .sg-tooltip-bubble {
                opacity: 1;
                visibility: visible;
            }

            .sg-dropdown {
                position: relative;
            }

            .sg-dropdown-menu {
                position: absolute;
                top: calc(100% + 8px);
                left: 50%;
                transform: translateX(-50%);
                min-width: 180px;
                background: #fff;
                border: 1px solid #d7dce3;
                box-shadow: 0 12px 30px rgba(0,0,0,.12);
                border-radius: 8px;
                overflow: hidden;
                display: none;
                z-index: 60;
            }

            .sg-dropdown-menu a {
                display: block;
                padding: 12px 14px;
                font-size: 13px;
                font-weight: 700;
                color: #304454;
                text-decoration: none;
                border-bottom: 1px solid #eef1f4;
            }

            .sg-dropdown-menu a:last-child {
                border-bottom: none;
            }

            .sg-dropdown-menu a:hover {
                background: #eef6ff;
            }

            .sg-dropdown.open .sg-dropdown-menu {
                display: block;
            }

            .sg-grid-wrap {
                overflow: auto;
                max-height: 720px;
                background: #fff;
            }

            .sg-grid-table {
                width: max-content;
                min-width: 100%;
                border-collapse: separate;
                border-spacing: 0;
                font-size: 13px;
                color: #243544;
            }

            .sg-grid-table thead th {
                position: sticky;
                top: 0;
                background: #f1f3f5;
                z-index: 2;
                border-bottom: 1px solid #cfd4da;
                border-right: 1px solid #d9dee3;
                padding: 12px 10px;
                white-space: nowrap;
                font-size: 11px;
                font-weight: 800;
                color: #2f3f50;
                text-transform: uppercase;
            }

            .sg-grid-table tbody td {
                border-bottom: 1px solid #eceff3;
                border-right: 1px solid #eef1f4;
                padding: 10px;
                vertical-align: top;
                white-space: normal;
                background: #fff;
            }

            .sg-grid-table tbody tr:nth-child(even) td {
                background: #fbfcfd;
            }

            .sg-grid-table tbody tr:hover td {
                background: #eef6ff;
            }

            .sg-col-check {
                width: 44px;
                min-width: 44px;
                text-align: center;
            }

            .sg-col-row {
                width: 48px;
                min-width: 48px;
                text-align: center;
                color: #8392a1;
            }

            .sg-col-status {
                width: 58px;
                min-width: 58px;
                text-align: center;
                color: #3b4b5c;
            }

            .sg-business-name {
                font-weight: 700;
                color: #193045;
            }

            .sg-multiline {
                min-width: 160px;
                max-width: 260px;
                white-space: pre-line;
                line-height: 1.35;
            }

            .sg-footer {
                background: #f3f4f6;
                border-top: 1px solid #cfd4da;
                padding: 10px 14px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 14px;
                flex-wrap: wrap;
            }

            .sg-mini-muted {
                font-size: 12px;
                color: #6d7a88;
            }

            .sg-page-box {
                width: 56px;
                height: 30px;
                border: 1px solid #cfd4da;
                border-radius: 3px;
                background: #fff;
                text-align: center;
            }

            .sg-pager-btn {
                width: 30px;
                height: 30px;
                border-radius: 50%;
                border: none;
                background: #6ea8ff;
                color: #fff;
                font-weight: 800;
            }

            .sg-pager-btn:disabled {
                background: #c8d1dc;
            }

            .sg-alert {
                margin: 14px 16px 0;
                padding: 12px 14px;
                border-radius: 8px;
                font-size: 14px;
                font-weight: 700;
            }

            .sg-alert.success {
                background: #e8f8ef;
                color: #146c43;
                border: 1px solid #b7e4c9;
            }

            .sg-alert.error {
                background: #fff1f1;
                color: #a33d3d;
                border: 1px solid #f2c3c3;
            }

            .sg-modal-overlay {
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,.45);
                display: none;
                align-items: center;
                justify-content: center;
                z-index: 3000;
                padding: 20px;
            }

            .sg-modal-overlay.active {
                display: flex;
            }

            .sg-modal-card {
                width: 100%;
                max-width: 540px;
                background: #fff;
                border-radius: 8px;
                overflow: hidden;
                box-shadow: 0 20px 45px rgba(0,0,0,.22);
            }

            .sg-modal-header {
                padding: 14px 16px;
                border-bottom: 1px solid #dbe1e8;
                display: flex;
                align-items: center;
                justify-content: space-between;
                font-weight: 800;
                color: #354556;
            }

            .sg-modal-close {
                border: none;
                background: transparent;
                font-size: 22px;
                color: #7b8794;
                cursor: pointer;
            }

            .sg-modal-body {
                padding: 18px 16px;
                max-height: 60vh;
                overflow: auto;
            }

            .sg-modal-footer {
                padding: 12px 16px;
                border-top: 1px solid #dbe1e8;
                display: flex;
                justify-content: flex-end;
                gap: 10px;
                background: #f8f9fb;
            }

            .sg-input,
            .sg-select {
                width: 100%;
                height: 44px;
                border: 1px solid #cfd7e1;
                border-radius: 6px;
                padding: 0 12px;
                font-size: 14px;
                outline: none;
            }

            .sg-btn {
                border: none;
                border-radius: 6px;
                padding: 10px 18px;
                font-size: 14px;
                font-weight: 800;
                cursor: pointer;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                gap: 8px;
            }

            .sg-btn.primary {
                background: #7fb4ea;
                color: #fff;
            }

            .sg-btn.dark {
                background: #25384a;
                color: #fff;
            }

            .sg-btn.light {
                background: #edf1f5;
                color: #304454;
                border: 1px solid #d7dde5;
            }

            .sg-column-grid {
                display: grid;
                grid-template-columns: 1fr;
                gap: 8px;
            }

            .sg-column-item {
                display: flex;
                align-items: center;
                gap: 10px;
                background: #4f98e2;
                color: #fff;
                padding: 12px 14px;
                border-radius: 6px;
                font-weight: 700;
            }

            .sg-column-item input {
                accent-color: #fff;
            }

            .sg-stats-grid {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 18px;
                padding: 18px 16px;
            }

            .sg-stat-card {
                background: #fff;
                border: 1px solid #dbe1e8;
                border-radius: 14px;
                padding: 20px;
                box-shadow: 0 14px 30px rgba(35, 57, 80, .06);
            }

            .sg-stat-card .k {
                font-size: 34px;
                font-weight: 800;
                color: #244459;
                line-height: 1;
            }

            .sg-stat-card .t {
                font-size: 13px;
                color: #728091;
                margin-top: 8px;
                font-weight: 700;
                text-transform: uppercase;
            }

            .sg-insight-grid,
            .sg-map-grid,
            .sg-detail-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 18px;
                padding: 0 16px 18px;
            }

            .sg-panel {
                background: #fff;
                border: 1px solid #dbe1e8;
                border-radius: 14px;
                overflow: hidden;
            }

            .sg-panel-head {
                padding: 14px 16px;
                border-bottom: 1px solid #eef1f4;
                font-weight: 800;
                color: #2e4152;
            }

            .sg-panel-body {
                padding: 16px;
            }

            .sg-rank-list {
                list-style: none;
                margin: 0;
                padding: 0;
            }

            .sg-rank-list li {
                display: flex;
                justify-content: space-between;
                gap: 14px;
                padding: 12px 0;
                border-bottom: 1px solid #eef2f6;
                font-size: 14px;
            }

            .sg-rank-list li:last-child {
                border-bottom: none;
            }

            .sg-detail-card,
            .sg-map-card {
                background: #fff;
                border: 1px solid #dbe1e8;
                border-radius: 14px;
                padding: 18px;
                box-shadow: 0 10px 24px rgba(35, 57, 80, .05);
            }

            .sg-detail-card h4,
            .sg-map-card h4 {
                font-size: 20px;
                margin-bottom: 10px;
                color: #1d3246;
            }

            .sg-detail-meta,
            .sg-map-meta {
                display: grid;
                grid-template-columns: 140px 1fr;
                gap: 8px 12px;
                font-size: 14px;
            }

            .sg-detail-meta strong,
            .sg-map-meta strong {
                color: #45586a;
            }

            .sg-map-link {
                margin-top: 14px;
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 10px 14px;
                border-radius: 8px;
                background: #1f67c7;
                color: #fff;
                text-decoration: none;
                font-size: 13px;
                font-weight: 800;
            }

            @media (max-width: 1200px) {
                .sg-stats-grid {
                    grid-template-columns: repeat(2, 1fr);
                }

                .sg-insight-grid,
                .sg-map-grid,
                .sg-detail-grid {
                    grid-template-columns: 1fr;
                }
            }

            @media (max-width: 768px) {
                .sg-stats-grid {
                    grid-template-columns: 1fr;
                }

                .sg-top-toolbar {
                    flex-direction: column;
                    align-items: stretch;
                }

                .sg-top-toolbar-left {
                    flex-direction: column;
                }

                .sg-action-group {
                    border-right: none;
                    border-bottom: 1px solid #d7dbe1;
                    padding-bottom: 10px;
                }

                .sg-action-group:last-child {
                    border-bottom: none;
                }

                .sg-tab-switch {
                    width: 100%;
                }

                .sg-tab-switch a {
                    flex: 1;
                    text-align: center;
                    padding: 10px 12px;
                }

                .sg-detail-meta,
                .sg-map-meta {
                    grid-template-columns: 1fr;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('click', function (e) {
                const openTarget = e.target.closest('[data-modal-open]');
                const closeTarget = e.target.closest('[data-modal-close]');
                const exportToggle = e.target.closest('[data-export-toggle]');

                if (openTarget) {
                    const modalId = openTarget.getAttribute('data-modal-open');
                    const modal = document.getElementById(modalId);
                    if (modal) modal.classList.add('active');
                }

                if (closeTarget) {
                    const modal = closeTarget.closest('.sg-modal-overlay');
                    if (modal) modal.classList.remove('active');
                }

                document.querySelectorAll('.sg-dropdown').forEach(function (item) {
                    if (!item.contains(e.target)) {
                        item.classList.remove('open');
                    }
                });

                if (exportToggle) {
                    exportToggle.closest('.sg-dropdown').classList.toggle('open');
                }
            });

            document.querySelectorAll('.sg-modal-overlay').forEach(function (modal) {
                modal.addEventListener('click', function (e) {
                    if (e.target === modal) {
                        modal.classList.remove('active');
                    }
                });
            });
        </script>
    @endpush
@endonce