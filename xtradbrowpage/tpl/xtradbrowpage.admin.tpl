<!-- 
/**
 * Admin panel for xtradbrowpage – Statistics, Edit extrafields, Edit with i18n
 *
 * Filename: plugins/xtradbrowpage/tpl/xtradbrowpage.admin.tpl
 *
 * Custom Extrafields for Pages plugin for Cotonti v1.+, PHP 8.5+, MySQL 8.4 
 *
 * Date: Aug 13, 2026
 * @package xtradbrowpage
 * @version 3.0.0
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff/xtradbrowpage-cotonti
 * @license BSD
 */
 -->
<!-- BEGIN: MAIN -->
<div class="container-fluid py-4">
    <h2>{PHP.L.xtradbrowpage_name}</h2>
    {FILE "{PHP.cfg.themes_dir}/{PHP.cfg.defaulttheme}/warnings.tpl"}
	
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link {TAB_STATS_ACTIVE}" href="{URL_STATS}">{PHP.L.xtradbrowpage_tab_stats}</a>
		</li>
        <li class="nav-item">
            <a class="nav-link {TAB_EDIT_ACTIVE}" href="{URL_EDIT}">{PHP.L.xtradbrowpage_tab_edit}</a>
		</li>
        <li class="nav-item">
            <a class="nav-link {TAB_I18N_ACTIVE}" href="{URL_I18N}">{PHP.L.xtradbrowpage_tab_i18n}</a>
		</li>
	</ul>
	
    <!-- ВКЛАДКА СТАТИСТИКА -->
    <!-- IF {PHP.tab} == 'stats' -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">{STATS_TOTAL_PAGES}</h5>
                    <p class="card-text">{PHP.L.xtradbrowpage_stats_total_pages}</p>
				</div>
			</div>
		</div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">{STATS_TOTAL_XTRA_ROWS}</h5>
                    <p class="card-text">{PHP.L.xtradbrowpage_stats_xtra_rows}</p>
				</div>
			</div>
		</div>
        <div class="col-md-4">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">{STATS_FILLED_COUNT}</h5>
                    <p class="card-text">{PHP.L.xtradbrowpage_stats_filled}</p>
				</div>
			</div>
		</div>
	</div>
	
    <h4>{PHP.L.xtradbrowpage_extrafields_info}</h4>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>{PHP.L.xtradbrowpage_field_name}</th>
                    <th>{PHP.L.xtradbrowpage_field_type}</th>
                    <th>{PHP.L.xtradbrowpage_field_description}</th>
                    <th>{PHP.L.xtradbrowpage_field_variants}</th>
                    <th>{PHP.L.xtradbrowpage_field_params}</th>
                    <th>{PHP.L.xtradbrowpage_field_default}</th>
                    <th>{PHP.L.xtradbrowpage_field_required}</th>
                    <th>{PHP.L.xtradbrowpage_field_enabled}</th>
				</tr>
			</thead>
            <tbody>
                <!-- BEGIN: STATS_EXTRAFIELDS_ROW -->
                <tr>
                    <td>{FIELD_NAME}</td>
                    <td>{FIELD_TYPE}</td>
                    <td>{FIELD_DESCRIPTION}</td>
                    <td>{FIELD_VARIANTS}</td>
                    <td>{FIELD_PARAMS}</td>
                    <td>{FIELD_DEFAULT}</td>
                    <td>{FIELD_REQUIRED}</td>
                    <td>{FIELD_ENABLED}</td>
				</tr>
                <!-- END: STATS_EXTRAFIELDS_ROW -->
                <!-- BEGIN: STATS_NO_EXTRAFIELDS -->
                <tr><td colspan="8" class="text-center">{PHP.L.xtradbrowpage_no_extrafields}</td></tr>
                <!-- END: STATS_NO_EXTRAFIELDS -->
			</tbody>
		</table>
	</div>
    <!-- ENDIF -->
	
    <!-- ВКЛАДКА РЕДАКТИРОВАНИЕ (ОСНОВНЫЕ) -->
    <!-- IF {PHP.tab} == 'edit' -->
    <div class="card filter-section p-3 mb-4" style="border: 5px var(--bs-dark-border-subtle) solid">
        <form method="get" action="{SEARCH_ACTION_URL}" class="mb-3">
            <input type="hidden" name="m" value="other">
            <input type="hidden" name="p" value="xtradbrowpage">
            <input type="hidden" name="tab" value="edit">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-lg-3">
                    <label class="form-label">{PHP.L.xtradbrowpage_search_sq}</label>
                    {SEARCH_SQ}
				</div>
                <div class="col-12 col-lg-2">
                    <label class="form-label">{PHP.L.xtradbrowpage_filter_id}</label>
                    {SEARCH_FILTER_ID}
				</div>
                <div class="col-12 col-lg-3">
                    <label class="form-label">{PHP.L.xtradbrowpage_search_cat}</label>
                    {SEARCH_CAT_SELECT2}
				</div>
                <div class="col-12">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="search_in" value="title" {SEARCH_IN_TITLE_CHECKED}>
                        <label>{PHP.L.xtradbrowpage_search_in_title}</label>
					</div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="search_in" value="desc" {SEARCH_IN_DESC_CHECKED}>
                        <label>{PHP.L.xtradbrowpage_search_in_desc}</label>
					</div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="search_in" value="alias" {SEARCH_IN_ALIAS_CHECKED}>
                        <label>{PHP.L.xtradbrowpage_search_in_alias}</label>
					</div>
				</div>
                <div class="col-12 col-lg-2">
                    <button type="submit" class="btn btn-outline-primary w-100"><i class="fa-solid fa-filter me-1"></i>{PHP.L.xtradbrowpage_search_btn}</button>
				</div>
                <div class="col-12 col-lg-2">
                    <a class="btn btn-outline-danger w-100" href="{URL_EDIT}"><i class="fa-solid fa-broom me-1"></i>{PHP.L.xtradbrowpage_search_reset}</a>
				</div>
			</div>
		</form>
        <!-- IF {SEARCH_RESULT_MSG} -->
        <div class="alert alert-info">{SEARCH_RESULT_MSG}</div>
        <!-- ENDIF -->
	</div>
	
    <form method="post" action="{EDIT_FORM_URL}">
        <input type="hidden" name="sq" value="{SEARCH_SQ_VALUE}">
        <input type="hidden" name="c" value="{SEARCH_C_VALUE}">
        <input type="hidden" name="search_in" value="{SEARCH_IN_VALUE}">
        <input type="hidden" name="filter_id" value="{SEARCH_FILTER_ID_VALUE}">
        <div class="table-responsive">
            <table class="table table-bordered align-middle massedit-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th class="table-success">{PHP.L.xtradbrowpage_page_title}</th>
                        <!-- BEGIN: EDIT_FIELDS_HEADER -->
                        <th>{FIELD_HEADER_TITLE}</th>
                        <!-- END: EDIT_FIELDS_HEADER -->
					</tr>
				</thead>
                <tbody>
                    <!-- BEGIN: EDIT_ROW -->
                    <tr>
                        <td><a href="{MANAGE_URL}" target="_blank">{MANAGE_ID}</a><input type="hidden" name="ids[]" value="{MANAGE_ID}"></td>
                        <td class="table-success">{MANAGE_TITLE}</td>
                        <!-- BEGIN: FIELD_CELL -->
                        <td>{FIELD_HTML}</td>
                        <!-- END: FIELD_CELL -->
					</tr>
                    <!-- END: EDIT_ROW -->
                    <!-- BEGIN: EDIT_EMPTY -->
                    <tr><td colspan="10" class="text-center">{PHP.L.xtradbrowpage_no_records}</td></tr>
                    <!-- END: EDIT_EMPTY -->
				</tbody>
			</table>
		</div>
        <!-- IF {PAGINATION} -->
        <nav class="mt-3">
            <div class="text-center mb-2">{PHP.L.Total}: {TOTAL_ENTRIES}, {PHP.L.Onpage}: {ENTRIES_ON_CURRENT_PAGE}</div>
            <ul class="pagination justify-content-center">{PREVIOUS_PAGE} {PAGINATION} {NEXT_PAGE}</ul>
		</nav>
        <!-- ENDIF -->
        <button type="submit" class="btn btn-success">{PHP.L.Update}</button>
	</form>
    <!-- ENDIF -->
	
    <!-- ВКЛАДКА РЕДАКТИРОВАНИЕ С ПЕРЕВОДАМИ (i18n) -->
    <!-- IF {PHP.tab} == 'i18n' -->
    <!-- IF {I18N_ACTIVE} -->
    <div class="alert alert-info">{PHP.L.xtradbrowpage_i18n_active}</div>
    <!-- ELSE -->
    <div class="alert alert-warning">{PHP.L.xtradbrowpage_i18n_disabled}</div>
    <!-- ENDIF -->
	
    <div class="card filter-section p-3 mb-4" style="border: 5px var(--bs-dark-border-subtle) solid">
        <form method="get" action="{SEARCH_ACTION_URL}" class="mb-3">
            <input type="hidden" name="m" value="other">
            <input type="hidden" name="p" value="xtradbrowpage">
            <input type="hidden" name="tab" value="i18n">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-lg-3">
                    <label class="form-label">{PHP.L.xtradbrowpage_search_sq}</label>
                    {SEARCH_SQ}
				</div>
                <div class="col-12 col-lg-2">
                    <label class="form-label">{PHP.L.xtradbrowpage_filter_id}</label>
                    {SEARCH_FILTER_ID}
				</div>
                <div class="col-12 col-lg-3">
                    <label class="form-label">{PHP.L.xtradbrowpage_search_cat}</label>
                    {SEARCH_CAT_SELECT2}
				</div>
                <div class="col-12">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="search_in" value="title" {SEARCH_IN_TITLE_CHECKED}>
                        <label>{PHP.L.xtradbrowpage_search_in_title}</label>
					</div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="search_in" value="desc" {SEARCH_IN_DESC_CHECKED}>
                        <label>{PHP.L.xtradbrowpage_search_in_desc}</label>
					</div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="search_in" value="alias" {SEARCH_IN_ALIAS_CHECKED}>
                        <label>{PHP.L.xtradbrowpage_search_in_alias}</label>
					</div>
				</div>
                <div class="col-12 col-lg-2">
                    <button type="submit" class="btn btn-outline-primary w-100"><i class="fa-solid fa-filter me-1"></i>{PHP.L.xtradbrowpage_search_btn}</button>
				</div>
                <div class="col-12 col-lg-2">
                    <a class="btn btn-outline-danger w-100" href="{URL_I18N}"><i class="fa-solid fa-broom me-1"></i>{PHP.L.xtradbrowpage_search_reset}</a>
				</div>
			</div>
		</form>
        <!-- IF {SEARCH_RESULT_MSG} -->
        <div class="alert alert-info">{SEARCH_RESULT_MSG}</div>
        <!-- ENDIF -->
	</div>
	
    <form method="post" action="{I18N_FORM_URL}">
        <input type="hidden" name="sq" value="{SEARCH_SQ_VALUE}">
        <input type="hidden" name="c" value="{SEARCH_C_VALUE}">
        <input type="hidden" name="search_in" value="{SEARCH_IN_VALUE}">
        <input type="hidden" name="filter_id" value="{SEARCH_FILTER_ID_VALUE}">
        <div class="table-responsive">
            <table class="table table-bordered align-middle massedit-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>{PHP.L.xtradbrowpage_page_title}</th>
                        <!-- BEGIN: I18N_FIELDS_HEADER -->
                        <th class="table-success">
                            {FIELD_HEADER_TITLE}
                            <!-- BEGIN: LANG_HEADER_ROW -->
                            <small class="d-block text-muted">{LANG_HEADER}</small>
                            <!-- END: LANG_HEADER_ROW -->
						</th>
                        <!-- END: I18N_FIELDS_HEADER -->
					</tr>
				</thead>
                <tbody>
                    <!-- BEGIN: I18N_ROW -->
                    <tr>
                        <td><a href="{MANAGE_URL}" target="_blank">{MANAGE_ID}</a><input type="hidden" name="ids[]" value="{MANAGE_ID}"></td>
                        <td class="table-success">{MANAGE_TITLE}</td>
                        <!-- BEGIN: FIELD_CELL -->
                        <td>
                            {FIELD_HTML}
                            <!-- BEGIN: LANG_ROW -->
                            <div class="mt-1">{LANG_FIELD}</div>
                            <!-- END: LANG_ROW -->
						</td>
                        <!-- END: FIELD_CELL -->
					</tr>
                    <!-- END: I18N_ROW -->
                    <!-- BEGIN: I18N_EMPTY -->
                    <tr><td colspan="10" class="text-center">{PHP.L.xtradbrowpage_no_records}</td></tr>
                    <!-- END: I18N_EMPTY -->
				</tbody>
			</table>
		</div>
        <!-- IF {PAGINATION} -->
        <nav class="mt-3">
            <div class="text-center mb-2">{PHP.L.Total}: {TOTAL_ENTRIES}, {PHP.L.Onpage}: {ENTRIES_ON_CURRENT_PAGE}</div>
            <ul class="pagination justify-content-center">{PREVIOUS_PAGE} {PAGINATION} {NEXT_PAGE}</ul>
		</nav>
        <!-- ENDIF -->
        <button type="submit" class="btn btn-success">{PHP.L.Update}</button>
	</form>
    <!-- ENDIF -->
</div>
<style>
    .table-responsive {
	overflow-x: auto !important;
	display: block !important;
	width: 100% !important;
    }
    .table {
	width: max-content !important;
	min-width: 100% !important;
	table-layout: auto !important;
	white-space: nowrap !important;
    }
    .table th,
    .table td {
	white-space: nowrap !important;
    }
/* закрепление первых двух колонок при горизонтальной прокрутке */
.massedit-table th:first-child,
.massedit-table td:first-child {
    position: sticky;
    left: 0;
    z-index: 3;
    width: 80px;
    min-width: 80px;
    max-width: 80px;
    background-color: #f8f9fa;
}
.massedit-table tbody td:first-child {
    background-color: #fff;
}
.massedit-table th:nth-child(2),
.massedit-table td:nth-child(2) {
    position: sticky;
    left: 80px;
    z-index: 3;
    min-width: 240px;
    background-color: #d1e7dd;
}
.massedit-table thead th:first-child {
    background-color: #f8f9fa;
}
.massedit-table thead th:nth-child(2) {
    background-color: #d1e7dd;
}
</style>
<!-- END: MAIN -->